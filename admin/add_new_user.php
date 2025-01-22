<?php
include "header.php";
include "../user/connection.php";

// Start a session to manage alert visibility
session_start();

if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]); // Clear the alert after the page is loaded
} else {
    $alert = null;
}
?>
<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i>
            Add New User</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Add New User</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">First Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="First name" name="firstname" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Last Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Last name" name="lastname"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Username :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="User name" name="username"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Password</label>
                                <div class="controls">
                                    <input type="password" class="span11" placeholder="Enter Password" name="password" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Department</label>
                                <div class="controls">
                                    <select name="department" class="span11">
                                        <option value="" disabled selected>Select Department</option> <!-- Placeholder -->
                                        <option>Accounting & Finance</option>
                                        <option>Advertising</option>
                                        <option>Circulation</option>
                                        <option>Editorial-News</option>
                                        <option>Editorial-Business</option>
                                        <option>HRAD</option>
                                        <option>Management Information System</option>
                                        <option>Operations</option>
                                        <option>Sales and Marketing</option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Select Role</label>
                                <div class="controls">
                                    <select name="role" class="span11">
                                        <option value="" disabled selected>Select Role</option> <!-- Placeholder -->
                                        <option>user</option>
                                        <option>admin</option>
                                    </select>
                                </div>
                            </div>

                            <?php if ($alert == "error") { ?>
                                <div class="alert alert-danger">
                                    Username already exists!
                                </div>
                            <?php } elseif ($alert == "success") { ?>
                                <div class="alert alert-success">
                                    Record inserted successfully!
                                </div>
                            <?php } ?>

                            <div class="form-actions">
                                <button type="submit" name="submit1" class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="widget-content nopadding">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Department</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>EDIT</th>
                                <th>DELETE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = mysqli_query($link, "SELECT * FROM user_registration");
                            while ($row = mysqli_fetch_array($res)) {
                            ?>
                                <tr>
                                    <td><?php echo $row["firstname"]; ?></td>
                                    <td><?php echo $row["lastname"]; ?></td>
                                    <td><?php echo $row["username"]; ?></td>
                                    <td><?php echo $row["department"]; ?></td>
                                    <td><?php echo $row["role"]; ?></td>
                                    <td><?php echo $row["status"]; ?></td>
                                    <td><a href="edit_user.php?id=<?php echo $row["id"]; ?>">Edit</a></td>
                                    <td><a href="delete_user.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_POST["submit1"])) {
    $res = mysqli_query($link, "SELECT * FROM user_registration WHERE username='$_POST[username]'");
    if (mysqli_num_rows($res) > 0) {
        $_SESSION["alert"] = "error";
    } else {
        mysqli_query($link, "INSERT INTO user_registration VALUES(NULL, '$_POST[firstname]', '$_POST[lastname]', '$_POST[username]', '$_POST[password]', '$_POST[department]', '$_POST[role]', 'active')");
        $_SESSION["alert"] = "success";
    }
    header("Location: " . $_SERVER['PHP_SELF']); // Redirect to the same page to refresh
    exit();
}
?>

<!--end-main-container-part-->

<?php
include "footer.php";
?>
