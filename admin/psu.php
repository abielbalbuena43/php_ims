<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing psu details from the psu table
$query = "SELECT * FROM psu WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$psu = mysqli_fetch_array($result);

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
if (isset($_POST["delete_psu"])) {
    $psu_id = $_POST["psu_id"];

    $deleteQuery = "DELETE FROM psu WHERE psu_id = $psu_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION["alert"] = "deleted";
        header("Location: psu.php?equipment_id=$equipment_id"); // Redirect after deletion
        exit();
    } else {
        $_SESSION["alert"] = "delete_error";
        header("Location: psu.php?equipment_id=$equipment_id");
        exit();
    }
}

// Handle form submission to update psu details
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

    // Check if psu exists for this equipment
    if ($psu) {
        // Update psu details in the database
        $updateQuery = "UPDATE psu SET 
                        psu_assettag = '$assettag', 
                        psu_brand = '$brand', 
                        psu_modelnumber = '$modelnumber', 
                        psu_serialnumber = '$serialnumber',
                        psu_dateacquired = '$dateacquired', 
                        psu_deviceage = '$deviceage', 
                        psu_assigneduser = '$assigneduser',  
                        psu_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: psu.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: psu.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the psu table
        $insertQuery = "INSERT INTO psu (equipment_id, psu_assettag, psu_brand, psu_modelnumber, psu_serialnumber, psu_dateacquired, psu_deviceage, psu_assigneduser, psu_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$serialnumber', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: psu.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: psu.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit PSU Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit PSU Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None" 
                                        value="<?php
                                            if (isset($psu['psu_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-PSU-' . $psu['psu_id'];
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
                                        value="<?php echo isset($psu['psu_brand']) ? $psu['psu_brand'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" 
                                        placeholder="None" 
                                        value="<?php echo isset($psu['psu_modelnumber']) ? $psu['psu_modelnumber'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Serial Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="serialnumber" 
                                        placeholder="None" 
                                        value="<?php echo isset($psu['psu_serialnumber']) ? $psu['psu_serialnumber'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Date Acquired :</label>
                                <div class="controls">
                                    <input type="date" class="span11" name="dateacquired" 
                                        value="<?php echo isset($psu['psu_dateacquired']) ? $psu['psu_dateacquired'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="deviceage" 
                                        placeholder="None" 
                                        value="<?php echo isset($psu['psu_deviceage']) ? $psu['psu_deviceage'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" 
                                        placeholder="None" 
                                        value="<?php echo isset($psu['psu_assigneduser']) ? $psu['psu_assigneduser'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($psu['psu_remarks']) ? $psu['psu_remarks'] : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo ($alert == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "PSU details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update PSU details.";
                                        } elseif ($alert == "deleted") {
                                            echo "PSU deleted!";
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

                <!-- Table to display PSU details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>PSU Details</h5>
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
                                                if (isset($psu['psu_id']) && isset($equipment['department'])) {
                                                    echo strtoupper($equipment['department']) . '-PSU-' . $psu['psu_id'];
                                                } else {
                                                    echo 'NOT YET SET';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo !empty($psu['psu_brand']) ? htmlspecialchars($psu['psu_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($psu['psu_modelnumber']) ? htmlspecialchars($psu['psu_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($psu['psu_serialnumber']) ? htmlspecialchars($psu['psu_serialnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($psu['psu_dateacquired']) ? htmlspecialchars($psu['psu_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($psu['psu_deviceage']) ? htmlspecialchars($psu['psu_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($psu['psu_assigneduser']) ? htmlspecialchars($psu['psu_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($psu['psu_remarks']) ? htmlspecialchars($psu['psu_remarks']) : 'None'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if ($psu): ?>
                    <form method="POST" style="display:inline; margin-top: 10px;" 
                          onsubmit="return confirm('Are you sure you want to delete this PSU?');">
                        <input type="hidden" name="psu_id" value="<?php echo $psu['psu_id']; ?>">
                        <button type="submit" name="delete_psu" class="btn btn-danger">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!--end-main-container-part-->
<?php include "footer.php"; ?>
