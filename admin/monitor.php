<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing monitor details from the monitor table
$query = "SELECT * FROM monitor WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$monitor = mysqli_fetch_array($result);

// Fetch equipment details from the equipment table
$equipmentQuery = "SELECT * FROM equipment WHERE equipment_id = $equipment_id";
$equipmentResult = mysqli_query($link, $equipmentQuery);
$equipment = mysqli_fetch_array($equipmentResult);

// Handling alert messages for success or error
if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]);
} else {
    $alert = null;
}

// Handle delete request
if (isset($_POST["delete_monitor"])) {
    $monitor_id = $_POST["monitor_id"];

    $deleteQuery = "DELETE FROM monitor WHERE monitor_id = $monitor_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION["alert"] = "deleted";
        header("Location: monitor.php?equipment_id=$equipment_id"); // Redirect after deletion
        exit();
    } else {
        $_SESSION["alert"] = "delete_error";
        header("Location: monitor.php?equipment_id=$equipment_id");
        exit();
    }
}

// Handle form submission to update monitor details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $serialnumber = mysqli_real_escape_string($link, $_POST["serialnumber"]);
    $size = mysqli_real_escape_string($link, $_POST["size"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if monitor exists for this equipment
    if ($monitor) {
        // Update monitor details in the database
        $updateQuery = "UPDATE monitor SET 
                        monitor_assettag = '$assettag', 
                        monitor_brand = '$brand', 
                        monitor_modelnumber = '$modelnumber', 
                        monitor_serialnumber = '$serialnumber',
                        monitor_size = '$size', 
                        monitor_dateacquired = '$dateacquired', 
                        monitor_deviceage = '$deviceage', 
                        monitor_assigneduser = '$assigneduser', 
                        monitor_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: monitor.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: monitor.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the monitor table
        $insertQuery = "INSERT INTO monitor (equipment_id, monitor_assettag, monitor_brand, monitor_modelnumber, monitor_serialnumber, monitor_size, monitor_dateacquired, monitor_deviceage, monitor_assigneduser, monitor_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$serialnumber', '$size', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: monitor.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: monitor.php?equipment_id=$equipment_id");
            exit();
        }
    }
}
?>
<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="equipment.php" class="tip-bottom">
                <i class="icon-home"></i> Edit Monitor Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Monitor Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None"
                                        value="<?php
                                            if (isset($monitor['monitor_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-MNTR-' . $monitor['monitor_id'];
                                            } else {
                                                echo 'NOT YET SET';
                                            }
                                        ?>" readonly />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="brand"
                                        placeholder="None"
                                        value="<?php echo isset($monitor['monitor_brand']) ? $monitor['monitor_brand'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber"
                                        placeholder="None"
                                        value="<?php echo isset($monitor['monitor_modelnumber']) ? $monitor['monitor_modelnumber'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Serial Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="serialnumber"
                                        placeholder="None"
                                        value="<?php echo isset($monitor['monitor_serialnumber']) ? $monitor['monitor_serialnumber'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Monitor Size :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="size"
                                        placeholder="None"
                                        value="<?php echo isset($monitor['monitor_size']) ? $monitor['monitor_size'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Date Acquired :</label>
                                <div class="controls">
                                    <input type="date" class="span11" name="dateacquired"
                                        value="<?php echo isset($monitor['monitor_dateacquired']) ? $monitor['monitor_dateacquired'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="deviceage"
                                        placeholder="None"
                                        value="<?php echo isset($monitor['monitor_deviceage']) ? $monitor['monitor_deviceage'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser"
                                        placeholder="None"
                                        value="<?php echo isset($monitor['monitor_assigneduser']) ? $monitor['monitor_assigneduser'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($monitor['monitor_remarks']) ? $monitor['monitor_remarks'] : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo ($alert == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php
                                        if ($alert == "success") {
                                            echo "Monitor details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update Monitor details.";
                                        } elseif ($alert == "deleted") {
                                            echo "Monitor deleted!";
                                        }
                                    ?>
                                </div>
                            <?php } ?>

                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-success">Save Changes</button>
                                <a href="equipment.php" class="btn">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table to display monitor details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title">
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Monitor Details</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <div style="overflow-x: auto;">
                            <table class="table table-bordered table-striped" style="min-width: 1200px;">
                                <thead>
                                    <tr>
                                        <th>PC Name</th>
                                        <th>Asset Tag</th>
                                        <th>Brand</th>
                                        <th>Model Number</th>
                                        <th>Serial Number</th>
                                        <th>Monitor Size</th>
                                        <th>Date Acquired</th>
                                        <th>Device Age</th>
                                        <th>Assigned User</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                        <td>
                                            <?php
                                                if (isset($monitor['monitor_id']) && isset($equipment['department'])) {
                                                    echo strtoupper($equipment['department']) . '-MNTR-' . $monitor['monitor_id'];
                                                } else {
                                                    echo 'NOT YET SET';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo !empty($monitor['monitor_brand']) ? htmlspecialchars($monitor['monitor_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($monitor['monitor_modelnumber']) ? htmlspecialchars($monitor['monitor_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($monitor['monitor_serialnumber']) ? htmlspecialchars($monitor['monitor_serialnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($monitor['monitor_size']) ? htmlspecialchars($monitor['monitor_size']) : 'None'; ?></td>
                                        <td><?php echo !empty($monitor['monitor_dateacquired']) ? htmlspecialchars($monitor['monitor_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($monitor['monitor_deviceage']) ? htmlspecialchars($monitor['monitor_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($monitor['monitor_assigneduser']) ? htmlspecialchars($monitor['monitor_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($monitor['monitor_remarks']) ? htmlspecialchars($monitor['monitor_remarks']) : 'None'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Delete Button -->
                <?php if ($monitor): ?>
                    <form method="POST" style="display:inline; margin-top: 10px;"
                          onsubmit="return confirm('Are you sure you want to delete this monitor?');">
                        <input type="hidden" name="monitor_id" value="<?php echo $monitor['monitor_id']; ?>">
                        <button type="submit" name="delete_monitor" class="btn btn-danger">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!--end-main-container-part-->
<?php
include "footer.php";
?>
