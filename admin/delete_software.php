<?php
// Start session
session_start();

// Include connection file
include "../user/connection.php";

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
        // Log the deletion action
        $log_action = "Deleted software for equipment: $pcname";
        $log_query = "INSERT INTO logs (pcname, action) VALUES ('" . mysqli_real_escape_string($link, $pcname) . "', '$log_action')";
        mysqli_query($link, $log_query);

        // Set a gray alert for successful deletion
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
