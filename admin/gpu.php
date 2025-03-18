<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

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

// Handle delete request
if (isset($_POST["delete_gpu"])) {
    $gpu_id = $_POST["gpu_id"];

    $deleteQuery = "DELETE FROM gpu WHERE gpu_id = $gpu_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION["alert"] = "deleted";
        header("Location: gpu.php?equipment_id=$equipment_id"); // Redirect after deletion
        exit();
    } else {
        $_SESSION["alert"] = "delete_error";
        header("Location: gpu.php?equipment_id=$equipment_id");
        exit();
    }
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
            <a href="equipment.php" class="tip-bottom">
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
                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None" 
                                        value="<?php
                                            if (isset($gpu['gpu_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-GPU-' . $gpu['gpu_id'];
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
                                        value="<?php echo isset($gpu['gpu_brand']) ? $gpu['gpu_brand'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" 
                                        placeholder="None" 
                                        value="<?php echo isset($gpu['gpu_modelnumber']) ? $gpu['gpu_modelnumber'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Size :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="size" 
                                        placeholder="None" 
                                        value="<?php echo isset($gpu['gpu_size']) ? $gpu['gpu_size'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Date Acquired :</label>
                                <div class="controls">
                                    <input type="date" class="span11" name="dateacquired" 
                                        value="<?php echo isset($gpu['gpu_dateacquired']) ? $gpu['gpu_dateacquired'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="deviceage" 
                                        placeholder="None" 
                                        value="<?php echo isset($gpu['gpu_deviceage']) ? $gpu['gpu_deviceage'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" 
                                        placeholder="None" 
                                        value="<?php echo isset($gpu['gpu_assigneduser']) ? $gpu['gpu_assigneduser'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($gpu['gpu_remarks']) ? $gpu['gpu_remarks'] : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo ($alert == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "GPU details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update GPU details.";
                                        } elseif ($alert == "deleted") {
                                            echo "GPU deleted!";
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
                                        <td>
                                            <?php 
                                                if (isset($gpu['gpu_id']) && isset($equipment['department'])) {
                                                    echo strtoupper($equipment['department']) . '-GPU-' . $gpu['gpu_id'];
                                                } else {
                                                    echo 'NOT YET SET';
                                                }
                                            ?>
                                        </td>
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
                <!-- Delete Button (Appears at the End, Only If a GPU Exists) -->
                <?php if ($gpu): ?>
                    <form method="POST" style="display:inline; margin-top: 10px;" 
                          onsubmit="return confirm('Are you sure you want to delete this GPU?');">
                        <input type="hidden" name="gpu_id" value="<?php echo $gpu['gpu_id']; ?>">
                        <button type="submit" name="delete_gpu" class="btn btn-danger">Delete</button>
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
