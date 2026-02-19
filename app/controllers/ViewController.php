<?php 
    class ViewController extends Controller {
        private $eventModel;

        public function __construct(){
            $this->eventModel = $this->model('EventModel');
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
            $this->view('eventView', [
                'event' => $event
            ]);
        }
    }

?>