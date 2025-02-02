<?php
// Start session
session_start();

// Include connection file
include "../user/connection.php";

// Check if the device ID is provided
if (isset($_GET['od_id'])) {
    $od_id = intval($_GET['od_id']);

    // Fetch the device name for logging before deletion
    $result = mysqli_query($link, "SELECT od_name FROM otherdevices WHERE od_id = $od_id");
    $row = mysqli_fetch_assoc($result);
    $od_name = $row['od_name'];

    // Delete the device
    $query = "DELETE FROM otherdevices WHERE od_id = $od_id";
    if (mysqli_query($link, $query)) {
        // Log the deletion action
        $log_action = "Deleted device: $od_name";
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

