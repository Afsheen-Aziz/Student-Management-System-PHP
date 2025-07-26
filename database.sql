CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- Users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    date_of_birth DATE,
    gender ENUM('Male', 'Female', 'Other'),
    address TEXT,
    course VARCHAR(100),
    semester INT,
    profile_picture VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO students (student_id, first_name, last_name, email, phone, date_of_birth, gender, address, course, semester) VALUES
('STU001', 'John', 'Doe', 'john.doe@email.com', '1234567890', '2000-05-15', 'Male', '123 Main St, City', 'Computer Science', 4),
('STU002', 'Jane', 'Smith', 'jane.smith@email.com', '0987654321', '1999-08-20', 'Female', '456 Oak Ave, Town', 'Mathematics', 6),
('STU003', 'Mike', 'Johnson', 'mike.johnson@email.com', '5555555555', '2001-02-10', 'Male', '789 Pine Rd, Village', 'Physics', 2);
