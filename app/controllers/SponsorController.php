<?php 

    class SponsorController extends Controller {
        private $sponModel;
        
        public function __construct(){
            Middleware::role(roles: 'sponsor');
            $this->sponModel = $this->model('SponsorModel');
        }

        public function dashboard(){
            $this->view('sponsor/dashboard');
        }

        public function analytics(){
            $this->view('sponsor/analytics');
        }

        public function profile(){
            $profileExists = $this->sponModel->profileExists($_SESSION['user_id']);
            
            if(!$profileExists){
                // No profile exists, show create form
                $this->view('sponsor/createProfile');
                return;
            }
            
            // Profile exists, show view
            $profile = $this->sponModel->getSponsorProfileByUser($_SESSION['user_id']);
            $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
            
            $portfolioItems = [];
            if($sponsorId){
                $portfolioItems = $this->sponModel->getPortfolioItems($sponsorId);
            }
            
            $this->view('sponsor/viewProfile', [
                'profile' => $profile,
                'portfolioItems' => $portfolioItems
            ]);
        }

        /**
         * CREATE - Show create profile form
         */
        public function createProfile(){
            // Check if already has profile
            if($this->sponModel->profileExists($_SESSION['user_id'])){
                header('Location: ' . SITE_URL . 'sponProf');
                exit;
            }
            $this->view('sponsor/createProfile');
        }

        /**
         * SAVE - Create sponsor profile
         */
        public function saveProfile(){
            // Verify user is sponsor
            if($_SESSION['role'] !== 'sponsor'){
                $_SESSION['error_message'] = "Unauthorized access";
                header('Location: ' . SITE_URL . 'sponDash');
                exit;
            }

            // Check if profile already exists
            if($this->sponModel->profileExists($_SESSION['user_id'])){
                $_SESSION['error_message'] = "Profile already exists";
                header('Location: ' . SITE_URL . 'sponProf');
                exit;
            }

            // Validate required fields
            if(empty($_POST['brand_name'])){
                $_SESSION['error_message'] = "Brand name is required";
                header('Location: ' . SITE_URL . 'sponProf');
                exit;
            }

            try {
                $profilePicPath = null;

                // Handle profile picture upload
                if(!empty($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE){
                    require_once __DIR__ . '/../model/FileUploader.php';
                    $uploader = new FileUploader(__DIR__ . '/../../uploads/profile/');
                    $profilePicPath = $uploader->upload($_FILES['profile_pic'], 'sponsor_');
                }

                // Create profile with profile picture
                $success = $this->sponModel->createSponsorProfile($_SESSION['user_id'], $_POST, $profilePicPath);

                if($success){
                    $_SESSION['success_message'] = "Sponsor profile created successfully!";
                    header('Location: ' . SITE_URL . 'sponProf');
                    exit;
                } else {
                    throw new Exception("Failed to create profile");
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'sponProf');
                exit;
            }
        }

        /**
         * EDIT - Show edit profile form
         */
        public function editProfile(){
            $profile = $this->sponModel->getSponsorProfileByUser($_SESSION['user_id']);
            
            if(!$profile || empty($profile['sponsor_id'])){
                $_SESSION['error_message'] = "Profile not found";
                header('Location: ' . SITE_URL . 'sponProf');
                exit;
            }

            $this->view('sponsor/editProfile', ['profile' => $profile]);
        }

        /**
         * UPDATE - Save profile changes
         */
        public function updateProfile(){
            $profile = $this->sponModel->getSponsorProfileByUser($_SESSION['user_id']);
            
            if(!$profile || empty($profile['sponsor_id'])){
                $_SESSION['error_message'] = "Profile not found";
                header('Location: ' . SITE_URL . 'sponProf');
                exit;
            }

            try {
                $profilePicPath = null;

                // Handle profile picture upload
                if(!empty($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] !== UPLOAD_ERR_NO_FILE){
                    require_once __DIR__ . '/../model/FileUploader.php';
                    $uploader = new FileUploader(__DIR__ . '/../../uploads/profile/');
                    $profilePicPath = $uploader->upload($_FILES['profile_pic'], 'sponsor_');
                }

                // Update profile
                $success = $this->sponModel->updateProfile($_SESSION["user_id"], $_POST, $profilePicPath);

                if($success){
                    $_SESSION['success_message'] = "Profile updated successfully!";
                    header('Location: ' . SITE_URL . 'sponProf');
                    exit;
                } else {
                    throw new Exception("Failed to update profile");
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'sponProf');
                exit;
            }
        }

        /**
         * DELETE - Delete sponsor profile
         */
        public function deleteProfile(){
            if(!isset($_POST['confirm_delete'])){
                $_SESSION['error_message'] = "Profile deletion not confirmed";
                header('Location: ' . SITE_URL . 'sponProf');
                exit;
            }

            try {
                $success = $this->sponModel->deleteSponsorProfile($_SESSION['user_id']);

                if($success){
                    $_SESSION['success_message'] = "Profile deleted successfully";
                    header('Location: ' . SITE_URL . 'sponDash');
                    exit;
                } else {
                    throw new Exception("Failed to delete profile");
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'sponProf');
                exit;
            }
        }

        // ============================================
        // PORTFOLIO MANAGEMENT
        // ============================================

        /**
         * Display portfolio items
         */
        public function viewPortfolio(){
            $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
            
            if(!$sponsorId){
                $_SESSION['error_message'] = "Sponsor profile not found";
                header('Location: ' . SITE_URL . 'sponDash');
                exit;
            }
            
            $portfolioItems = $this->sponModel->getPortfolioItems($sponsorId);
            $portfolioStats = $this->sponModel->getPortfolioStats($sponsorId);
            
            $this->view('sponsor/portfolio/viewPortfolio', [
                'portfolioItems' => $portfolioItems,
                'portfolioStats' => $portfolioStats
            ]);
        }

        /**
         * View single portfolio item
         */
        public function viewPortfolioItem(){
            $itemId = $_GET['id'] ?? null;
            
            if(!$itemId){
                $_SESSION['error_message'] = "Portfolio item not found";
                header('Location: ' . SITE_URL . 'sponPortfolio');
                exit;
            }

            $item = $this->sponModel->getPortfolioItemWithSponsor($itemId);
            
            if(!$item){
                $_SESSION['error_message'] = "Portfolio item not found";
                header('Location: ' . SITE_URL . 'sponPortfolio');
                exit;
            }

            $this->view('sponsor/portfolio/viewSinglePortfolio', [
                'item' => $item
            ]);
        }

        /**
         * Show add portfolio item form
         */
        public function addPortfolio(){
            $this->view('sponsor/portfolio/addPortfolio');
        }

        /**
         * Handle portfolio item creation
         */
        public function createPortfolio(){
            require_once __DIR__ . '/../model/FileUploader.php';

            $uploadDir = __DIR__ . '/../../uploads/portfolio/';
            $files = [];
            
            try {
                $uploader = new FileUploader($uploadDir);

                // Upload logo
                if(!empty($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE){
                    $files['logo'] = $uploader->upload($_FILES['logo'], 'logo_');
                }

                // Upload banner
                if(!empty($_FILES['banner']) && $_FILES['banner']['error'] !== UPLOAD_ERR_NO_FILE){
                    $files['banner'] = $uploader->upload($_FILES['banner'], 'banner_');
                }

                // Upload image
                if(!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
                    $files['image'] = $uploader->upload($_FILES['image'], 'image_');
                }

                // Get sponsor_id from user_id
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                // Add portfolio item
                $success = $this->sponModel->addPortfolioItem($sponsorId, $_POST, $files);

                if($success){
                    $_SESSION['success_message'] = "Portfolio item added successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to add portfolio item";
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
            }

            header('Location: ' . SITE_URL . 'sponPortfolio');
            exit;
        }

        /**
         * Show edit portfolio item form
         */
        public function editPortfolio(){
            $itemId = $_GET['id'] ?? null;
            
            if(!$itemId){
                header('Location: ' . SITE_URL . 'sponPortfolio');
                exit;
            }

            $portfolioItem = $this->sponModel->getPortfolioItem($itemId);
            
            // Verify ownership
            if(!$portfolioItem){
                $_SESSION['error_message'] = "Portfolio item not found";
                header('Location: ' . SITE_URL . 'sponPortfolio');
                exit;
            }

            // Get sponsor_id for this user
            $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);

            if(!$sponsorId || $portfolioItem['sponsor_id'] != $sponsorId){
                $_SESSION['error_message'] = "Unauthorized access";
                header('Location: ' . SITE_URL . 'sponPortfolio');
                exit;
            }

            $this->view('sponsor/portfolio/editPortfolio', [
                'portfolioItem' => $portfolioItem
            ]);
        }

        /**
         * Handle portfolio item update
         */
        public function updatePortfolio(){
            require_once __DIR__ . '/../model/FileUploader.php';

            $itemId = $_POST['id'] ?? null;
            
            if(!$itemId){
                $_SESSION['error_message'] = "Invalid portfolio item";
                header('Location: ' . SITE_URL . 'sponPortfolio');
                exit;
            }

            $uploadDir = __DIR__ . '/../../uploads/portfolio/';
            $files = [];

            try {
                // Get current portfolio item to preserve existing images
                $currentItem = $this->sponModel->getPortfolioItem($itemId);
                
                if(!$currentItem){
                    throw new Exception("Portfolio item not found");
                }

                $uploader = new FileUploader($uploadDir);

                // Upload logo if provided (keep existing if not)
                if(!empty($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE){
                    $files['logo'] = $uploader->upload($_FILES['logo'], 'logo_');
                } else {
                    // Keep existing logo if no new upload
                    $files['logo'] = $currentItem['logo_url'];
                }

                // Upload banner if provided (keep existing if not)
                if(!empty($_FILES['banner']) && $_FILES['banner']['error'] !== UPLOAD_ERR_NO_FILE){
                    $files['banner'] = $uploader->upload($_FILES['banner'], 'banner_');
                } else {
                    // Keep existing banner if no new upload
                    $files['banner'] = $currentItem['banner_url'];
                }

                // Upload image if provided (keep existing if not)
                if(!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
                    $files['image'] = $uploader->upload($_FILES['image'], 'image_');
                } else {
                    // Keep existing image if no new upload
                    $files['image'] = $currentItem['image_url'];
                }

                // Get sponsor_id
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                // Verify ownership
                if($currentItem['sponsor_id'] != $sponsorId){
                    throw new Exception("Unauthorized access");
                }

                // Update portfolio item
                $success = $this->sponModel->updatePortfolioItem($itemId, $sponsorId, $_POST, $files);

                if($success){
                    $_SESSION['success_message'] = "Portfolio item updated successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to update portfolio item";
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
            }

            header('Location: ' . SITE_URL . 'sponPortfolio');
            exit;
        }

        /**
         * Handle portfolio item deletion
         */
        public function deletePortfolio(){
            $itemId = $_POST['portfolio_id'] ?? $_POST['id'] ?? null;

            if(!$itemId){
                $_SESSION['error_message'] = "Invalid portfolio item";
                header('Location: ' . SITE_URL . 'sponPortfolio');
                exit;
            }

            try {
                // Get sponsor_id
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                // Delete portfolio item
                $success = $this->sponModel->deletePortfolioItem($itemId, $sponsorId);

                if($success){
                    $_SESSION['success_message'] = "Portfolio item deleted successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to delete portfolio item";
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
            }

            header('Location: ' . SITE_URL . 'sponPortfolio');
            exit;
        }

        // ============================================
        // PROMOTE / FUND EVENT MANAGEMENT
        // ============================================

        /**
         * Show list of events available for sponsorship
         */
        public function fundEvent(){
            $availableEvents = $this->sponModel->getAvailableEvents();
            
            $this->view('sponsor/sponsorships/fundEvent', [
                'availableEvents' => $availableEvents
            ]);
        }

        /**
         * View all sponsored events
         */
        public function mySponsorships(){
            $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
            
            if(!$sponsorId){
                $_SESSION['error_message'] = "Sponsor profile not found";
                header('Location: ' . SITE_URL . 'sponDash');
                exit;
            }
            
            $sponsoredEvents = $this->sponModel->getSponsoredEvents($sponsorId);
            $sponsorshipStats = $this->sponModel->getSponsorshipStats($sponsorId);
            
            $this->view('sponsor/sponsorships/mySponsorships', [
                'sponsoredEvents' => $sponsoredEvents,
                'stats' => $sponsorshipStats
            ]);
        }

        /**
         * View single sponsorship details
         */
        public function viewSponsorship(){
            $sponsorshipId = $_GET['id'] ?? null;
            
            if(!$sponsorshipId){
                header('Location: ' . SITE_URL . 'sponMySponsorships');
                exit;
            }

            $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
            
            if(!$sponsorId){
                $_SESSION['error_message'] = "Sponsor profile not found";
                header('Location: ' . SITE_URL . 'sponDash');
                exit;
            }

            $sponsorship = $this->sponModel->getSponsoredEvent($sponsorshipId, $sponsorId);
            
            if(!$sponsorship){
                $_SESSION['error_message'] = "Sponsorship not found";
                header('Location: ' . SITE_URL . 'sponMySponsorships');
                exit;
            }

            $this->view('sponsor/sponsorships/viewSponsorship', [
                'sponsorship' => $sponsorship
            ]);
        }

        /**
         * Handle sponsorship request submission
         */
        public function sendRequest(){
            $eventId = $_POST['event_id'] ?? null;

            if(!$eventId){
                $_SESSION['error_message'] = "Event not specified";
                header('Location: ' . SITE_URL . 'sponFundEvent');
                exit;
            }

            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                $success = $this->sponModel->sendSponsorshipRequest($sponsorId, $eventId, $_POST);

                if($success){
                    $_SESSION['success_message'] = "Sponsorship request sent successfully!";
                    header('Location: ' . SITE_URL . 'sponMySponsorships');
                } else {
                    $_SESSION['error_message'] = "You have already requested to sponsor this event";
                    header('Location: ' . SITE_URL . 'sponFundEvent');
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'sponFundEvent');
            }
            exit;
        }

        /**
         * Handle funding details update
         */
        public function updateFunding(){
            $sponsorshipId = $_POST['id'] ?? null;

            if(!$sponsorshipId){
                $_SESSION['error_message'] = "Invalid sponsorship";
                header('Location: ' . SITE_URL . 'sponMySponsorships');
                exit;
            }

            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                $success = $this->sponModel->updateFundingDetails($sponsorshipId, $sponsorId, $_POST);

                if($success){
                    $_SESSION['success_message'] = "Funding details updated successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to update funding details";
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
            }

            header('Location: ' . SITE_URL . 'sponSponsor?id=' . $sponsorshipId);
            exit;
        }

        // ============================================
        // INCOMING SPONSORSHIP REQUESTS
        // ============================================

        /**
         * View all incoming sponsorship requests from organizers
         */
        public function viewRequests(){
            $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
            
            if(!$sponsorId){
                $_SESSION['error_message'] = "Sponsor profile not found";
                header('Location: ' . SITE_URL . 'sponDash');
                exit;
            }

            // Get filter if provided
            $status = $_GET['status'] ?? null;
            
            $incomingRequests = $this->sponModel->getIncomingRequests($sponsorId, $status);
            $requestStats = $this->sponModel->getIncomingRequestStats($sponsorId);
            
            $this->view('sponsor/requests/viewRequests', [
                'incomingRequests' => $incomingRequests,
                'stats' => $requestStats,
                'filter' => $status
            ]);
        }

        /**
         * View single incoming sponsorship request
         */
        public function viewRequest(){
            $requestId = $_GET['id'] ?? null;
            
            if(!$requestId){
                header('Location: ' . SITE_URL . 'sponRequests');
                exit;
            }

            $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
            
            if(!$sponsorId){
                $_SESSION['error_message'] = "Sponsor profile not found";
                header('Location: ' . SITE_URL . 'sponDash');
                exit;
            }

            $request = $this->sponModel->getIncomingRequest($requestId, $sponsorId);
            
            if(!$request){
                $_SESSION['error_message'] = "Request not found";
                header('Location: ' . SITE_URL . 'sponRequests');
                exit;
            }

            $this->view('sponsor/requests/viewRequest', [
                'request' => $request
            ]);
        }

        /**
         * Handle incoming request status update
         */
        public function updateRequestStatus(){
            $requestId = $_POST['id'] ?? null;
            $newStatus = $_POST['status'] ?? null;

            if(!$requestId || !$newStatus){
                $_SESSION['error_message'] = "Invalid request data";
                header('Location: ' . SITE_URL . 'sponRequests');
                exit;
            }

            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                $notes = $_POST['notes'] ?? null;
                $success = $this->sponModel->updateRequestStatus($requestId, $sponsorId, $newStatus, $notes);

                if($success){
                    $_SESSION['success_message'] = "Request status updated successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to update request status";
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
            }

            header('Location: ' . SITE_URL . 'sponRequest?id=' . $requestId);
            exit;
        }

        /**
         * Handle adding sponsor notes to request
         */
        public function addRequestNotes(){
            $requestId = $_POST['id'] ?? null;
            $notes = $_POST['notes'] ?? null;

            if(!$requestId || !$notes){
                $_SESSION['error_message'] = "Invalid request or empty notes";
                header('Location: ' . SITE_URL . 'sponRequest?id=' . $requestId);
                exit;
            }

            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                $success = $this->sponModel->addSponsorNotes($requestId, $sponsorId, $notes);

                if($success){
                    $_SESSION['success_message'] = "Notes added successfully!";
                } else {
                    $_SESSION['error_message'] = "Failed to add notes";
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
            }

            header('Location: ' . SITE_URL . 'sponRequest?id=' . $requestId);
            exit;
        }

        // ============================================
        // PROMOTION MANAGEMENT (CREATE, READ, UPDATE, DELETE)
        // ============================================

        /**
         * VIEW - Show all promotions
         */
        public function viewPromotions(){
            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    $_SESSION['error_message'] = "Sponsor profile not found";
                    header('Location: ' . SITE_URL . 'sponDash');
                    exit;
                }

                $promotionModel = $this->model('PromotionModel');
                $promotions = $promotionModel->getPromotions($sponsorId);
                $stats = $promotionModel->getPromotionStats($sponsorId);

                $this->view('sponsor/promotions/viewPromotions', [
                    'promotions' => $promotions,
                    'stats' => $stats
                ]);

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'sponDash');
                exit;
            }
        }

        /**
         * CREATE - Show create promotion form
         */
        public function createPromotionForm(){
            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    $_SESSION['error_message'] = "Sponsor profile not found";
                    header('Location: ' . SITE_URL . 'sponDash');
                    exit;
                }

                $promotionModel = $this->model('PromotionModel');
                $events = $promotionModel->getAvailableEvents();

                $this->view('sponsor/promotions/createPromotion', [
                    'events' => $events
                ]);

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'sponPromotions');
                exit;
            }
        }

        /**
         * CREATE - Save promotion
         */
        public function createPromotion(){
            require_once __DIR__ . '/../model/FileUploader.php';

            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                // Validate required fields
                if(empty($_POST['title'])){
                    throw new Exception("Title is required");
                }

                $promotionModel = $this->model('PromotionModel');
                $imageFile = null;

                // Handle image upload
                if(!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
                    $uploader = new FileUploader(__DIR__ . '/../../uploads/promotions/');
                    $imageFile = $uploader->upload($_FILES['image'], 'promo_');
                }

                // Create promotion
                $success = $promotionModel->createPromotion($sponsorId, $_POST, $imageFile);

                if($success){
                    $_SESSION['success_message'] = "Promotional post created successfully!";
                    header('Location: ' . SITE_URL . 'sponPromotions');
                } else {
                    throw new Exception("Failed to create promotion");
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'sponPromotions');
            }
            exit;
        }

        /**
         * UPDATE - Show edit promotion form
         */
        public function editPromotionForm(){
            $promotionId = $_GET['id'] ?? null;

            if(!$promotionId){
                $_SESSION['error_message'] = "Invalid promotion";
                header('Location: ' . SITE_URL . 'sponPromotions');
                exit;
            }

            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                $promotionModel = $this->model('PromotionModel');
                $promotion = $promotionModel->getPromotionById($promotionId);

                // Verify ownership
                if(!$promotion || $promotion['sponsor_id'] != $sponsorId){
                    throw new Exception("Unauthorized access");
                }

                $events = $promotionModel->getAvailableEvents();

                $this->view('sponsor/promotions/editPromotion', [
                    'promotion' => $promotion,
                    'events' => $events
                ]);

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'sponPromotions');
                exit;
            }
        }

        /**
         * UPDATE - Save promotion changes
         */
        public function updatePromotion(){
            require_once __DIR__ . '/../model/FileUploader.php';

            $promotionId = $_POST['id'] ?? null;

            if(!$promotionId){
                $_SESSION['error_message'] = "Invalid promotion";
                header('Location: ' . SITE_URL . 'sponPromotions');
                exit;
            }

            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                $promotionModel = $this->model('PromotionModel');
                $currentPromotion = $promotionModel->getPromotionById($promotionId);

                // Verify ownership
                if(!$currentPromotion || $currentPromotion['sponsor_id'] != $sponsorId){
                    throw new Exception("Unauthorized access");
                }

                $imageFile = null;

                // Handle image upload if provided
                if(!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE){
                    $uploader = new FileUploader(__DIR__ . '/../../uploads/promotions/');
                    $imageFile = $uploader->upload($_FILES['image'], 'promo_');
                }

                // Update promotion
                $success = $promotionModel->updatePromotion($promotionId, $sponsorId, $_POST, $imageFile);

                if($success){
                    $_SESSION['success_message'] = "Promotion updated successfully!";
                    header('Location: ' . SITE_URL . 'sponPromotions');
                } else {
                    throw new Exception("Failed to update promotion");
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
                header('Location: ' . SITE_URL . 'sponPromotions');
            }
            exit;
        }

        /**
         * DELETE - Remove promotion
         */
        public function deletePromotion(){
            $promotionId = $_POST['id'] ?? null;

            if(!$promotionId){
                $_SESSION['error_message'] = "Invalid promotion";
                header('Location: ' . SITE_URL . 'sponPromotions');
                exit;
            }

            try {
                $sponsorId = $this->sponModel->getSponsorIdByUser($_SESSION['user_id']);
                
                if(!$sponsorId){
                    throw new Exception("Sponsor profile not found");
                }

                $promotionModel = $this->model('PromotionModel');
                $success = $promotionModel->deletePromotion($promotionId, $sponsorId);

                if($success){
                    $_SESSION['success_message'] = "Promotion deleted successfully!";
                } else {
                    throw new Exception("Failed to delete promotion");
                }

            } catch (Exception $e) {
                $_SESSION['error_message'] = $e->getMessage();
            }

            header('Location: ' . SITE_URL . 'sponPromotions');
            exit;
        }
    }

?>
