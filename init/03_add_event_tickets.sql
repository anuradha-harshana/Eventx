-- ============================================
-- MIGRATION: Add pricing_type to events + event_tickets table
-- Run this once against an existing eventx_db database.
-- Safe to re-run (IF NOT EXISTS / IF NOT EXISTS guards).
-- ============================================

USE eventx_db;

-- 1. Add pricing_type column to events (no-op if already present)
SET @col_exists = (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'events'
      AND COLUMN_NAME  = 'pricing_type'
);
SET @sql = IF(@col_exists = 0,
    "ALTER TABLE events ADD COLUMN pricing_type ENUM('free','paid') DEFAULT 'free' AFTER capacity",
    'SELECT ''pricing_type column already exists'' AS info'
);
PREPARE _stmt FROM @sql;
EXECUTE _stmt;
DEALLOCATE PREPARE _stmt;

-- 2. Create event_tickets table
CREATE TABLE IF NOT EXISTS event_tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    capacity INT NOT NULL,
    available_count INT NOT NULL,
    terms TEXT DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    INDEX idx_event_tickets_event (event_id)
);
