<?php
// Start session
session_start();
include "session_verification.php";
include "../user/connection.php";

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
        $log_action = "Deleted device: $device_name (Type: $device_type, Asset Tag: $device_assettag) for PC: $device_pcname";
        $log_query = "INSERT INTO logs (action) VALUES ('" . mysqli_real_escape_string($link, $log_action) . "')";
        mysqli_query($link, $log_query);

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
