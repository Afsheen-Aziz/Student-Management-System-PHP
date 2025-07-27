# StudentHub - Modern Student Management System

A comprehensive PHP-based student management system featuring modern UI design, secure authentication, and complete CRUD operations for managing student records.

## Features

### Authentication & Security
- **Secure User Registration & Login** - Password hashing with PHP's built-in functions
- **Session Management** - Secure session handling for user authentication
- **Login Protection** - All pages require authentication to access
- **Input Sanitization** - Protection against SQL injection and XSS attacks

### Student Management
- **Add New Students** - Complete student registration with profile pictures
- **View All Students** - Modern table interface with search and pagination
- **Edit Student Information** - Update student details and profile pictures
- **Advanced Search** - Multi-criteria search by ID, name, email, course, semester, and gender
- **File Upload** - Profile picture upload with validation and security

### Modern UI/UX
- **Responsive Design** - Works perfectly on desktop, tablet, and mobile devices
- **Dark/Light Theme** - Toggle between themes with 30-day cookie persistence
- **Modern Gradient Design** - Beautiful gradients and glass morphism effects
- **Smooth Animations** - Hover effects and transitions for better user experience
- **Professional Typography** - System fonts for optimal readability

### Dashboard & Analytics
- **Statistics Dashboard** - Quick overview of total students and system metrics
- **Activity Logging** - Track all system activities and user actions
- **Recent Activity Feed** - View latest system activities

### Technical Features
- **MySQL Database** - Robust data storage with proper relationships
- **PDO Database Connection** - Secure and modern database interactions
- **Error Handling** - Comprehensive error handling and user feedback
- **File Management** - Secure file upload and management system
- **Responsive Bootstrap** - Bootstrap 5.1.3 for responsive design

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework**: Bootstrap 5.1.3
- **Icons**: Font Awesome 6.0.0
- **Server**: Apache (XAMPP recommended)

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server
- Web browser with JavaScript enabled

## Installation & Setup

### 1. Install XAMPP
- Download and install [XAMPP](https://www.apachefriends.org/download.html)
- Start Apache and MySQL services from XAMPP Control Panel

### 2. Download Project
```bash
# Clone the repository or download the project to your XAMPP htdocs folder
git clone https://github.com/Afsheen-Aziz/Student-Management-System-PHP.git
# Or extract ZIP file to: C:\xampp\htdocs\Student-Management-System-PHP\
```

### 3. Database Setup
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `student_management`
3. Import the `database.sql` file to create tables
4. The system will create default admin user during first setup

### 4. Configuration
1. Open `config.php`
2. Update database credentials if needed:
```php
$host = 'localhost';
$dbname = 'student_management';
$username = 'root';
$password = '';
```

### 5. Access the System
- Open your web browser
- Navigate to: `http://localhost/Student-Management-System-PHP/`
- Register a new account or use existing credentials

## Project Structure

```
Student-Management-System-PHP/
├── config.php              # Database configuration and utility functions
├── index.php               # Login page (entry point)
├── register.php            # User registration
├── dashboard.php           # Main dashboard with statistics
├── add_student.php         # Add new student form
├── edit_student.php        # Edit student information
├── view_students.php       # List all students
├── search_student.php      # Advanced search functionality
├── logs.php                # System activity logs
├── logout.php              # User logout
├── database.sql            # Database schema and setup
├── .gitignore              # Git ignore file
├── uploads/                # Student profile pictures directory
│   └── .gitkeep           # Ensures directory is tracked
├── logs/                   # System log files directory
│   └── .gitkeep           # Ensures directory is tracked
└── README.md               # Project documentation
```

## Troubleshooting

### Common Issues

1. **White Screen/Page Not Loading**
   - Check if Apache and MySQL are running
   - Verify database connection in config.php
   - Check PHP error logs

2. **Database Connection Error**
   - Ensure MySQL service is running
   - Verify database credentials in config.php
   - Check if database `student_management` exists

3. **File Upload Not Working**
   - Check if `uploads/` directory exists and is writable
   - Verify PHP upload settings in php.ini
   - Ensure file size limits are appropriate

4. **Sessions Not Working**
   - Check if session.save_path is writable
   - Verify session settings in PHP configuration

## License

This project is developed for educational purposes. Feel free to use and modify for learning and non-commercial purposes.

## Submission Notes

This project is complete and ready for academic submission. All features are implemented and tested:

- ✅ User authentication system
- ✅ Complete CRUD operations for students
- ✅ Modern responsive UI with dark/light themes
- ✅ File upload functionality with error handling
- ✅ Search and filtering capabilities
- ✅ Activity logging system
- ✅ Comprehensive error handling
- ✅ Security measures (input sanitization, password hashing)

## Future Enhancements

- Email notifications
- Advanced reporting system
- Bulk operations
- Export functionality (PDF, Excel)
- Student attendance tracking
- Grade management system

---

**Note**: This system is designed for educational purposes and college project demonstrations. For production use, additional security measures and optimizations may be required.
