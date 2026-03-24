
CREATE DATABASE IF NOT EXISTS mojo_muscle;
USE mojo_muscle;

-- ── USERS ────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    email      VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('member','admin') DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── MEMBERSHIPS ──────────────────────────────
CREATE TABLE IF NOT EXISTS memberships (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT NOT NULL,
    plan       ENUM('Free','Premium','VIP','Monthly','Quarterly','Annual') NOT NULL DEFAULT 'Free',
    status     ENUM('active','expired','cancelled') DEFAULT 'active',
    start_date DATE NOT NULL,
    end_date   DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── GYM CLASSES ──────────────────────────────
CREATE TABLE IF NOT EXISTS gym_classes (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    class_name   VARCHAR(100) NOT NULL,
    instructor   VARCHAR(100) NOT NULL,
    schedule_day ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
    start_time   TIME NOT NULL,
    end_time     TIME NOT NULL,
    max_slots    INT DEFAULT 20,
    status       ENUM('open','closed') DEFAULT 'open',
    created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── BOOKINGS ─────────────────────────────────
CREATE TABLE IF NOT EXISTS bookings (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    user_id   INT NOT NULL,
    class_id  INT NOT NULL,
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY no_double_book (user_id, class_id),
    FOREIGN KEY (user_id)  REFERENCES users(id)       ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES gym_classes(id) ON DELETE CASCADE
);

-- ── HOME CONTENT ─────────────────────────────
CREATE TABLE IF NOT EXISTS home_content (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    content_key   VARCHAR(50) NOT NULL UNIQUE,
    content_value TEXT        NOT NULL,
    updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── DEFAULT HOME CONTENT ─────────────────────
INSERT INTO home_content (content_key, content_value) VALUES
('active_members',    '120'),
('upcoming_events',   'None scheduled'),
('announcement',      ''),
('hours_monday',      '6 AM - 10 PM'),
('hours_tuesday',     '6 AM - 10 PM'),
('hours_wednesday',   '6 AM - 10 PM'),
('hours_thursday',    '6 AM - 10 PM'),
('hours_friday',      '6 AM - 10 PM'),
('hours_saturday',    '8 AM - 8 PM'),
('hours_sunday',      '10 AM - 6 PM'),
('status_monday',     'open'),
('status_tuesday',    'open'),
('status_wednesday',  'open'),
('status_thursday',   'open'),
('status_friday',     'open'),
('status_saturday',   'open'),
('status_sunday',     'open');

-- ── DEFAULT ADMIN ─────────────────────────────
-- Password: Admin@1234  (bcrypt hash)
INSERT INTO users (username, email, password, role) VALUES
('MojoAdmin', 'admin@mojo.com',
 '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
 'admin');

-- NOTE: The hash above is for "password" (Laravel default test hash).
-- To set your own password, run this after importing:
--   UPDATE users SET password = '<your_bcrypt_hash>' WHERE username = 'MojoAdmin';
-- Generate a hash with: php -r "echo password_hash('YourPassword123', PASSWORD_BCRYPT);"

-- ── SAMPLE GYM CLASSES ───────────────────────
INSERT INTO gym_classes (class_name, instructor, schedule_day, start_time, end_time, max_slots, status) VALUES
('Power Lifting',  'Coach Mojo',      'Monday',    '08:00:00', '09:00:00', 15, 'open'),
('HIIT Training',  'Coach Mojo',      'Tuesday',   '06:00:00', '07:00:00', 18, 'open'),
('Cardio Blast',   'Coach Jojo',      'Wednesday', '10:00:00', '11:00:00', 20, 'open'),
('Core Crusher',   'Coach Jojo',      'Thursday',  '09:00:00', '10:00:00', 15, 'open'),
('Yoga & Stretch', 'Coach Buttercup', 'Friday',    '07:00:00', '08:00:00', 12, 'open'),
('Weekend Warrior','Coach Mojo',      'Saturday',  '09:00:00', '10:30:00', 25, 'open');

