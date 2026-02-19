<?php
class ParticipantModel {
    private $db;

    public function __construct($db){
        $this->db = $db->conn;
    }

    // Get participant ID by user ID
    public function getParticipantByUserId($userId){
        $stmt = $this->db->prepare("SELECT id FROM participant_details WHERE user_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    // Fetch participant profile by participant ID
    public function getProfile($participantId){
        $stmt = $this->db->prepare("SELECT * FROM participant_details WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $participantId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update profile with optional profile picture path
    public function updateProfile($userId, $data, $profilePicPath = null){
        $fields = [];
        $params = ['user_id' => $userId];

        if (!empty($data['date_of_birth'])) {
            $fields[] = "date_of_birth = :date_of_birth";
            $params['date_of_birth'] = $data['date_of_birth'];
        }

        if (!empty($data['location'])) {
            $fields[] = "location = :location";
            $params['location'] = $data['location'];
        }

        if (!empty($data['bio'])) {
            $fields[] = "bio = :bio";
            $params['bio'] = $data['bio'];
        }

        if (!empty($data['interests'])) {
            $fields[] = "interests = :interests";
            $params['interests'] = $data['interests'];
        }

        if (!empty($data['occupation'])) {
            $fields[] = "occupation = :occupation";
            $params['occupation'] = $data['occupation'];
        }

        if (!empty($data['company'])) {
            $fields[] = "company = :company";
            $params['company'] = $data['company'];
        }

        if (!empty($data['education'])) {
            $fields[] = "education = :education";
            $params['education'] = $data['education'];
        }

        if (!empty($data['phone'])) {
            $fields[] = "phone = :phone";
            $params['phone'] = $data['phone'];
        }

        // Include profile picture if provided
        if ($profilePicPath) {
            $fields[] = "profile_pic = :profile_pic";
            $params['profile_pic'] = $profilePicPath;
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "UPDATE participant_details SET " . implode(", ", $fields) . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }
}
