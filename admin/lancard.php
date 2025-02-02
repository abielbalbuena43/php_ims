<?php
session_start();
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing lancard details from the lancard table
$query = "SELECT * FROM lancard WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$lancard = mysqli_fetch_array($result);

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

// Handle form submission to update lancard details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $macaddress = mysqli_real_escape_string($link, $_POST["macaddress"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if lancard exists for this equipment
    if ($lancard) {
        // Update lancard details in the database
        $updateQuery = "UPDATE lancard SET 
                        lancard_assettag = '$assettag', 
                        lancard_brand = '$brand', 
                        lancard_modelnumber = '$modelnumber', 
                        lancard_dateacquired = '$dateacquired', 
                        lancard_deviceage = '$deviceage', 
                        lancard_assigneduser = '$assigneduser', 
                        lancard_macaddress = '$macaddress', 
                        lancard_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: lancard.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: lancard.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the lancard table
        $insertQuery = "INSERT INTO lancard (equipment_id, lancard_assettag, lancard_brand, lancard_modelnumber, lancard_dateacquired, lancard_deviceage, lancard_assigneduser, lancard_macaddress, lancard_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$dateacquired', '$deviceage', '$assigneduser', '$macaddress', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: lancard.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: lancard.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit LAN Card Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit LAN Card Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" value="<?php echo isset($lancard['lancard_assettag']) ? $lancard['lancard_assettag'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="brand" value="<?php echo isset($lancard['lancard_brand']) ? $lancard['lancard_brand'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" value="<?php echo isset($lancard['lancard_modelnumber']) ? $lancard['lancard_modelnumber'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Date Acquired :</label>
                                <div class="controls">
                                    <input type="date" class="span11" name="dateacquired" value="<?php echo isset($lancard['lancard_dateacquired']) ? $lancard['lancard_dateacquired'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div> <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="deviceage" 
                                        value="<?php echo isset($lancard['lancard_deviceage']) ? $lancard['lancard_deviceage'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" value="<?php echo isset($lancard['lancard_assigneduser']) ? $lancard['lancard_assigneduser'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">MAC Address :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="macaddress" value="<?php echo isset($lancard['lancard_macaddress']) ? $lancard['lancard_macaddress'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks"><?php echo isset($lancard['lancard_remarks']) ? $lancard['lancard_remarks'] : 'None'; ?></textarea>
                                </div>
                            </div>
                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo $alert == 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "LAN card details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update LAN card details.";
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

                <!-- Table to display LAN card details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>LAN Card Details</h5>
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
                                        <th>MAC Address</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                        <td><?php echo !empty($lancard['lancard_assettag']) ? htmlspecialchars($lancard['lancard_assettag']) : 'None'; ?></td>
                                        <td><?php echo !empty($lancard['lancard_brand']) ? htmlspecialchars($lancard['lancard_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($lancard['lancard_modelnumber']) ? htmlspecialchars($lancard['lancard_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($lancard['lancard_dateacquired']) ? htmlspecialchars($lancard['lancard_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($lancard['lancard_deviceage']) ? htmlspecialchars($lancard['lancard_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($lancard['lancard_assigneduser']) ? htmlspecialchars($lancard['lancard_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($lancard['lancard_macaddress']) ? htmlspecialchars($lancard['lancard_macaddress']) : 'None'; ?></td>
                                        <td><?php echo !empty($lancard['lancard_remarks']) ? htmlspecialchars($lancard['lancard_remarks']) : 'None'; ?></td>
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
