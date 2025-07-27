<?php
require_once 'config.php';

$error = '';
$success = '';

// Handle theme change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme'])) {
    setUserTheme($_POST['theme']);
    header('Location: register.php');
    exit();
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);
    $full_name = sanitizeInput($_POST['full_name']);
    
    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = 'All fields are required!';
    } elseif (strlen($username) < 3) {
        $error = 'Username must be at least 3 characters!';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters!';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address!';
    } else {
        // Try to create user
        $user_id = createUser($username, $email, $password, $full_name);
        if ($user_id) {
            $success = 'Registration successful! You can now login.';
            writeLog("New user registered: $username ($email)");
            // Clear form
            $_POST = array();
        } else {
            $error = 'Username or email already exists!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Modern Minimal Design System */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-bg: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%);
            --dark-surface: #1e1e1e;
            --dark-border: #333;
        }

        * {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 25px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
            backdrop-filter: blur(20px);
            background: rgba(255,255,255,0.95);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 40px 80px rgba(0,0,0,0.25);
        }

        .card-body {
            padding: 3rem;
        }

        h3 {
            font-weight: 700;
            color: #2c3e50;
            margin-top: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .form-control, .form-select {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
            background: rgba(255,255,255,0.95);
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .btn {
            border-radius: 15px;
            font-weight: 600;
            padding: 1rem 2rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .alert {
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            font-weight: 500;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .theme-switcher {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 10;
        }

        .form-select-sm {
            border-radius: 10px;
            border: 2px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.2);
            color: white;
            backdrop-filter: blur(10px);
            font-size: 0.8rem;
            min-width: 100px;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        /* Dark Theme */
        .dark-theme {
            background: var(--dark-bg) !important;
        }

        .dark-theme .card {
            background: rgba(30, 30, 30, 0.95) !important;
            color: #ffffff !important;
        }

        .dark-theme h3 {
            color: #ffffff !important;
            margin-top: 2.5rem;
        }

        .dark-theme .form-label {
            color: #ffffff !important;
        }

        .dark-theme .form-control, 
        .dark-theme .form-select {
            background: rgba(51, 51, 51, 0.8) !important;
            border-color: #333 !important;
            color: #ffffff !important;
        }

        .dark-theme .form-control:focus,
        .dark-theme .form-select:focus {
            background: rgba(51, 51, 51, 0.9) !important;
            border-color: #667eea !important;
            color: #ffffff !important;
        }

        .dark-theme .form-select-sm {
            background: rgba(51, 51, 51, 0.9) !important;
            border-color: #555 !important;
            color: #ffffff !important;
        }

        .dark-theme .alert-danger {
            background: linear-gradient(135deg, #3a1e1e 0%, #5a2d2d 100%) !important;
            color: #e6b8b8 !important;
        }

        .dark-theme .alert-success {
            background: linear-gradient(135deg, #1e3a28 0%, #2d5a3d 100%) !important;
            color: #b8e6c6 !important;
        }

        .dark-theme .login-link {
            border-top-color: #333 !important;
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: slideInUp 0.8s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .register-container {
                padding: 1rem;
            }
            
            .card-body {
                padding: 2rem;
            }
            
            .card {
                border-radius: 20px;
            }
        }
    </style>
</head>
<body class="<?php echo (getUserTheme() == 'dark') ? 'dark-theme' : ''; ?>">

<div class="register-container">
    <div class="card">
        <div class="card-body">
            <!-- Theme Switcher -->
            <div class="theme-switcher">
                <form method="POST" class="d-inline">
                    <select name="theme" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="light" <?php echo (getUserTheme() == 'light') ? 'selected' : ''; ?>>☀️ Light</option>
                        <option value="dark" <?php echo (getUserTheme() == 'dark') ? 'selected' : ''; ?>>🌙 Dark</option>
                    </select>
                </form>
            </div>
            
            <h3>Join StudentHub</h3>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                                <div class="form-text">Minimum 3 characters</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Minimum 6 characters</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </div>
                    </form>
                    
                    <div class="login-link">
                        <p>Already have an account? <a href="index.php" class="text-decoration-none fw-bold">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
