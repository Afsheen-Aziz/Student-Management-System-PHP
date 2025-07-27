<?php
require_once 'config.php';
requireLogin();

if (!isset($_GET['id'])) {
    header('Location: view_students.php');
    exit();
}

$student_id = (int)$_GET['id'];
$message = '';
$messageType = '';

try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        header('Location: view_students.php');
        exit();
    }
} catch (Exception $e) {
    $message = 'Error: ' . $e->getMessage();
    $messageType = 'danger';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $date_of_birth = sanitizeInput($_POST['date_of_birth']);
    $gender = sanitizeInput($_POST['gender']);
    $address = sanitizeInput($_POST['address']);
    $course = sanitizeInput($_POST['course']);
    $semester = sanitizeInput($_POST['semester']);
    
    if (!validateEmail($email)) {
        $message = 'Please enter a valid email address.';
        $messageType = 'danger';
    } else {
        try {
            // Check if email exists for other students
            $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
            $stmt->execute([$email, $student_id]);
            
            if ($stmt->rowCount() > 0) {
                $message = 'Email already exists for another student!';
                $messageType = 'danger';
            } else {
                // Handle file upload
                $profile_picture = $student['profile_picture'];
                $upload_error = '';
                
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
                    $upload_error_code = $_FILES['profile_picture']['error'];
                    
                    if ($upload_error_code == 0) {
                        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                        $filename = $_FILES['profile_picture']['name'];
                        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        
                        if (in_array($file_ext, $allowed)) {
                            $new_filename = uniqid() . '.' . $file_ext;
                            $upload_path = 'uploads/' . $new_filename;
                            
                            // Ensure uploads directory exists and is writable
                            if (!is_dir('uploads')) {
                                mkdir('uploads', 0755, true);
                            }
                            
                            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                                // Delete old profile picture
                                if ($profile_picture && file_exists('uploads/' . $profile_picture)) {
                                    unlink('uploads/' . $profile_picture);
                                }
                                $profile_picture = $new_filename;
                            } else {
                                $upload_error = 'Failed to move uploaded file. Check directory permissions.';
                            }
                        } else {
                            $upload_error = 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.';
                        }
                    } else {
                        switch ($upload_error_code) {
                            case UPLOAD_ERR_INI_SIZE:
                            case UPLOAD_ERR_FORM_SIZE:
                                $upload_error = 'File is too large.';
                                break;
                            case UPLOAD_ERR_PARTIAL:
                                $upload_error = 'File upload was interrupted.';
                                break;
                            case UPLOAD_ERR_NO_TMP_DIR:
                                $upload_error = 'Missing temporary folder.';
                                break;
                            case UPLOAD_ERR_CANT_WRITE:
                                $upload_error = 'Failed to write file to disk.';
                                break;
                            default:
                                $upload_error = 'Unknown upload error.';
                        }
                    }
                }
                
                // Update student
                $stmt = $pdo->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ?, phone = ?, date_of_birth = ?, gender = ?, address = ?, course = ?, semester = ?, profile_picture = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$first_name, $last_name, $email, $phone, $date_of_birth, $gender, $address, $course, $semester, $profile_picture, $student_id]);
                
                $message = 'Student updated successfully!';
                if ($upload_error) {
                    $message .= ' Note: ' . $upload_error;
                    $messageType = 'warning';
                } else {
                    $messageType = 'success';
                }
                writeLog("Student updated: {$student['student_id']} - $first_name $last_name");
                
                // Refresh student data
                $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
                $stmt->execute([$student_id]);
                $student = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) {
            $message = 'Error: ' . $e->getMessage();
            $messageType = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Management System</title>
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

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 117, 125, 0.4);
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

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .img-thumbnail {
            border-radius: 12px;
            border: 3px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .img-thumbnail:hover {
            transform: scale(1.05);
            border-color: #667eea;
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

        .dark-theme .alert-success {
            background: linear-gradient(135deg, #1e3a28 0%, #2d5a3d 100%) !important;
            color: #b8e6c6 !important;
        }

        .dark-theme .alert-danger {
            background: linear-gradient(135deg, #3a1e1e 0%, #5a2d2d 100%) !important;
            color: #e6b8b8 !important;
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
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Student - <?php echo htmlspecialchars($student['student_id']); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $messageType; ?>"><?php echo $message; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="student_id" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="student_id" 
                                       value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
                                <small class="text-muted">Student ID cannot be changed</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($student['email']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($student['phone']); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                       value="<?php echo htmlspecialchars($student['date_of_birth']); ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo ($student['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="profile_picture" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                                <?php if ($student['profile_picture']): ?>
                                    <small class="text-muted">Current: <?php echo htmlspecialchars($student['profile_picture']); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($student['profile_picture']): ?>
                            <div class="mb-3">
                                <label class="form-label">Current Photo</label><br>
                                <img src="uploads/<?php echo htmlspecialchars($student['profile_picture']); ?>" 
                                     alt="Current Profile" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="course" class="form-label">Course</label>
                                <input type="text" class="form-control" id="course" name="course" 
                                       value="<?php echo htmlspecialchars($student['course']); ?>">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="semester" class="form-label">Semester</label>
                                <select class="form-select" id="semester" name="semester">
                                    <option value="">Select Semester</option>
                                    <?php for ($i = 1; $i <= 8; $i++): ?>
                                        <option value="<?php echo $i; ?>" <?php echo ($student['semester'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($student['address']); ?></textarea>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="view_students.php" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Update Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
