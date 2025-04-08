<?php
require_once '../../includes/db/db.php'; // Include the database connection

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is admin
if ($_SESSION['role'] !== 'admin') {
    echo "Unauthorized";
    exit;
}

// Check if ID is sent via POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]); // Convert to integer to prevent SQL injection

    $deleteQuery = "DELETE FROM branch WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}
?>
