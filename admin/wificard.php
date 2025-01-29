<?php
session_start();
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing wificard details from the wificard table
$query = "SELECT * FROM wificard WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$wificard = mysqli_fetch_array($result);

// Fetch equipment details from the new_equipment table
$equipmentQuery = "SELECT * FROM new_equipment WHERE equipment_id = $equipment_id";
$equipmentResult = mysqli_query($link, $equipmentQuery);
$equipment = mysqli_fetch_array($equipmentResult);

// Handling alert messages for success or error
if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]);
} else {
    $alert = null;
}

// Handle form submission to update wificard details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if wificard exists for this equipment
    if ($wificard) {
        // Update wificard details in the database
        $updateQuery = "UPDATE wificard SET 
                        wificard_assettag = '$assettag', 
                        wificard_brand = '$brand', 
                        wificard_modelnumber = '$modelnumber', 
                        wificard_dateacquired = '$dateacquired', 
                        wificard_deviceage = '$deviceage', 
                        wificard_assigneduser = '$assigneduser',  
                        wificard_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: wificard.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: wificard.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the wificard table
        $insertQuery = "INSERT INTO wificard (equipment_id, wificard_assettag, wificard_brand, wificard_modelnumber, wificard_dateacquired, wificard_deviceage, wificard_assigneduser, wificard_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: wificard.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: wificard.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit WIFI Card Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit WIFI Card Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" value="<?php echo isset($wificard['wificard_assettag']) ? $wificard['wificard_assettag'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="brand" value="<?php echo isset($wificard['wificard_brand']) ? $wificard['wificard_brand'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" value="<?php echo isset($wificard['wificard_modelnumber']) ? $wificard['wificard_modelnumber'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Date Acquired :</label>
                                <div class="controls">
                                    <input type="date" class="span11" name="dateacquired" value="<?php echo isset($wificard['wificard_dateacquired']) ? $wificard['wificard_dateacquired'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="number" class="span11" name="deviceage" value="<?php echo isset($wificard['wificard_deviceage']) ? $wificard['wificard_deviceage'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" value="<?php echo isset($wificard['wificard_assigneduser']) ? $wificard['wificard_assigneduser'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks"><?php echo isset($wificard['wificard_remarks']) ? $wificard['wificard_remarks'] : 'None'; ?></textarea>
                                </div>
                            </div>
                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo $alert == 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "WIFI Card details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update WIFI Card details.";
                                        }
                                    ?>
                                </div>
                            <?php } ?>
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-success">Save Changes</button>
                                <a href="equipment_list.php" class="btn">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table to display WIFI Card details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>WIFI Card Details</h5>
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
                                        <th>Date Acquired</th>
                                        <th>Device Age</th>
                                        <th>Assigned User</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                        <td><?php echo !empty($wificard['wificard_assettag']) ? htmlspecialchars($wificard['wificard_assettag']) : 'None'; ?></td>
                                        <td><?php echo !empty($wificard['wificard_brand']) ? htmlspecialchars($wificard['wificard_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($wificard['wificard_modelnumber']) ? htmlspecialchars($wificard['wificard_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($wificard['wificard_dateacquired']) ? htmlspecialchars($wificard['wificard_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($wificard['wificard_deviceage']) ? htmlspecialchars($wificard['wificard_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($wificard['wificard_assigneduser']) ? htmlspecialchars($wificard['wificard_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($wificard['wificard_remarks']) ? htmlspecialchars($wificard['wificard_remarks']) : 'None'; ?></td>
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
