<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing msoffice details from the msoffice table
$query = "SELECT * FROM msoffice WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$msoffice = mysqli_fetch_array($result);

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
if (isset($_POST["delete_msoffice"])) {
    $msoffice_id = $_POST["msoffice_id"];

    $deleteQuery = "DELETE FROM msoffice WHERE msoffice_id = $msoffice_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION["alert"] = "deleted";
        header("Location: msoffice.php?equipment_id=$equipment_id"); // Redirect after deletion
        exit();
    } else {
        $_SESSION["alert"] = "delete_error";
        header("Location: msoffice.php?equipment_id=$equipment_id");
        exit();
    }
}

// Handle form submission to update msoffice details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $officeversion = mysqli_real_escape_string($link, $_POST["officeversion"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $licensekey = mysqli_real_escape_string($link, $_POST["licensekey"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if msoffice record exists for this equipment
    if ($msoffice) {
        // Update msoffice details in the database
        $updateQuery = "UPDATE msoffice SET 
                        msoffice_assettag = '$assettag', 
                        msoffice_brand = '$brand', 
                        msoffice_modelnumber = '$modelnumber', 
                        msoffice_officeversion = '$officeversion', 
                        msoffice_assigneduser = '$assigneduser', 
                        msoffice_licensekey = '$licensekey', 
                        msoffice_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: msoffice.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: msoffice.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the msoffice table
        $insertQuery = "INSERT INTO msoffice (equipment_id, msoffice_assettag, msoffice_brand, msoffice_modelnumber, msoffice_officeversion, msoffice_assigneduser, msoffice_licensekey, msoffice_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$officeversion', '$assigneduser', '$licensekey', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: msoffice.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: msoffice.php?equipment_id=$equipment_id");
            exit();
        }
    }
}
?>

<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="software.php" class="tip-bottom">
                <i class="icon-home"></i> Edit Office Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Office Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None" 
                                        value="<?php
                                            if (isset($msoffice['msoffice_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-MSOF-' . $msoffice['msoffice_id'];
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
                                        value="<?php echo isset($msoffice['msoffice_brand']) ? $msoffice['msoffice_brand'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" 
                                        placeholder="None" 
                                        value="<?php echo isset($msoffice['msoffice_modelnumber']) ? $msoffice['msoffice_modelnumber'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Office Version :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="officeversion" 
                                        placeholder="None" 
                                        value="<?php echo isset($msoffice['msoffice_officeversion']) ? $msoffice['msoffice_officeversion'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" 
                                        placeholder="None" 
                                        value="<?php echo isset($msoffice['msoffice_assigneduser']) ? $msoffice['msoffice_assigneduser'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">License Key :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="licensekey" 
                                        placeholder="None" 
                                        value="<?php echo isset($msoffice['msoffice_licensekey']) ? $msoffice['msoffice_licensekey'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($msoffice['msoffice_remarks']) ? $msoffice['msoffice_remarks'] : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo ($alert == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "MS Office details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update MS Office details.";
                                        } elseif ($alert == "deleted") {
                                            echo "MS Office deleted!";
                                        }
                                    ?>
                                </div>
                            <?php } ?>

                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-success">Save Changes</button>
                                <a href="software.php" class="btn">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Display Office details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Office Details</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <div style="overflow-x: auto;">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>PC Name</th>
                                        <th>Asset Tag</th>
                                        <th>Brand</th>
                                        <th>Model Number</th>
                                        <th>Office Version</th>
                                        <th>Assigned User</th>
                                        <th>License Key</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                        <td>
                                            <?php 
                                                if (isset($msoffice['msoffice_id']) && isset($equipment['department'])) {
                                                    echo strtoupper($equipment['department']) . '-MSOF-' . $msoffice['msoffice_id'];
                                                } else {
                                                    echo 'NOT YET SET';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo !empty($msoffice['msoffice_brand']) ? htmlspecialchars($msoffice['msoffice_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($msoffice['msoffice_modelnumber']) ? htmlspecialchars($msoffice['msoffice_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($msoffice['msoffice_officeversion']) ? htmlspecialchars($msoffice['msoffice_officeversion']) : 'None'; ?></td>
                                        <td><?php echo !empty($msoffice['msoffice_assigneduser']) ? htmlspecialchars($msoffice['msoffice_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($msoffice['msoffice_licensekey']) ? htmlspecialchars($msoffice['msoffice_licensekey']) : 'None'; ?></td>
                                        <td><?php echo !empty($msoffice['msoffice_remarks']) ? htmlspecialchars($msoffice['msoffice_remarks']) : 'None'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Delete Button (Appears at the End, Only If an msoffice Exists) -->
                <?php if ($msoffice): ?>
                    <form method="POST" style="display:inline; margin-top: 10px;" 
                          onsubmit="return confirm('Are you sure you want to delete this MS Office?');">
                        <input type="hidden" name="msoffice_id" value="<?php echo $msoffice['msoffice_id']; ?>">
                        <button type="submit" name="delete_msoffice" class="btn btn-danger">Delete</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>