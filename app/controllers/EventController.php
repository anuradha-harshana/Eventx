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
                $_SESSION['upload_error'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'orgDash');
                exit;
            }
        }

        $eventId     = $this->eventModel->createEvent($organizerId, $_POST, $bannerPic);
        $pricingType = isset($_POST['pricing_type']) && $_POST['pricing_type'] === 'paid' ? 'paid' : 'free';

        if ($pricingType === 'paid') {
            // Persist each named ticket type
            if (!empty($_POST['tickets']) && is_array($_POST['tickets'])) {
                $this->eventModel->createTickets($eventId, $_POST['tickets']);
            }
        } else {
            // Free event — always insert a "Free Admission" ticket with no capacity limit
            $this->eventModel->createTickets($eventId, [[
                'name'     => 'Free Admission',
                'price'    => 0.00,
                'capacity' => null,
                'terms'    => null,
            ]]);
        }

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

    public function allParticipants($id = null)
    {
        $id = (int) $id;
        if ($id <= 0) {
            header('Location: ' . SITE_URL . 'orgDash');
            exit;
        }

        $organizerId = $this->getOrganizerId();
        $event = $this->eventModel->getEventById($id, $organizerId);

        if (!$event) {
            header('Location: ' . SITE_URL . 'orgDash');
            exit;
        }

        $registrationModel = $this->model('RegistrationModel');
        $participants = $registrationModel->getParticipantsByEvent($id);

        $this->view('organizer/allParticipants', [
            'event'        => $event,
            'participants' => $participants,
        ]);
    }

    public function getParticipants($id = null)
    {
        header('Content-Type: application/json');

        $id = (int) $id;
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid event ID']);
            return;
        }

        $registrationModel = $this->model('RegistrationModel');
        $participants = $registrationModel->getParticipantsByEvent($id);

        echo json_encode(['success' => true, 'participants' => $participants]);
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


    // ── Itinerary API ────────────────────────────────────────────────────

    public function getItinerary($id = null)
    {
        header('Content-Type: application/json');
        $id = (int)$id;
        if ($id <= 0) { echo json_encode(['success' => false, 'message' => 'Invalid event ID.']); return; }

        $organizerId = $this->getOrganizerId();
        if (!$this->eventModel->getEventById($id, $organizerId)) {
            echo json_encode(['success' => false, 'message' => 'Event not found.']);
            return;
        }

        $items = $this->eventModel->getItineraryItems($id);
        echo json_encode(['success' => true, 'items' => $items]);
    }

    public function addItinerary($id = null)
    {
        header('Content-Type: application/json');
        $id = (int)$id;
        if ($id <= 0) { echo json_encode(['success' => false, 'message' => 'Invalid event ID.']); return; }

        $organizerId = $this->getOrganizerId();
        $event = $this->eventModel->getEventById($id, $organizerId);
        if (!$event) {
            echo json_encode(['success' => false, 'message' => 'Event not found.']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['title']) || empty($data['start_time']) || empty($data['end_time'])) {
            echo json_encode(['success' => false, 'message' => 'Title, start time and end time are required.']);
            return;
        }

        // Normalise datetime-local format (YYYY-MM-DDTHH:MM → YYYY-MM-DD HH:MM:SS)
        $toDatetime = function(string $v): string {
            $v = str_replace('T', ' ', trim($v));
            return strlen($v) === 16 ? $v . ':00' : $v;
        };

        $startDt    = $toDatetime($data['start_time']);
        $endDt      = $toDatetime($data['end_time']);
        $eventStart = date('Y-m-d H:i:s', strtotime($event['start_at']));
        $eventEnd   = date('Y-m-d H:i:s', strtotime($event['end_at']));
        if ($startDt < $eventStart || $startDt > $eventEnd) {
            echo json_encode(['success' => false, 'message' => 'Start time is outside the event timeframe.']); return;
        }
        if ($endDt < $startDt || $endDt > $eventEnd) {
            echo json_encode(['success' => false, 'message' => 'End time must be after start and within the event timeframe.']); return;
        }

        $item = [
            'title'       => htmlspecialchars(strip_tags($data['title']), ENT_QUOTES),
            'description' => isset($data['description']) ? htmlspecialchars(strip_tags($data['description']), ENT_QUOTES) : null,
            'start_time'  => $toDatetime($data['start_time']),
            'end_time'    => $toDatetime($data['end_time']),
            'location'    => isset($data['location']) ? htmlspecialchars(strip_tags($data['location']), ENT_QUOTES) : null,
        ];

        $newId = $this->eventModel->addItineraryItem($id, $item);
        $item['id'] = $newId;
        echo json_encode(['success' => true, 'item' => $item]);
    }

    public function removeItinerary($id = null)
    {
        header('Content-Type: application/json');
        $id = (int)$id;
        if ($id <= 0) { echo json_encode(['success' => false, 'message' => 'Invalid event ID.']); return; }

        $organizerId = $this->getOrganizerId();
        if (!$this->eventModel->getEventById($id, $organizerId)) {
            echo json_encode(['success' => false, 'message' => 'Event not found.']);
            return;
        }

        $data    = json_decode(file_get_contents('php://input'), true);
        $itemId  = isset($data['item_id']) ? (int)$data['item_id'] : 0;

        if ($itemId <= 0) { echo json_encode(['success' => false, 'message' => 'Invalid item ID.']); return; }

        $this->eventModel->removeItineraryItem($itemId, $id);
        echo json_encode(['success' => true]);
    }

    public function checkin()
    {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) session_start();

        $data = json_decode(file_get_contents('php://input'), true);
        $verifyCode = isset($data['verify_code']) ? strtoupper(trim($data['verify_code'])) : '';
        $eventId    = isset($data['event_id'])    ? (int)$data['event_id']                 : 0;

        if (!$verifyCode || $eventId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid input.']);
            return;
        }

        // Ensure organizer owns this event
        $organizerId = $this->getOrganizerId();
        $event = $this->eventModel->getEventById($eventId, $organizerId);
        if (!$event) {
            echo json_encode(['success' => false, 'message' => 'Event not found.']);
            return;
        }

        $registrationModel = $this->model('RegistrationModel');
        $result = $registrationModel->checkinByCode($verifyCode, $eventId);

        echo json_encode($result);
    }

    public function checkinStats($id = null)
    {
        header('Content-Type: application/json');

        $id = (int)$id;
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid event ID.']);
            return;
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        $organizerId = $this->getOrganizerId();
        $event = $this->eventModel->getEventById($id, $organizerId);
        if (!$event) {
            echo json_encode(['success' => false, 'message' => 'Event not found.']);
            return;
        }

        $registrationModel = $this->model('RegistrationModel');
        $stats = $registrationModel->getCheckinStats($id);

        echo json_encode(['success' => true, 'stats' => $stats]);
    }

}
