<?php
session_start();
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing gpu details from the gpu table
$query = "SELECT * FROM gpu WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$gpu = mysqli_fetch_array($result);

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

// Handle form submission to update gpu details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $size = mysqli_real_escape_string($link, $_POST["size"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if gpu exists for this equipment
    if ($gpu) {
        // Update gpu details in the database
        $updateQuery = "UPDATE gpu SET 
                        gpu_assettag = '$assettag', 
                        gpu_brand = '$brand', 
                        gpu_modelnumber = '$modelnumber', 
                        gpu_size = '$size', 
                        gpu_dateacquired = '$dateacquired', 
                        gpu_deviceage = '$deviceage', 
                        gpu_assigneduser = '$assigneduser', 
                        gpu_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: gpu.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: gpu.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the gpu table
        $insertQuery = "INSERT INTO gpu (equipment_id, gpu_assettag, gpu_brand, gpu_modelnumber, gpu_size, gpu_dateacquired, gpu_deviceage, gpu_assigneduser, gpu_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$size', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: gpu.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: gpu.php?equipment_id=$equipment_id");
            exit();
        }
    }
}
?>

<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="index.html" class="tip-bottom">
                <i class="icon-home"></i> Edit GPU Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit GPU Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" value="<?php echo isset($gpu['gpu_assettag']) ? $gpu['gpu_assettag'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="brand" value="<?php echo isset($gpu['gpu_brand']) ? $gpu['gpu_brand'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" value="<?php echo isset($gpu['gpu_modelnumber']) ? $gpu['gpu_modelnumber'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Size :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="size" value="<?php echo isset($gpu['gpu_size']) ? $gpu['gpu_size'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Date Acquired :</label>
                                <div class="controls">
                                    <input type="date" class="span11" name="dateacquired" value="<?php echo isset($gpu['gpu_dateacquired']) ? $gpu['gpu_dateacquired'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div> <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="deviceage" 
                                        value="<?php echo isset($gpu['gpu_deviceage']) ? $gpu['gpu_deviceage'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" value="<?php echo isset($gpu['gpu_assigneduser']) ? $gpu['gpu_assigneduser'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks"><?php echo isset($gpu['gpu_remarks']) ? $gpu['gpu_remarks'] : 'None'; ?></textarea>
                                </div>
                            </div>
                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo $alert == 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "GPU details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update GPU details.";
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

                <!-- Table to display GPU details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>GPU Details</h5>
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
                                        <th>Size</th>
                                        <th>Date Acquired</th>
                                        <th>Device Age</th>
                                        <th>Assigned User</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                        <td><?php echo !empty($gpu['gpu_assettag']) ? htmlspecialchars($gpu['gpu_assettag']) : 'None'; ?></td>
                                        <td><?php echo !empty($gpu['gpu_brand']) ? htmlspecialchars($gpu['gpu_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($gpu['gpu_modelnumber']) ? htmlspecialchars($gpu['gpu_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($gpu['gpu_size']) ? htmlspecialchars($gpu['gpu_size']) : 'None'; ?></td>
                                        <td><?php echo !empty($gpu['gpu_dateacquired']) ? htmlspecialchars($gpu['gpu_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($gpu['gpu_deviceage']) ? htmlspecialchars($gpu['gpu_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($gpu['gpu_assigneduser']) ? htmlspecialchars($gpu['gpu_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($gpu['gpu_remarks']) ? htmlspecialchars($gpu['gpu_remarks']) : 'None'; ?></td>
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
