<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing HDD details from the hdd table
$query = "SELECT * FROM hdd WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$hdd = mysqli_fetch_array($result);

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
if (isset($_POST["delete_hdd"])) {
    $hdd_id = $_POST["hdd_id"];

    $deleteQuery = "DELETE FROM hdd WHERE hdd_id = $hdd_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION["alert"] = "deleted";
        header("Location: hdd.php?equipment_id=$equipment_id"); // Redirect after deletion
        exit();
    } else {
        $_SESSION["alert"] = "delete_error";
        header("Location: hdd.php?equipment_id=$equipment_id");
        exit();
    }
}

// Handle form submission to update HDD details
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

    // Check if HDD exists for this equipment
    if ($hdd) {
        // Update HDD details in the database
        $updateQuery = "UPDATE hdd SET 
                        hdd_assettag = '$assettag', 
                        hdd_brand = '$brand', 
                        hdd_modelnumber = '$modelnumber', 
                        hdd_size = '$size', 
                        hdd_dateacquired = '$dateacquired', 
                        hdd_deviceage = '$deviceage', 
                        hdd_assigneduser = '$assigneduser', 
                        hdd_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: hdd.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: hdd.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the hdd table
        $insertQuery = "INSERT INTO hdd (equipment_id, hdd_assettag, hdd_brand, hdd_modelnumber, hdd_size, hdd_dateacquired, hdd_deviceage, hdd_assigneduser, hdd_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$size', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: hdd.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: hdd.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit HDD Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit HDD Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                        <!-- Asset Tag -->
                        <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None" 
                                        value="<?php
                                            if (isset($hdd['hdd_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-HDD-' . $hdd['hdd_id'];
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
                                value="<?php echo isset($hdd['hdd_brand']) ? $hdd['hdd_brand'] : ''; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Model Number :</label>
                        <div class="controls">
                            <input type="text" class="span11" name="modelnumber" 
                                placeholder="None" 
                                value="<?php echo isset($hdd['hdd_modelnumber']) ? $hdd['hdd_modelnumber'] : ''; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Size :</label>
                        <div class="controls">
                            <input type="text" class="span11" name="size" 
                                placeholder="None" 
                                value="<?php echo isset($hdd['hdd_size']) ? $hdd['hdd_size'] : ''; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Date Acquired :</label>
                        <div class="controls">
                            <input type="date" class="span11" name="dateacquired" 
                                value="<?php echo isset($hdd['hdd_dateacquired']) ? $hdd['hdd_dateacquired'] : ''; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Device Age :</label>
                        <div class="controls">
                            <input type="text" class="span11" name="deviceage" 
                                placeholder="None" 
                                value="<?php echo isset($hdd['hdd_deviceage']) ? $hdd['hdd_deviceage'] : ''; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Assigned User :</label>
                        <div class="controls">
                            <input type="text" class="span11" name="assigneduser" 
                                placeholder="None" 
                                value="<?php echo isset($hdd['hdd_assigneduser']) ? $hdd['hdd_assigneduser'] : ''; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">Remarks :</label>
                        <div class="controls">
                            <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($hdd['hdd_remarks']) ? $hdd['hdd_remarks'] : ''; ?></textarea>
                        </div>
                    </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo ($alert == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "HDD details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update HDD details.";
                                        } elseif ($alert == "deleted") {
                                            echo "HDD deleted!";
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

                <!-- Table to display HDD details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>HDD Details</h5>
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
                                            if (isset($hdd['hdd_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-HDD-' . $hdd['hdd_id'];
                                            } else {
                                                echo 'NOT YET SET';
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo !empty($hdd['hdd_brand']) ? htmlspecialchars($hdd['hdd_brand']) : 'None'; ?></td>
                                    <td><?php echo !empty($hdd['hdd_modelnumber']) ? htmlspecialchars($hdd['hdd_modelnumber']) : 'None'; ?></td>
                                    <td><?php echo !empty($hdd['hdd_size']) ? htmlspecialchars($hdd['hdd_size']) : 'None'; ?></td>
                                    <td><?php echo !empty($hdd['hdd_dateacquired']) ? htmlspecialchars($hdd['hdd_dateacquired']) : 'None'; ?></td>
                                    <td><?php echo !empty($hdd['hdd_deviceage']) ? htmlspecialchars($hdd['hdd_deviceage']) : 'None'; ?></td>
                                    <td><?php echo !empty($hdd['hdd_assigneduser']) ? htmlspecialchars($hdd['hdd_assigneduser']) : 'None'; ?></td>
                                    <td><?php echo !empty($hdd['hdd_remarks']) ? htmlspecialchars($hdd['hdd_remarks']) : 'None'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                <?php if ($hdd): ?>
                    <form method="POST" style="display:inline; margin-top: 10px;" 
                          onsubmit="return confirm('Are you sure you want to delete this hdd?');">
                        <input type="hidden" name="hdd_id" value="<?php echo $hdd['hdd_id']; ?>">
                        <button type="submit" name="delete_hdd" class="btn btn-danger">Delete</button>
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
