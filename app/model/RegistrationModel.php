<?php 

    class RegistrationModel {
        private $db;
        public function __construct($db){
            $this->db = $db->conn;
        }

        // Generates a 16-char uppercase hex ticket code seeded from IDs + random bytes
        private function generateVerifyCode(int $registrationId, int $ticketId, int $eventId): string {
            return strtoupper(substr(
                hash_hmac(
                    'sha256',
                    "{$registrationId}:{$ticketId}:{$eventId}:" . bin2hex(random_bytes(8)),
                    'EVENTX_VERIFY_2026'
                ),
                0, 16
            ));
        }

        public function isRegistered($participantId, $eventId){
            $stmt = $this->db->prepare("SELECT * FROM registrations WHERE participant_id = :participant_id AND event_id = :event_id");
            $stmt->execute([
                'participant_id' => $participantId,
                'event_id' => $eventId
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        }
            public function register($participantId, $eventId) {
            $stmt = $this->db->prepare("INSERT INTO registrations (participant_id, event_id) VALUES (:participant_id, :event_id)");
            $stmt->execute(['participant_id' => $participantId, 'event_id' => $eventId]);
            $registrationId = (int)$this->db->lastInsertId();

            // Issue one ticket_purchase row with a verify code for free events
            $tk = $this->db->prepare("
                SELECT id FROM event_tickets
                WHERE event_id = :event_id AND price = 0.00 AND is_active = TRUE
                LIMIT 1
            ");
            $tk->execute(['event_id' => $eventId]);
            $ticket = $tk->fetch(PDO::FETCH_ASSOC);

            if ($ticket && $registrationId) {
                $code = $this->generateVerifyCode($registrationId, (int)$ticket['id'], (int)$eventId);
                $ins = $this->db->prepare("
                    INSERT INTO ticket_purchases (registration_id, ticket_id, unit_price, verify_code)
                    VALUES (:reg_id, :ticket_id, 0.00, :verify_code)
                ");
                $ins->execute([
                    'reg_id'      => $registrationId,
                    'ticket_id'   => (int)$ticket['id'],
                    'verify_code' => $code,
                ]);
            }
        }

        public function cancel($participantId, $eventId) {
            $stmt = $this->db->prepare("DELETE FROM registrations WHERE participant_id = :participant_id AND event_id = :event_id");
            $stmt->execute(['participant_id' => $participantId, 'event_id' => $eventId]);
        }

        public function registerPaid(int $participantId, array $order): bool {
            try {
                $this->db->beginTransaction();

                $eventId = (int)$order['event_id'];

                // Prevent duplicate registration
                $chk = $this->db->prepare("
                    SELECT id FROM registrations
                    WHERE participant_id = :pid AND event_id = :eid LIMIT 1
                ");
                $chk->execute(['pid' => $participantId, 'eid' => $eventId]);
                if ($chk->fetch()) {
                    $this->db->rollBack();
                    return false;
                }

                // Verify availability (no FOR UPDATE needed here — INSERT is atomic within transaction)
                $avail = $this->db->prepare("
                    SELECT id, available_count FROM event_tickets WHERE id = :id
                ");
                foreach ($order['items'] as $item) {
                    $avail->execute(['id' => (int)$item['ticket_id']]);
                    $row = $avail->fetch(PDO::FETCH_ASSOC);
                    if (!$row || (int)$row['available_count'] < (int)$item['quantity']) {
                        $this->db->rollBack();
                        return false;
                    }
                }

                // Insert registration
                $reg = $this->db->prepare("
                    INSERT INTO registrations (event_id, participant_id)
                    VALUES (:event_id, :participant_id)
                ");
                $reg->execute(['event_id' => $eventId, 'participant_id' => $participantId]);
                $registrationId = (int)$this->db->lastInsertId();

                if ($registrationId === 0) {
                    $this->db->rollBack();
                    return false;
                }

                // Insert one ticket_purchase row per ticket unit, each with a unique verify code
                $ins = $this->db->prepare("
                    INSERT INTO ticket_purchases (registration_id, ticket_id, unit_price, verify_code)
                    VALUES (:reg_id, :ticket_id, :price, :verify_code)
                ");
                $dec = $this->db->prepare("
                    UPDATE event_tickets
                    SET available_count = available_count - :qty
                    WHERE id = :id
                ");
                foreach ($order['items'] as $item) {
                    for ($i = 0; $i < (int)$item['quantity']; $i++) {
                        $code = $this->generateVerifyCode($registrationId, (int)$item['ticket_id'], $eventId);
                        $ins->execute([
                            'reg_id'      => $registrationId,
                            'ticket_id'   => (int)$item['ticket_id'],
                            'price'       => (float)$item['price'],
                            'verify_code' => $code,
                        ]);
                    }
                    $dec->execute(['qty' => (int)$item['quantity'], 'id' => (int)$item['ticket_id']]);
                }

                // Increment current_participants on the event
                $totalQty = array_sum(array_column($order['items'], 'quantity'));
                $upd = $this->db->prepare("
                    UPDATE events SET current_participants = current_participants + :qty WHERE id = :id
                ");
                $upd->execute(['qty' => (int)$totalQty, 'id' => $eventId]);

                $this->db->commit();
                return true;

            } catch (Exception $e) {
                if ($this->db->inTransaction()) {
                    $this->db->rollBack();
                }
                error_log('registerPaid exception: ' . $e->getMessage());
                return false;
            }
        }

        public function getRegistrationsForOrganizer($organizerId) {
            $stmt = $this->db->prepare("
                SELECT r.*, u.name, u.email
                FROM registrations r
                JOIN events e ON r.event_id = e.id
                JOIN users u ON r.participant_id = u.id
                WHERE e.organizer_id = :organizer_id
            ");
            $stmt->execute(['organizer_id' => $organizerId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public function getRegisteredEvents($participantId)
        {
            $stmt = $this->db->prepare("
                SELECT e.*
                FROM registrations r
                JOIN events e ON r.event_id = e.id
                WHERE r.participant_id = :participant_id
                ORDER BY e.start_at ASC
            ");

            $stmt->execute(['participant_id' => $participantId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getParticipantsByEvent($eventId)
        {
            $stmt = $this->db->prepare("
                SELECT
                    r.id              AS registration_id,
                    r.registration_date,
                    r.status          AS registration_status,
                    r.checkin_time,
                    r.checkin_method,
                    r.feedback,
                    r.rating,
                    u.full_name,
                    u.username,
                    u.email,
                    COALESCE(pd.profile_pic, u.profile_pic) AS profile_pic,
                    pd.phone,
                    pd.date_of_birth,
                    pd.location,
                    pd.occupation,
                    pd.company,
                    pd.education,
                    pd.bio,
                    pd.badges_earned
                FROM registrations r
                JOIN participant_details pd ON r.participant_id = pd.id
                JOIN users u ON pd.user_id = u.id
                WHERE r.event_id = :event_id
                ORDER BY u.full_name ASC
            ");
            $stmt->execute(['event_id' => $eventId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Validate a verify_code and check in the attendee.
         * Returns ['success', 'message', 'participant'] on success.
         */
        public function checkinByCode(string $verifyCode, int $eventId): array
        {
            // Look up ticket purchase by code, joined to its registration + participant
            $stmt = $this->db->prepare("
                SELECT
                    tp.id           AS purchase_id,
                    tp.used_at,
                    tp.ticket_id,
                    tp.verify_code,
                    r.id            AS registration_id,
                    r.event_id,
                    r.status        AS reg_status,
                    r.checkin_time,
                    u.full_name,
                    u.email,
                    et.name         AS ticket_name
                FROM ticket_purchases tp
                JOIN registrations r  ON tp.registration_id = r.id
                JOIN participant_details pd ON r.participant_id = pd.id
                JOIN users u          ON pd.user_id = u.id
                JOIN event_tickets et ON tp.ticket_id = et.id
                WHERE tp.verify_code = :code
                LIMIT 1
            ");
            $stmt->execute(['code' => $verifyCode]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$row) {
                return ['success' => false, 'message' => 'Invalid ticket code. No match found.'];
            }

            if ((int)$row['event_id'] !== $eventId) {
                return ['success' => false, 'message' => 'Ticket does not belong to this event.'];
            }

            if ($row['used_at'] !== null) {
                return [
                    'success' => false,
                    'message' => 'Ticket already used.',
                    'used_at' => $row['used_at'],
                    'participant' => [
                        'name'        => $row['full_name'],
                        'email'       => $row['email'],
                        'ticket_name' => $row['ticket_name'],
                    ],
                ];
            }

            $now = date('Y-m-d H:i:s');

            // Mark ticket as used
            $this->db->prepare("
                UPDATE ticket_purchases SET used_at = :now WHERE id = :id
            ")->execute(['now' => $now, 'id' => (int)$row['purchase_id']]);

            // Update registration: status → checked_in, checkin_time, checkin_method
            $this->db->prepare("
                UPDATE registrations
                SET status = 'checked_in', checkin_time = :now, checkin_method = 'passcode'
                WHERE id = :id
            ")->execute(['now' => $now, 'id' => (int)$row['registration_id']]);

            return [
                'success'     => true,
                'message'     => 'Check-in successful!',
                'participant' => [
                    'name'        => $row['full_name'],
                    'email'       => $row['email'],
                    'ticket_name' => $row['ticket_name'],
                    'checkin_time' => $now,
                ],
            ];
        }

        /**
         * Returns attendance stats for an event: total registered, total checked in.
         */
        public function getCheckinStats(int $eventId): array
        {
            $stmt = $this->db->prepare("
                SELECT
                    COUNT(*)                                          AS total_registered,
                    SUM(status = 'checked_in' OR status = 'attended') AS total_checked_in
                FROM registrations
                WHERE event_id = :event_id
            ");
            $stmt->execute(['event_id' => $eventId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return [
                'total_registered' => (int)($row['total_registered'] ?? 0),
                'total_checked_in' => (int)($row['total_checked_in'] ?? 0),
            ];
        }

    }

?>