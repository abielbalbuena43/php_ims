<?php
session_start();
include "session_verification.php";
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing adobe details from the adobe table
$query = "SELECT * FROM adobe WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$adobe = mysqli_fetch_array($result);

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

// Handle form submission to update adobe details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $adobeversion = mysqli_real_escape_string($link, $_POST["adobeversion"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $licensekey = mysqli_real_escape_string($link, $_POST["licensekey"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if adobe record exists for this equipment
    if ($adobe) {
        // Update adobe details in the database
        $updateQuery = "UPDATE adobe SET 
                        adobe_assettag = '$assettag', 
                        adobe_brand = '$brand', 
                        adobe_modelnumber = '$modelnumber', 
                        adobe_adobeversion = '$adobeversion', 
                        adobe_assigneduser = '$assigneduser', 
                        adobe_licensekey = '$licensekey', 
                        adobe_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: adobe.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: adobe.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the adobe table
        $insertQuery = "INSERT INTO adobe (equipment_id, adobe_assettag, adobe_brand, adobe_modelnumber, adobe_adobeversion, adobe_assigneduser, adobe_licensekey, adobe_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$adobeversion', '$assigneduser', '$licensekey', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: adobe.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: adobe.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit Adobe Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Adobe Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Form to edit Adobe details -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" 
                                        placeholder="None" 
                                        value="<?php echo isset($adobe['adobe_assettag']) ? $adobe['adobe_assettag'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="brand" 
                                        placeholder="None" 
                                        value="<?php echo isset($adobe['adobe_brand']) ? $adobe['adobe_brand'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" 
                                        placeholder="None" 
                                        value="<?php echo isset($adobe['adobe_modelnumber']) ? $adobe['adobe_modelnumber'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Adobe Version :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="windowsversion" 
                                        placeholder="None" 
                                        value="<?php echo isset($adobe['adobe_windowsversion']) ? $adobe['adobe_windowsversion'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" 
                                        placeholder="None" 
                                        value="<?php echo isset($adobe['adobe_assigneduser']) ? $adobe['adobe_assigneduser'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">License Key :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="licensekey" 
                                        placeholder="None" 
                                        value="<?php echo isset($adobe['adobe_licensekey']) ? $adobe['adobe_licensekey'] : ''; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($adobe['adobe_remarks']) ? $adobe['adobe_remarks'] : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo $alert == 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "Adobe details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update Adobe details.";
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

                <!-- Display Adobe details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Adobe Details</h5>
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
                                        <th>Adobe Version</th>
                                        <th>Assigned User</th>
                                        <th>License Key</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                        <td><?php echo !empty($adobe['adobe_assettag']) ? htmlspecialchars($adobe['adobe_assettag']) : 'None'; ?></td>
                                        <td><?php echo !empty($adobe['adobe_brand']) ? htmlspecialchars($adobe['adobe_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($adobe['adobe_modelnumber']) ? htmlspecialchars($adobe['adobe_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($adobe['adobe_windowsversion']) ? htmlspecialchars($adobe['adobe_windowsversion']) : 'None'; ?></td>
                                        <td><?php echo !empty($adobe['adobe_assigneduser']) ? htmlspecialchars($adobe['adobe_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($adobe['adobe_licensekey']) ? htmlspecialchars($adobe['adobe_licensekey']) : 'None'; ?></td>
                                        <td><?php echo !empty($adobe['adobe_remarks']) ? htmlspecialchars($adobe['adobe_remarks']) : 'None'; ?></td>
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
