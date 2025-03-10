<?php
// Database connection details
$host = "localhost"; // Usually localhost
$username = "root";  // Your database username
$password = "";      // Your database password (leave blank if none)
$database = "php_ims"; // Your database name

// Create connection
$link = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
