<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .login-container { margin-top: 10vh; }
        .card { 
            border: none;
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
        }
        
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 12px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid transparent;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        /* Dark theme for login */
        .dark-theme {
            background: linear-gradient(135deg, #0f0f0f 0%, #1a1a1a 100%) !important;
        }
        
        .dark-theme .card {
            background-color: rgba(30, 30, 30, 0.95) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        .dark-theme .form-control {
            background-color: #2a2a2a !important;
            border: 2px solid #3a3a3a !important;
            color: #ffffff !important;
        }
        
        .dark-theme .form-control:focus {
            background-color: #2a2a2a !important;
            border-color: #667eea !important;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2) !important;
        }
        
        .dark-theme .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .dark-theme .btn-primary:hover {
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .dark-theme .form-select {
            background-color: #2a2a2a !important;
            border: 2px solid #3a3a3a !important;
            color: #ffffff !important;
            border-radius: 8px;
        }
        
        .dark-theme h3 {
            color: #ffffff !important;
        }
        
        .dark-theme a {
            color: #667eea !important;
        }
        
        .dark-theme a:hover {
            color: #764ba2 !important;
        }
    </style>
</head>
<body class="<?php require_once 'config.php'; echo (getUserTheme() == 'dark') ? 'dark-theme' : ''; ?>">
<?php
require_once 'config.php';

$error = '';
$success = '';

// Handle theme change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme'])) {
    setUserTheme($_POST['theme']);
    // Redirect to avoid resubmission
    header('Location: index.php');
    exit();
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    
    // Authenticate user against database
    $user = authenticateUser($username, $password);
    if ($user) {
        loginUser($user);
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password!';
        writeLog("Failed login attempt for username: $username");
    }
}
?>
?>

<div class="container">
    <div class="row justify-content-center login-container">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-body p-5">
                    <!-- Theme Switcher -->
                    <div class="text-end mb-3">
                        <form method="POST" class="d-inline">
                            <select name="theme" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; display: inline-block;">
                                <option value="light" <?php echo (getUserTheme() == 'light') ? 'selected' : ''; ?>>☀️ Light</option>
                                <option value="dark" <?php echo (getUserTheme() == 'dark') ? 'selected' : ''; ?>>🌙 Dark</option>
                            </select>
                        </form>
                    </div>
                    
                    <h3 class="text-center mb-4 fw-bold">Welcome Back</h3>
                    <p class="text-center text-muted mb-4">Sign in to your account</p>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p class="mb-1">Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
