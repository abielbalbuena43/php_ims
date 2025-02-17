<?php
// Start session
session_start();
include "session_verification.php";
include "../user/connection.php";

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $_SESSION["message"] = "You must be logged in to delete a peripheral.";
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Check if the peripheral ID is provided
if (isset($_GET['peripheral_id'])) {
    $peripheral_id = intval($_GET['peripheral_id']);

    // Fetch the related equipment's PC name and other info for logging before deletion
    $result = mysqli_query($link, "SELECT e.pcname FROM peripherals p 
                                   JOIN equipment e ON p.equipment_id = e.equipment_id
                                   WHERE p.peripheral_id = $peripheral_id");
    $row = mysqli_fetch_assoc($result);
    $pcname = $row['pcname'];

    // Delete the peripheral
    $query = "DELETE FROM peripherals WHERE peripheral_id = $peripheral_id";
    if (mysqli_query($link, $query)) {
        // Log the deletion action with user info
        $log_action = "Deleted Peripheral for: $pcname";

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
    // Redirect to the peripherals list if no ID is provided
    $_SESSION['alert'] = 'error';
}

// Redirect to the peripherals list page
header("Location: peripherals.php");
exit();
?>
