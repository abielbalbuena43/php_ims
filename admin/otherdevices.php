<?php
session_start();
include "session_verification.php";
include "header.php";
include "../user/connection.php";

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $_SESSION["message"] = "You must be logged in to add a device.";
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Handling form submission
if (isset($_POST["submit1"])) {
    // Insert new device into the database
    $query = "INSERT INTO otherdevices (device_type, device_name, device_assettag, device_brand, device_modelnumber, device_deviceage, device_pcname, device_macaddress, device_remarks) 
              VALUES ('" . mysqli_real_escape_string($link, $_POST["device_type"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["device_name"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["device_assettag"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["device_brand"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["device_modelnumber"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["device_deviceage"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["device_pcname"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["device_macaddress"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["device_remarks"]) . "')";

    if (mysqli_query($link, $query)) {
        // Log the action after the successful insertion
        $log_action = "Added new Device: " . $_POST["device_type"] . " - " . $_POST["device_name"];

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

<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="otherdevices.php" class="tip-bottom"><i class="icon-home"></i> Other Devices</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">

                <!-- Search bar and button -->
                <div id="searchContainer">
                    <input type="text" id="searchInput" placeholder="Search user...">
                    <button class="btn-info" id="searchButton" onclick="searchDevices()">Search</button>
                </div>

                <!-- Button to toggle the form -->
                <button id="toggleFormButton" class="btn btn-primary" onclick="toggleForm()">Add New Device</button>

                <div id="addDeviceForm" style="display: none; margin-top: 20px;">
                    <div class="widget-box">
                        <div class="widget-title"> 
                            <span class="icon"><i class="icon-align-justify"></i></span>
                            <h5>Other Devices</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form name="form1" action="" method="post" class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label">Device Type :</label>
                                    <div class="controls">
                                        <select name="device_type" class="span11" required>
                                            <option value="">Select Device Type</option>
                                            <option value="NAS">NAS</option>
                                            <option value="External Storage & HDDs">External Storage & HDDs</option>
                                            <option value="Server">Server</option>
                                            <option value="Router">Router</option>
                                            <option value="Network Switches">Network Switches</option>
                                            <option value="Network Tester">Network Tester</option>
                                            <option value="Tone Tracer">Tone Tracer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Device Name :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Device Name" name="device_name" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Asset Tag :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Asset Tag" name="device_assettag" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Brand :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Brand" name="device_brand" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Model Number :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Model Number" name="device_modelnumber" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Device Age :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Device Age" name="device_deviceage" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">PC Name :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="PC Name" name="device_pcname" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">MAC Address :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="MAC Address" name="device_macaddress" />
                                    </div>
                                </div>
                                <div class="control-group">
                                     <label class="control-label">Remarks :</label>
                                        <div class="controls">
                                    <textarea class="span11" name="device_remarks"><?php echo isset($keyboard['device_remarks']) ? $keyboard['device_remarks'] : 'None'; ?></textarea>
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
                        Device added successfully!
                    </div>
                <?php } elseif ($alert == "deleted") { ?>
                    <div class="alert" style="background-color: gray; color: white; margin-top: 20px;">
                        Device deleted.
                    </div>
                <?php } ?>

                <div class="widget-content nopadding" style="margin-top: 20px;">
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Device Type</th>
                                    <th>Device Name</th>
                                    <th>Asset Tag</th>
                                    <th>Brand</th>
                                    <th>Model Number</th>
                                    <th>Device Age</th>
                                    <th>PC Name</th>
                                    <th>MAC Address</th>
                                    <th>Date Acquired</th>
                                    <th>Date Edited</th>
                                    <th>Remarks</th>
                                    <th>EDIT</th>
                                    <th>DELETE</th>
                                </tr>
                            </thead>
                            <tbody id="deviceTableBody">
                                <?php
                                $res = mysqli_query($link, "SELECT * FROM otherdevices");
                                while ($row = mysqli_fetch_array($res)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row["device_type"]; ?></td>
                                        <td><?php echo $row["device_name"]; ?></td>
                                        <td><?php echo $row["device_assettag"]; ?></td>
                                        <td><?php echo $row["device_brand"]; ?></td>
                                        <td><?php echo $row["device_modelnumber"]; ?></td>
                                        <td><?php echo $row["device_deviceage"]; ?></td>
                                        <td><?php echo $row["device_pcname"]; ?></td>
                                        <td><?php echo $row["device_macaddress"]; ?></td>
                                        <td><?php echo $row["device_dateacquired"]; ?></td>
                                        <td><?php echo $row["device_dateedited"] ? $row["device_dateedited"] : 'N/A'; ?></td>
                                        <td><?php echo $row["device_remarks"]; ?></td>
                                        <td><a href="edit_otherdevices.php?od_id=<?php echo $row['device_id']; ?>" class="btn btn-primary">Edit</a></td>
                                        <td>
                                            <a href="delete_otherdevices.php?od_id=<?php echo $row['device_id']; ?>" class="btn btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this device?');">
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
        let form = document.getElementById('addDeviceForm');
        let button = document.getElementById('toggleFormButton');

        // Toggle form visibility
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            button.innerText = 'Hide Form'; // Change button text
        } else {
            form.style.display = 'none';
            button.innerText = 'Add New Device'; // Reset button text
        }
    }

    function searchDevices() {
        let searchQuery = document.getElementById("searchInput").value;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "search_otherdevices.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("deviceTableBody").innerHTML = xhr.responseText;
            }
        };

        xhr.send("query=" + searchQuery);
    }
</script>

<?php
include "footer.php";
?>
