<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing SSD details from the ssd table
$query = "SELECT * FROM ssd WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$ssd = mysqli_fetch_array($result);

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
if (isset($_POST["delete_ssd"])) {
    $ssd_id = $_POST["ssd_id"];

    $deleteQuery = "DELETE FROM ssd WHERE ssd_id = $ssd_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION["alert"] = "deleted";
        header("Location: ssd.php?equipment_id=$equipment_id"); // Redirect after deletion
        exit();
    } else {
        $_SESSION["alert"] = "delete_error";
        header("Location: ssd.php?equipment_id=$equipment_id");
        exit();
    }
}

// Handle form submission to update SSD details
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

    // Check if SSD exists for this equipment
    if ($ssd) {
        // Update SSD details in the database
        $updateQuery = "UPDATE ssd SET 
                        ssd_assettag = '$assettag', 
                        ssd_brand = '$brand', 
                        ssd_modelnumber = '$modelnumber', 
                        ssd_serialnumber = '$serialnumber',
                        ssd_size = '$size', 
                        ssd_dateacquired = '$dateacquired', 
                        ssd_deviceage = '$deviceage', 
                        ssd_assigneduser = '$assigneduser', 
                        ssd_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: ssd.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: ssd.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the ssd table
        $insertQuery = "INSERT INTO ssd (equipment_id, ssd_assettag, ssd_brand, ssd_modelnumber, ssd_serialnumber, ssd_size, ssd_dateacquired, ssd_deviceage, ssd_assigneduser, ssd_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$serialnumber', '$size', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: ssd.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: ssd.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit SSD Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit SSD Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None" 
                                        value="<?php
                                            if (isset($ssd['ssd_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-SSD-' . $ssd['ssd_id'];
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
                                        value="<?php echo isset($ssd['ssd_brand']) ? $ssd['ssd_brand'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" 
                                        placeholder="None" 
                                        value="<?php echo isset($ssd['ssd_modelnumber']) ? $ssd['ssd_modelnumber'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Serial Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="serialnumber" 
                                        placeholder="None" 
                                        value="<?php echo isset($ssd['ssd_serialnumber']) ? $ssd['ssd_serialnumber'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Size :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="size" 
                                        placeholder="None" 
                                        value="<?php echo isset($ssd['ssd_size']) ? $ssd['ssd_size'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Date Acquired :</label>
                                <div class="controls">
                                    <input type="date" class="span11" name="dateacquired" 
                                        value="<?php echo isset($ssd['ssd_dateacquired']) ? $ssd['ssd_dateacquired'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="deviceage" 
                                        placeholder="None" 
                                        value="<?php echo isset($ssd['ssd_deviceage']) ? $ssd['ssd_deviceage'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" 
                                        placeholder="None" 
                                        value="<?php echo isset($ssd['ssd_assigneduser']) ? $ssd['ssd_assigneduser'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($ssd['ssd_remarks']) ? $ssd['ssd_remarks'] : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo ($alert == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "SSD details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update SSD details.";
                                        } elseif ($alert == "deleted") {
                                            echo "SSD deleted!";
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

                <!-- Table to display SSD details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>SSD Details</h5>
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
                                                if (isset($ssd['ssd_id']) && isset($equipment['department'])) {
                                                    echo strtoupper($equipment['department']) . '-SSD-' . $ssd['ssd_id'];
                                                } else {
                                                    echo 'NOT YET SET';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo !empty($ssd['ssd_brand']) ? htmlspecialchars($ssd['ssd_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($ssd['ssd_modelnumber']) ? htmlspecialchars($ssd['ssd_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($ssd['ssd_serialnumber']) ? htmlspecialchars($ssd['ssd_serialnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($ssd['ssd_size']) ? htmlspecialchars($ssd['ssd_size']) : 'None'; ?></td>
                                        <td><?php echo !empty($ssd['ssd_dateacquired']) ? htmlspecialchars($ssd['ssd_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($ssd['ssd_deviceage']) ? htmlspecialchars($ssd['ssd_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($ssd['ssd_assigneduser']) ? htmlspecialchars($ssd['ssd_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($ssd['ssd_remarks']) ? htmlspecialchars($ssd['ssd_remarks']) : 'None'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Delete Button (Appears at the End, Only If an SSD Exists) -->
                <?php if ($ssd): ?>
                    <form method="POST" style="display:inline; margin-top: 10px;" 
                          onsubmit="return confirm('Are you sure you want to delete this SSD?');">
                        <input type="hidden" name="ssd_id" value="<?php echo $ssd['ssd_id']; ?>">
                        <button type="submit" name="delete_ssd" class="btn btn-danger">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
