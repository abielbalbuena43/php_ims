<?php
// Include required files and start session
include "header.php";
include "../user/connection.php";
session_start();

// Handle alert management
if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]);
} else {
    $alert = null;
}
?>

<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> Add New User</a></div>
    </div>
    <!--End-breadcrumbs-->

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <!-- Button to toggle the form -->
                <button id="toggleFormButton" class="btn btn-primary" onclick="toggleForm()">Add New User</button>

                <!-- Form to add a new user -->
                <div id="addUserForm" style="display: none; margin-top: 20px;">
                    <div class="widget-box">
                        <div class="widget-title"> 
                            <span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>Add New User</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form name="form1" action="" method="post" class="form-horizontal">
                                <!-- First Name -->
                                <div class="control-group">
                                    <label class="control-label">First Name :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="First Name" name="firstname" required />
                                    </div>
                                </div>
                                <!-- Last Name -->
                                <div class="control-group">
                                    <label class="control-label">Last Name :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Last Name" name="lastname" required />
                                    </div>
                                </div>
                                <!-- Username -->
                                <div class="control-group">
                                    <label class="control-label">Username :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Username" name="username" required />
                                    </div>
                                </div>
                                <!-- Password -->
                                <div class="control-group">
                                    <label class="control-label">Password :</label>
                                    <div class="controls">
                                        <input type="password" class="span11" placeholder="Enter Password" name="password" required />
                                    </div>
                                </div>
                                <!-- Department -->
                                <div class="control-group">
                                    <label class="control-label">Department :</label>
                                    <div class="controls">
                                        <select name="department" class="span11" required>
                                            <option value="" disabled selected>Select Department</option>
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
                                <!-- Role -->
                                <div class="control-group">
                                    <label class="control-label">Select Role :</label>
                                    <div class="controls">
                                        <select name="role" class="span11" required>
                                            <option value="" disabled selected>Select Role</option>
                                            <option>user</option>
                                            <option>admin</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Status -->
                                <div class="control-group">
                                    <label class="control-label">Status :</label>
                                    <div class="controls">
                                        <select name="status" class="span11" required>
                                            <option value="" disabled selected>Select Status</option>
                                            <option>active</option>
                                            <option>inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-actions">
                                    <button type="submit" name="submit1" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Alert Section -->
                <?php if ($alert == "error") { ?>
                    <div class="alert alert-danger" style="margin-top: 20px;">
                        Username already exists!
                    </div>
                <?php } elseif ($alert == "success") { ?>
                    <div class="alert alert-success" style="margin-top: 20px;">
                        User added successfully!
                    </div>
                <?php } ?>

                <!-- Table displaying users -->
                <div class="widget-content nopadding" style="margin-top: 20px;">
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

<script>
function toggleForm() {
    const form = document.getElementById("addUserForm");
    const button = document.getElementById("toggleFormButton");
    if (form.style.display === "none") {
        form.style.display = "block";
        button.textContent = "Hide Form";
    } else {
        form.style.display = "none";
        button.textContent = "Add New User";
    }
}
</script>

<?php
if (isset($_POST["submit1"])) {
    $res = mysqli_query($link, "SELECT * FROM user_registration WHERE username = '" . mysqli_real_escape_string($link, $_POST['username']) . "'");

    if (mysqli_num_rows($res) > 0) {
        $_SESSION["alert"] = "error";
    } else {
        mysqli_query($link, "INSERT INTO user_registration (firstname, lastname, username, password, department, role, status) VALUES ('" . mysqli_real_escape_string($link, $_POST['firstname']) . "', '" . mysqli_real_escape_string($link, $_POST['lastname']) . "', '" . mysqli_real_escape_string($link, $_POST['username']) . "', '" . mysqli_real_escape_string($link, $_POST['password']) . "', '" . mysqli_real_escape_string($link, $_POST['department']) . "', '" . mysqli_real_escape_string($link, $_POST['role']) . "', '" . mysqli_real_escape_string($link, $_POST['status']) . "')");

        $_SESSION["alert"] = "success";
    }

    echo "<script>window.location.href = window.location.href;</script>";
    exit();
}
?>

<!--end-main-container-part-->

<?php
include "footer.php";
?>
