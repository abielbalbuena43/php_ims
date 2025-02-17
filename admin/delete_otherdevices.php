<?php
// Start session
session_start();
include "session_verification.php";
include "../user/connection.php";

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $_SESSION["message"] = "You must be logged in to delete a device.";
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Check if the device ID is provided
if (isset($_GET['od_id'])) {
    $od_id = intval($_GET['od_id']);

    // Fetch the device details for logging before deletion
    $result = mysqli_query($link, "SELECT device_name, device_pcname, device_type, device_assettag FROM otherdevices WHERE device_id = $od_id");
    $row = mysqli_fetch_assoc($result);
    $device_name = $row['device_name'];
    $device_pcname = $row['device_pcname'];
    $device_type = $row['device_type'];
    $device_assettag = $row['device_assettag'];

    // Delete the device
    $query = "DELETE FROM otherdevices WHERE device_id = $od_id";
    if (mysqli_query($link, $query)) {
        // Log the deletion action with relevant details
        $log_action = "Deleted Device: $device_name (Type: $device_type, Asset Tag: $device_assettag) for PC: $device_pcname";

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
    // Redirect to the other devices list if no ID is provided
    $_SESSION['alert'] = 'error';
}

// Redirect to the otherdevices.php page
header("Location: otherdevices.php");
exit();
?>
