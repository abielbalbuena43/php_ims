<?php
ob_start(); // Start output buffering
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the device ID from the URL
$device_id = isset($_GET['od_id']) ? $_GET['od_id'] : '';

// Fetch the existing device details based on device_id
$query = "SELECT * FROM otherdevices WHERE device_id = '$device_id'";
$result = mysqli_query($link, $query);
$device = mysqli_fetch_assoc($result);

if (!$device) {
    // If no device found, redirect to the devices list page
    header("Location: otherdevices.php");
    exit();
}

// Handle form submission to update device details
if (isset($_POST["submit1"])) {
    // Get the form data
    $device_assettag = mysqli_real_escape_string($link, $_POST["device_assettag"]);
    $device_department = mysqli_real_escape_string($link, $_POST["device_department"]);
    $device_type = mysqli_real_escape_string($link, $_POST["device_type"]);
    $device_name = mysqli_real_escape_string($link, $_POST["device_name"]);
    $device_brand = mysqli_real_escape_string($link, $_POST["device_brand"]);
    $device_modelnumber = mysqli_real_escape_string($link, $_POST["device_modelnumber"]);
    $device_serialnumber = mysqli_real_escape_string($link, $_POST["device_serialnumber"]);
    $device_deviceage = mysqli_real_escape_string($link, $_POST["device_deviceage"]);
    $device_pcname = mysqli_real_escape_string($link, $_POST["device_pcname"]);
    $device_macaddress = mysqli_real_escape_string($link, $_POST["device_macaddress"]);
    $device_remarks = mysqli_real_escape_string($link, $_POST["device_remarks"]);


    // Fetch old device data before update for logging changes
    $old_device_data = $device; // Already fetched above

    // Update the device details in the database
    $update_query = "UPDATE otherdevices SET 
                        device_assettag = '$device_assettag',
                        device_department = '$device_department',
                        device_type = '$device_type',
                        device_name = '$device_name', 
                        device_brand = '$device_brand', 
                        device_modelnumber = '$device_modelnumber',
                        device_serialnumber = '$device_serialnumber',
                        device_deviceage = '$device_deviceage', 
                        device_pcname = '$device_pcname',
                        device_macaddress = '$device_macaddress', 
                        device_remarks = '$device_remarks', 
                        device_dateedited = NOW() 
                    WHERE device_id = '$device_id'";

    if (mysqli_query($link, $update_query)) {
        // Log the changes made to the device
        $log_action = "Updated Device for (ID: $device_id): ";

        // Prepare to track changes
        $changes = [];

        // Compare old and new data, log changes
        if ($old_device_data['device_assettag'] !== $device_assettag) {
            $changes[] = "Device Asset Tag: {$old_device_data['device_type']} → $device_assettag";
        }
        if ($old_device_data['device_department'] !== $device_department) {
            $changes[] = "Device Department: {$old_device_data['device_department']} → $device_department";
        }
        if ($old_device_data['device_type'] !== $device_type) {
            $changes[] = "Device Type: {$old_device_data['device_type']} → $device_type";
        }
        if ($old_device_data['device_name'] !== $device_name) {
            $changes[] = "Device Name: {$old_device_data['device_name']} → $device_name";
        }
        if ($old_device_data['device_brand'] !== $device_brand) {
            $changes[] = "Brand: {$old_device_data['device_brand']} → $device_brand";
        }
        if ($old_device_data['device_modelnumber'] !== $device_modelnumber) {
            $changes[] = "Model Number: {$old_device_data['device_modelnumber']} → $device_modelnumber";
        }
        if ($old_device_data['device_serialnumber'] !== $device_serialnumber) {
            $changes[] = "Model Number: {$old_device_data['device_serialnumber']} → $device_serialnumber";
        }
        if ($old_device_data['device_deviceage'] !== $device_deviceage) {
            $changes[] = "Device Age: {$old_device_data['device_deviceage']} → $device_deviceage";
        }
        if ($old_device_data['device_pcname'] !== $device_pcname) {
            $changes[] = "PC Name: {$old_device_data['device_pcname']} → $device_pcname";
        }
        if ($old_device_data['device_macaddress'] !== $device_macaddress) {
            $changes[] = "MAC Address: {$old_device_data['device_macaddress']} → $device_macaddress";
        }
        if ($old_device_data['device_remarks'] !== $device_remarks) {
            $changes[] = "Remarks: {$old_device_data['device_remarks']} → $device_remarks";
        }

        // If there are changes, log them
        if (!empty($changes)) {
            $log_action .= implode(", ", $changes);

            // Insert log into the database without the date/time
            $log_query = "INSERT INTO logs (user_id, action) VALUES ('" . $_SESSION['user_id'] . "', '$log_action')";
            mysqli_query($link, $log_query);
        }

        $_SESSION["alert"] = "success";
        header("Location: edit_otherdevices.php?od_id=$device_id");
        exit();
    } else {
        $_SESSION["alert"] = "error";
        header("Location: edit_otherdevices.php?od_id=$device_id");
        exit();
    }
}

// Handling alert messages
if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]);
} else {
    $alert = null;
}

?>
<!-- main-container-part -->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="otherdevices.php" class="tip-bottom"><i class="icon-home"></i> Edit Device</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Device</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag:</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assettag" placeholder="None" 
                                        value="<?php echo isset($device['device_id'], $device['device_department'], $device['device_type']) 
                                            ? htmlspecialchars(strtoupper($device['device_department']) . '-' . $device['device_type'] . '-' . $device['device_id']) 
                                            : 'NOT YET SET'; ?>" readonly />
                                </div>
                            </div>
                            
                            <!-- Department Selection -->
                            <div class="control-group">
                                <label class="control-label">Department :</label>
                                <div class="controls">
                                    <select name="device_department" class="span11" required>
                                        <option value="" disabled>Select Department</option>
                                        <?php
                                        $departments = [
                                            "ACFN" => "Accounting & Finance (ACFN)",
                                            "ADVT" => "Advertising (ADVT)",
                                            "CIRC" => "Circulation (CIRC)",
                                            "EDTN" => "Editorial-News (EDTN)",
                                            "EDTB" => "Editorial-Business (EDTB)",
                                            "HRAD" => "HRAD (HRAD)",
                                            "MIS" => "Management Information System (MIS)",
                                            "OPER" => "Operations (OPER)",
                                            "SLSM" => "Sales and Marketing (SLSM)"
                                        ];
                                        foreach ($departments as $key => $value) {
                                            $selected = isset($device['device_department']) && $device['device_department'] == $key ? 'selected' : '';
                                            echo "<option value='$key' $selected>$value</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Device Type Selection -->
                            <div class="control-group">
                                <label class="control-label">Device Type :</label>
                                <div class="controls">
                                    <select name="device_type" class="span11" required>
                                        <option value="">Select Device Type</option>
                                        <?php
                                        $device_types = [
                                            "NAS" => "Network Attached Storage (NAS)",
                                            "STRG" => "External Storage & HDDs (STRG)",
                                            "SRVR" => "Server (SRVR)",
                                            "RTR" => "Router (RTR)",
                                            "SWTC" => "Network Switches (SWTC)",
                                            "TSTR" => "Network Tester (TSTR)",
                                            "TONE" => "Tone Tracer (TONE)",
                                            "WIFI" => "WIFI Card (WIFI)",
                                            "LAN" => "LAN Card (LAN)"
                                        ];
                                        foreach ($device_types as $key => $value) {
                                            $selected = isset($device['device_type']) && $device['device_type'] == $key ? 'selected' : '';
                                            echo "<option value='$key' $selected>$value</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Input Fields -->
                            <?php
                            $fields = [
                                'device_name' => 'Device Name',
                                'device_brand' => 'Brand',
                                'device_modelnumber' => 'Model Number',
                                'device_serialnumber' => 'Serial Number',
                                'device_deviceage' => 'Device Age',
                                'device_pcname' => 'PC Name',
                                'device_macaddress' => 'MAC Address'
                            ];
                            foreach ($fields as $name => $placeholder) {
                                $value = isset($device[$name]) ? htmlspecialchars($device[$name]) : '';
                                echo "<div class='control-group'>
                                    <label class='control-label'>$placeholder :</label>
                                    <div class='controls'>
                                        <input type='text' class='span11' placeholder='$placeholder' name='$name' value='$value' />
                                    </div>
                                </div>";
                            }
                            ?>
                            
                            <!-- Remarks -->
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <select name="device_remarks" class="span11" required>
                                        <option value="" disabled <?php echo empty($device['device_remarks']) ? 'selected' : ''; ?>>Select Remark</option>
                                        <option value="Available" <?php echo (isset($device['device_remarks']) && $device['device_remarks'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                        <option value="In Use" <?php echo (isset($device['device_remarks']) && $device['device_remarks'] == 'In Use') ? 'selected' : ''; ?>>In Use</option>
                                        <option value="Defective" <?php echo (isset($device['device_remarks']) && $device['device_remarks'] == 'Defective') ? 'selected' : ''; ?>>Defective</option>
                                        <option value="For Repair" <?php echo (isset($device['device_remarks']) && $device['device_remarks'] == 'For Repair') ? 'selected' : ''; ?>>For Repair</option>
                                        <option value="Under Repair" <?php echo (isset($device['device_remarks']) && $device['device_remarks'] == 'Under Repair') ? 'selected' : ''; ?>>Under Repair</option>
                                        <option value="For Disposal" <?php echo (isset($device['device_remarks']) && $device['device_remarks'] == 'For Disposal') ? 'selected' : ''; ?>>For Disposal</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Alert Display -->
                            <?php if (isset($alert)) {
                                if ($alert == "error") { ?>
                                    <div class="alert alert-danger" style="margin-top: 20px;">
                                        Failed to update device.
                                    </div>
                                <?php } elseif ($alert == "success") { ?>
                                    <div class="alert alert-success" style="margin-top: 20px;">
                                        Device updated successfully!
                                    </div>
                                <?php }
                            } ?>
                            
                            <!-- Form Actions -->
                            <div class="form-actions">
                                <button type="submit" name="submit1" class="btn btn-success">Save Changes</button>
                                <a href="otherdevices.php" class="btn">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--end-main-container-part-->

<?php
ob_end_flush(); // Send output buffer
include "footer.php";
?>



