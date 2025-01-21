<?php
include "connection.php";
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
</head>

<body>
<div id="loginbox">
    <form name="form1" class="form-vertical" action="" method="POST">
        <div class="control-group normal_text"><h3>Login Page</h3></div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lg"><i class="icon-user"> </i></span>
                    <input type="text" placeholder="Username" name="username" required />
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span>
                    <input type="password" placeholder="Password" name="password" required />
                </div>
            </div>
        </div>
        <div class="form-actions">
            <center>
                <input type="submit" name="submit1" value="Login" class="btn btn-success" />
            </center>
        </div>
    </form>

    <?php
    // Check if the form is submitted
    if (isset($_POST["submit1"])) {
        // Sanitize user input to prevent SQL injection
        $username = mysqli_real_escape_string($link, $_POST["username"]);
        $password = mysqli_real_escape_string($link, $_POST["password"]);

        // Query the database
        $query = "SELECT * FROM user_registration WHERE username = '$username' AND password = '$password' AND role = 'user' AND status = 'active'";
        $res = mysqli_query($link, $query);

        if (!$res) {
            echo '<div class="alert alert-danger">Database query failed: ' . mysqli_error($link) . '</div>';
        } else {
            // Count the number of matching rows
            $count = mysqli_num_rows($res);

            if ($count > 0) {
                // Redirect to demo.php on successful login
                echo '<script type="text/javascript">window.location="demo.php";</script>';
            } else {
                // Display error message on failed login
                echo '<div class="alert alert-danger">Invalid credentials</div>';
            }
        }
    }
    ?>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/matrix.login.js"></script>
</body>

</html>
