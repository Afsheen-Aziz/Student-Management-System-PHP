<?php
require_once 'config.php';
requireLogin();

// Handle sorting
$sort_by = isset($_GET['sort']) ? sanitizeInput($_GET['sort']) : 'id';
$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

// Valid sort columns
$valid_sorts = ['id', 'student_id', 'first_name', 'last_name', 'email', 'course', 'semester', 'created_at'];
if (!in_array($sort_by, $valid_sorts)) {
    $sort_by = 'id';
}

try {
    $pdo = getDBConnection();
    
    // Build query
    $sql = "SELECT * FROM students ORDER BY $sort_by $order";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error = "Error fetching students: " . $e->getMessage();
}

// Helper function for sort links
function getSortLink($column, $current_sort, $current_order) {
    $new_order = ($current_sort == $column && $current_order == 'ASC') ? 'desc' : 'asc';
    return "?sort=$column&order=$new_order";
}

// Helper function for sort icon
function getSortIcon($column, $current_sort, $current_order) {
    if ($current_sort != $column) return '<i class="fas fa-sort text-muted"></i>';
    return $current_order == 'ASC' ? '<i class="fas fa-sort-up text-primary"></i>' : '<i class="fas fa-sort-down text-primary"></i>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - Student Management System</title>
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

        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .btn-outline-danger:hover {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
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
            position: sticky;
            top: 0;
            z-index: 10;
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
            font-weight: 500;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid rgba(21, 87, 36, 0.2);
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid rgba(114, 28, 36, 0.2);
        }

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
            border: 1px solid rgba(12, 84, 96, 0.2);
        }

        /* Text improvements for light mode */
        .text-muted {
            color: #6c757d !important;
        }

        .text-primary {
            color: #667eea !important;
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
            color: #ffffff !important;
        }

        .dark-theme .table tbody tr {
            background: rgba(30, 30, 30, 0.9) !important;
        }

        .dark-theme .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.1) !important;
        }

        .dark-theme .alert-success {
            background: linear-gradient(135deg, #1e3a28 0%, #2d5a3d 100%) !important;
            color: #b8e6c6 !important;
            border-color: rgba(184, 230, 198, 0.2) !important;
        }

        .dark-theme .alert-danger {
            background: linear-gradient(135deg, #3a1e1e 0%, #5a2d2d 100%) !important;
            color: #e6b8b8 !important;
            border-color: rgba(230, 184, 184, 0.2) !important;
        }

        .dark-theme .alert-info {
            background: linear-gradient(135deg, #1e3a3f 0%, #2d5a5f 100%) !important;
            color: #b8e6f0 !important;
            border-color: rgba(184, 230, 240, 0.2) !important;
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
            <a class="nav-link" href="add_student.php">Add Student</a>
            <a class="btn btn-outline-light btn-sm ms-2" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>All Students</h5>
                </div>
                <div class="col-auto">
                    <a href="search_student.php" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-search me-1"></i>Search Students
                    </a>
                    <a href="add_student.php" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Add New Student
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php elseif (empty($students)): ?>
                <div class="alert alert-info">
                    No students found. <a href="add_student.php">Add the first student</a>.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>
                                    <a href="<?php echo getSortLink('student_id', $sort_by, $order); ?>" class="text-white text-decoration-none">
                                        Student ID <?php echo getSortIcon('student_id', $sort_by, $order); ?>
                                    </a>
                                </th>
                                <th>Photo</th>
                                <th>
                                    <a href="<?php echo getSortLink('first_name', $sort_by, $order); ?>" class="text-white text-decoration-none">
                                        Name <?php echo getSortIcon('first_name', $sort_by, $order); ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo getSortLink('email', $sort_by, $order); ?>" class="text-white text-decoration-none">
                                        Email <?php echo getSortIcon('email', $sort_by, $order); ?>
                                    </a>
                                </th>
                                <th>Phone</th>
                                <th>
                                    <a href="<?php echo getSortLink('course', $sort_by, $order); ?>" class="text-white text-decoration-none">
                                        Course <?php echo getSortIcon('course', $sort_by, $order); ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo getSortLink('semester', $sort_by, $order); ?>" class="text-white text-decoration-none">
                                        Semester <?php echo getSortIcon('semester', $sort_by, $order); ?>
                                    </a>
                                </th>
                                <th>
                                    <a href="<?php echo getSortLink('created_at', $sort_by, $order); ?>" class="text-white text-decoration-none">
                                        Created <?php echo getSortIcon('created_at', $sort_by, $order); ?>
                                    </a>
                                </th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($student['student_id']); ?></strong></td>
                                    <td>
                                        <?php if ($student['profile_picture']): ?>
                                            <img src="uploads/<?php echo htmlspecialchars($student['profile_picture']); ?>" 
                                                 alt="Profile" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><?php echo htmlspecialchars($student['phone'] ?: '-'); ?></td>
                                    <td><?php echo htmlspecialchars($student['course'] ?: '-'); ?></td>
                                    <td><?php echo htmlspecialchars($student['semester'] ?: '-'); ?></td>
                                    <td><?php echo date('M j, Y', strtotime($student['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="view_student.php?id=<?php echo $student['id']; ?>" 
                                               class="btn btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit_student.php?id=<?php echo $student['id']; ?>" 
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete_student.php?id=<?php echo $student['id']; ?>" 
                                               class="btn btn-outline-danger" title="Delete"
                                               onclick="return confirm('Are you sure you want to delete this student?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        Showing <?php echo count($students); ?> student(s)
                        <?php if ($search): ?>
                            for search "<?php echo htmlspecialchars($search); ?>"
                        <?php endif; ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
