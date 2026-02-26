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
            $filter = $_GET['filter'] ?? 'all';
            $category = $_GET['category'] ?? null;
            $location = $_GET['location'] ?? null;
            $search = $_GET['search'] ?? null;
            
            $events = [];
            $categories = $this->eventModel->getCategories();
            
            // Apply filters
            if($search){
                $events = $this->eventModel->searchEvents($search);
            } elseif($category){
                $events = $this->eventModel->getEventsByCategory($category);
            } elseif($location){
                $events = $this->eventModel->getEventsByLocation($location);
            } elseif($filter === 'upcoming'){
                $events = $this->eventModel->getUpcomingEvents();
            } elseif($filter === 'past'){
                $events = $this->eventModel->getPastEvents();
            } elseif($filter === 'popular'){
                $events = $this->eventModel->getPopularEvents();
            } else {
                // Default: all upcoming events
                $events = $this->eventModel->getUpcomingEvents();
            }
            
            $this->view('explore', [
                'events' => $events,
                'categories' => $categories,
                'activeFilter' => $filter,
                'selectedCategory' => $category,
                'selectedLocation' => $location,
                'searchTerm' => $search
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