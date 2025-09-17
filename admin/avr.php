<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing avr details from the avr table
$query = "SELECT * FROM avr WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$avr = mysqli_fetch_array($result);

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
if (isset($_POST["delete_avr"])) {
    $avr_id = $_POST["avr_id"];

    $deleteQuery = "DELETE FROM avr WHERE avr_id = $avr_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION["alert"] = "deleted";
        header("Location: avr.php?equipment_id=$equipment_id"); // Redirect after deletion
        exit();
    } else {
        $_SESSION["alert"] = "delete_error";
        header("Location: avr.php?equipment_id=$equipment_id");
        exit();
    }
}

// Handle form submission to update avr details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $serialnumber = mysqli_real_escape_string($link, $_POST["serialnumber"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if avr exists for this equipment
    if ($avr) {
        // Update avr details in the database
        $updateQuery = "UPDATE avr SET 
                        avr_assettag = '$assettag', 
                        avr_brand = '$brand', 
                        avr_modelnumber = '$modelnumber',
                        avr_serialnumber = '$serialnumber', 
                        avr_dateacquired = '$dateacquired', 
                        avr_deviceage = '$deviceage', 
                        avr_assigneduser = '$assigneduser', 
                        avr_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: avr.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: avr.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the avr table
        $insertQuery = "INSERT INTO avr (equipment_id, avr_assettag, avr_brand, avr_modelnumber, avr_serialnumber, avr_dateacquired, avr_deviceage, avr_assigneduser, avr_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$serialnumber', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: avr.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: avr.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit AVR Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit AVR Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Form to edit avr details -->
                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None" 
                                        value="<?php
                                            if (isset($avr['avr_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-AVR-' . $avr['avr_id'];
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
                                    value="<?php echo isset($avr['avr_brand']) ? $avr['avr_brand'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Model Number :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="modelnumber" 
                                    placeholder="None" 
                                    value="<?php echo isset($avr['avr_modelnumber']) ? $avr['avr_modelnumber'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Serial Number :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="serialnumber" 
                                    placeholder="None" 
                                    value="<?php echo isset($avr['avr_serialnumber']) ? $avr['avr_serialnumber'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Date Acquired :</label>
                            <div class="controls">
                                <input type="date" class="span11" name="dateacquired" 
                                    placeholder="None" 
                                    value="<?php echo isset($avr['avr_dateacquired']) ? $avr['avr_dateacquired'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Device Age :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="deviceage" 
                                    placeholder="None" 
                                    value="<?php echo isset($avr['avr_deviceage']) ? $avr['avr_deviceage'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Assigned User :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="assigneduser" 
                                    placeholder="None" 
                                    value="<?php echo isset($avr['avr_assigneduser']) ? $avr['avr_assigneduser'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Remarks :</label>
                            <div class="controls">
                                <select name="remarks" class="span11" required>
                                    <option value="" disabled <?php echo empty($avr['avr_remarks']) ? 'selected' : ''; ?>>Select Remark</option>
                                    <option value="Available" <?php echo (isset($avr['avr_remarks']) && $avr['avr_remarks'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                    <option value="In Use" <?php echo (isset($avr['avr_remarks']) && $avr['avr_remarks'] == 'In Use') ? 'selected' : ''; ?>>In Use</option>
                                    <option value="Defective" <?php echo (isset($avr['avr_remarks']) && $avr['avr_remarks'] == 'Defective') ? 'selected' : ''; ?>>Defective</option>
                                    <option value="For Repair" <?php echo (isset($avr['avr_remarks']) && $avr['avr_remarks'] == 'For Repair') ? 'selected' : ''; ?>>For Repair</option>
                                    <option value="Under Repair" <?php echo (isset($avr['avr_remarks']) && $avr['avr_remarks'] == 'Under Repair') ? 'selected' : ''; ?>>Under Repair</option>
                                    <option value="For Disposal" <?php echo (isset($avr['avr_remarks']) && $avr['avr_remarks'] == 'For Disposal') ? 'selected' : ''; ?>>For Disposal</option>
                                </select>
                            </div>
                        </div>


                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo ($alert == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "avr details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update avr details.";
                                        } elseif ($alert == "deleted") {
                                            echo "avr deleted!";
                                        }
                                    ?>
                                </div>
                            <?php } ?>
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-success">Save Changes</button>
                                <a href="peripherals.php" class="btn">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table to display avr details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>AVR Details</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <!-- Wrapper div for horizontal scrolling -->
                        <div style="overflow-x: auto;">
                            <table class="table table-bordered table-striped" style="min-width: 1200px;">
                                <thead>
                                    <tr>
                                        <th>PC Name</th>
                                        <th>Asset Tag</th>
                                        <th>Brand</th>
                                        <th>Model Number</th>
                                        <th>Serial Number</th>
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
                                                if (isset($avr['avr_id']) && isset($equipment['department'])) {
                                                    echo strtoupper($equipment['department']) . '-AVR-' . $avr['avr_id'];
                                                } else {
                                                    echo 'NOT YET SET';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo !empty($avr['avr_brand']) ? htmlspecialchars($avr['avr_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($avr['avr_modelnumber']) ? htmlspecialchars($avr['avr_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($avr['avr_serialnumber']) ? htmlspecialchars($avr['avr_serialnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($avr['avr_dateacquired']) ? htmlspecialchars($avr['avr_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($avr['avr_deviceage']) ? htmlspecialchars($avr['avr_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($avr['avr_assigneduser']) ? htmlspecialchars($avr['avr_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($avr['avr_remarks']) ? htmlspecialchars($avr['avr_remarks']) : 'None'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Delete Button (Appears at the End, Only If a avr Exists) -->
                <?php if ($avr): ?>
                    <form method="POST" style="display:inline; margin-top: 10px;" 
                          onsubmit="return confirm('Are you sure you want to delete this avr?');">
                        <input type="hidden" name="avr_id" value="<?php echo $avr['avr_id']; ?>">
                        <button type="submit" name="delete_avr" class="btn btn-danger">Delete</button>
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
