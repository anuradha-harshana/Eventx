<?php

class PaymentController extends Controller {

    private $eventModel;
    private $registrationModel;
    private $participantModel;

    public function __construct() {
        Middleware::role('participant');
        $this->eventModel        = $this->model('EventModel');
        $this->registrationModel = $this->model('RegistrationModel');
        $this->participantModel  = $this->model('ParticipantModel');
    }

    // POST /payment/initiate  — called via fetch() from eventView (JSON body)
    public function initiate() {
        header('Content-Type: application/json');

        $raw  = file_get_contents('php://input');
        $body = json_decode($raw, true);

        $eventId    = isset($body['event_id']) ? (int)$body['event_id'] : 0;
        $selections = $body['tickets'] ?? [];

        if (!$eventId || empty($selections)) {
            echo json_encode(['success' => false, 'message' => 'Invalid request.']);
            return;
        }

        $event = $this->eventModel->getEventDetails($eventId);
        if (!$event || $event['pricing_type'] !== 'paid') {
            echo json_encode(['success' => false, 'message' => 'Event not found or is not a paid event.']);
            return;
        }

        $allTickets = $this->eventModel->getTicketsByEvent($eventId);
        $ticketMap  = [];
        foreach ($allTickets as $t) {
            $ticketMap[(int)$t['id']] = $t;
        }

        $orderItems = [];
        $total      = 0.0;

        foreach ($selections as $sel) {
            $tid = (int)($sel['ticket_id'] ?? 0);
            $qty = (int)($sel['quantity']  ?? 0);

            if ($qty <= 0 || !isset($ticketMap[$tid])) continue;

            $t = $ticketMap[$tid];

            if ($qty > (int)$t['available_count']) {
                echo json_encode(['success' => false, 'message' => 'Not enough availability for: ' . htmlspecialchars($t['name'])]);
                return;
            }

            $orderItems[] = [
                'ticket_id' => $tid,
                'name'      => $t['name'],
                'price'     => (float)$t['price'],
                'quantity'  => $qty,
                'subtotal'  => $qty * (float)$t['price'],
            ];
            $total += $qty * (float)$t['price'];
        }

        if (empty($orderItems)) {
            echo json_encode(['success' => false, 'message' => 'No valid tickets selected.']);
            return;
        }

        $_SESSION['pending_payment'] = [
            'event_id' => $eventId,
            'event'    => $event,
            'items'    => $orderItems,
            'total'    => $total,
        ];

        echo json_encode(['success' => true, 'redirect' => SITE_URL . 'payment/checkout']);
    }

    // GET /payment/checkout
    public function checkout() {
        if (empty($_SESSION['pending_payment'])) {
            header('Location: ' . SITE_URL . 'explore');
            exit;
        }

        $order = $_SESSION['pending_payment'];
        $this->view('participant/checkout', ['order' => $order]);
    }

    // POST /payment/complete
    public function complete() {
        if (empty($_SESSION['pending_payment'])) {
            header('Location: ' . SITE_URL . 'explore');
            exit;
        }

        $order         = $_SESSION['pending_payment'];
        $participantId = $this->participantModel->getParticipantByUserId($_SESSION['user_id']);

        if (!$participantId) {
            unset($_SESSION['pending_payment']);
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Participant profile not found. Please complete your profile first.'];
            header('Location: ' . SITE_URL . 'parProf');
            exit;
        }

        $success = $this->registrationModel->registerPaid($participantId, $order);

        unset($_SESSION['pending_payment']);

        if ($success) {
            $_SESSION['flash'] = [
                'type'    => 'success',
                'message' => 'Payment successful! You are now registered for ' . $order['event']['title'] . '.',
            ];
            header('Location: ' . SITE_URL . 'myEvents');
        } else {
            $_SESSION['flash'] = [
                'type'    => 'error',
                'message' => 'Registration failed. You may already be registered, or the tickets are no longer available.',
            ];
            header('Location: ' . SITE_URL . 'explore');
        }
        exit;
    }
}
