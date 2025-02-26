<?php
session_start();
include "../user/connection.php"; // Include your DB connection

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($link, $_POST['username']);
    $password = mysqli_real_escape_string($link, $_POST['password']);

    // Check if the username exists
    $query = "SELECT * FROM user_registration WHERE username = '$username'";
    $result = mysqli_query($link, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Check if the password is correct and the role is admin
        if ($user['password'] === $password && $user['role'] === 'admin') {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect to the admin dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Incorrect username.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login - PHP Inventory Management System</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css"/>
    <link rel="stylesheet" href="css/matrix-login.css"/>
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <style>
        body {
            background: url('Malaya.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
        }
        #loginbox {
            padding: 40px;
            margin-left: 150px;
            border-radius: 10px;
            width: 400px;
            background: rgba(30, 30, 30, 0.8);
        }
        .form-actions {
            text-align: center;
        }
        .btn {
            margin: 0 auto;
            font-size: 18px;
            padding: 10px 20px;
        }
        .control-group.normal_text h3, .control-group.normal_text h4 {
            color: white;
            text-align: center;
            font-size: 24px;
        }
        .main_input_box input {
            background: rgba(255, 255, 255, 0.7);
            border: none;
            padding: 15px;
            border-radius: 5px;
            width: calc(100% - 40px);
            font-size: 16px;
        }
        .add-on {
            color: black;
            font-size: 16px;
        }
    </style>
</head>

<body>
<div id="loginbox">
    <form id="loginform" class="form-vertical" method="post" action="login.php">
        <div class="control-group normal_text"><h3>Login Page</h3></div>
        <div class="control-group normal_text"><h4>Inventory System</h4></div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lg"><i class="icon-user"> </i></span>
                    <input type="text" placeholder="Username" name="username" required/>
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span>
                    <input type="password" placeholder="Password" name="password" required/>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" name="login" class="btn btn-success">Login</button>
        </div>
        <?php
        if (isset($error)) {
            echo "<p style='color: red; text-align: center;'>$error</p>";
        }
        ?>
    </form>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/matrix.login.js"></script>
</body>
</html>
