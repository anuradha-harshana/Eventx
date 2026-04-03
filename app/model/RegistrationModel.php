<?php 

    class RegistrationModel {
        private $db;
        public function __construct($db){
            $this->db = $db->conn;
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
        }

        public function cancel($participantId, $eventId) {
            $stmt = $this->db->prepare("DELETE FROM registrations WHERE participant_id = :participant_id AND event_id = :event_id");
            $stmt->execute(['participant_id' => $participantId, 'event_id' => $eventId]);
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

    }

?>