<?php
// Start the session
session_start();

// Check if the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login/login.php");
    exit;
}

// Include the database connection
require_once '../../includes/db/db.php';

// Check if the ID parameter is set
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    // Delete dependent records in applications before deleting the user
    $deleteApplications = $conn->prepare("DELETE FROM applications WHERE student_id = ?");
    $deleteApplications->bind_param("i", $user_id);
    $deleteApplications->execute();
    $deleteApplications->close();

    // Delete dependent records in notifications before deleting the user
    $deleteNotifications = $conn->prepare("DELETE FROM notifications WHERE user_id = ?");
    $deleteNotifications->bind_param("i", $user_id);
    $deleteNotifications->execute();
    $deleteNotifications->close();

    // Now delete the user
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "User, related applications, and notifications deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting user.";
    }
    
    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error_message'] = "Invalid user ID.";
}

// Redirect back to the user management page with a success or error message
header("Location: users.php");
exit;
?>