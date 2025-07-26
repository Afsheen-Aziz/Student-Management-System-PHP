<?php
require_once 'config.php';
requireLogin();

// Handle theme change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['theme'])) {
    setUserTheme($_POST['theme']);
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Modern Minimal Design System */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --warning-gradient: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            --info-gradient: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            --dark-bg: #0f0f0f;
            --dark-surface: #1e1e1e;
            --dark-border: #333;
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
        }

        * {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .navbar {
            background: var(--primary-gradient) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #fff !important;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.98);
            transition: all 0.3s ease;
            color: #2c3e50;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 1.5rem;
            border: none;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .card-body {
            color: #2c3e50;
            padding: 2rem;
        }

        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: var(--success-gradient);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 172, 254, 0.4);
        }

        .btn-warning {
            background: var(--warning-gradient);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 193, 7, 0.4);
            color: white;
        }

        .btn-info {
            background: var(--info-gradient);
            color: white;
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(23, 162, 184, 0.4);
        }

        /* Stats cards with proper light mode styling */
        .stats-card {
            background: rgba(255,255,255,0.95);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            color: #2c3e50;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .stats-card.primary {
            background: var(--primary-gradient);
            color: white;
        }

        .stats-card.success {
            background: var(--success-gradient);
            color: white;
        }

        .stats-card.warning {
            background: var(--warning-gradient);
            color: white;
        }

        .stats-card.info {
            background: var(--info-gradient);
            color: white;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-weight: 600;
            opacity: 0.9;
        }

        /* Text improvements for light mode */
        .text-muted {
            color: #6c757d !important;
        }

        h1, h2, h3, h4, h5, h6 {
            color: #2c3e50;
            font-weight: 600;
        }

        /* Welcome message styling */
        .welcome-header {
            background: var(--primary-gradient);
            color: white;
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .welcome-header h2 {
            color: white;
            margin-bottom: 0.5rem;
        }

        .welcome-header p {
            opacity: 0.9;
            margin: 0;
        }

        /* Theme switcher styling */
        .theme-switcher {
            background: rgba(255,255,255,0.9);
            border-radius: 10px;
            padding: 0.5rem;
        }

        .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            color: #2c3e50;
            font-weight: 500;
        }

        /* Dark Theme */
        .dark-theme {
            background: var(--dark-bg) !important;
            color: #ffffff !important;
        }

        .dark-theme .card {
            background: rgba(30, 30, 30, 0.95) !important;
            color: #ffffff !important;
        }

        .dark-theme .card-body {
            color: #ffffff !important;
        }

        .dark-theme .stats-card {
            background: rgba(30, 30, 30, 0.9) !important;
            color: #ffffff !important;
        }

        .dark-theme .theme-switcher {
            background: rgba(30, 30, 30, 0.9) !important;
        }

        .dark-theme .form-select {
            background: rgba(51, 51, 51, 0.8) !important;
            border-color: var(--dark-border) !important;
            color: #ffffff !important;
        }

        .dark-theme h1, .dark-theme h2, .dark-theme h3, 
        .dark-theme h4, .dark-theme h5, .dark-theme h6 {
            color: #ffffff !important;
        }

        .dark-theme .text-muted {
            color: #adb5bd !important;
        }

        /* Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .stats-card, .welcome-header {
            animation: slideInUp 0.6s ease forwards;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card {
                margin: 1rem;
                border-radius: 15px;
            }
            
            .navbar-brand {
                font-size: 1.25rem;
            }

            .stats-number {
                font-size: 2rem;
            }

            .welcome-header {
                padding: 1.5rem;
                margin: 1rem;
            }
        }
    </style>
</head>
<body class="<?php echo (getUserTheme() == 'dark') ? 'dark-theme' : ''; ?>">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="fas fa-graduation-cap me-2"></i>StudentHub
        </a>
        
        <div class="navbar-nav ms-auto">
            <span class="navbar-text me-3">
                Welcome, <?php echo $_SESSION['full_name']; ?>!
            </span>
            
            <!-- Theme Switcher -->
            <form method="POST" class="d-inline me-2">
                <select name="theme" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                    <option value="light" <?php echo (getUserTheme() == 'light') ? 'selected' : ''; ?>>☀️ Light</option>
                    <option value="dark" <?php echo (getUserTheme() == 'dark') ? 'selected' : ''; ?>>🌙 Dark</option>
                </select>
            </form>
            
            <a class="btn btn-outline-light btn-sm" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-md-3 mb-4">
            <div class="stats-card primary">
                <div class="stats-number">
                    <?php
                    try {
                        $pdo = getDBConnection();
                        $stmt = $pdo->query("SELECT COUNT(*) FROM students");
                        echo $stmt->fetchColumn();
                    } catch (Exception $e) {
                        echo "0";
                    }
                    ?>
                </div>
                <div class="stats-label">
                    <i class="fas fa-users me-2"></i>Total Students
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stats-card success">
                <div class="stats-number">
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT COUNT(DISTINCT course) FROM students");
                        echo $stmt->fetchColumn();
                    } catch (Exception $e) {
                        echo "0";
                    }
                    ?>
                </div>
                <div class="stats-label">
                    <i class="fas fa-book me-2"></i>Courses
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stats-card warning">
                <div class="stats-number">
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) FROM students WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
                        echo $stmt->fetchColumn();
                    } catch (Exception $e) {
                        echo "0";
                    }
                    ?>
                </div>
                <div class="stats-label">
                    <i class="fas fa-user-plus me-2"></i>New This Month
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="stats-card info">
                <div class="stats-number">Active</div>
                <div class="stats-label">
                    <i class="fas fa-check-circle me-2"></i>System Status
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="add_student.php" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>Add New Student
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="view_students.php" class="btn btn-success w-100">
                                <i class="fas fa-list me-2"></i>View All Students
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="search_student.php" class="btn btn-info w-100">
                                <i class="fas fa-search me-2"></i>Search Students
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="logs.php" class="btn btn-warning w-100">
                                <i class="fas fa-history me-2"></i>View Logs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
