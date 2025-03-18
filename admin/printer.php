<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch existing printer details from the printer table
$query = "SELECT * FROM printer WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$printer = mysqli_fetch_array($result);

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
if (isset($_POST["delete_printer"])) {
    $printer_id = $_POST["printer_id"];

    $deleteQuery = "DELETE FROM printer WHERE printer_id = $printer_id";

    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION["alert"] = "deleted";
        header("Location: printer.php?equipment_id=$equipment_id"); // Redirect after deletion
        exit();
    } else {
        $_SESSION["alert"] = "delete_error";
        header("Location: printer.php?equipment_id=$equipment_id");
        exit();
    }
}

// Handle form submission to update printer details
if (isset($_POST["submit"])) {
    // Get the form data and escape special characters
    $assettag = mysqli_real_escape_string($link, $_POST["assettag"]);
    $brand = mysqli_real_escape_string($link, $_POST["brand"]);
    $modelnumber = mysqli_real_escape_string($link, $_POST["modelnumber"]);
    $dateacquired = mysqli_real_escape_string($link, $_POST["dateacquired"]);
    $deviceage = mysqli_real_escape_string($link, $_POST["deviceage"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $remarks = mysqli_real_escape_string($link, $_POST["remarks"]);

    // Check if printer exists for this equipment
    if ($printer) {
        // Update printer details in the database
        $updateQuery = "UPDATE printer SET 
                        printer_assettag = '$assettag', 
                        printer_brand = '$brand', 
                        printer_modelnumber = '$modelnumber', 
                        printer_dateacquired = '$dateacquired', 
                        printer_deviceage = '$deviceage', 
                        printer_assigneduser = '$assigneduser', 
                        printer_remarks = '$remarks' 
                        WHERE equipment_id = $equipment_id";

        if (mysqli_query($link, $updateQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: printer.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: printer.php?equipment_id=$equipment_id");
            exit();
        }
    } else {
        // If no record exists, create a new record in the printer table
        $insertQuery = "INSERT INTO printer (equipment_id, printer_assettag, printer_brand, printer_modelnumber, printer_dateacquired, printer_deviceage, printer_assigneduser, printer_remarks) 
                        VALUES ($equipment_id, '$assettag', '$brand', '$modelnumber', '$dateacquired', '$deviceage', '$assigneduser', '$remarks')";

        if (mysqli_query($link, $insertQuery)) {
            $_SESSION["alert"] = "success";
            header("Location: printer.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
            exit();
        } else {
            $_SESSION["alert"] = "error";
            header("Location: printer.php?equipment_id=$equipment_id");
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
                <i class="icon-home"></i> Edit Printer Details
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Printer Details for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Form to edit printer details -->
                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None" 
                                        value="<?php
                                            if (isset($printer['printer_id']) && isset($equipment['department'])) {
                                                echo strtoupper($equipment['department']) . '-KEYB-' . $printer['printer_id'];
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
                                    value="<?php echo isset($printer['printer_brand']) ? $printer['printer_brand'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Model Number :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="modelnumber" 
                                    placeholder="None" 
                                    value="<?php echo isset($printer['printer_modelnumber']) ? $printer['printer_modelnumber'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Date Acquired :</label>
                            <div class="controls">
                                <input type="date" class="span11" name="dateacquired" 
                                    placeholder="None" 
                                    value="<?php echo isset($printer['printer_dateacquired']) ? $printer['printer_dateacquired'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Device Age :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="deviceage" 
                                    placeholder="None" 
                                    value="<?php echo isset($printer['printer_deviceage']) ? $printer['printer_deviceage'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Assigned User :</label>
                            <div class="controls">
                                <input type="text" class="span11" name="assigneduser" 
                                    placeholder="None" 
                                    value="<?php echo isset($printer['printer_assigneduser']) ? $printer['printer_assigneduser'] : ''; ?>" />
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Remarks :</label>
                            <div class="controls">
                                <textarea class="span11" name="remarks" placeholder="None"><?php echo isset($printer['printer_remarks']) ? $printer['printer_remarks'] : ''; ?></textarea>
                            </div>
                        </div>

                            <!-- Success/Failure Alert -->
                            <?php if (isset($alert)) { ?>
                                <div class="alert <?php echo ($alert == 'success') ? 'alert-success' : 'alert-danger'; ?>">
                                    <?php 
                                        if ($alert == "success") {
                                            echo "printer details updated successfully!";
                                        } elseif ($alert == "error") {
                                            echo "Failed to update printer details.";
                                        } elseif ($alert == "deleted") {
                                            echo "printer deleted!";
                                        }
                                    ?>
                                </div>
                            <?php } ?>
                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-success">Save Changes</button>
                                <a href="peripherals.php" class="btn">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table to display printer details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Printer Details</h5>
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
                                                if (isset($printer['printer_id']) && isset($equipment['department'])) {
                                                    echo strtoupper($equipment['department']) . '-KEYB-' . $printer['printer_id'];
                                                } else {
                                                    echo 'NOT YET SET';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo !empty($printer['printer_brand']) ? htmlspecialchars($printer['printer_brand']) : 'None'; ?></td>
                                        <td><?php echo !empty($printer['printer_modelnumber']) ? htmlspecialchars($printer['printer_modelnumber']) : 'None'; ?></td>
                                        <td><?php echo !empty($printer['printer_dateacquired']) ? htmlspecialchars($printer['printer_dateacquired']) : 'None'; ?></td>
                                        <td><?php echo !empty($printer['printer_deviceage']) ? htmlspecialchars($printer['printer_deviceage']) : 'None'; ?></td>
                                        <td><?php echo !empty($printer['printer_assigneduser']) ? htmlspecialchars($printer['printer_assigneduser']) : 'None'; ?></td>
                                        <td><?php echo !empty($printer['printer_remarks']) ? htmlspecialchars($printer['printer_remarks']) : 'None'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Delete Button (Appears at the End, Only If a printer Exists) -->
                <?php if ($printer): ?>
                    <form method="POST" style="display:inline; margin-top: 10px;" 
                          onsubmit="return confirm('Are you sure you want to delete this printer?');">
                        <input type="hidden" name="printer_id" value="<?php echo $printer['printer_id']; ?>">
                        <button type="submit" name="delete_printer" class="btn btn-danger">Delete</button>
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
