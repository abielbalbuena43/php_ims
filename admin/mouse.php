<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing mouse details from the mouse table
$query = "SELECT * FROM mouse WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$mouse = mysqli_fetch_array($result);

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
if (isset($_POST["delete_mouse"])) {
    $mouse_id = $_POST["mouse_id"];

    $deleteQuery = "DELETE FROM mouse WHERE mouse_id = $mouse_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION["alert"] = "deleted";
        header("Location: mouse.php?equipment_id=$equipment_id"); // Redirect after deletion
        exit();
    } else {
        $_SESSION["alert"] = "delete_error";
        header("Location: mouse.php?equipment_id=$equipment_id");
        exit();
    }
}

// Handle form submission to update mouse details
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

    // Check if mouse exists for this equipment
    if ($mouse) {
        // Update mouse details in the database
        $updateQuery = "UPDATE mouse SET 
                        mouse_assettag = '$assettag', 
                        mouse_brand = '$brand', 
                        mouse_modelnumber = '$modelnumber', 
                        mouse_serialnumber = '$serialnumber',
                        mouse_dateacquired = '$dateacquired', 
                        mouse_deviceage = '$deviceage', 
                        mouse_assigneduser = '$assigneduser', 
                        mouse_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: mouse.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: mouse.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the mouse table
        $insertQuery = "INSERT INTO mouse (equipment_id, mouse_assettag, mouse_brand, mouse_modelnumber, mouse_serialnumber, mouse_dateacquired, mouse_deviceage, mouse_assigneduser, mouse_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$serialnumber', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: mouse.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: mouse.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit Mouse Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Mouse Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Form to edit mouse details -->
                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None" 
                                        value="<?php
                                            if (isset($mouse['mouse_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-MOUSE-' . $mouse['mouse_id'];
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
                                    value="<?php echo isset($mouse['mouse_brand']) ? $mouse['mouse_brand'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Model Number :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="modelnumber" 
                                    placeholder="None" 
                                    value="<?php echo isset($mouse['mouse_modelnumber']) ? $mouse['mouse_modelnumber'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Serial Number :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="serialnumber" 
                                    placeholder="None" 
                                    value="<?php echo isset($mouse['mouse_serialnumber']) ? $mouse['mouse_serialnumber'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Date Acquired :</label>
                            <div class="controls">
                                <input type="date" class="span11" name="dateacquired" 
                                    placeholder="None" 
                                    value="<?php echo isset($mouse['mouse_dateacquired']) ? $mouse['mouse_dateacquired'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Device Age :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="deviceage" 
                                    placeholder="None" 
                                    value="<?php echo isset($mouse['mouse_deviceage']) ? $mouse['mouse_deviceage'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Assigned User :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="assigneduser" 
                                    placeholder="None" 
                                    value="<?php echo isset($mouse['mouse_assigneduser']) ? $mouse['mouse_assigneduser'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Remarks :</label>
                            <div class="controls">
                                <select name="remarks" class="span11" required>
                                    <option value="" disabled <?php echo empty($mouse['mouse_remarks']) ? 'selected' : ''; ?>>Select Remark</option>
                                    <option value="Available" <?php echo (isset($mouse['mouse_remarks']) && $mouse['mouse_remarks'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                    <option value="In Use" <?php echo (isset($mouse['mouse_remarks']) && $mouse['mouse_remarks'] == 'In Use') ? 'selected' : ''; ?>>In Use</option>
                                    <option value="Defective" <?php echo (isset($mouse['mouse_remarks']) && $mouse['mouse_remarks'] == 'Defective') ? 'selected' : ''; ?>>Defective</option>
                                    <option value="For Repair" <?php echo (isset($mouse['mouse_remarks']) && $mouse['mouse_remarks'] == 'For Repair') ? 'selected' : ''; ?>>For Repair</option>
                                    <option value="Under Repair" <?php echo (isset($mouse['mouse_remarks']) && $mouse['mouse_remarks'] == 'Under Repair') ? 'selected' : ''; ?>>Under Repair</option>
                                    <option value="For Disposal" <?php echo (isset($mouse['mouse_remarks']) && $mouse['mouse_remarks'] == 'For Disposal') ? 'selected' : ''; ?>>For Disposal</option>
                                </select>
                            </div>
                        </div>

                             <!-- Success/Failure Alert -->
                             <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo ($alert == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "Mouse details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update mouse details.";
                                        } elseif ($alert == "deleted") {
                                            echo "Mouse deleted!";
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

                <!-- Table to display mouse details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Mouse Details</h5>
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
                                                if (isset($mouse['mouse_id']) && isset($equipment['department'])) {
                                                    echo strtoupper($equipment['department']) . '-MOUSE-' . $mouse['mouse_id'];
                                                } else {
                                                    echo 'NOT YET SET';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo !empty($mouse['mouse_brand']) ? htmlspecialchars($mouse['mouse_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_modelnumber']) ? htmlspecialchars($mouse['mouse_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_serialnumber']) ? htmlspecialchars($mouse['mouse_serialnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_dateacquired']) ? htmlspecialchars($mouse['mouse_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_deviceage']) ? htmlspecialchars($mouse['mouse_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_assigneduser']) ? htmlspecialchars($mouse['mouse_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_remarks']) ? htmlspecialchars($mouse['mouse_remarks']) : 'None'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Delete Button (Appears at the End, Only If a mouse Exists) -->
                <?php if ($mouse): ?>
                    <form method="POST" style="display:inline; margin-top: 10px;" 
                          onsubmit="return confirm('Are you sure you want to delete this mouse?');">
                        <input type="hidden" name="mouse_id" value="<?php echo $mouse['mouse_id']; ?>">
                        <button type="submit" name="delete_mouse" class="btn btn-danger">Delete</button>
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
