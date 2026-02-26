<?php 
    class SponsorModel {
        private $db;
        public function __construct($db){
            $this->db = $db->conn;
        }
        public function updateProfile($id, $data, $profilePicPath = null){

            $fields = [];
            $params = ['id'=>$id];

            if(!empty($data['brand_name'])){
                $fields[] = "brand_name = :brand_name";
                $params['brand_name'] = $data['brand_name'];
            }

            if(!empty($data['industry'])){
                $fields[] = "industry = :industry";
                $params['industry'] = $data['industry'];
            }

            if(!empty($data['description'])){
                $fields[] = "description = :description";
                $params['description'] = $data['description'];
            }

            if(!empty($data['website'])){
                $fields[] = "website = :website";
                $params['website'] = $data['website'];
            }

            if(!empty($data['contact_email'])){
                $fields[] = "contact_email = :contact_email";
                $params['contact_email'] = $data['contact_email'];
            }

            if(!empty($data['contact_phone'])){
                $fields[] = "contact_phone = :contact_phone";
                $params['contact_phone'] = $data['contact_phone'];
            }

            if(!empty($data['budget_range'])){
                $fields[] = "budget_range = :budget_range";
                $params['budget_range'] = $data['budget_range'];
            }

            if ($profilePicPath) {
                $fields[] = "profile_pic = :profile_pic";
                $params['profile_pic'] = $profilePicPath;
            }

            $sql = "UPDATE sponsor_details SET " . implode(", ", $fields) . " WHERE user_id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute($params);
        }

    /**
     * CREATE - Create a new sponsor profile
     */
    public function createSponsorProfile($userId, $data = [], $profilePic = null){
        $sql = "INSERT INTO sponsor_details (
                user_id, brand_name, industry, description, 
                website, contact_email, contact_phone, budget_range, profile_pic
            ) VALUES (
                :user_id, :brand_name, :industry, :description,
                :website, :contact_email, :contact_phone, :budget_range, :profile_pic
            )";
        
        $params = [
            ':user_id' => $userId,
            ':brand_name' => $data['brand_name'] ?? '',
            ':industry' => $data['industry'] ?? null,
            ':description' => $data['description'] ?? null,
            ':website' => $data['website'] ?? null,
            ':contact_email' => $data['contact_email'] ?? null,
            ':contact_phone' => $data['contact_phone'] ?? null,
            ':budget_range' => $data['budget_range'] ?? null,
            ':profile_pic' => $profilePic
        ];
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * READ - Check if sponsor profile exists
     */
    public function profileExists($userId){
        $sql = "SELECT id FROM sponsor_details WHERE user_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    /**
     * Get sponsor ID by user ID
     */
    public function getSponsorIdByUser($userId){
        $sql = "SELECT id FROM sponsor_details WHERE user_id = :user_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    /**
     * READ - Get sponsor profile details by user ID
     */
    public function getSponsorProfileByUser($userId){
        $sql = "SELECT 
                    u.id as user_id, u.username, u.email, u.phone, u.full_name,
                    sd.id as sponsor_id, sd.brand_name, sd.industry, sd.description,
                    sd.website, sd.contact_email, sd.contact_phone, sd.profile_pic,
                    sd.budget_range, sd.verified, sd.updated_at
                FROM users u
                LEFT JOIN sponsor_details sd ON u.id = sd.user_id
                WHERE u.id = :user_id AND u.role = 'sponsor'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * DELETE - Delete sponsor profile and all related data
     */
    public function deleteSponsorProfile($userId){
        try {
            // Start transaction
            $this->db->beginTransaction();
            
            // Delete portfolio items
            $sql1 = "DELETE FROM sponsor_portfolio WHERE sponsor_id = (SELECT id FROM sponsor_details WHERE user_id = :user_id)";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->execute([':user_id' => $userId]);
            
            // Delete social links
            $sql2 = "DELETE FROM sponsor_social_links WHERE user_id = :user_id";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->execute([':user_id' => $userId]);
            
            // Delete profile
            $sql3 = "DELETE FROM sponsor_details WHERE user_id = :user_id";
            $stmt3 = $this->db->prepare($sql3);
            $stmt3->execute([':user_id' => $userId]);
            
            // Commit transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // ============================================

        /**
         * CREATE - Add a new portfolio item
         * @param int $sponsorId - Sponsor ID
         * @param array $data - Portfolio item data
         * @param array $files - File paths from upload
         * @return bool
         */
        public function addPortfolioItem($sponsorId, $data, $files = []){
            $sql = "INSERT INTO sponsor_portfolio (
                sponsor_id, title, brand_description, sponsorship_category, 
                past_collaboration, logo_url, banner_url, image_url, 
                event_name, year
            ) VALUES (
                :sponsor_id, :title, :brand_description, :sponsorship_category,
                :past_collaboration, :logo_url, :banner_url, :image_url,
                :event_name, :year
            )";

            $params = [
                ':sponsor_id' => $sponsorId,
                ':title' => $data['title'] ?? '',
                ':brand_description' => $data['brand_description'] ?? null,
                ':sponsorship_category' => $data['sponsorship_category'] ?? null,
                ':past_collaboration' => $data['past_collaboration'] ?? null,
                ':logo_url' => $files['logo'] ?? null,
                ':banner_url' => $files['banner'] ?? null,
                ':image_url' => $files['image'] ?? null,
                ':event_name' => $data['event_name'] ?? null,
                ':year' => $data['year'] ?? date('Y')
            ];

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        /**
         * READ - Get all portfolio items for a sponsor
         * @param int $sponsorId - Sponsor ID
         * @return array
         */
        public function getPortfolioItems($sponsorId){
            $sql = "SELECT * FROM sponsor_portfolio 
                    WHERE sponsor_id = :sponsor_id 
                    ORDER BY created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':sponsor_id' => $sponsorId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * READ - Get a single portfolio item
         * @param int $itemId - Portfolio item ID
         * @return array|null
         */
        public function getPortfolioItem($itemId){
            $sql = "SELECT * FROM sponsor_portfolio WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $itemId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * READ - Get portfolio item with sponsor details
         * @param int $itemId - Portfolio item ID
         * @return array|null
         */
        public function getPortfolioItemWithSponsor($itemId){
            $sql = "SELECT sp.*, 
                        sd.brand_name, sd.industry, sd.description, sd.website,
                        sd.contact_email, sd.contact_phone, sd.profile_pic as sponsor_logo,
                        sd.budget_range, sd.verified
                    FROM sponsor_portfolio sp
                    LEFT JOIN sponsor_details sd ON sp.sponsor_id = sd.id
                    WHERE sp.id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $itemId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * UPDATE - Edit a portfolio item
         * @param int $itemId - Portfolio item ID
         * @param int $sponsorId - Sponsor ID (for authorization)
         * @param array $data - Portfolio item data
         * @param array $files - File paths from upload
         * @return bool
         */
        public function updatePortfolioItem($itemId, $sponsorId, $data, $files = []){
            $fields = [];
            $params = [':id' => $itemId, ':sponsor_id' => $sponsorId];

            if(!empty($data['title'])){
                $fields[] = "title = :title";
                $params[':title'] = $data['title'];
            }

            if(isset($data['brand_description'])){
                $fields[] = "brand_description = :brand_description";
                $params[':brand_description'] = $data['brand_description'] ?: null;
            }

            if(isset($data['sponsorship_category'])){
                $fields[] = "sponsorship_category = :sponsorship_category";
                $params[':sponsorship_category'] = $data['sponsorship_category'] ?: null;
            }

            if(isset($data['past_collaboration'])){
                $fields[] = "past_collaboration = :past_collaboration";
                $params[':past_collaboration'] = $data['past_collaboration'] ?: null;
            }

            if(isset($data['event_name'])){
                $fields[] = "event_name = :event_name";
                $params[':event_name'] = $data['event_name'] ?: null;
            }

            if(isset($data['year'])){
                $fields[] = "year = :year";
                $params[':year'] = $data['year'] ?: date('Y');
            }

            // Handle file uploads - Always update if provided in files array (including null values means delete)
            if(array_key_exists('logo', $files) && !empty($files['logo'])){
                $fields[] = "logo_url = :logo_url";
                $params[':logo_url'] = $files['logo'];
            }

            if(array_key_exists('banner', $files) && !empty($files['banner'])){
                $fields[] = "banner_url = :banner_url";
                $params[':banner_url'] = $files['banner'];
            }

            if(array_key_exists('image', $files) && !empty($files['image'])){
                $fields[] = "image_url = :image_url";
                $params[':image_url'] = $files['image'];
            }

            if(empty($fields)){
                return true; // No updates
            }

            $fields[] = "updated_at = NOW()";
            
            $sql = "UPDATE sponsor_portfolio SET " . implode(", ", $fields) . 
                   " WHERE id = :id AND sponsor_id = :sponsor_id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        /**
         * DELETE - Remove a portfolio item
         * @param int $itemId - Portfolio item ID
         * @param int $sponsorId - Sponsor ID (for authorization)
         * @return bool
         */
        public function deletePortfolioItem($itemId, $sponsorId){
            $sql = "DELETE FROM sponsor_portfolio 
                    WHERE id = :id AND sponsor_id = :sponsor_id";
            
            $params = [
                ':id' => $itemId,
                ':sponsor_id' => $sponsorId
            ];

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        /**
         * Get sponsor's portfolio statistics
         */
        public function getPortfolioStats($sponsorId){
            $sql = "SELECT 
                    COUNT(*) as total_items,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_items,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_items,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_items
                    FROM sponsor_portfolio 
                    WHERE sponsor_id = :sponsor_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':sponsor_id' => $sponsorId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // ============================================
        // PROMOTE / FUND EVENT MANAGEMENT
        // ============================================

        /**
         * CREATE - Send a sponsorship request to an event
         * @param int $sponsorId - Sponsor ID
         * @param int $eventId - Event ID
         * @param array $data - Sponsorship data (amount, branding_level, resources)
         * @return bool
         */
        public function sendSponsorshipRequest($sponsorId, $eventId, $data){
            // Check if already sponsored
            $checkSql = "SELECT id FROM sponsorships WHERE sponsor_id = :sponsor_id AND event_id = :event_id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([':sponsor_id' => $sponsorId, ':event_id' => $eventId]);
            
            if($checkStmt->fetch()){
                return false; // Already sponsored this event
            }

            $sql = "INSERT INTO sponsorships (
                event_id, sponsor_id, amount, resources, branding_level, status
            ) VALUES (
                :event_id, :sponsor_id, :amount, :resources, :branding_level, 'pending'
            )";

            $params = [
                ':event_id' => $eventId,
                ':sponsor_id' => $sponsorId,
                ':amount' => $data['amount'] ?? null,
                ':resources' => $data['resources'] ?? null,
                ':branding_level' => $data['branding_level'] ?? 'basic'
            ];

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        /**
         * READ - Get all events sponsored by a sponsor
         * @param int $sponsorId - Sponsor ID
         * @return array
         */
        public function getSponsoredEvents($sponsorId){
            $sql = "SELECT 
                    s.*, 
                    e.title as event_title, 
                    e.banner_url as event_banner,
                    e.start_at, 
                    e.end_at,
                    e.location_text,
                    od.organization_name,
                    od.logo as organizer_logo
                    FROM sponsorships s
                    JOIN events e ON s.event_id = e.id
                    JOIN organizer_details od ON e.organizer_id = od.id
                    WHERE s.sponsor_id = :sponsor_id
                    ORDER BY s.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':sponsor_id' => $sponsorId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * READ - Get a single sponsored event
         * @param int $sponsorshipId - Sponsorship ID
         * @param int $sponsorId - Sponsor ID (for authorization)
         * @return array|null
         */
        public function getSponsoredEvent($sponsorshipId, $sponsorId){
            $sql = "SELECT 
                    s.*, 
                    e.title as event_title, 
                    e.description as event_description,
                    e.banner_url as event_banner,
                    e.start_at, 
                    e.end_at,
                    e.location_text,
                    e.capacity,
                    e.current_participants,
                    od.organization_name,
                    od.logo as organizer_logo,
                    od.contact_email as organizer_email,
                    od.contact_phone as organizer_phone
                    FROM sponsorships s
                    JOIN events e ON s.event_id = e.id
                    JOIN organizer_details od ON e.organizer_id = od.id
                    WHERE s.id = :id AND s.sponsor_id = :sponsor_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $sponsorshipId, ':sponsor_id' => $sponsorId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * UPDATE - Modify funding details of sponsorship
         * @param int $sponsorshipId - Sponsorship ID
         * @param int $sponsorId - Sponsor ID (for authorization)
         * @param array $data - Updated funding data
         * @return bool
         */
        public function updateFundingDetails($sponsorshipId, $sponsorId, $data){
            $fields = [];
            $params = [':id' => $sponsorshipId, ':sponsor_id' => $sponsorId];

            if(isset($data['amount'])){
                $fields[] = "amount = :amount";
                $params[':amount'] = $data['amount'] ?: null;
            }

            if(isset($data['resources'])){
                $fields[] = "resources = :resources";
                $params[':resources'] = $data['resources'] ?: null;
            }

            if(isset($data['branding_level'])){
                $fields[] = "branding_level = :branding_level";
                $params[':branding_level'] = $data['branding_level'] ?? 'basic';
            }

            if(empty($fields)){
                return true; // No updates
            }

            $fields[] = "updated_at = NOW()";
            
            $sql = "UPDATE sponsorships SET " . implode(", ", $fields) . 
                   " WHERE id = :id AND sponsor_id = :sponsor_id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        /**
         * READ - Get available events for sponsorship
         * @return array
         */
        public function getAvailableEvents(){
            $sql = "SELECT 
                    e.id,
                    e.title,
                    e.description,
                    e.banner_url,
                    e.start_at,
                    e.end_at,
                    e.location_text,
                    e.capacity,
                    e.current_participants,
                    od.organization_name,
                    od.logo as organizer_logo,
                    c.name as category_name
                    FROM events e
                    JOIN organizer_details od ON e.organizer_id = od.id
                    LEFT JOIN categories c ON e.category_id = c.id
                    WHERE e.status IN ('published', 'live')
                    AND e.start_at > NOW()
                    ORDER BY e.start_at ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * Get sponsorship statistics for sponsor
         */
        public function getSponsorshipStats($sponsorId){
            $sql = "SELECT 
                    COUNT(*) as total_sponsorships,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_sponsorships,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_sponsorships,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_sponsorships,
                    COALESCE(SUM(CASE WHEN status = 'approved' THEN amount ELSE 0 END), 0) as total_funded
                    FROM sponsorships 
                    WHERE sponsor_id = :sponsor_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':sponsor_id' => $sponsorId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // ============================================
        // INCOMING SPONSORSHIP REQUESTS (Organizer-Initiated)
        // ============================================

        /**
         * READ - Get all incoming sponsorship requests for a sponsor
         * @param int $sponsorId - Sponsor ID
         * @param string $status - Filter by status (optional)
         * @return array
         */
        public function getIncomingRequests($sponsorId, $status = null){
            $sql = "SELECT 
                    s.*, 
                    e.title as event_title, 
                    e.banner_url as event_banner,
                    e.start_at, 
                    e.end_at,
                    e.location_text,
                    e.capacity,
                    e.current_participants,
                    od.organization_name,
                    od.logo as organizer_logo,
                    od.contact_email as organizer_email,
                    od.contact_phone as organizer_phone
                    FROM sponsorships s
                    JOIN events e ON s.event_id = e.id
                    JOIN organizer_details od ON e.organizer_id = od.id
                    WHERE s.sponsor_id = :sponsor_id";
            
            $params = [':sponsor_id' => $sponsorId];

            if($status){
                $sql .= " AND s.status = :status";
                $params[':status'] = $status;
            }

            $sql .= " ORDER BY s.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        /**
         * READ - Get a single incoming sponsorship request
         * @param int $requestId - Sponsorship ID
         * @param int $sponsorId - Sponsor ID (for authorization)
         * @return array|null
         */
        public function getIncomingRequest($requestId, $sponsorId){
            $sql = "SELECT 
                    s.*, 
                    e.title as event_title, 
                    e.description as event_description,
                    e.banner_url as event_banner,
                    e.start_at, 
                    e.end_at,
                    e.location_text,
                    e.capacity,
                    e.current_participants,
                    od.organization_name,
                    od.logo as organizer_logo,
                    od.contact_email as organizer_email,
                    od.contact_phone as organizer_phone
                    FROM sponsorships s
                    JOIN events e ON s.event_id = e.id
                    JOIN organizer_details od ON e.organizer_id = od.id
                    WHERE s.id = :id AND s.sponsor_id = :sponsor_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $requestId, ':sponsor_id' => $sponsorId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        /**
         * UPDATE - Change sponsorship request status
         * @param int $requestId - Sponsorship ID
         * @param int $sponsorId - Sponsor ID (for authorization)
         * @param string $status - New status (accepted, rejected, negotiating, pending, completed)
         * @param string $notes - Sponsor notes
         * @return bool
         */
        public function updateRequestStatus($requestId, $sponsorId, $status, $notes = null){
            $fields = [];
            $params = [':id' => $requestId, ':sponsor_id' => $sponsorId];

            $fields[] = "status = :status";
            $params[':status'] = $status;

            if($status !== 'pending'){
                $fields[] = "responded_at = NOW()";
            }

            if($notes !== null){
                $fields[] = "sponsor_notes = :sponsor_notes";
                $params[':sponsor_notes'] = $notes;
            }

            $fields[] = "updated_at = NOW()";
            
            $sql = "UPDATE sponsorships SET " . implode(", ", $fields) . 
                   " WHERE id = :id AND sponsor_id = :sponsor_id";

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        /**
         * Add or update sponsor notes on a sponsorship request
         * @param int $requestId - Sponsorship ID
         * @param int $sponsorId - Sponsor ID (for authorization)
         * @param string $notes - Notes text
         * @return bool
         */
        public function addSponsorNotes($requestId, $sponsorId, $notes){
            $sql = "UPDATE sponsorships 
                    SET sponsor_notes = :notes, updated_at = NOW()
                    WHERE id = :id AND sponsor_id = :sponsor_id";
            
            $params = [
                ':id' => $requestId,
                ':sponsor_id' => $sponsorId,
                ':notes' => $notes
            ];

            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        }

        /**
         * Get statistics for incoming requests
         */
        public function getIncomingRequestStats($sponsorId){
            $sql = "SELECT 
                    COUNT(*) as total_requests,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests,
                    SUM(CASE WHEN status = 'negotiating' THEN 1 ELSE 0 END) as negotiating_requests,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_requests,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_requests,
                    COALESCE(SUM(CASE WHEN status = 'approved' THEN amount ELSE 0 END), 0) as total_approved_value
                    FROM sponsorships 
                    WHERE sponsor_id = :sponsor_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':sponsor_id' => $sponsorId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

?>