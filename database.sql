-- Parliament Intern Logbook System Database Schema
-- Created for Parliament of Sri Lanka

CREATE DATABASE IF NOT EXISTS parliament1_db;
USE parliament1_db;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('intern', 'supervisor', 'admin') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Intern profiles table
CREATE TABLE intern_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    department INT NOT NULL,
    supervisor_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('active', 'completed', 'terminated') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (supervisor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Daily logs table
CREATE TABLE daily_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    intern_id INT NOT NULL,
    date DATE NOT NULL,
    task_description TEXT NOT NULL,
    skills TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    supervisor_comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (intern_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Evaluations table
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    intern_id INT NOT NULL,
    week_no INT NOT NULL,
    rating_technical INT NOT NULL CHECK (rating_technical >= 1 AND rating_technical <= 5),
    rating_softskills INT NOT NULL CHECK (rating_softskills >= 1 AND rating_softskills <= 5),
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (intern_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Announcements table
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_by INT NOT NULL,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Activity logs table (for audit trail)
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample data

-- Sample users
INSERT INTO users (name, email, password, role) VALUES
('Admin User', 'admin@parliament.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Dr. Sarah Perera', 'sarah.perera@parliament.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor'),
('Mr. James Fernando', 'james.fernando@parliament.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor'),
('Kavindu Silva', 'kavindu.silva@parliament.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'intern'),
('Nethmi Perera', 'nethmi.perera@parliament.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'intern'),
('Dilshan Rajapaksa', 'dilshan.rajapaksa@parliament.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'intern');

-- Sample intern profiles
INSERT INTO intern_profiles (user_id, department, supervisor_id, start_date, end_date) VALUES
(4, 1, 2, '2024-01-15', '2024-06-15'),
(5, 2, 2, '2024-01-15', '2024-06-15'),
(6, 1, 3, '2024-02-01', '2024-07-01');

-- Sample daily logs
INSERT INTO daily_logs (intern_id, date, task_description, skills, status, supervisor_comment) VALUES
(4, '2024-01-16', 'Assisted in updating the parliamentary website content and learned about content management systems.', 'Web Development, Content Management', 'approved', 'Good work on the website updates. Keep learning about CMS systems.'),
(4, '2024-01-17', 'Participated in database maintenance tasks and learned SQL optimization techniques.', 'Database Management, SQL', 'approved', 'Excellent progress in database skills.'),
(5, '2024-01-16', 'Helped organize HR training sessions and learned about employee onboarding processes.', 'Human Resources, Training Coordination', 'approved', 'Great organizational skills shown.'),
(5, '2024-01-17', 'Assisted in preparing monthly HR reports and learned about data analysis.', 'Report Writing, Data Analysis', 'pending', ''),
(6, '2024-02-02', 'Started working on IT infrastructure documentation and learned about network protocols.', 'Network Administration, Documentation', 'approved', 'Good start with infrastructure work.');

-- Sample evaluations
INSERT INTO evaluations (intern_id, week_no, rating_technical, rating_softskills, comments) VALUES
(4, 1, 4, 5, 'Kavindu shows excellent technical aptitude and great communication skills. Very proactive in learning new technologies.'),
(5, 1, 3, 4, 'Nethmi is developing well in HR processes. Good interpersonal skills and eager to learn.'),
(6, 1, 4, 4, 'Dilshan demonstrates strong technical knowledge and good teamwork abilities.');

-- Sample announcements
INSERT INTO announcements (title, message, created_by) VALUES
('Welcome New Interns', 'Welcome to the Parliament Internship Program 2024. Please ensure you submit your daily logs on time.', 1),
('Weekly Evaluation Reminder', 'Supervisors, please complete weekly evaluations for your assigned interns by Friday.', 1),
('System Maintenance', 'The logbook system will be under maintenance this Sunday from 2 AM to 4 AM.', 1);

-- Create indexes for better performance
CREATE INDEX idx_daily_logs_intern_date ON daily_logs(intern_id, date);
CREATE INDEX idx_daily_logs_status ON daily_logs(status);
CREATE INDEX idx_evaluations_intern_week ON evaluations(intern_id, week_no);
CREATE INDEX idx_activity_logs_user_date ON activity_logs(user_id, created_at);


