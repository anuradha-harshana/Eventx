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

    }

?>