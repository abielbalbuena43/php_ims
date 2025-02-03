<?php
include "../user/connection.php";
$user_id = $_GET["user_id"];

// Fetch the user details before deletion for logging purposes
$query = "SELECT * FROM user_registration WHERE user_id = $user_id";
$result = mysqli_query($link, $query);
$user = mysqli_fetch_array($result);

if ($user) {
    // Log the deletion action
    $log_action = "Deleted user: {$user['firstname']} {$user['lastname']}, Username: {$user['username']}, Department: {$user['department']}, Role: {$user['role']}";
    $log_query = "INSERT INTO logs (action) VALUES ('$log_action')";
    mysqli_query($link, $log_query);

    // Proceed to delete the user
    mysqli_query($link, "DELETE FROM user_registration WHERE user_id = $user_id");
}
?>

<script type="text/javascript">
    window.location = "add_new_user.php";
</script>
