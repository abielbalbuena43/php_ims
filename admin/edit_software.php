<?php
session_start();
include "session_verification.php";
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch the existing software details for this equipment
$query = "SELECT * FROM software WHERE equipment_id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'i', $equipment_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$software = mysqli_fetch_array($result);
mysqli_stmt_close($stmt);

// Handling alert messages for success or error
if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]);
} else {
    $alert = null;
}

// Fetch equipment details for reference
$equipment_query = "SELECT equipment_id, pcname FROM equipment WHERE equipment_id = ?";
$stmt = mysqli_prepare($link, $equipment_query);
mysqli_stmt_bind_param($stmt, 'i', $equipment_id);
mysqli_stmt_execute($stmt);
$equipment_result = mysqli_stmt_get_result($stmt);
$equipment = mysqli_fetch_assoc($equipment_result);
mysqli_stmt_close($stmt);

// If no equipment is found, redirect
if (!$equipment) {
    $_SESSION["alert"] = "error";
    $_SESSION["error_message"] = "Error: Equipment not found.";
    header("Location: software.php");
    exit();
}

// Handle form submission to update software details
if (isset($_POST["submit"])) {
    // Get form data and sanitize input
    $software_msos = mysqli_real_escape_string($link, $_POST["software_msos"]);
    $software_msoffice = mysqli_real_escape_string($link, $_POST["software_msoffice"]);
    $software_adobe = mysqli_real_escape_string($link, $_POST["software_adobe"]);
    $software_remarks = mysqli_real_escape_string($link, $_POST["software_remarks"]);

    // Fetch previous software data before updating
    $old_data = $software;

    // Prepare log details
    $log_action = "Updated software for equipment ({$equipment['pcname']}): ";
    $changes = [];

    if ($old_data['software_msos'] !== $software_msos) {
        $changes[] = "MS OS: {$old_data['software_msos']} → $software_msos";
    }
    if ($old_data['software_msoffice'] !== $software_msoffice) {
        $changes[] = "MS Office: {$old_data['software_msoffice']} → $software_msoffice";
    }
    if ($old_data['software_adobe'] !== $software_adobe) {
        $changes[] = "Adobe: {$old_data['software_adobe']} → $software_adobe";
    }
    if ($old_data['software_remarks'] !== $software_remarks) {
        $changes[] = "Remarks: {$old_data['software_remarks']} → $software_remarks";
    }

    // If changes exist, log them
    if (!empty($changes)) {
        $log_action .= implode(", ", $changes);
        $log_query = "INSERT INTO logs (action, date_edited) VALUES (?, NOW())";
        $stmt = mysqli_prepare($link, $log_query);
        mysqli_stmt_bind_param($stmt, "s", $log_action);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Check if a software entry exists for this equipment
    if ($software) {
        // Update existing software record
        $update_query = "UPDATE software SET 
                        software_msos = ?, software_msoffice = ?, software_adobe = ?, 
                        software_remarks = ?, software_dateedited = NOW()
                        WHERE equipment_id = ?";
        $stmt = mysqli_prepare($link, $update_query);
        mysqli_stmt_bind_param($stmt, "ssssi", $software_msos, $software_msoffice, $software_adobe, $software_remarks, $equipment_id);
    } else {
        // Insert a new software record for this equipment
        $insert_query = "INSERT INTO software (equipment_id, software_msos, software_msoffice, software_adobe, software_remarks, software_dateadded, software_dateedited) 
                         VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = mysqli_prepare($link, $insert_query);
        mysqli_stmt_bind_param($stmt, "issss", $equipment_id, $software_msos, $software_msoffice, $software_adobe, $software_remarks);
    }

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["alert"] = "success";
        header("Location: edit_software.php?equipment_id=$equipment_id");
        exit();
    } else {
        $_SESSION["alert"] = "error";
        $_SESSION["error_message"] = "Error: " . mysqli_stmt_error($stmt);
        header("Location: edit_software.php?equipment_id=$equipment_id");
        exit();
    }
}
?>

<!-- Main Container -->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">
            <a href="software.php" class="tip-bottom">
                <i class="icon-home"></i> Edit Software
            </a>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Software for <?php echo htmlspecialchars($equipment['pcname']); ?></h5>
                    </div>
                    <div class="widget-content nopadding">

                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">PC Name:</label>
                                <div class="controls">
                                    <input type="text" class="span11" value="<?php echo htmlspecialchars($equipment['pcname']); ?>" disabled />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">MS OS:</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="software_msos" value="<?php echo isset($software['software_msos']) ? htmlspecialchars($software['software_msos']) : ''; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">MS Office:</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="software_msoffice" value="<?php echo isset($software['software_msoffice']) ? htmlspecialchars($software['software_msoffice']) : ''; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Adobe:</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="software_adobe" value="<?php echo isset($software['software_adobe']) ? htmlspecialchars($software['software_adobe']) : ''; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks:</label>
                                <div class="controls">
                                    <textarea class="span11" name="software_remarks"><?php echo isset($software['software_remarks']) ? htmlspecialchars($software['software_remarks']) : ''; ?></textarea>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" name="submit" class="btn btn-success">Save Changes</button>
                                <a href="software.php" class="btn">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Alert Display -->
                <?php if ($alert == "error") { ?>
                                <div class="alert alert-danger">
                                    Failed to update equipment.
                                </div>
                            <?php } elseif ($alert == "success") { ?>
                                <div class="alert alert-success">
                                    Equipment updated successfully!
                                </div>
                            <?php } ?>

                <!-- Display Software Details -->
                <div class="widget-box" style="margin-top: 20px;">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-table"></i> </span>
                        <h5>Software Details</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>PC Name</th>
                                    <th>MS OS</th>
                                    <th>MS Office</th>
                                    <th>Adobe</th>
                                    <th>Date Added</th>
                                    <th>Date Edited</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlspecialchars($equipment['pcname']); ?></td>
                                    <td><?php echo !empty($software['software_msos']) ? htmlspecialchars($software['software_msos']) : 'N/A'; ?></td>
                                    <td><?php echo !empty($software['software_msoffice']) ? htmlspecialchars($software['software_msoffice']) : 'N/A'; ?></td>
                                    <td><?php echo !empty($software['software_adobe']) ? htmlspecialchars($software['software_adobe']) : 'N/A'; ?></td>
                                    <td><?php echo $software['software_dateadded'] ?? 'N/A'; ?></td>
                                    <td><?php echo $software['software_dateedited'] ?? 'N/A'; ?></td>
                                    <td><?php echo !empty($software['software_remarks']) ? htmlspecialchars($software['software_remarks']) : 'N/A'; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- End Main Container -->
<?php
include "footer.php";
?>
