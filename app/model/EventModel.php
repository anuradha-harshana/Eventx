<?php 

class EventModel {
    private $db;

    public function __construct($db){
        $this->db = $db->conn;
    }

    // Create event
    public function createEvent($organizerId, $data, $bannerPic = null){
        $stmt = $this->db->prepare("
            INSERT INTO events 
            (organizer_id, title, description, category_id, location_text, location_map, start_at, end_at, capacity, status, banner_url) 
            VALUES 
            (:organizer_id, :title, :description, :category_id, :location_text, :location_link, :start_at, :end_at, :capacity, :status, :banner_url)
        ");

        return $stmt->execute([
            'organizer_id' => $organizerId,
            'title' => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            'location_text' => $data['location_text'],
            'location_link' => $data['location_link'],
            'start_at' => $data['start_at'],
            'end_at' => $data['end_at'],
            'capacity' => $data['capacity'],
            'status' => $data['status'],
            'banner_url' => $bannerPic,
        ]);
    }

    public function getEventDetails($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                e.*,
                c.name AS category_name
            FROM events e
            LEFT JOIN categories c ON e.category_id = c.id
            WHERE e.id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $eventId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getEventById($eventId, $organizerId){
        $stmt = $this->db->prepare("
            SELECT * FROM events 
            WHERE id = :id AND organizer_id = :organizer_id
        ");

        $stmt->execute([
            'id' => $eventId,
            'organizer_id' => $organizerId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getEvents(){
        $stmt = $this->db->prepare("
            SELECT 
            e.id,
            e.title,
            e.banner_url,
            e.location_text,
            e.start_at,
            e.end_at,
            e.capacity,
            e.created_at,
            c.name AS category_name
        FROM events e
        JOIN categories c ON e.category_id = c.id
        WHERE e.status = 'published'
        ORDER BY e.start_at ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all events for one organizer
    public function getEventsByOrganizer($organizerId){
        $stmt = $this->db->prepare("
            SELECT * FROM events 
            WHERE organizer_id = :organizer_id 
            ORDER BY start_at DESC
        ");

        $stmt->execute(['organizer_id' => $organizerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEventsStatusByOrganizer($organizerId){
            $stmt = $this->db->prepare("
            SELECT 
                COUNT(*) AS total_events,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) AS published_events,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS draft_events
            FROM events
            WHERE organizer_id = :organizer_id
        ");

        $stmt->execute(['organizer_id' => $organizerId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategories()
        {
            $stmt = $this->db->prepare("
                SELECT id, name 
                FROM categories
                ORDER BY name ASC
            ");

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    // Edit event (secure)
    public function editEvent($eventId, $organizerId, $data, $bannerPic = null){
        $fields = [];
        $params = [
            'id' => $eventId,
            'organizer_id' => $organizerId
        ];

        if(isset($data['title'])){
            $fields[] = "title = :title";
            $params['title'] = $data['title'];
        }

        if(isset($data['description'])){
            $fields[] = "description = :description";
            $params['description'] = $data['description'];
        }

        if(isset($data['location_text'])){
            $fields[] = "location_text = :location_text";
            $params['location_text'] = $data['location_text'];
        }

        if(isset($data['start_at'])){
            $fields[] = "start_at = :start_at";
            $params['start_at'] = $data['start_at'];
        }

        if(isset($data['end_at'])){
            $fields[] = "end_at = :end_at";
            $params['end_at'] = $data['end_at'];
        }

        if(isset($data['capacity'])){
            $fields[] = "capacity = :capacity";
            $params['capacity'] = $data['capacity'];
        }

        if(isset($data['status'])){
            $fields[] = "status = :status";
            $params['status'] = $data['status'];
        }

        if ($bannerPic) {
            $fields[] = "banner_url = :banner_url";
            $params['banner_url'] = $bannerPic;
        }

        // Prevent empty update crash
        if(empty($fields)){
            return false;
        }

        $sql = "
            UPDATE events 
            SET " . implode(", ", $fields) . " 
            WHERE id = :id AND organizer_id = :organizer_id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($params);
    }

    public function deleteEvent($eventId, $organizerId){
        $stmt = $this->db->prepare("
            DELETE FROM events 
            WHERE id = :id AND organizer_id = :organizer_id
        ");

        return $stmt->execute([
            'id' => $eventId,
            'organizer_id' => $organizerId
        ]);
    }
}
