<?php
session_start();
include "session_verification.php";
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing processor details from the processor table
$query = "SELECT * FROM processor WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$processor = mysqli_fetch_array($result);

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

// Handle form submission to update processor details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if processor exists for this equipment
    if ($processor) {
        // Update processor details in the database
        $updateQuery = "UPDATE processor SET 
                        processor_assettag = '$assettag', 
                        processor_brand = '$brand', 
                        processor_modelnumber = '$modelnumber', 
                        processor_dateacquired = '$dateacquired', 
                        processor_deviceage = '$deviceage', 
                        processor_assigneduser = '$assigneduser', 
                        processor_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: processor.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: processor.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the processor table
        $insertQuery = "INSERT INTO processor (equipment_id, processor_assettag, processor_brand, processor_modelnumber, processor_dateacquired, processor_deviceage, processor_assigneduser, processor_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: processor.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: processor.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit Processor Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Processor Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Form to edit processor details -->
                            <div class="control-group">
                            <label class="control-label">Asset Tag :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="assettag" 
                                    placeholder="None" 
                                    value="<?php echo isset($processor['processor_assettag']) ? $processor['processor_assettag'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Brand :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="brand" 
                                    placeholder="None" 
                                    value="<?php echo isset($processor['processor_brand']) ? $processor['processor_brand'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Model Number :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="modelnumber" 
                                    placeholder="None" 
                                    value="<?php echo isset($processor['processor_modelnumber']) ? $processor['processor_modelnumber'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Date Acquired :</label>
                            <div class="controls">
                                <input type="date" class="span11" name="dateacquired" 
                                    placeholder="None" 
                                    value="<?php echo isset($processor['processor_dateacquired']) ? $processor['processor_dateacquired'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Device Age :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="deviceage" 
                                    placeholder="None" 
                                    value="<?php echo isset($processor['processor_deviceage']) ? $processor['processor_deviceage'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Assigned User :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="assigneduser" 
                                    placeholder="None" 
                                    value="<?php echo isset($processor['processor_assigneduser']) ? $processor['processor_assigneduser'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Remarks :</label>
                            <div class="controls">
                                <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($processor['processor_remarks']) ? $processor['processor_remarks'] : ''; ?></textarea>
                            </div>
                        </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo $alert == 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "Processor details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update processor details.";
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

                <!-- Table to display processor details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Processor Details</h5>
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
                                        <td><?php echo !empty($processor['processor_assettag']) ? htmlspecialchars($processor['processor_assettag']) : 'None'; ?></td>
                                        <td><?php echo !empty($processor['processor_brand']) ? htmlspecialchars($processor['processor_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($processor['processor_modelnumber']) ? htmlspecialchars($processor['processor_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($processor['processor_dateacquired']) ? htmlspecialchars($processor['processor_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($processor['processor_deviceage']) ? htmlspecialchars($processor['processor_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($processor['processor_assigneduser']) ? htmlspecialchars($processor['processor_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($processor['processor_remarks']) ? htmlspecialchars($processor['processor_remarks']) : 'None'; ?></td>
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
