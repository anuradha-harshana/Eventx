-- ============================================
-- EVENTX DATABASE COMPLETE SCHEMA
-- ============================================
CREATE DATABASE IF NOT EXISTS eventx_db;
USE eventx_db;

-- ============================================
-- 1. CORE TABLES
-- ============================================

-- USERS TABLE (Base table for all user types)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('participant', 'organizer', 'sponsor', 'supplier', 'admin') DEFAULT 'participant',
    profile_pic VARCHAR(255) DEFAULT NULL,
    full_name VARCHAR(100) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username),
    INDEX idx_role (role)
);

-- ============================================
-- 2. ROLE-SPECIFIC DETAILS TABLES
-- ============================================

-- PARTICIPANT DETAILS
CREATE TABLE IF NOT EXISTS participant_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    profile_pic VARCHAR(255) DEFAULT NULL,
    date_of_birth DATE DEFAULT NULL,
    location VARCHAR(100) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    interests TEXT DEFAULT NULL,
    occupation VARCHAR(100) DEFAULT NULL,
    company VARCHAR(100) DEFAULT NULL,
    education VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(255) DEFAULT NULL,
    badges_earned INT DEFAULT 0,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_location (location)
);

-- ORGANIZER DETAILS
CREATE TABLE IF NOT EXISTS organizer_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    profile_pic VARCHAR(255) DEFAULT NULL,
    organization_name VARCHAR(100) NULL,
    organization_type ENUM('company', 'nonprofit', 'educational', 'community', 'other') DEFAULT 'other',
    description TEXT DEFAULT NULL,
    logo VARCHAR(255) DEFAULT 'default-org.png',
    website VARCHAR(255) DEFAULT NULL,
    contact_email VARCHAR(100) DEFAULT NULL,
    contact_phone VARCHAR(20) DEFAULT NULL,
    founded_year YEAR DEFAULT NULL,
    verified BOOLEAN DEFAULT FALSE,
    verification_document VARCHAR(255) DEFAULT NULL,
    social_links TEXT DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_org_name (organization_name),
    INDEX idx_verified (verified)
);

-- SPONSOR DETAILS
CREATE TABLE IF NOT EXISTS sponsor_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    profile_pic VARCHAR(255) DEFAULT NULL,
    brand_name VARCHAR(100) NOT NULL,
    industry VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    logo VARCHAR(255) DEFAULT 'default-sponsor.png',
    website VARCHAR(255) DEFAULT NULL,
    contact_email VARCHAR(100) DEFAULT NULL,
    contact_phone VARCHAR(20) DEFAULT NULL,
    sponsorship_categories TEXT DEFAULT NULL,
    budget_range VARCHAR(50) DEFAULT NULL,
    verified BOOLEAN DEFAULT FALSE,
    verification_document VARCHAR(255) DEFAULT NULL,
    social_links TEXT DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_brand_name (brand_name)
);

-- SUPPLIER DETAILS
CREATE TABLE IF NOT EXISTS supplier_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    profile_pic VARCHAR(255) DEFAULT NULL,
    company_name VARCHAR(100) NOT NULL,
    business_type VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    logo VARCHAR(255) DEFAULT 'default-supplier.png',
    website VARCHAR(255) DEFAULT NULL,
    contact_email VARCHAR(100) DEFAULT NULL,
    contact_phone VARCHAR(20) DEFAULT NULL,
    established_year YEAR DEFAULT NULL,
    service_area VARCHAR(255) DEFAULT NULL,
    delivery_available BOOLEAN DEFAULT TRUE,
    verified BOOLEAN DEFAULT FALSE,
    verification_document VARCHAR(255) DEFAULT NULL,
    social_links TEXT DEFAULT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_company_name (company_name),
    INDEX idx_service_area (service_area)
);

-- ============================================
-- 3. EVENT MANAGEMENT TABLES
-- ============================================

-- EVENT CATEGORIES (lookup table)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- EVENTS TABLE
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    organizer_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category_id INT NOT NULL,
    event_type ENUM('physical', 'online', 'hybrid') DEFAULT 'physical',
    location_text VARCHAR(255) DEFAULT NULL,
    location_map VARCHAR(255) DEFAULT NULL,
    online_link VARCHAR(255) DEFAULT NULL,
    start_at DATETIME NOT NULL,
    end_at DATETIME NOT NULL,
    banner_url VARCHAR(255) DEFAULT 'default-event.jpg',
    capacity INT DEFAULT NULL,
    current_participants INT DEFAULT 0,
    status ENUM('draft', 'pending', 'published', 'live', 'completed', 'cancelled') DEFAULT 'draft',
    checkin_code VARCHAR(10) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES organizer_details(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX idx_status (status),
    INDEX idx_start_date (start_at),
    INDEX idx_category (category_id)
);

-- EVENT COLLABORATIONS (Multiple organizers per event)
CREATE TABLE IF NOT EXISTS event_collaborations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    organizer_id INT NOT NULL,
    role VARCHAR(50) DEFAULT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (organizer_id) REFERENCES organizer_details(id) ON DELETE CASCADE,
    UNIQUE KEY unique_collaboration (event_id, organizer_id)
);

-- ============================================
-- 4. PARTICIPANT INTERACTION TABLES
-- ============================================

-- REGISTRATIONS (Event RSVPs)
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    participant_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('registered', 'checked_in', 'attended', 'no_show', 'cancelled') DEFAULT 'registered',
    checkin_time TIMESTAMP NULL,
    checkin_method ENUM('qr', 'geolocation', 'passcode') DEFAULT NULL,
    feedback TEXT DEFAULT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (participant_id) REFERENCES participant_details(id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (event_id, participant_id),
    INDEX idx_status (status)
);

-- FOLLOWS (Participants following Organizers)
CREATE TABLE IF NOT EXISTS follows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    participant_id INT NOT NULL,
    organizer_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (participant_id) REFERENCES participant_details(id) ON DELETE CASCADE,
    FOREIGN KEY (organizer_id) REFERENCES organizer_details(id) ON DELETE CASCADE,
    UNIQUE KEY unique_follow (participant_id, organizer_id)
);

-- FRIENDS (Participant connections)
CREATE TABLE IF NOT EXISTS friends (
    id INT AUTO_INCREMENT PRIMARY KEY,
    participant_id1 INT NOT NULL,
    participant_id2 INT NOT NULL,
    status ENUM('pending', 'accepted', 'blocked') DEFAULT 'pending',
    action_participant_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (participant_id1) REFERENCES participant_details(id) ON DELETE CASCADE,
    FOREIGN KEY (participant_id2) REFERENCES participant_details(id) ON DELETE CASCADE,
    FOREIGN KEY (action_participant_id) REFERENCES participant_details(id),
    UNIQUE KEY unique_friendship (participant_id1, participant_id2),
    INDEX idx_status (status)
);

-- ============================================
-- 5. GAMIFICATION TABLES
-- ============================================

-- BADGES
CREATE TABLE IF NOT EXISTS badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    icon VARCHAR(255) NOT NULL,
    criteria TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- PARTICIPANT BADGES
CREATE TABLE IF NOT EXISTS participant_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    participant_id INT NOT NULL,
    badge_id INT NOT NULL,
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (participant_id) REFERENCES participant_details(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE,
    UNIQUE KEY unique_participant_badge (participant_id, badge_id)
);

-- CERTIFICATE TEMPLATES
CREATE TABLE IF NOT EXISTS certificate_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    template_file VARCHAR(255) NOT NULL,
    preview_image VARCHAR(255) DEFAULT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- CERTIFICATES
CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_id INT NOT NULL UNIQUE,
    certificate_url VARCHAR(255) NOT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    template_used INT DEFAULT NULL,
    FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
    FOREIGN KEY (template_used) REFERENCES certificate_templates(id) ON DELETE SET NULL
);

-- ============================================
-- 6. SPONSORSHIP TABLES
-- ============================================

-- SPONSORSHIPS
CREATE TABLE IF NOT EXISTS sponsorships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    sponsor_id INT NOT NULL,
    amount DECIMAL(10,2) DEFAULT NULL,
    resources TEXT DEFAULT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending',
    branding_level ENUM('platinum', 'gold', 'silver', 'bronze', 'basic') DEFAULT 'basic',
    contract_file VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (sponsor_id) REFERENCES sponsor_details(id) ON DELETE CASCADE,
    INDEX idx_status (status)
);

-- SPONSOR PORTFOLIO
CREATE TABLE IF NOT EXISTS sponsor_portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sponsor_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    event_name VARCHAR(100) DEFAULT NULL,
    year YEAR DEFAULT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (sponsor_id) REFERENCES sponsor_details(id) ON DELETE CASCADE
);

-- ============================================
-- 7. SUPPLIER MANAGEMENT TABLES
-- ============================================

-- SUPPLIER CATEGORIES
CREATE TABLE IF NOT EXISTS supplier_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    icon VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- SUPPLIER PRODUCTS
CREATE TABLE IF NOT EXISTS supplier_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(10,2) DEFAULT NULL,
    price_unit ENUM('per_item', 'per_hour', 'per_day', 'per_event', 'negotiable') DEFAULT 'per_item',
    min_order INT DEFAULT 1,
    max_order INT DEFAULT NULL,
    availability_quantity INT DEFAULT NULL,
    image_url VARCHAR(255) DEFAULT 'default-product.png',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES supplier_details(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES supplier_categories(id) ON DELETE CASCADE,
    INDEX idx_supplier (supplier_id),
    INDEX idx_category (category_id)
);

-- SUPPLIER PORTFOLIO
CREATE TABLE IF NOT EXISTS supplier_portfolio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    event_name VARCHAR(100) DEFAULT NULL,
    event_date DATE DEFAULT NULL,
    client_name VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES supplier_details(id) ON DELETE CASCADE
);

-- EVENT SUPPLIER REQUESTS
CREATE TABLE IF NOT EXISTS event_supplier_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    supplier_id INT NOT NULL,
    organizer_id INT NOT NULL,
    product_id INT DEFAULT NULL,
    quantity INT DEFAULT 1,
    requirements TEXT DEFAULT NULL,
    budget DECIMAL(10,2) DEFAULT NULL,
    status ENUM('pending', 'accepted', 'declined', 'completed', 'cancelled') DEFAULT 'pending',
    organizer_notes TEXT DEFAULT NULL,
    supplier_notes TEXT DEFAULT NULL,
    requested_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    responded_date TIMESTAMP NULL,
    completed_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES supplier_details(id) ON DELETE CASCADE,
    FOREIGN KEY (organizer_id) REFERENCES organizer_details(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES supplier_products(id) ON DELETE SET NULL,
    INDEX idx_status (status)
);

-- EVENT SUPPLIER CONTRACTS
CREATE TABLE IF NOT EXISTS event_supplier_contracts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL UNIQUE,
    event_id INT NOT NULL,
    supplier_id INT NOT NULL,
    organizer_id INT NOT NULL,
    contract_number VARCHAR(50) UNIQUE,
    agreed_amount DECIMAL(10,2) NOT NULL,
    payment_terms VARCHAR(255) DEFAULT NULL,
    delivery_date DATETIME DEFAULT NULL,
    setup_time DATETIME DEFAULT NULL,
    teardown_time DATETIME DEFAULT NULL,
    special_conditions TEXT DEFAULT NULL,
    contract_file VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    signed_by_organizer BOOLEAN DEFAULT FALSE,
    signed_by_supplier BOOLEAN DEFAULT FALSE,
    signed_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES event_supplier_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES supplier_details(id) ON DELETE CASCADE,
    FOREIGN KEY (organizer_id) REFERENCES organizer_details(id) ON DELETE CASCADE,
    INDEX idx_status (status)
);

-- SUPPLIER REVIEWS
CREATE TABLE IF NOT EXISTS supplier_reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contract_id INT NOT NULL UNIQUE,
    event_id INT NOT NULL,
    supplier_id INT NOT NULL,
    organizer_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT DEFAULT NULL,
    delivery_rating INT CHECK (delivery_rating >= 1 AND delivery_rating <= 5),
    quality_rating INT CHECK (quality_rating >= 1 AND quality_rating <= 5),
    communication_rating INT CHECK (communication_rating >= 1 AND communication_rating <= 5),
    value_rating INT CHECK (value_rating >= 1 AND value_rating <= 5),
    would_recommend BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES event_supplier_contracts(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES supplier_details(id) ON DELETE CASCADE,
    FOREIGN KEY (organizer_id) REFERENCES organizer_details(id) ON DELETE CASCADE,
    INDEX idx_supplier_rating (supplier_id, rating)
);

-- SUPPLIER AVAILABILITY
CREATE TABLE IF NOT EXISTS supplier_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT NOT NULL,
    date DATE NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES supplier_details(id) ON DELETE CASCADE,
    UNIQUE KEY unique_supplier_date (supplier_id, date)
);

-- ============================================
-- 8. NOTIFICATIONS & COMMUNICATION TABLES
-- ============================================

-- NOTIFICATIONS (General)
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('event_reminder', 'friend_request', 'friend_accept', 'event_update', 
              'checkin_success', 'badge_earned', 'certificate_issued', 'sponsorship_update',
              'new_request', 'request_accepted', 'request_declined', 'contract_signed', 'new_review') NOT NULL,
    title VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) DEFAULT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read)
);

-- ============================================
-- 9. EVENT MEDIA & STORIES
-- ============================================

-- EVENT STORIES (Live updates)
CREATE TABLE IF NOT EXISTS event_stories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    organizer_id INT NOT NULL,
    content TEXT NOT NULL,
    media_url VARCHAR(255) DEFAULT NULL,
    story_type ENUM('text', 'image', 'video') DEFAULT 'text',
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (organizer_id) REFERENCES organizer_details(id) ON DELETE CASCADE,
    INDEX idx_expires (expires_at)
);

-- EVENT MEDIA (Photos/Videos)
CREATE TABLE IF NOT EXISTS event_media (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    uploaded_by INT NOT NULL,
    media_url VARCHAR(255) NOT NULL,
    media_type ENUM('image', 'video') NOT NULL,
    caption TEXT DEFAULT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- 10. REPORTING & MODERATION TABLES
-- ============================================

-- REPORTS
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    reported_user_id INT DEFAULT NULL,
    reported_event_id INT DEFAULT NULL,
    report_type ENUM('spam', 'abuse', 'inappropriate', 'fake', 'other') NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'investigating', 'resolved', 'dismissed') DEFAULT 'pending',
    admin_notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at TIMESTAMP NULL,
    resolved_by INT DEFAULT NULL,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reported_user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (reported_event_id) REFERENCES events(id) ON DELETE SET NULL,
    FOREIGN KEY (resolved_by) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_status (status)
);

-- ACTIVITY LOGS (For analytics)
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    action VARCHAR(50) NOT NULL,
    target_type VARCHAR(50) DEFAULT NULL,
    target_id INT DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    user_agent TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_action_time (action, created_at)
);

-- ============================================
-- 11. DEFAULT DATA INSERTION
-- ============================================

-- Insert default categories
INSERT IGNORE INTO categories (name, icon, description) VALUES
('Technology', '💻', 'Tech conferences, hackathons, workshops'),
('Business', '📊', 'Networking, seminars, business meetings'),
('Music', '🎵', 'Concerts, music festivals, live performances'),
('Arts', '🎨', 'Art exhibitions, workshops, galleries'),
('Sports', '⚽', 'Sports events, tournaments, fitness classes'),
('Education', '📚', 'Workshops, training, educational seminars'),
('Food & Drink', '🍳', 'Food festivals, cooking classes, wine tasting'),
('Charity', '🤝', 'Fundraisers, community service, charity events'),
('Networking', '🤝', 'Professional networking, meetups'),
('Workshop', '🔧', 'Hands-on workshops, training sessions'),
('Conference', '🎤', 'Conferences, summits, conventions'),
('Festival', '🎪', 'Cultural festivals, celebrations');

-- Insert default badges
INSERT IGNORE INTO badges (name, description, icon, criteria) VALUES
('First Event', 'Attended your first event', 'badge-first.png', '{"type":"attendance","count":1}'),
('Event Enthusiast', 'Attended 10 events', 'badge-enthusiast.png', '{"type":"attendance","count":10}'),
('Event Master', 'Attended 50 events', 'badge-master.png', '{"type":"attendance","count":50}'),
('Social Butterfly', 'Made 5 friends', 'badge-social.png', '{"type":"friends","count":5}'),
('Networker', 'Made 20 friends', 'badge-networker.png', '{"type":"friends","count":20}'),
('Early Bird', 'Registered for an event 30 days in advance', 'badge-early.png', '{"type":"early_registration"}'),
('Reviewer', 'Left feedback for 5 events', 'badge-reviewer.png', '{"type":"reviews","count":5}');

-- Insert default certificate templates
INSERT IGNORE INTO certificate_templates (name, template_file, preview_image, is_default) VALUES
('Default Certificate', 'default-certificate.php', 'default-cert-preview.png', TRUE),
('Modern Design', 'modern-certificate.php', 'modern-cert-preview.png', FALSE),
('Classic Design', 'classic-certificate.php', 'classic-cert-preview.png', FALSE);

-- Insert default supplier categories
INSERT IGNORE INTO supplier_categories (name, description, icon) VALUES
('Catering', 'Food, beverages, and catering services', '🍽️'),
('Audio & Visual', 'Sound systems, projectors, lighting equipment', '🎤'),
('Furniture', 'Chairs, tables, sofas, and seating arrangements', '🪑'),
('Tents & Structures', 'Tents, stages, canopies, and temporary structures', '⛺'),
('Decorations', 'Floral arrangements, banners, and event decor', '🎨'),
('Photography', 'Event photography and videography services', '📷'),
('Transportation', 'Vehicle rentals, shuttle services', '🚗'),
('Security', 'Security personnel and equipment', '🛡️'),
('Printing', 'Banners, flyers, tickets, and promotional materials', '🖨️'),
('Gifts & Merchandise', 'Event merchandise, souvenirs, and gift items', '🎁'),
('Cleaning Services', 'Pre and post-event cleaning', '🧹'),
('Technical Support', 'IT support, WiFi, and technical equipment', '💻'),
('Staffing', 'Event staff, volunteers, and temporary workers', '👥'),
('Entertainment', 'DJs, live bands, performers, and entertainers', '🎭'),
('Parking Services', 'Parking management and valet services', '🅿️');

-- ============================================
-- 12. TRIGGERS
-- ============================================

-- Trigger for auto-generating check-in code
DROP TRIGGER IF EXISTS before_insert_events;
CREATE TRIGGER before_insert_events
BEFORE INSERT ON events
FOR EACH ROW
SET NEW.checkin_code = IFNULL(NEW.checkin_code, UPPER(SUBSTRING(MD5(RAND()), 1, 8)));

-- Trigger for auto-generating contract number
-- DELIMITER is required for BEGIN...END blocks when imported via MySQL CLI
DROP TRIGGER IF EXISTS before_insert_event_supplier_contracts;
DELIMITER //
CREATE TRIGGER before_insert_event_supplier_contracts
BEFORE INSERT ON event_supplier_contracts
FOR EACH ROW
BEGIN
    DECLARE next_id INT;
    DECLARE year_prefix VARCHAR(4);

    SET year_prefix = DATE_FORMAT(NOW(), '%Y');
    SELECT AUTO_INCREMENT INTO next_id
    FROM information_schema.TABLES
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'event_supplier_contracts'
    LIMIT 1;

    IF next_id IS NULL THEN
        SET next_id = 1;
    END IF;

    SET NEW.contract_number = CONCAT('SUP-', year_prefix, '-', LPAD(next_id, 6, '0'));
END //
DELIMITER ;

-- ============================================
-- 13. INDEXES FOR PERFORMANCE
-- ============================================

-- Additional indexes for better query performance
CREATE INDEX idx_events_organizer ON events(organizer_id);
CREATE INDEX idx_events_dates ON events(start_at, end_at);
CREATE INDEX idx_registrations_event ON registrations(event_id);
CREATE INDEX idx_registrations_participant ON registrations(participant_id);
CREATE INDEX idx_notifications_user ON notifications(user_id, created_at);
CREATE INDEX idx_supplier_requests_event ON event_supplier_requests(event_id);
CREATE INDEX idx_supplier_requests_supplier ON event_supplier_requests(supplier_id);
CREATE INDEX idx_supplier_products_supplier ON supplier_products(supplier_id);
CREATE INDEX idx_supplier_details_verified ON supplier_details(verified);
CREATE INDEX idx_organizer_details_verified ON organizer_details(verified);
CREATE INDEX idx_sponsor_details_verified ON sponsor_details(verified);

-- ============================================
-- 14. SAMPLE ADMIN USER (Optional - Uncomment and hash password)
-- ============================================
-- INSERT INTO users (username, email, password, role, full_name) VALUES 
-- ('admin', 'admin@eventx.com', '$2y$10$YourHashedPasswordHere', 'admin', 'System Administrator');