<?php 
    class ViewController extends Controller {
        private $eventModel;
        private $registrationModel;

        public function __construct(){
            $this->eventModel = $this->model('EventModel');
            $this->registrationModel = $this->model('RegistrationModel');
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
            $isRegistered = $this->registrationModel->isRegistered($_SESSION['user_id'], $event['id']);
            $this->view('eventView', [
                'event' => $event,
                'isRegistered' => $isRegistered
            ]);
        }
    }

?>