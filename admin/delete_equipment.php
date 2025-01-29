<?php
// Start session
session_start();

// Include connection file
include "../user/connection.php";

// Check if the equipment ID is provided
if (isset($_GET['equipment_id'])) {
    $equipment_id = intval($_GET['equipment_id']);

    // Fetch the PC name for logging before deletion
    $result = mysqli_query($link, "SELECT pcname FROM new_equipment WHERE equipment_id = $equipment_id");
    $row = mysqli_fetch_assoc($result);
    $pcname = $row['pcname'];

    // Delete the equipment
    $query = "DELETE FROM new_equipment WHERE equipment_id = $equipment_id";
    if (mysqli_query($link, $query)) {
        // Log the deletion action
        $log_action = "Deleted equipment: $pcname";
        $log_query = "INSERT INTO logs (pcname, action) VALUES ('" . mysqli_real_escape_string($link, $pcname) . "', '$log_action')";
        mysqli_query($link, $log_query);

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
