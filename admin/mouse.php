<?php
session_start();
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing mouse details from the mouse table
$query = "SELECT * FROM mouse WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$mouse = mysqli_fetch_array($result);

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

// Handle form submission to update mouse details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if mouse exists for this equipment
    if ($mouse) {
        // Update mouse details in the database
        $updateQuery = "UPDATE mouse SET 
                        mouse_assettag = '$assettag', 
                        mouse_brand = '$brand', 
                        mouse_modelnumber = '$modelnumber', 
                        mouse_dateacquired = '$dateacquired', 
                        mouse_deviceage = '$deviceage', 
                        mouse_assigneduser = '$assigneduser',  
                        mouse_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: mouse.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: mouse.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the mouse table
        $insertQuery = "INSERT INTO mouse (equipment_id, mouse_assettag, mouse_brand, mouse_modelnumber, mouse_dateacquired, mouse_deviceage, mouse_assigneduser, mouse_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: mouse.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: mouse.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Mouse Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Mouse Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" value="<?php echo isset($mouse['mouse_assettag']) ? $mouse['mouse_assettag'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="brand" value="<?php echo isset($mouse['mouse_brand']) ? $mouse['mouse_brand'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="modelnumber" value="<?php echo isset($mouse['mouse_modelnumber']) ? $mouse['mouse_modelnumber'] : 'None'; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Date Acquired :</label>
                                <div class="controls">
                                    <input type="date" class="span11" name="dateacquired" value="<?php echo isset($mouse['mouse_dateacquired']) ? $mouse['mouse_dateacquired'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="number" class="span11" name="deviceage" value="<?php echo isset($mouse['mouse_deviceage']) ? $mouse['mouse_deviceage'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" value="<?php echo isset($mouse['mouse_assigneduser']) ? $mouse['mouse_assigneduser'] : 'None'; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="remarks"><?php echo isset($mouse['mouse_remarks']) ? $mouse['mouse_remarks'] : 'None'; ?></textarea>
                                </div>
                            </div>
                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo $alert == 'success' ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "mouse details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update mouse details.";
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

                <!-- Table to display mouse details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Mouse Details</h5>
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
                                        <td><?php echo !empty($mouse['mouse_assettag']) ? htmlspecialchars($mouse['mouse_assettag']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_brand']) ? htmlspecialchars($mouse['mouse_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_modelnumber']) ? htmlspecialchars($mouse['mouse_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_dateacquired']) ? htmlspecialchars($mouse['mouse_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_deviceage']) ? htmlspecialchars($mouse['mouse_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_assigneduser']) ? htmlspecialchars($mouse['mouse_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($mouse['mouse_remarks']) ? htmlspecialchars($mouse['mouse_remarks']) : 'None'; ?></td>
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
