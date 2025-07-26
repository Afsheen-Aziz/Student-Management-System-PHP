<?php
require_once 'config.php';

// Destroy session and redirect to login
session_destroy();
writeLog('User logged out');
header('Location: index.php?message=logged_out');
exit();
?>
