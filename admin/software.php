<?php
session_start();
include "session_verification.php";
include "header.php";
include "../user/connection.php";

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $_SESSION["message"] = "You must be logged in to add software.";
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Fetch existing equipment for dropdown
$equipment_result = mysqli_query($link, "SELECT equipment_id, pcname FROM equipment");
$equipment_options = "";
while ($row = mysqli_fetch_assoc($equipment_result)) {
    $equipment_options .= "<option value='" . $row['equipment_id'] . "'>" . $row['pcname'] . "</option>";
}

// Handling form submission
if (isset($_POST["submit1"])) {
    // Insert a new software record
    $query = "INSERT INTO software (equipment_id, software_msos, software_msoffice, software_adobe, software_dateadded, software_dateedited, software_remarks) 
              VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?)";
    
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "issss", $_POST["equipment_id"], $_POST["software_msos"], $_POST["software_msoffice"], $_POST["software_adobe"], $_POST["software_remarks"]);

    if (mysqli_stmt_execute($stmt)) {
        // Fetch the pcname associated with the selected equipment_id
        $equipment_id = $_POST["equipment_id"];
        $pcname_query = "SELECT pcname FROM equipment WHERE equipment_id = $equipment_id";
        $pcname_result = mysqli_query($link, $pcname_query);
        $pcname_data = mysqli_fetch_assoc($pcname_result);
        $pcname = $pcname_data['pcname'];

        // Log the action after the successful insertion
        $log_action = "Added new Software for: " . $pcname;

        // Insert the log entry with user_id
        $insert_log_query = "INSERT INTO logs (user_id, action, date_edited) 
                             VALUES (?, ?, NOW())";
        $stmt_log = mysqli_prepare($link, $insert_log_query);
        mysqli_stmt_bind_param($stmt_log, "is", $_SESSION['user_id'], $log_action);
        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);

        $_SESSION["alert"] = "success";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION["alert"] = "error";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$alert = $_SESSION["alert"] ?? null;
unset($_SESSION["alert"]);

?>

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="software.php" class="tip-bottom"><i class="icon-home"></i> Software</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <!-- Search bar and button -->
                <div id="searchContainer">
                    <input type="text" id="searchInput" placeholder="Search user...">
                    <button class="btn-info" id="searchButton" onclick="searchSoftware()">Search</button>
                </div>

                <!-- Button to toggle the form -->
                <button id="toggleFormButton" class="btn btn-primary" onclick="toggleForm()">Add New Software</button>

                <div id="addSoftwareForm" style="display: none; margin-top: 20px;">
                    <div class="widget-box">
                        <div class="widget-title"> 
                            <span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>Software</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form name="form1" action="" method="post" class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label">Equipment (PC Name) :</label>
                                    <div class="controls">
                                        <select name="equipment_id" class="span11" required>
                                            <option value="">Select Equipment</option>
                                            <?php echo $equipment_options; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">MS OS :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="MS OS" name="software_msos" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">MS Office :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="MS Office" name="software_msoffice" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Adobe :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Adobe" name="software_adobe" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Remarks :</label>
                                    <div class="controls">
                                        <textarea class="span11" name="software_remarks"></textarea>
                                    </div>
                                </div>
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
                        Software added successfully!
                    </div>
                <?php } elseif ($alert == "deleted") { ?>
                    <div class="alert" style="background-color: gray; color: white; margin-top: 20px;">
                        Software deleted.
                    </div>
                <?php } ?>

                <div class="widget-content nopadding" style="margin-top: 20px;">
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>PC Name</th>
                                    <th>MS OS</th>
                                    <th>MS Office</th>
                                    <th>Adobe</th>
                                    <th>Date Added</th>
                                    <th>Date Edited</th>
                                    <th>Remarks</th>
                                    <th>EDIT</th>
                                    <th>DELETE</th>
                                </tr>
                            </thead>
                            <tbody id="softwareTableBody">
                                <?php
                                // Fetch software with PC name by joining software and equipment tables
                                $res = mysqli_query($link, "SELECT s.*, e.pcname FROM software s 
                                                            JOIN equipment e ON s.equipment_id = e.equipment_id");
                                while ($row = mysqli_fetch_array($res)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row["pcname"]; ?></td>
                                        <td><a href="msos.php?equipment_id=<?php echo $row['equipment_id']; ?>"><?php echo $row["software_msos"]; ?></a></td>
                                        <td><a href="msoffice.php?equipment_id=<?php echo $row['equipment_id']; ?>"><?php echo $row["software_msoffice"]; ?></a></td>
                                        <td><a href="adobe.php?equipment_id=<?php echo $row['equipment_id']; ?>"><?php echo $row["software_adobe"]; ?></a></td>
                                        <td><?php echo $row["software_dateadded"]; ?></td>
                                        <td><?php echo $row["software_dateedited"]; ?></td>
                                        <td><?php echo $row["software_remarks"]; ?></td>
                                        <td><a href="edit_software.php?equipment_id=<?php echo $row['equipment_id']; ?>" class="btn btn-primary">Edit</a></td>
                                        <td>
                                            <a href="delete_software.php?software_id=<?php echo $row['software_id']; ?>" 
                                            class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this software?');">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleForm() {
        let form = document.getElementById('addSoftwareForm');
        form.style.display = (form.style.display === 'none') ? 'block' : 'none';
    }

    function searchSoftware() {
        let searchQuery = document.getElementById("searchInput").value;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "search_software.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("softwareTableBody").innerHTML = xhr.responseText;
            }
        };

        xhr.send("query=" + searchQuery);
    }
</script>

<?php
include "footer.php";
?> 
