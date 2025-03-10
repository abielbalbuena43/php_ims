<?php
// Start session
session_start();
include "session_verification.php";
include "../admin/connection.php";

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $_SESSION["message"] = "You must be logged in to delete a user.";
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Check if the user ID is provided
if (isset($_GET['user_id'])) {
    $user_id_to_delete = intval($_GET['user_id']);

    // Fetch the user details for logging before deletion
    $result = mysqli_query($link, "SELECT username, firstname, lastname FROM user_registration WHERE user_id = $user_id_to_delete");
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];

    // Delete the user
    $query = "DELETE FROM user_registration WHERE user_id = $user_id_to_delete";
    if (mysqli_query($link, $query)) {
        // Log the deletion action with relevant details
        $log_action = "Deleted User: $firstname $lastname ($username)";

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
    // Redirect to the user list if no ID is provided
    $_SESSION['alert'] = 'error';
}

// Redirect to the user list page
header("Location: add_new_user.php");
exit();
?>
