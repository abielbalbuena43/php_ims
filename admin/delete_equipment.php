<?php
// Start session
session_start();
include "session_verification.php";
include "../admin/connection.php";

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $_SESSION["message"] = "You must be logged in to delete equipment.";
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Check if the equipment ID is provided
if (isset($_GET['equipment_id'])) {
    $equipment_id = intval($_GET['equipment_id']);

    // Fetch the PC name for logging before deletion
    $result = mysqli_query($link, "SELECT pcname FROM equipment WHERE equipment_id = $equipment_id");
    $row = mysqli_fetch_assoc($result);
    $pcname = $row['pcname'];

    // Delete the equipment
    $query = "DELETE FROM equipment WHERE equipment_id = $equipment_id";
    if (mysqli_query($link, $query)) {
        // Log the deletion action with the logged-in user
        $log_action = "Deleted equipment: $pcname";

        // Insert the log with user_id and action
        $insert_log_query = "INSERT INTO logs (user_id, action, date_edited) 
                             VALUES (?, ?, NOW())";
        $stmt_log = mysqli_prepare($link, $insert_log_query);
        mysqli_stmt_bind_param($stmt_log, "is", $_SESSION['user_id'], $log_action);
        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);

        // Set a gray alert for successful deletion
        $_SESSION['alert'] = 'deleted';
    } else {
        // Set an error alert if the deletion fails
        $_SESSION['alert'] = 'error';
    }
} else {
    // Redirect to the equipment list if no ID is provided
    $_SESSION['alert'] = 'error';
}

// Redirect to the equipment list page
header("Location: equipment.php");
exit();
?>