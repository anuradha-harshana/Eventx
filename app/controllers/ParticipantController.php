<?php
class ParticipantController extends Controller {
    private $parModel;

    public function __construct(){
        Middleware::role('participant');
        $this->parModel = $this->model('ParticipantModel');
    }

    public function events(){
        $this->view('participant/myEvents');
    }

    public function profile(){
        $this->view('participant/profile');
    }
    private function getParticipantId(){
        return $this->parModel->getParticipantByUserId($_SESSION['user_id']);
    }
    public function dashboard(){
        $participant_id = $this->getParticipantId();
        $profile = $this->parModel->getProfile($participant_id);
        $this->view('participant/dashboard', [
            'profile' => $profile
        ]);
    }

    public function updateProfile(){
        $profilePicPath = null;

        if (!empty($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE) {
            require_once __DIR__ . '/../model/FileUploader.php';

            try {
                $uploader = new FileUploader(__DIR__ . '/../../uploads/profile/');
                $profilePicPath = $uploader->upload($_FILES['profile_pic'], 'profile_');
            } catch (Exception $e) {
                // Handle upload error gracefully
                $_SESSION['upload_error'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'parProf');
                exit;
            }
        }

        $this->parModel->updateProfile($_SESSION['user_id'], $_POST, $profilePicPath);

        header('Location:' . SITE_URL . 'parDash');
        exit;
    }
}
