<?php 
    class ViewController extends Controller {
        private $eventModel;
        private $registrationModel;
        private $participantModel;

        public function __construct(){
            $this->eventModel = $this->model('EventModel');
            $this->registrationModel = $this->model('RegistrationModel');
            $this->participantModel = $this->model('ParticipantModel');
        }
        public function home(){
            //echo 'Controller hit';
            $this->view('welcome');
        }
        public function showLoginForm(){
            //echo 'Controller hit';
            $this->view('login');
        }
        public function showRegisterForm(){
            //echo 'Controller hit';
            $this->view('register');
        }
        public function explore(){
            
            $events = $this->eventModel->getEvents();
            $this->view('explore', [
                'events' => $events
            ]); 
        }

        public function viewEvent(){
            $eventId = $_POST['id'];
            $event = $this->eventModel->getEventDetails($eventId);
            $tickets = $this->eventModel->getTicketsByEvent((int)$event['id']);

            $isRegistered = false;
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'participant' && isset($_SESSION['user_id'])) {
                $participantId = $this->participantModel->getParticipantByUserId($_SESSION['user_id']);
                if ($participantId) {
                    $isRegistered = $this->registrationModel->isRegistered($participantId, $event['id']);
                }
            }

            $this->view('eventView', [
                'event'        => $event,
                'isRegistered' => $isRegistered,
                'tickets'      => $tickets,
            ]);
        }
    }

?>