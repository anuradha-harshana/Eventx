<?php 

    class SupplierModel {
        private $db;
        public function __construct($db){
            $this->db = $db->conn;
        }

        public function getOrganizerIdByUser($userId){
            $stmt = $this->db->prepare("
                SELECT id 
                FROM supplier_details 
                WHERE user_id = :user_id
                LIMIT 1
            ");

            $stmt->execute([
                'user_id' => $userId
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['id'] : null;
        }
        public function getSupplierByUserId($userId){
            $stmt = $this->db->prepare("
                SELECT id 
                FROM supplier_details 
                WHERE user_id = :user_id
                LIMIT 1
            ");

            $stmt->execute([
                'user_id' => $userId
            ]);

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result ? $result['id'] : null;
        }

        public function updateProfile($id, $data, $profilePicPath = null){

            $fields = [];
            $params = ['id'=>$id];

            if(!empty($data['company_name'])){
                $fields[] = "company_name = :company_name";
                $params['company_name'] = $data['company_name'];
            }

            if(!empty($data['business_type'])){
                $fields[] = "business_type = :business_type";
                $params['business_type'] = $data['business_type'];
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

            if ($profilePicPath) {
                $fields[] = "profile_pic = :profile_pic";
                $params['profile_pic'] = $profilePicPath;
            }

            $sql = "UPDATE supplier_details SET " . implode(", ", $fields) . " WHERE user_id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute($params);
        }

            public function getCategories()
        {
            $stmt = $this->db->prepare("
                SELECT id, name 
                FROM supplier_categories
                ORDER BY name ASC
            ");

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function createProduct($supplierId, $data, $imageUrl = null){
            $stmt = $this->db->prepare("
                INSERT INTO supplier_products 
                (supplier_id, category_id, name, description, price, stock, status, image_url) 
                VALUES 
                (:supplier_id, :category_id, :name, :description, :price, :stock, :status, :image_url)
            ");

            return $stmt->execute([
                'supplier_id' => $supplierId,
                'name' => $data['name'],
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'status' => $data['status'],
                'image_url' => $imageUrl,
            ]);
        }
        public function getProductsBySupplier($supplierId){
            $stmt = $this->db->prepare("
                SELECT * FROM supplier_products
                WHERE supplier_id = :supplier_id");

            $stmt->execute(['supplier_id' => $supplierId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        public function getProductById($productId, $supplierId){
            $stmt = $this->db->prepare("
                SELECT * FROM supplier_products 
                WHERE id = :id AND supplier_id = :supplier_id
            ");

            $stmt->execute([
                'id' => $productId,
                'supplier_id' => $supplierId
            ]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
         public function updateProduct($productId, $supplierId, $data, $imageUrl = null){
            $fields = [];
            $params = [
                'id' => $productId,
                'organizer_id' => $supplierId
            ];

            if(isset($data['name'])){
                $fields[] = "name = :name";
                $params['name'] = $data['name'];
            }

            if(isset($data['description'])){
                $fields[] = "description = :description";
                $params['description'] = $data['description'];
            }

            if(isset($data['price'])){
                $fields[] = "price = :price";
                $params['price'] = $data['price'];
            }

            if(isset($data['stock'])){
                $fields[] = "stock = :stock";
                $params['stock'] = $data['stock'];
            }

            if(isset($data['status'])){
                $fields[] = "status = :status";
                $params['status'] = $data['status'];
            }

            if(isset($data['category_id'])){
                $fields[] = "category_id = :category_id";
                $params['category_id'] = $data['category_id'];
            }

            if ($imageUrl) {
                $fields[] = "banner_url = :banner_url";
                $params['banner_url'] = $imageUrl;
            }

            // Prevent empty update crash
            if(empty($fields)){
                return false;
            }

            $sql = "
                UPDATE supplier_products 
                SET " . implode(", ", $fields) . " 
                WHERE id = :id AND supplier_id = :supplier_id
            ";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute($params);
        }
        public function deleteProduct($productId, $supplierId){
            $stmt = $this->db->prepare("
                DELETE FROM supplier_products 
                WHERE id = :id AND supplier_id = :supplier_id
            ");

            return $stmt->execute([
                'id' => $productId,
                'supplier_id' => $supplierId
            ]);
        }


    }

?>