<?php
// Start session
session_start();
include "session_verification.php";
include "../user/connection.php";

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
        // Log the deletion action
        $log_action = "Deleted peripheral for equipment: $pcname";
        $log_query = "INSERT INTO logs (pcname, action) VALUES ('" . mysqli_real_escape_string($link, $pcname) . "', '$log_action')";
        mysqli_query($link, $log_query);

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
