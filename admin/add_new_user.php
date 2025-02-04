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
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> User List</a></div>
    </div>
    <!--End-breadcrumbs-->

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">

                <!-- Search bar and button integrated -->
 <!-- Search bar and button -->
<div style="margin-top: 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 11px;">
    <input type="text" id="searchInput" class="span5" placeholder="Search user...">
    <button class="btn btn-info" onclick="searchUsers()">Search</button>
</div>


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

                <!-- Alert section -->
                <?php if ($alert == "error") { ?>
                    <div class="alert alert-danger" style="margin-top: 20px;">
                        An error occurred while processing your request.
                    </div>
                <?php } elseif ($alert == "success") { ?>
                    <div class="alert alert-success" style="margin-top: 20px;">
                        User added successfully!
                    </div>
                <?php } elseif ($alert == "deleted") { ?>
                    <div class="alert" style="background-color: gray; color: white; margin-top: 20px;">
                        User deleted.
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
                        <tbody id="userTableBody">
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
                                    <td>
                                        <!-- Edit Button with Updated Styling -->
                                        <a href="edit_user.php?user_id=<?php echo $row["user_id"]; ?>" class="btn btn-warning" style="font-size: 14px; padding: 5px 10px;">
                                            <i class="icon-pencil"></i> Edit
                                        </a>
                                    </td>
                                    <td>
                                        <!-- Delete Button with Updated Styling -->
                                        <a href="delete_user.php?user_id=<?php echo $row["user_id"]; ?>" class="btn btn-danger" style="font-size: 14px; padding: 5px 10px;" onclick="return confirm('Are you sure you want to delete this user?');">
                                            <i class="icon-trash"></i> Delete
                                        </a>
                                    </td>
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

function searchUsers() {
    let searchQuery = document.getElementById("searchInput").value;
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "search_user.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById("userTableBody").innerHTML = xhr.responseText;
        }
    };

    xhr.send("query=" + searchQuery);
}
</script>

<?php
if (isset($_POST["submit1"])) {
    // Check if username already exists
    $res = mysqli_query($link, "SELECT * FROM user_registration WHERE username = '" . mysqli_real_escape_string($link, $_POST['username']) . "'");

    if (mysqli_num_rows($res) > 0) {
        $_SESSION["alert"] = "error";
    } else {
        // Insert new user into the database
        $firstname = mysqli_real_escape_string($link, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($link, $_POST['lastname']);
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $password = mysqli_real_escape_string($link, $_POST['password']);
        $department = mysqli_real_escape_string($link, $_POST['department']);
        $role = mysqli_real_escape_string($link, $_POST['role']);
        $status = mysqli_real_escape_string($link, $_POST['status']);

        // Insert the new user
        mysqli_query($link, "INSERT INTO user_registration (firstname, lastname, username, password, department, role, status) 
            VALUES ('$firstname', '$lastname', '$username', '$password', '$department', '$role', '$status')");

        // Log the action of adding a new user
        $log_action = "Added new user: $firstname $lastname, Username: $username, Department: $department, Role: $role, Status: $status";
        $log_query = "INSERT INTO logs (action) VALUES ('$log_action')";
        mysqli_query($link, $log_query);

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
