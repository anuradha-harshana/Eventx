<?php 
    class SponsorModel {
        private $db;
        public function __construct($db){
            $this->db = $db->conn;
        }
        public function updateProfile($id, $data, $profilePicPath = null){

            $fields = [];
            $params = ['id'=>$id];

            if(!empty($data['brand_name'])){
                $fields[] = "brand_name = :brand_name";
                $params['brand_name'] = $data['brand_name'];
            }

            if(!empty($data['industry'])){
                $fields[] = "industry = :industry";
                $params['industry'] = $data['industry'];
            }

            if(!empty($data['description'])){
                $fields[] = "description = :description";
                $params['description'] = $data['description'];
            }

            if(!empty($data['website'])){
                $fields[] = "website = :website";
                $params['website'] = $data['website'];
            }

            if(!empty($data['contact_email'])){
                $fields[] = "contact_email = :contact_email";
                $params['contact_email'] = $data['contact_email'];
            }

            if(!empty($data['contact_phone'])){
                $fields[] = "contact_phone = :contact_phone";
                $params['contact_phone'] = $data['contact_phone'];
            }

            if(!empty($data['budget_range'])){
                $fields[] = "budget_range = :budget_range";
                $params['budget_range'] = $data['budget_range'];
            }

            if ($profilePicPath) {
                $fields[] = "profile_pic = :profile_pic";
                $params['profile_pic'] = $profilePicPath;
            }

            $sql = "UPDATE sponsor_details SET " . implode(", ", $fields) . " WHERE user_id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute($params);
        }
    }

?>