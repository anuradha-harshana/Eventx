<?php 

    class SponsorController extends Controller {
        private $sponModel;
        public function __construct(){
            Middleware::role(roles: 'sponsor');
            $this->sponModel = $this->model('SponsorModel');
        }
        public function dashboard(){
            $this->view('sponsor/dashboard');
        }

        public function analytics(){
            $this->view('sponsor/analytics');
        }

        public function profile(){
            $this->view('sponsor/profile');
        }
        public function updateProfile() {
            $profilePicPath = null;

            if (!empty($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE) {
                require_once __DIR__ . '/../model/FileUploader.php';

                try {
                    $uploader = new FileUploader(__DIR__ . '/../../uploads/profile/');
                    $profilePicPath = $uploader->upload($_FILES['profile_pic'], 'profile_');
                } catch (Exception $e) {
                    // Handle upload error gracefully
                    $_SESSION['upload_error'] = $e->getMessage();
                    header('Location: ' . SITE_URL . 'sponProf');
                    exit;
                }
            }

            $this->sponModel->updateProfile($_SESSION["user_id"], $_POST, $profilePicPath);
            header('Location:' .SITE_URL. 'sponDash');
        }

    }

?>