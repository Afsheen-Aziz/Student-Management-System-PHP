<?php
require_once 'config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Modern Minimal Design System */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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

        .card-body {
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

        .btn {
            border-radius: 12px;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-outline-danger {
            border: 2px solid #dc3545;
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .btn-outline-danger:hover {
            background: var(--secondary-gradient);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(220, 53, 69, 0.4);
        }

        .table {
            border-radius: 15px;
            overflow: hidden;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .table thead th {
            background: var(--primary-gradient);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            border-color: rgba(0,0,0,0.08);
            vertical-align: middle;
            color: #2c3e50;
            font-weight: 500;
        }

        .table tbody tr {
            background: rgba(255,255,255,0.95);
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.08) !important;
            transform: scale(1.01);
        }

        .alert {
            border-radius: 12px;
            border: none;
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
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

        .dark-theme .table {
            background: rgba(30, 30, 30, 0.9) !important;
            color: #ffffff !important;
        }

        .dark-theme .table tbody td {
            border-color: var(--dark-border) !important;
        }

        .dark-theme .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.1) !important;
        }

        .dark-theme .alert-success {
            background: linear-gradient(135deg, #1e3a28 0%, #2d5a3d 100%) !important;
            color: #b8e6c6 !important;
        }

        .dark-theme .alert-info {
            background: linear-gradient(135deg, #1e3a3f 0%, #2d5a5f 100%) !important;
            color: #b8e6f0 !important;
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

        .card {
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

            .table-responsive {
                border-radius: 15px;
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
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="view_students.php">View Students</a>
            <a class="btn btn-outline-light btn-sm ms-2" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Activity Logs</h5>
                </div>
                <div class="col-auto">
                    <form method="POST" class="d-inline">
                        <button type="submit" name="clear_logs" class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('Are you sure you want to clear all logs?')">
                            <i class="fas fa-trash me-1"></i>Clear Logs
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php
            $logFile = 'logs/activity.log';
            
            // Handle clear logs
            if (isset($_POST['clear_logs'])) {
                if (file_exists($logFile)) {
                    file_put_contents($logFile, '');
                    writeLog('Activity logs cleared by ' . $_SESSION['username']);
                    echo '<div class="alert alert-success">Logs cleared successfully!</div>';
                }
            }
            
            // Read and display logs
            if (file_exists($logFile)) {
                $logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $logs = array_reverse($logs); // Show newest first
                
                if (!empty($logs)) {
                    echo '<div class="mb-3">';
                    echo '<small class="text-muted">Showing ' . count($logs) . ' log entries (newest first)</small>';
                    echo '</div>';
                    
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-striped table-hover">';
                    echo '<thead class="table-dark">';
                    echo '<tr>';
                    echo '<th style="width: 20%;">Timestamp</th>';
                    echo '<th style="width: 80%;">Activity</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    foreach ($logs as $index => $log) {
                        if (preg_match('/^\[(.*?)\] (.*)$/', $log, $matches)) {
                            $timestamp = $matches[1];
                            $activity = $matches[2];
                            
                            // Color code different activities
                            $rowClass = '';
                            $icon = 'fas fa-info-circle text-info';
                            
                            if (strpos($activity, 'login') !== false) {
                                $rowClass = 'table-success';
                                $icon = 'fas fa-sign-in-alt text-success';
                            } elseif (strpos($activity, 'Failed login') !== false) {
                                $rowClass = 'table-danger';
                                $icon = 'fas fa-exclamation-triangle text-danger';
                            } elseif (strpos($activity, 'added') !== false) {
                                $rowClass = 'table-info';
                                $icon = 'fas fa-plus text-primary';
                            } elseif (strpos($activity, 'updated') !== false) {
                                $rowClass = 'table-warning';
                                $icon = 'fas fa-edit text-warning';
                            } elseif (strpos($activity, 'deleted') !== false) {
                                $rowClass = 'table-danger';
                                $icon = 'fas fa-trash text-danger';
                            }
                            
                            echo '<tr class="' . $rowClass . '">';
                            echo '<td><small>' . htmlspecialchars($timestamp) . '</small></td>';
                            echo '<td>';
                            echo '<i class="' . $icon . ' me-2"></i>';
                            echo htmlspecialchars($activity);
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        // Limit display to prevent page overload
                        if ($index >= 99) {
                            echo '<tr>';
                            echo '<td colspan="2" class="text-center text-muted">';
                            echo '<em>... and ' . (count($logs) - 100) . ' more entries (showing last 100)</em>';
                            echo '</td>';
                            echo '</tr>';
                            break;
                        }
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-info">';
                    echo '<i class="fas fa-info-circle me-2"></i>No activity logs found.';
                    echo '</div>';
                }
            } else {
                echo '<div class="alert alert-info">';
                echo '<i class="fas fa-info-circle me-2"></i>Log file does not exist yet. Activity will be logged as you use the system.';
                echo '</div>';
            }
            ?>
            
            <div class="mt-4">
                <h6>Log Legend:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <small>
                            <i class="fas fa-sign-in-alt text-success me-2"></i>Login Activities<br>
                            <i class="fas fa-plus text-primary me-2"></i>Student Added<br>
                            <i class="fas fa-edit text-warning me-2"></i>Student Updated
                        </small>
                    </div>
                    <div class="col-md-6">
                        <small>
                            <i class="fas fa-trash text-danger me-2"></i>Student Deleted<br>
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>Failed Login<br>
                            <i class="fas fa-info-circle text-info me-2"></i>General Activity
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
