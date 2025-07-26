<?php
require_once 'config.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Students - Student Management System</title>
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
            margin-bottom: 2rem;
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

        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            color: #2c3e50;
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
            background: #ffffff;
            color: #2c3e50;
        }

        .form-label {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
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

        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 193, 7, 0.4);
            color: white;
        }

        .btn-danger {
            background: var(--secondary-gradient);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(240, 147, 251, 0.4);
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

        .img-thumbnail {
            border-radius: 50%;
            border: 3px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .img-thumbnail:hover {
            transform: scale(1.1);
            border-color: #667eea;
        }

        .alert {
            border-radius: 12px;
            border: none;
            backdrop-filter: blur(10px);
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            font-weight: 500;
            border: 1px solid rgba(12, 84, 96, 0.2);
        }

        /* Light mode text improvements */
        .text-muted {
            color: #6c757d !important;
        }

        h6 {
            color: #2c3e50;
            font-weight: 600;
        }

        /* Button group improvements for light mode */
        .btn-outline-primary {
            border-color: #667eea;
            color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }

        .btn-outline-primary:hover {
            background: #667eea;
            border-color: #667eea;
            color: white;
        }

        .btn-outline-warning {
            border-color: #ffc107;
            color: #856404;
            background: rgba(255, 193, 7, 0.1);
        }

        .btn-outline-warning:hover {
            background: #ffc107;
            border-color: #ffc107;
            color: #212529;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
            background: rgba(108, 117, 125, 0.1);
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
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

        .dark-theme .form-control, 
        .dark-theme .form-select {
            background: rgba(51, 51, 51, 0.8) !important;
            border-color: var(--dark-border) !important;
            color: #ffffff !important;
        }

        .dark-theme .form-control:focus,
        .dark-theme .form-select:focus {
            background: rgba(51, 51, 51, 0.9) !important;
            border-color: #667eea !important;
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
            <h5 class="mb-0"><i class="fas fa-search me-2"></i>Advanced Student Search</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="student_id" class="form-label">Student ID</label>
                        <input type="text" class="form-control" id="student_id" name="student_id" 
                               value="<?php echo isset($_GET['student_id']) ? htmlspecialchars($_GET['student_id']) : ''; ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : ''; ?>"
                               placeholder="First or Last name">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="course" class="form-label">Course</label>
                        <input type="text" class="form-control" id="course" name="course" 
                               value="<?php echo isset($_GET['course']) ? htmlspecialchars($_GET['course']) : ''; ?>">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester">
                            <option value="">All Semesters</option>
                            <?php for ($i = 1; $i <= 8; $i++): ?>
                                <option value="<?php echo $i; ?>" 
                                        <?php echo (isset($_GET['semester']) && $_GET['semester'] == $i) ? 'selected' : ''; ?>>
                                    Semester <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-select" id="gender" name="gender">
                            <option value="">All Genders</option>
                            <option value="Male" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo (isset($_GET['gender']) && $_GET['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="search_student.php" class="btn btn-outline-secondary me-md-2">Clear</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search Students
                    </button>
                </div>
            </form>

            <?php
            // Process search if form is submitted
            if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty(array_filter($_GET))) {
                try {
                    $pdo = getDBConnection();
                    
                    // Build dynamic query
                    $sql = "SELECT * FROM students WHERE 1=1";
                    $params = [];
                    
                    if (!empty($_GET['student_id'])) {
                        $sql .= " AND student_id LIKE ?";
                        $params[] = "%" . sanitizeInput($_GET['student_id']) . "%";
                    }
                    
                    if (!empty($_GET['name'])) {
                        $sql .= " AND (first_name LIKE ? OR last_name LIKE ?)";
                        $name_param = "%" . sanitizeInput($_GET['name']) . "%";
                        $params[] = $name_param;
                        $params[] = $name_param;
                    }
                    
                    if (!empty($_GET['email'])) {
                        $sql .= " AND email LIKE ?";
                        $params[] = "%" . sanitizeInput($_GET['email']) . "%";
                    }
                    
                    if (!empty($_GET['course'])) {
                        $sql .= " AND course LIKE ?";
                        $params[] = "%" . sanitizeInput($_GET['course']) . "%";
                    }
                    
                    if (!empty($_GET['semester'])) {
                        $sql .= " AND semester = ?";
                        $params[] = sanitizeInput($_GET['semester']);
                    }
                    
                    if (!empty($_GET['gender'])) {
                        $sql .= " AND gender = ?";
                        $params[] = sanitizeInput($_GET['gender']);
                    }
                    
                    $sql .= " ORDER BY first_name, last_name";
                    
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute($params);
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    echo '<hr>';
                    echo '<h6>Search Results (' . count($results) . ' found)</h6>';
                    
                    if (empty($results)) {
                        echo '<div class="alert alert-info">No students found matching your search criteria.</div>';
                    } else {
                        echo '<div class="table-responsive">';
                        echo '<table class="table table-striped">';
                        echo '<thead class="table-dark">';
                        echo '<tr>';
                        echo '<th>Student ID</th>';
                        echo '<th>Photo</th>';
                        echo '<th>Name</th>';
                        echo '<th>Email</th>';
                        echo '<th>Course</th>';
                        echo '<th>Semester</th>';
                        echo '<th>Gender</th>';
                        echo '<th>Actions</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        
                        foreach ($results as $student) {
                            echo '<tr>';
                            echo '<td><strong>' . htmlspecialchars($student['student_id']) . '</strong></td>';
                            echo '<td>';
                            if ($student['profile_picture']) {
                                echo '<img src="uploads/' . htmlspecialchars($student['profile_picture']) . '" 
                                          alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">';
                            } else {
                                echo '<div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                          style="width: 40px; height: 40px;">
                                          <i class="fas fa-user text-white"></i>
                                      </div>';
                            }
                            echo '</td>';
                            echo '<td>' . htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($student['email']) . '</td>';
                            echo '<td>' . htmlspecialchars($student['course'] ?: '-') . '</td>';
                            echo '<td>' . htmlspecialchars($student['semester'] ?: '-') . '</td>';
                            echo '<td>' . htmlspecialchars($student['gender'] ?: '-') . '</td>';
                            echo '<td>';
                            echo '<div class="btn-group btn-group-sm">';
                            echo '<a href="view_student.php?id=' . $student['id'] . '" class="btn btn-outline-primary" title="View Details">';
                            echo '<i class="fas fa-eye"></i>';
                            echo '</a>';
                            echo '<a href="edit_student.php?id=' . $student['id'] . '" class="btn btn-outline-warning" title="Edit">';
                            echo '<i class="fas fa-edit"></i>';
                            echo '</a>';
                            echo '</div>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        echo '</tbody>';
                        echo '</table>';
                        echo '</div>';
                    }
                    
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Error performing search: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
