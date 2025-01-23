<?php
include "header.php";
include "../user/connection.php";

$user_id = $_GET["user_id"]; // Get the user ID from the URL

// Fetch the user details from the database
$query = "SELECT * FROM user_registration WHERE user_id = $user_id";
$result = mysqli_query($link, $query);
$user = mysqli_fetch_array($result);

// Start a session to manage alert visibility
session_start();

if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]); // Clear the alert after the page is loaded
} else {
    $alert = null;
}

if (isset($_POST["submit1"])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $department = $_POST['department'];
    $role = $_POST['role'];

    $query = "UPDATE user_registration SET firstname='$firstname', lastname='$lastname', username='$username', password='$password', department='$department', role='$role' WHERE user_id=$user_id";
    if (mysqli_query($link, $query)) {
        $_SESSION["alert"] = "success";
        header("Location: edit_user.php?user_id=$user_id"); // Redirect to the same page to reload and show the changes
        exit(); // Ensure the script stops after the redirect
    } else {
        $_SESSION["alert"] = "error";
        header("Location: edit_user.php?user_id=$user_id"); // Redirect to show error
        exit();
    }
}
?>

<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i>
            Edit User</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit User</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">First Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="First name" name="firstname" value="<?php echo $user['firstname']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Last Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Last name" name="lastname" value="<?php echo $user['lastname']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Username :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="User name" name="username" value="<?php echo $user['username']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Password</label>
                                <div class="controls">
                                    <input type="password" class="span11" placeholder="Enter Password" name="password" value="<?php echo $user['password']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Department</label>
                                <div class="controls">
                                    <select name="department" class="span11">
                                        <option value="" disabled>Select Department</option>
                                        <option value="Accounting & Finance" <?php echo ($user['department'] == 'Accounting & Finance') ? 'selected' : ''; ?>>Accounting & Finance</option>
                                        <option value="Advertising" <?php echo ($user['department'] == 'Advertising') ? 'selected' : ''; ?>>Advertising</option>
                                        <option value="Circulation" <?php echo ($user['department'] == 'Circulation') ? 'selected' : ''; ?>>Circulation</option>
                                        <option value="Editorial-News" <?php echo ($user['department'] == 'Editorial-News') ? 'selected' : ''; ?>>Editorial-News</option>
                                        <option value="Editorial-Business" <?php echo ($user['department'] == 'Editorial-Business') ? 'selected' : ''; ?>>Editorial-Business</option>
                                        <option value="HRAD" <?php echo ($user['department'] == 'HRAD') ? 'selected' : ''; ?>>HRAD</option>
                                        <option value="Management Information System" <?php echo ($user['department'] == 'Management Information System') ? 'selected' : ''; ?>>Management Information System</option>
                                        <option value="Operations" <?php echo ($user['department'] == 'Operations') ? 'selected' : ''; ?>>Operations</option>
                                        <option value="Sales and Marketing" <?php echo ($user['department'] == 'Sales and Marketing') ? 'selected' : ''; ?>>Sales and Marketing</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Select Role</label>
                                <div class="controls">
                                    <select name="role" class="span11">
                                        <option value="" disabled>Select Role</option>
                                        <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                                        <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                </div>
                            </div>

                            <?php if ($alert == "error") { ?>
                                <div class="alert alert-danger">
                                    Username already exists!
                                </div>
                            <?php } elseif ($alert == "success") { ?>
                                <div class="alert alert-success">
                                    Record updated successfully!
                                </div>
                            <?php } ?>

                            <div class="form-actions">
                                <button type="submit" name="submit1" class="btn btn-success">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--end-main-container-part-->

<?php
include "footer.php";
?>
