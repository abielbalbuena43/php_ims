<?php
session_start();
session_unset(); // Clear the session variables
session_destroy(); // Destroy the session
header("Location: login.php"); // Redirect to login page after logout
exit();
?>
