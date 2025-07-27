<?php
require_once 'config.php';
requireLogin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: view_students.php');
    exit();
}

$student_id = (int)$_GET['id'];

try {
    // Get student details before deletion for logging
    $stmt = $pdo->prepare("SELECT student_id, first_name, last_name, profile_picture FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if (!$student) {
        header('Location: view_students.php?error=' . urlencode('Student not found!'));
        exit();
    }
    
    // Delete the student record
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    
    // Delete profile picture if exists
    if ($student['profile_picture'] && file_exists('uploads/' . $student['profile_picture'])) {
        unlink('uploads/' . $student['profile_picture']);
    }
    
    // Log the deletion
    writeLog("Student deleted: {$student['student_id']} - {$student['first_name']} {$student['last_name']}");
    
    header('Location: view_students.php?success=' . urlencode('Student deleted successfully!'));
    exit();
    
} catch(PDOException $e) {
    writeLog("Error deleting student: " . $e->getMessage());
    header('Location: view_students.php?error=' . urlencode('Error deleting student!'));
    exit();
}
?>
