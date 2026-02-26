<?php 

class EventController extends Controller {
    private $eventModel;
    private $orgModel;

    public function __construct(){
        Middleware::role('organizer');
        $this->eventModel = $this->model('EventModel');
        $this->orgModel = $this->model('OrganizerModel');
    }

    private function getOrganizerId(){
        return $this->orgModel->getOrganizerIdByUser($_SESSION['user_id']);
    }
    
    public function createEvent(){
        $organizerId = $this->getOrganizerId();

        $bannerPic = null;

        if (!empty($_FILES['banner_url']) && $_FILES['banner_url']['error'] !== UPLOAD_ERR_NO_FILE) {
            require_once __DIR__ . '/../model/FileUploader.php';

            try {
                $uploader = new FileUploader(__DIR__ . '/../../uploads/events/');
                $bannerPic = $uploader->upload($_FILES['banner_url'], 'banner_');
            } catch (Exception $e) {
                // Handle upload error gracefully
                $_SESSION['upload_error'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'orgDash');
                exit;
            }
        }

        $this->eventModel->createEvent($organizerId, $_POST, $bannerPic);

        header('Location: ' . SITE_URL . 'orgDash');
        exit;
    }

    public function editEvent(){
        $organizerId = $this->getOrganizerId();
        $eventId = $_POST['event_id'];

                $bannerPic = null;

        if (!empty($_FILES['banner_url']) && $_FILES['banner_url']['error'] !== UPLOAD_ERR_NO_FILE) {
            require_once __DIR__ . '/../model/FileUploader.php';

            try {
                $uploader = new FileUploader(__DIR__ . '/../../uploads/events/');
                $bannerPic = $uploader->upload($_FILES['banner_url'], 'banner_');
            } catch (Exception $e) {
                // Handle upload error gracefully
                $_SESSION['upload_error'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'orgDash');
                exit;
            }
        }

        $this->eventModel->editEvent($eventId, $organizerId, $_POST, $bannerPic);

        header('Location: ' . SITE_URL . 'orgDash');
        exit;
    }

    public function deleteEvent()
    {
        if (!isset($_POST['event_id'])) {
            header('Location: ' . SITE_URL . 'orgDash');
            exit;
        }

        $organizerId = $this->orgModel->getOrganizerIdByUser($_SESSION['user_id']);

        $eventId = $_POST['event_id'];

        $this->eventModel->deleteEvent($eventId, $organizerId);

        header('Location: ' . SITE_URL . 'orgDash');
        exit;
    }

    public function manageEvent($id = null)
    {
        $id = (int) $id;

        if ($id <= 0) {
            header('Location: ' . SITE_URL . 'orgDash');
            exit;
        }

        $organizerId = $this->getOrganizerId();

        // getEventById requires both eventId AND organizerId
        $event = $this->eventModel->getEventById($id, $organizerId);

        if (!$event) {
            header('Location: ' . SITE_URL . 'orgDash');
            exit;
        }

        $this->view('organizer/manageEvent', ['event' => $event]);
    }

}
