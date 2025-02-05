<?php
session_start();
include "../user/connection.php"; // Include your DB connection

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = mysqli_real_escape_string($link, $_POST['password']);

    // Query to get the user
    $query = "SELECT * FROM user_registration WHERE username = '$username' AND password = '$password' AND role = 'admin'";
    $result = mysqli_query($link, $query);

    // Check if user is found and if they are admin
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect to the admin dashboard or equipment page
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid login credentials or you're not an admin.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form method="post" action="login.php">
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="login">Login</button>
    </form>
    <?php
    if (isset($error)) {
        echo "<p>$error</p>";
    }
    ?>
</body>
</html>
