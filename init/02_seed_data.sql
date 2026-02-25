-- ============================================================
-- EVENTX SEED DATA
-- All passwords are bcrypt hashes of: password123
-- ============================================================
USE eventx_db;

-- ── 1. USERS ──────────────────────────────────────────────────────────────────
INSERT INTO users (id, username, email, password, role, full_name, phone, is_active) VALUES
-- Admin
(1,  'admin',         'admin@eventx.com',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin',       'System Administrator', '+1-555-000-0001', TRUE),
-- Organizers
(2,  'techevents',    'tech@eventz.com',          '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'organizer',   'Tech Events Co.',      '+1-555-100-0001', TRUE),
(3,  'artsculture',   'arts@eventz.com',          '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'organizer',   'Arts & Culture Hub',   '+1-555-100-0002', TRUE),
-- Participants
(4,  'alice_j',       'alice@example.com',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'participant', 'Alice Johnson',        '+1-555-200-0001', TRUE),
(5,  'bob_smith',     'bob@example.com',          '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'participant', 'Bob Smith',            '+1-555-200-0002', TRUE),
(6,  'carol_w',       'carol@example.com',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'participant', 'Carol Williams',       '+1-555-200-0003', TRUE),
-- Sponsors
(7,  'techcorp',      'sponsorship@techcorp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sponsor',     'TechCorp Inc.',        '+1-555-300-0001', TRUE),
(8,  'globalbrand',   'events@globalbrand.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sponsor',     'Global Brand Ltd.',    '+1-555-300-0002', TRUE),
-- Suppliers
(9,  'cateringpro',   'hello@cateringpro.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier',    'Catering Pro LLC',     '+1-555-400-0001', TRUE),
(10, 'avsolutions',   'info@avsolutions.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier',    'AV Solutions Ltd.',    '+1-555-400-0002', TRUE);

-- ── 2. ROLE-SPECIFIC DETAIL TABLES ───────────────────────────────────────────

-- Organizer details (id=1 → user 2, id=2 → user 3)
INSERT INTO organizer_details (id, user_id, organization_name, organization_type, description, website, contact_email, contact_phone, founded_year, verified) VALUES
(1, 2, 'Tech Events Co.',   'company',   'Premier technology events, conferences and hackathons.', 'https://techevents.example.com', 'tech@eventz.com', '+1-555-100-0001', 2018, TRUE),
(2, 3, 'Arts & Culture Hub','nonprofit', 'Celebrating creativity through art, music and culture.',  'https://artsculture.example.com', 'arts@eventz.com', '+1-555-100-0002', 2015, TRUE);

-- Participant details (id=1–3 → users 4–6)
INSERT INTO participant_details (id, user_id, date_of_birth, location, bio, interests, occupation) VALUES
(1, 4, '1995-03-14', 'New York, NY',    'Tech enthusiast and avid hackathon participant.',        'Technology, AI, Startups',     'Software Engineer'),
(2, 5, '1990-07-22', 'San Francisco, CA','Creative professional who loves arts and networking.', 'Arts, Design, Photography',    'Graphic Designer'),
(3, 6, '1998-11-05', 'Austin, TX',       'Always looking for the next great live music event.',  'Music, Festivals, Photography', 'Marketing Manager');

-- Sponsor details (id=1–2 → users 7–8)
INSERT INTO sponsor_details (id, user_id, brand_name, industry, description, website, contact_email, budget_range, verified) VALUES
(1, 7, 'TechCorp Inc.',   'Technology', 'Enterprise software solutions for the modern enterprise.', 'https://techcorp.example.com',   'sponsorship@techcorp.com', '$10,000 - $50,000', TRUE),
(2, 8, 'Global Brand Ltd.','Retail',    'Global consumer goods brand reaching millions worldwide.',  'https://globalbrand.example.com', 'events@globalbrand.com',   '$5,000 - $20,000',  TRUE);

-- Supplier details (id=1–2 → users 9–10)
INSERT INTO supplier_details (id, user_id, company_name, business_type, description, website, contact_email, service_area, delivery_available, verified) VALUES
(1,  9, 'Catering Pro LLC',  'Catering',    'Full-service catering for events of any size.',           'https://cateringpro.example.com', 'hello@cateringpro.com', 'New York & New Jersey', TRUE, TRUE),
(2, 10, 'AV Solutions Ltd.', 'Audio/Visual','Professional sound, lighting and projection rental.',      'https://avsolutions.example.com', 'info@avsolutions.com',  'Nationwide',            TRUE, TRUE);

-- ── 3. EVENTS ─────────────────────────────────────────────────────────────────
-- category IDs come from the schema's INSERT IGNORE in section 11:
--   1=Technology, 2=Business, 4=Arts, 11=Conference, 12=Festival
INSERT INTO events (id, organizer_id, title, description, category_id, event_type, location_text, start_at, end_at, capacity, status) VALUES
(1, 1, 'NYC Tech Summit 2026',
    'A two-day conference bringing together industry leaders, innovators and developers to explore the future of technology.',
    11, 'physical', 'Javits Center, New York, NY',
    '2026-04-15 09:00:00', '2026-04-16 18:00:00', 500, 'published'),

(2, 1, 'AI & Machine Learning Hackathon',
    '48-hour hackathon focused on building AI-powered solutions for real-world problems. Cash prizes for top teams.',
    1, 'physical', 'Brooklyn Tech Hub, Brooklyn, NY',
    '2026-05-10 08:00:00', '2026-05-12 08:00:00', 200, 'published'),

(3, 2, 'Modern Art Exhibition: "New Voices"',
    'A curated exhibition showcasing emerging artists from across the country. Opening night includes a live Q&A with featured artists.',
    4, 'physical', 'Gallery 28, SoHo, New York, NY',
    '2026-04-20 18:00:00', '2026-04-20 22:00:00', 150, 'published'),

(4, 2, 'Spring Music & Culture Festival',
    'A full day outdoor festival celebrating music, food and culture with 12 live acts across 3 stages.',
    12, 'physical', 'Central Park Great Lawn, New York, NY',
    '2026-06-07 12:00:00', '2026-06-07 22:00:00', 2000, 'published');

-- ── 4. REGISTRATIONS ─────────────────────────────────────────────────────────
-- participant_id references participant_details.id (not users.id)
INSERT INTO registrations (event_id, participant_id, status, rating, feedback) VALUES
-- Alice: registered for tech summit and hackathon
(1, 1, 'registered',  NULL, NULL),
(2, 1, 'attended',    5,    'Incredible event! Learned so much. Would definitely attend again.'),
-- Bob: registered for art show and music festival
(3, 2, 'attended',    4,    'Beautiful exhibition. The artist Q&A was the highlight of the evening.'),
(4, 2, 'registered',  NULL, NULL),
-- Carol: going to summit and art show
(1, 3, 'registered',  NULL, NULL),
(3, 3, 'attended',    5,    'Loved every moment. Discovered some amazing new artists.');

-- ── 5. SPONSORSHIPS ───────────────────────────────────────────────────────────
-- sponsor_id references sponsor_details.id
INSERT INTO sponsorships (event_id, sponsor_id, amount, status, branding_level) VALUES
(1, 1, 25000.00, 'approved', 'gold'),
(2, 1, 10000.00, 'approved', 'silver'),
(1, 2,  5000.00, 'approved', 'bronze'),
(4, 2,  8000.00, 'pending',  'silver');

-- ── 6. SUPPLIER PRODUCTS ──────────────────────────────────────────────────────
-- supplier_id references supplier_details.id
-- category_id references supplier_categories.id (seeded in schema: 1=Catering, 2=Audio & Visual)
INSERT INTO supplier_products (supplier_id, category_id, name, description, price, price_unit, min_order, availability_quantity, is_active) VALUES
-- Catering Pro (supplier 1)
(1, 1, 'Buffet Package – Standard',  '3-course buffet for up to 100 guests. Includes setup and teardown.',     1500.00, 'per_event', 1,  10, TRUE),
(1, 1, 'Buffet Package – Premium',   '5-course buffet for up to 200 guests. Includes dedicated wait staff.',   3500.00, 'per_event', 1,   5, TRUE),
(1, 1, 'Coffee & Snacks Station',    'Self-service coffee station with pastries. Suitable for conferences.',    400.00,  'per_day',   1,  20, TRUE),
-- AV Solutions (supplier 2)
(2, 2, 'PA System – Small Venue',    'Full PA system suitable for venues up to 500 people.',                   800.00,  'per_day',   1,   8, TRUE),
(2, 2, 'HD Projector & Screen',      '4K projector with 120" screen and HDMI/wireless connectivity.',          350.00,  'per_day',   1,  15, TRUE),
(2, 2, 'Stage Lighting Package',     'Full LED lighting rig including spotlights, wash lights and controller.', 1200.00, 'per_event', 1,   6, TRUE);
