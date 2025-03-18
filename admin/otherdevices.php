<?php
ob_start(); // Prevent "headers already sent" errors
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION["message"] = "You must be logged in to add a device.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit1"])) {
    // Validate required fields
    $required_fields = ["device_department", "device_type", "device_name"];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION["alert"] = "error";
            $_SESSION["message"] = "Error: $field is required.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }

    // Assign values, ensuring they are not NULL
    $device_type = $_POST["device_type"];
    $device_department = $_POST["device_department"];
    $device_name = $_POST["device_name"];
    $device_assettag = !empty($_POST["device_assettag"]) ? $_POST["device_assettag"] : NULL;
    $device_brand = !empty($_POST["device_brand"]) ? $_POST["device_brand"] : NULL;
    $device_modelnumber = !empty($_POST["device_modelnumber"]) ? $_POST["device_modelnumber"] : NULL;
    $device_deviceage = !empty($_POST["device_deviceage"]) ? $_POST["device_deviceage"] : NULL;
    $device_pcname = !empty($_POST["device_pcname"]) ? $_POST["device_pcname"] : NULL;
    $device_macaddress = !empty($_POST["device_macaddress"]) ? $_POST["device_macaddress"] : NULL;
    $device_remarks = !empty($_POST["device_remarks"]) ? $_POST["device_remarks"] : NULL;

    // Use a prepared statement to insert the data
    $query = "INSERT INTO otherdevices 
        (device_type, device_department, device_name, device_assettag, device_brand, 
        device_modelnumber, device_deviceage, device_pcname, device_macaddress, device_remarks) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $query)) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssss",
            $device_type,
            $device_department,
            $device_name,
            $device_assettag,
            $device_brand,
            $device_modelnumber,
            $device_deviceage,
            $device_pcname,
            $device_macaddress,
            $device_remarks
        );

        if (mysqli_stmt_execute($stmt)) {
            // Log action
            $log_action = "Added new Device: " . $device_type . " - " . $device_name;
            $insert_log_query = "INSERT INTO logs (user_id, action, date_edited) VALUES (?, ?, NOW())";

            if ($log_stmt = mysqli_prepare($link, $insert_log_query)) {
                mysqli_stmt_bind_param($log_stmt, "is", $user_id, $log_action);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);
            }

            $_SESSION["alert"] = "success";
            $_SESSION["message"] = "Device added successfully!";
        } else {
            $_SESSION["alert"] = "error";
            $_SESSION["message"] = "Database Error: " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION["alert"] = "error";
        $_SESSION["message"] = "Database Error: " . mysqli_error($link);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$alert = $_SESSION["alert"] ?? null;
$message = $_SESSION["message"] ?? null;
unset($_SESSION["alert"], $_SESSION["message"]);
?>

<!-- Alert section -->
<?php if ($alert == "error") { ?>
    <div class="alert alert-danger" style="margin-top: 20px;">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php } elseif ($alert == "success") { ?>
    <div class="alert alert-success" style="margin-top: 20px;">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php } ?>


<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="otherdevices.php" class="tip-bottom"><i class="icon-home"></i> Other Devices</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">

                <!-- Search bar and live results -->
                <div id="searchContainer">
                    <input type="text" id="searchInput" placeholder="Search other devices..." onkeyup="searchDevices()">
                </div>
                <div id="searchResults"></div>

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
                                <!-- Asset Tag -->
                            <div class="control-group">
                                    <label class="control-label">Asset Tag:</label>
                                    <div class="controls">
                                        <input type="text" class="span11" name="assettag" placeholder="None" 
                                            value="<?php
                                                echo isset($device['device_id'], $device['device_department'], $device['device_type']) 
                                                    ? strtoupper($device['device_department']) . '-' . $device['device_type'] . '-' . $device['device_id'] 
                                                    : 'NOT YET SET';
                                            ?>" readonly />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Department :</label>
                                    <div class="controls">
                                        <select name="device_department" class="span11" required>
                                            <option value="" disabled selected>Select Department</option>
                                            <option value="ACFN">Accounting & Finance (ACFN)</option>
                                            <option value="ADVT">Advertising (ADVT)</option>
                                            <option value="CIRC">Circulation (CIRC)</option>
                                            <option value="EDTN">Editorial-News (EDTN)</option>
                                            <option value="EDTB">Editorial-Business (EDTB)</option>
                                            <option value="HRAD">HRAD (HRAD)</option>
                                            <option value="MIS">Management Information System (MIS)</option>
                                            <option value="OPER">Operations (OPER)</option>
                                            <option value="SLSM">Sales and Marketing (SLSM)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Device Type :</label>
                                    <div class="controls">
                                    <select name="device_type" class="span11" required>
                                        <option value="">Select Device Type</option>
                                        <option value="NAS">Network Attached Storage (NAS)</option>
                                        <option value="EXTHDD">External Storage & HDDs (EXTHDD)</option>
                                        <option value="SRV">Server (SRV)</option>
                                        <option value="RTR">Router (RTR)</option>
                                        <option value="NWSW">Network Switches (NWSW)</option>
                                        <option value="NWTST">Network Tester (NWTST)</option>
                                        <option value="TT">Tone Tracer (TT)</option>
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
                                    <textarea class="span11" name="device_remarks" placeholder="Remarks"><?php echo isset($keyboard['device_remarks']) ? htmlspecialchars($keyboard['device_remarks']) : ''; ?></textarea>
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

                <div style="overflow-x: auto; max-height: 550px; margin-top: 20px;">
                    <table class="table table-bordered table-striped">
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
                                        <td>
                                            <?php 
                                                if (isset($row['device_id']) && isset($row['device_department']) && isset($row['device_type'])) {
                                                    echo strtoupper($row['device_department']) . '-' . $row['device_type'] . '-' . $row['device_id'];
                                                } else {
                                                    echo 'NOT YET SET';
                                                }
                                            ?>
                                        </td>
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