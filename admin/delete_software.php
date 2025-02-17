<?php
// Start session
session_start();
include "session_verification.php";
include "../user/connection.php";

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $_SESSION["message"] = "You must be logged in to delete software.";
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Check if the software ID is provided
if (isset($_GET['software_id'])) {
    $software_id = intval($_GET['software_id']);

    // Fetch the PC name for logging before deletion
    $result = mysqli_query($link, "SELECT e.pcname FROM software s JOIN equipment e ON s.equipment_id = e.equipment_id WHERE s.software_id = $software_id");
    $row = mysqli_fetch_assoc($result);
    $pcname = $row['pcname'];

    // Delete the software record
    $query = "DELETE FROM software WHERE software_id = $software_id";
    if (mysqli_query($link, $query)) {
        // Log the deletion action with user info
        $log_action = "Deleted Software for: $pcname";

        // Insert the log with user_id and action
        $insert_log_query = "INSERT INTO logs (user_id, action, date_edited) 
                             VALUES (?, ?, NOW())";
        $stmt_log = mysqli_prepare($link, $insert_log_query);
        mysqli_stmt_bind_param($stmt_log, "is", $_SESSION['user_id'], $log_action);
        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);

        // Set a success alert for successful deletion
        $_SESSION['alert'] = 'deleted';
    } else {
        // Set an error alert if the deletion fails
        $_SESSION['alert'] = 'error';
    }
} else {
    // Redirect to the software list if no ID is provided
    $_SESSION['alert'] = 'error';
}

// Redirect to the software list page
header("Location: software.php");
exit();
?>
