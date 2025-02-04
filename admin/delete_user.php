<?php
// Start session
session_start();
include "session_verification.php";
include "../user/connection.php";

// Check if the user ID is provided
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Fetch the user details for logging before deletion
    $result = mysqli_query($link, "SELECT username, firstname, lastname FROM user_registration WHERE user_id = $user_id");
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];

    // Delete the user
    $query = "DELETE FROM user_registration WHERE user_id = $user_id";
    if (mysqli_query($link, $query)) {
        // Log the deletion action with relevant details
        $log_action = "Deleted user: $firstname $lastname ($username)";
        $log_query = "INSERT INTO logs (action) VALUES ('" . mysqli_real_escape_string($link, $log_action) . "')";
        mysqli_query($link, $log_query);

        // Set a gray alert for successful deletion
        $_SESSION['alert'] = 'deleted';
    } else {
        // Set an error alert if the deletion fails
        $_SESSION['alert'] = 'error';
    }
} else {
    // Redirect to the user list if no ID is provided
    $_SESSION['alert'] = 'error';
}

// Redirect to the user list page
header("Location: add_new_user.php");
exit();
?>
