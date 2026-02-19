<?php

class UserModel {

    private $db;

    public function __construct($db) {
        $this->db = $db->conn;
    }

    public function register($data)
    {
        try {
            $this->db->beginTransaction();

            // Check if user already exists (email OR username)
            $check = $this->db->prepare("
                SELECT id FROM users 
                WHERE email = :email 
                OR username = :username
                LIMIT 1
            ");

            $check->execute([
                'email' => $data['email'],
                'username' => $data['username']
            ]);

            $existing = $check->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $this->db->rollBack();
                return [
                    'status' => false,
                    'message' => 'User already exists'
                ];
            }

            // Hash password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            // Insert into users table
            $stmt = $this->db->prepare("
                INSERT INTO users (username, email, password, role)
                VALUES (:username, :email, :password, :role)
            ");

            $stmt->execute([
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => $hashedPassword,
                'role'     => $data['role']
            ]);

            $userId = $this->db->lastInsertId();

            // Insert role-specific details
            switch ($data['role']) {

                case 'participant':
                    $stmt = $this->db->prepare("
                        INSERT INTO participant_details (user_id) 
                        VALUES (:user_id)
                    ");
                    break;

                case 'organizer':
                    $stmt = $this->db->prepare("
                        INSERT INTO organizer_details (user_id) 
                        VALUES (:user_id)
                    ");
                    break;

                case 'sponsor':
                    $stmt = $this->db->prepare("
                        INSERT INTO sponsor_details (user_id) 
                        VALUES (:user_id)
                    ");
                    break;
                
                case 'supplier':
                    $stmt = $this->db->prepare("
                        INSERT INTO supplier_details (user_id) 
                        VALUES (:user_id)
                    ");
                    break;    

                default:
                    throw new Exception("Invalid user role.");
            }

            $stmt->execute(['user_id' => $userId]);

            $this->db->commit();

            return [
                'status' => true
            ];

        } catch (Exception $e) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    public function login($email,$password) {

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email=:email");
        $stmt->execute(['email'=>$email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password,$user['password']))
            return $user;

        return false;
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id=:id");
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id,$data,$file=null) {

        $sql = "UPDATE users SET full_name=:full_name, phone=:phone";
        $params = [
            'full_name'=>$data['full_name'],
            'phone'=>$data['phone'],
            'id'=>$id
        ];

        if($file && $file['tmp_name']) {

            $allowed = ['image/jpeg','image/png','image/webp'];
            if(!in_array($file['type'],$allowed))
                die("Invalid file type");

            if($file['size'] > 2*1024*1024)
                die("File too large");

            $filename = time().'_'.basename($file['name']);
            $path = 'uploads/profile/'.$filename;

            move_uploaded_file($file['tmp_name'],$path);

            $sql .= ", profile_pic=:profile_pic";
            $params['profile_pic'] = $path;
        }

        $sql .= " WHERE id=:id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
