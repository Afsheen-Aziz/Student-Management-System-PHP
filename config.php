<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'student_management');

// Start session
session_start();

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection function
function getDBConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        throw new Exception("Database connection failed: " . $e->getMessage());
    }
}

// Log function
function writeLog($message) {
    $logFile = 'logs/activity.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message" . PHP_EOL;
    
    // Create logs directory if it doesn't exist
    if (!file_exists('logs')) {
        mkdir('logs', 0777, true);
    }
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Validation functions
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// User authentication functions
function authenticateUser($username, $password) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT id, username, email, password_hash, full_name FROM users WHERE username = ? AND is_active = 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    } catch (Exception $e) {
        writeLog("Authentication error: " . $e->getMessage());
        return false;
    }
}

function createUser($username, $email, $password, $full_name) {
    try {
        $pdo = getDBConnection();
        
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            return false; // User already exists
        }
        
        // Hash password and create user
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, full_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $password_hash, $full_name]);
        
        return $pdo->lastInsertId();
    } catch (Exception $e) {
        writeLog("User creation error: " . $e->getMessage());
        return false;
    }
}

function loginUser($user) {
    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    writeLog("User {$user['username']} logged in successfully");
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
}

// Theme functions (simple implementation)
function getUserTheme() {
    return isset($_COOKIE['user_theme']) ? $_COOKIE['user_theme'] : 'light';
}

function setUserTheme($theme) {
    setcookie('user_theme', $theme, time() + (86400 * 30), "/");
}
?>
