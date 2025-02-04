<?php
session_start();
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing MSOS details from the msos table
$query = "SELECT * FROM msos WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$msos = mysqli_fetch_array($result);

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

// Handle form submission to update msos details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $windowsversion = mysqli_real_escape_string($link, $_POST["windowsversion"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $licensekey = mysqli_real_escape_string($link, $_POST["licensekey"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if MSOS record exists for this equipment
    if ($msos) {
        // Update MSOS details in the database
        $updateQuery = "UPDATE msos SET 
                        msos_assettag = '$assettag', 
                        msos_brand = '$brand', 
                        msos_modelnumber = '$modelnumber', 
                        msos_windowsversion = '$windowsversion', 
                        msos_assigneduser = '$assigneduser', 
                        msos_licensekey = '$licensekey', 
                        msos_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: msos.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: msos.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the msos table
        $insertQuery = "INSERT INTO msos (equipment_id, msos_assettag, msos_brand, msos_modelnumber, msos_windowsversion, msos_assigneduser, msos_licensekey, msos_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$windowsversion', '$assigneduser', '$licensekey', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: msos.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: msos.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit MSOS Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit MSOS Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Form to edit MSOS details -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" value="<?php echo isset($msos['msos_assettag']) ? $msos['msos_assettag'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="brand" value="<?php echo isset($msos['msos_brand']) ? $msos['msos_brand'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" value="<?php echo isset($msos['msos_modelnumber']) ? $msos['msos_modelnumber'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Windows Version :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="windowsversion" value="<?php echo isset($msos['msos_windowsversion']) ? $msos['msos_windowsversion'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" value="<?php echo isset($msos['msos_assigneduser']) ? $msos['msos_assigneduser'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">License Key :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="licensekey" value="<?php echo isset($msos['msos_licensekey']) ? $msos['msos_licensekey'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks"><?php echo isset($msos['msos_remarks']) ? $msos['msos_remarks'] : 'None'; ?></textarea>
                                </div>
                            </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo $alert == 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "MSOS details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update MSOS details.";
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

                <!-- Display MSOS details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>MSOS Details</h5>
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
                                        <th>Windows Version</th>
                                        <th>Assigned User</th>
                                        <th>License Key</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                        <td><?php echo !empty($msos['msos_assettag']) ? htmlspecialchars($msos['msos_assettag']) : 'None'; ?></td>
                                        <td><?php echo !empty($msos['msos_brand']) ? htmlspecialchars($msos['msos_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($msos['msos_modelnumber']) ? htmlspecialchars($msos['msos_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($msos['msos_windowsversion']) ? htmlspecialchars($msos['msos_windowsversion']) : 'None'; ?></td>
                                        <td><?php echo !empty($msos['msos_assigneduser']) ? htmlspecialchars($msos['msos_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($msos['msos_licensekey']) ? htmlspecialchars($msos['msos_licensekey']) : 'None'; ?></td>
                                        <td><?php echo !empty($msos['msos_remarks']) ? htmlspecialchars($msos['msos_remarks']) : 'None'; ?></td>
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

<?php
include "footer.php";
?>
