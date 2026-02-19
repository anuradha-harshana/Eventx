<?php 

class OrganizerModel {
    private $db;

    public function __construct($db){
        $this->db = $db->conn;
    }

    public function getOrganizerIdByUser($userId){
        $stmt = $this->db->prepare("
            SELECT id 
            FROM organizer_details 
            WHERE user_id = :user_id
            LIMIT 1
        ");

        $stmt->execute([
            'user_id' => $userId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['id'] : null;
    }

    public function updateProfile($userId, $data, $profilePicPath = null)
    {
        $fields = [];
        $params = ['user_id' => $userId];

        if (!empty($data['organization_name'])) {
            $fields[] = "organization_name = :organization_name";
            $params['organization_name'] = $data['organization_name'];
        }

        if (!empty($data['organization_type'])) {
            $fields[] = "organization_type = :organization_type";
            $params['organization_type'] = $data['organization_type'];
        }

        if (!empty($data['description'])) {
            $fields[] = "description = :description";
            $params['description'] = $data['description'];
        }

        if (!empty($data['website'])) {
            $fields[] = "website = :website";
            $params['website'] = $data['website'];
        }

        if (!empty($data['contact_email'])) {
            $fields[] = "contact_email = :contact_email";
            $params['contact_email'] = $data['contact_email'];
        }

        if (!empty($data['contact_phone'])) {
            $fields[] = "contact_phone = :contact_phone";
            $params['contact_phone'] = $data['contact_phone'];
        }

        if ($profilePicPath) {
            $fields[] = "profile_pic = :profile_pic";
            $params['profile_pic'] = $profilePicPath;
        }

        if (empty($fields)) {
            return false;
        }

        $sql = "
            UPDATE organizer_details 
            SET " . implode(", ", $fields) . " 
            WHERE user_id = :user_id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

}

?>
