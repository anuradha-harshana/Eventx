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
            (organizer_id, title, description, category_id, location_text, start_at, end_at, capacity, status, banner_url) 
            VALUES 
            (:organizer_id, :title, :description, :category_id, :location_text, :start_at, :end_at, :capacity, :status, :banner_url)
        ");

        return $stmt->execute([
            'organizer_id' => $organizerId,
            'title' => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            'location_text' => $data['location_text'],
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

    /**
     * Get upcoming events (after current date)
     * @param int|null $limit - Limit results
     * @return array
     */
    public function getUpcomingEvents($limit = null){
        $sql = "
            SELECT 
                e.id,
                e.title,
                e.banner_url,
                e.location_text,
                e.start_at,
                e.end_at,
                e.capacity,
                e.current_participants,
                e.created_at,
                c.name AS category_name
            FROM events e
            JOIN categories c ON e.category_id = c.id
            WHERE e.status = 'published' AND e.start_at > NOW()
            ORDER BY e.start_at ASC
        ";
        
        if($limit) $sql .= " LIMIT $limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get past events (before current date)
     * @param int|null $limit - Limit results
     * @return array
     */
    public function getPastEvents($limit = null){
        $sql = "
            SELECT 
                e.id,
                e.title,
                e.banner_url,
                e.location_text,
                e.start_at,
                e.end_at,
                e.capacity,
                e.current_participants,
                e.created_at,
                c.name AS category_name
            FROM events e
            JOIN categories c ON e.category_id = c.id
            WHERE e.status = 'published' AND e.start_at <= NOW()
            ORDER BY e.start_at DESC
        ";
        
        if($limit) $sql .= " LIMIT $limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get events by category
     * @param int $categoryId - Category ID
     * @return array
     */
    public function getEventsByCategory($categoryId){
        $stmt = $this->db->prepare("
            SELECT 
                e.id,
                e.title,
                e.banner_url,
                e.location_text,
                e.start_at,
                e.end_at,
                e.capacity,
                e.current_participants,
                e.created_at,
                c.name AS category_name
            FROM events e
            JOIN categories c ON e.category_id = c.id
            WHERE e.status = 'published' AND e.category_id = :category_id AND e.start_at > NOW()
            ORDER BY e.start_at ASC
        ");

        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get events by location
     * @param string $location - Location text
     * @return array
     */
    public function getEventsByLocation($location){
        $stmt = $this->db->prepare("
            SELECT 
                e.id,
                e.title,
                e.banner_url,
                e.location_text,
                e.start_at,
                e.end_at,
                e.capacity,
                e.current_participants,
                e.created_at,
                c.name AS category_name
            FROM events e
            JOIN categories c ON e.category_id = c.id
            WHERE e.status = 'published' AND e.location_text LIKE :location AND e.start_at > NOW()
            ORDER BY e.start_at ASC
        ");

        $stmt->execute(['location' => '%' . trim($location) . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get popular events (by participant count)
     * @param int|null $limit - Limit results
     * @return array
     */
    public function getPopularEvents($limit = null){
        $sql = "
            SELECT 
                e.id,
                e.title,
                e.banner_url,
                e.location_text,
                e.start_at,
                e.end_at,
                e.capacity,
                e.current_participants,
                e.created_at,
                c.name AS category_name
            FROM events e
            JOIN categories c ON e.category_id = c.id
            WHERE e.status = 'published' AND e.start_at > NOW()
            ORDER BY e.current_participants DESC, e.start_at ASC
        ";
        
        if($limit) $sql .= " LIMIT $limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Search events by keyword
     * @param string $keyword - Search keyword
     * @return array
     */
    public function searchEvents($keyword){
        $stmt = $this->db->prepare("
            SELECT 
                e.id,
                e.title,
                e.banner_url,
                e.location_text,
                e.start_at,
                e.end_at,
                e.capacity,
                e.current_participants,
                e.created_at,
                c.name AS category_name
            FROM events e
            JOIN categories c ON e.category_id = c.id
            WHERE e.status = 'published' 
                AND (e.title LIKE :keyword OR e.description LIKE :keyword OR e.location_text LIKE :keyword)
                AND e.start_at > NOW()
            ORDER BY e.start_at ASC
        ");

        $searchTerm = '%' . trim($keyword) . '%';
        $stmt->execute(['keyword' => $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
