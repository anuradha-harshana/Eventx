<?php 

    class RegistrationController extends Controller{
        private $registrationModel;
        private $participantModel;
        public function __construct(){
            Middleware::role('participant');
            $this->registrationModel = $this->model('RegistrationModel');
            $this->participantModel = $this->model('ParticipantModel');
        }
        public function register(){
            $participantId = $this->participantModel->getParticipantByUserId($_SESSION["user_id"]);
            $eventId = $_POST["event_id"];

            // Block paid events — they must go through the payment flow
            $eventModel = $this->model('EventModel');
            $event = $eventModel->getEventDetails($eventId);
            if (!$event || $event['pricing_type'] === 'paid') {
                echo json_encode(['status' => 'error', 'message' => 'This event requires ticket purchase.']);
                return;
            }

            if ($this->registrationModel->isRegistered($participantId, $eventId)) {
                echo json_encode(['status' => 'error', 'message' => 'Already registered']);
                return;
            }

            $this->registrationModel->register($participantId, $eventId);

            echo json_encode(['status' => 'success', 'message' => 'Registered successfully']);
        }
        public function cancel() {
            $participantId = $this->participantModel->getParticipantByUserId($_SESSION["user_id"]);
            $eventId = $_POST['event_id'];

            $this->registrationModel->cancel($participantId, $eventId);

            echo json_encode(['status' => 'success', 'message' => 'Registration canceled']);
        }
    }

?>