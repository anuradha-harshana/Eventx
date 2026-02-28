<?php 

    class RegistrationController extends Controller{
        private $registrationModel;
        private $participantModel;
        public function __construct(){
            $this->registrationModel = $this->model('RegistrationModel');
            $this->participantModel = $this->model('ParticipantModel');
        }
        public function register(){
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }
            $participantId = $this->participantModel->getParticipantByUserId($_SESSION["user_id"]);
            $eventId = $_POST["event_id"];
            if ($this->registrationModel->isRegistered($participantId, $eventId)) {
                echo json_encode(['status' => 'error', 'message' => 'Already registered']);
                return;
            }

            $this->registrationModel->register($participantId, $eventId);

            echo json_encode(['status' => 'success', 'message' => 'Registered successfully']);
        }
        public function cancel() {
            if(session_status() === PHP_SESSION_NONE){
                session_start();
            }
            $participantId = $this->participantModel->getParticipantByUserId($_SESSION["user_id"]);
            $eventId = $_POST['event_id'];

            $this->registrationModel->cancel($participantId, $eventId);

            echo json_encode(['status' => 'success', 'message' => 'Registration canceled']);
        }
    }

?>