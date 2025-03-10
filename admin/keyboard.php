<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing keyboard details from the keyboard table
$query = "SELECT * FROM keyboard WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$keyboard = mysqli_fetch_array($result);

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

// Handle form submission to update keyboard details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if keyboard exists for this equipment
    if ($keyboard) {
        // Update keyboard details in the database
        $updateQuery = "UPDATE keyboard SET 
                        keyboard_assettag = '$assettag', 
                        keyboard_brand = '$brand', 
                        keyboard_modelnumber = '$modelnumber', 
                        keyboard_dateacquired = '$dateacquired', 
                        keyboard_deviceage = '$deviceage', 
                        keyboard_assigneduser = '$assigneduser', 
                        keyboard_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: keyboard.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: keyboard.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the keyboard table
        $insertQuery = "INSERT INTO keyboard (equipment_id, keyboard_assettag, keyboard_brand, keyboard_modelnumber, keyboard_dateacquired, keyboard_deviceage, keyboard_assigneduser, keyboard_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: keyboard.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: keyboard.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit Keyboard Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Keyboard Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Form to edit keyboard details -->
                            <div class="control-group">
                            <label class="control-label">Asset Tag :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="assettag" 
                                    placeholder="None" 
                                    value="<?php echo isset($keyboard['keyboard_assettag']) ? $keyboard['keyboard_assettag'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Brand :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="brand" 
                                    placeholder="None" 
                                    value="<?php echo isset($keyboard['keyboard_brand']) ? $keyboard['keyboard_brand'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Model Number :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="modelnumber" 
                                    placeholder="None" 
                                    value="<?php echo isset($keyboard['keyboard_modelnumber']) ? $keyboard['keyboard_modelnumber'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Date Acquired :</label>
                            <div class="controls">
                                <input type="date" class="span11" name="dateacquired" 
                                    placeholder="None" 
                                    value="<?php echo isset($keyboard['keyboard_dateacquired']) ? $keyboard['keyboard_dateacquired'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Device Age :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="deviceage" 
                                    placeholder="None" 
                                    value="<?php echo isset($keyboard['keyboard_deviceage']) ? $keyboard['keyboard_deviceage'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Assigned User :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="assigneduser" 
                                    placeholder="None" 
                                    value="<?php echo isset($keyboard['keyboard_assigneduser']) ? $keyboard['keyboard_assigneduser'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Remarks :</label>
                            <div class="controls">
                                <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($keyboard['keyboard_remarks']) ? $keyboard['keyboard_remarks'] : ''; ?></textarea>
                            </div>
                        </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo $alert == 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "Keyboard details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update keyboard details.";
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

                <!-- Table to display keyboard details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Keyboard Details</h5>
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
                                        <th>Date Acquired</th>
                                        <th>Device Age</th>
                                        <th>Assigned User</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                        <td><?php echo !empty($keyboard['keyboard_assettag']) ? htmlspecialchars($keyboard['keyboard_assettag']) : 'None'; ?></td>
                                        <td><?php echo !empty($keyboard['keyboard_brand']) ? htmlspecialchars($keyboard['keyboard_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($keyboard['keyboard_modelnumber']) ? htmlspecialchars($keyboard['keyboard_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($keyboard['keyboard_dateacquired']) ? htmlspecialchars($keyboard['keyboard_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($keyboard['keyboard_deviceage']) ? htmlspecialchars($keyboard['keyboard_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($keyboard['keyboard_assigneduser']) ? htmlspecialchars($keyboard['keyboard_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($keyboard['keyboard_remarks']) ? htmlspecialchars($keyboard['keyboard_remarks']) : 'None'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
