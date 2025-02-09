<?php
session_start();
include "session_verification.php";
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing motherboard details from the motherboard table
$query = "SELECT * FROM motherboard WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$motherboard = mysqli_fetch_array($result);

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

// Handle form submission to update motherboard details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $ramslot = mysqli_real_escape_string($link, $_POST["ramslot"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $computername = mysqli_real_escape_string($link, $_POST["computername"]);
    $macaddress = mysqli_real_escape_string($link, $_POST["macaddress"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if motherboard exists for this equipment
    if ($motherboard) {
        // Update motherboard details in the database
        $updateQuery = "UPDATE motherboard SET 
                        mobo_assettag = '$assettag', 
                        mobo_brand = '$brand', 
                        mobo_modelnumber = '$modelnumber', 
                        mobo_ramslot = '$ramslot', 
                        mobo_dateacquired = '$dateacquired', 
                        mobo_deviceage = '$deviceage', 
                        mobo_assigneduser = '$assigneduser', 
                        mobo_computername = '$computername', 
                        mobo_macaddress = '$macaddress', 
                        mobo_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: motherboard.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: motherboard.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the motherboard table
        $insertQuery = "INSERT INTO motherboard (equipment_id, mobo_assettag, mobo_brand, mobo_modelnumber, mobo_ramslot, mobo_dateacquired, mobo_deviceage, mobo_assigneduser, mobo_computername, mobo_macaddress, mobo_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$ramslot', '$dateacquired', '$deviceage', '$assigneduser', '$computername', '$macaddress', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: motherboard.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: motherboard.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit Motherboard Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Motherboard Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Form to edit motherboard details -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" 
                                        placeholder="None" 
                                        value="<?php echo isset($motherboard['mobo_assettag']) ? $motherboard['mobo_assettag'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="brand" 
                                        placeholder="None" 
                                        value="<?php echo isset($motherboard['mobo_brand']) ? $motherboard['mobo_brand'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" 
                                        placeholder="None" 
                                        value="<?php echo isset($motherboard['mobo_modelnumber']) ? $motherboard['mobo_modelnumber'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">RAM Slot :</label>
                                <div class="controls">
                                    <input type="number" class="span11" name="ramslot" 
                                        placeholder="None" 
                                        value="<?php echo isset($motherboard['mobo_ramslot']) ? $motherboard['mobo_ramslot'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Date Acquired :</label>
                                <div class="controls">
                                    <input type="date" class="span11" name="dateacquired" 
                                        placeholder="None" 
                                        value="<?php echo isset($motherboard['mobo_dateacquired']) ? $motherboard['mobo_dateacquired'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="deviceage" 
                                        placeholder="None" 
                                        value="<?php echo isset($motherboard['mobo_deviceage']) ? $motherboard['mobo_deviceage'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" 
                                        placeholder="None" 
                                        value="<?php echo isset($motherboard['mobo_assigneduser']) ? $motherboard['mobo_assigneduser'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Computer Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="computername" 
                                        placeholder="None" 
                                        value="<?php echo isset($motherboard['mobo_computername']) ? $motherboard['mobo_computername'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">MAC Address :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="macaddress" 
                                        placeholder="None" 
                                        value="<?php echo isset($motherboard['mobo_macaddress']) ? $motherboard['mobo_macaddress'] : ''; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($motherboard['mobo_remarks']) ? $motherboard['mobo_remarks'] : ''; ?></textarea>
                                </div>
                            </div>

                            <!-- Success/Failure Alert -->
                                <?php if (isset($alert)) { ?>
                                    <div class="alert <?php echo $alert == 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                        <?php 
                                            if ($alert == "success") {
                                                echo "Motherboard details updated successfully!";
                                            } elseif ($alert == "error") {
                                                echo "Failed to update motherboard details.";
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
                

                <!-- Table to display motherboard details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Motherboard Details</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <!-- Wrapper div for horizontal scrolling -->
                        <div style="overflow-x: auto;">
                            <table class="table table-bordered table-striped" style="min-width: 1200px;">
                                <thead>
                                    <tr>
                                        <th>PC Name</th>
                                        <th>Asset Tag</th>
                                        <th>Brand</th>
                                        <th>Model Number</th>
                                        <th>RAM Slot</th>
                                        <th>Date Acquired</th>
                                        <th>Device Age</th>
                                        <th>Assigned User</th>
                                        <th>Computer Name</th>
                                        <th>MAC Address</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                        <td><?php echo !empty($motherboard['mobo_assettag']) ? htmlspecialchars($motherboard['mobo_assettag']) : 'None'; ?></td>
                                        <td><?php echo !empty($motherboard['mobo_brand']) ? htmlspecialchars($motherboard['mobo_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($motherboard['mobo_modelnumber']) ? htmlspecialchars($motherboard['mobo_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($motherboard['mobo_ramslot']) ? htmlspecialchars($motherboard['mobo_ramslot']) : 'None'; ?></td>
                                        <td><?php echo !empty($motherboard['mobo_dateacquired']) ? htmlspecialchars($motherboard['mobo_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($motherboard['mobo_deviceage']) ? htmlspecialchars($motherboard['mobo_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($motherboard['mobo_assigneduser']) ? htmlspecialchars($motherboard['mobo_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($motherboard['mobo_computername']) ? htmlspecialchars($motherboard['mobo_computername']) : 'None'; ?></td>
                                        <td><?php echo !empty($motherboard['mobo_macaddress']) ? htmlspecialchars($motherboard['mobo_macaddress']) : 'None'; ?></td>
                                        <td><?php echo !empty($motherboard['mobo_remarks']) ? htmlspecialchars($motherboard['mobo_remarks']) : 'None'; ?></td>
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
