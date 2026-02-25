<?php 

class OrganizerController extends Controller {
    private $orgModel;
    private $eventModel;

    public function __construct(){
        Middleware::role('organizer');
        $this->orgModel = $this->model('OrganizerModel');
        $this->eventModel = $this->model('EventModel');
    }

    private function getOrganizerId(){
        return $this->orgModel->getOrganizerIdByUser($_SESSION['user_id']);
    }

    public function dashboard(){
        $organizerId = $this->getOrganizerId();

        $events = $this->eventModel->getEventsByOrganizer($organizerId);

        $stats = $this->eventModel->getEventStatsByOrganizer($organizerId);

        $this->view('organizer/dashboard', [
            'events' => $events,
            'stats' => $stats
        ]);
    }

    public function analytics(){
        $this->view('organizer/analytics');
    }

    public function profile(){
        $this->view('organizer/profile');
    }

    public function createEvent(){
        $categories = $this->eventModel->getCategories();
        $this->view('organizer/createEvent',[
            'categories' => $categories
        ]);
    }

    public function editEvent($id){
        $organizerId = $this->getOrganizerId();

        $event = $this->eventModel->getEventById($id, $organizerId);

        $this->view('organizer/editEvent', [
            'event' => $event
        ]);
    }

    public function sponsors(){
        $this->view('organizer/viewSponsors');
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
                header('Location: ' . SITE_URL . 'orgProf');
                exit;
            }
        }

        $this->orgModel->updateProfile($_SESSION['user_id'], $_POST, $profilePicPath);

        header('Location: ' . SITE_URL . 'orgDash');
        exit;
    }
}
