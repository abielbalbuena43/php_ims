<?php
session_start();
include "session_verification.php";
include "header.php";
include "../user/connection.php";

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
    $device_type = mysqli_real_escape_string($link, $_POST["device_type"]);
    $device_name = mysqli_real_escape_string($link, $_POST["device_name"]);
    $device_assettag = mysqli_real_escape_string($link, $_POST["device_assettag"]);
    $device_brand = mysqli_real_escape_string($link, $_POST["device_brand"]);
    $device_modelnumber = mysqli_real_escape_string($link, $_POST["device_modelnumber"]);
    $device_deviceage = mysqli_real_escape_string($link, $_POST["device_deviceage"]);
    $device_pcname = mysqli_real_escape_string($link, $_POST["device_pcname"]);
    $device_macaddress = mysqli_real_escape_string($link, $_POST["device_macaddress"]);
    $device_remarks = mysqli_real_escape_string($link, $_POST["device_remarks"]);


    // Fetch old device data before update for logging changes
    $old_device_data = $device; // Already fetched above

    // Update the device details in the database
    $update_query = "UPDATE otherdevices SET 
                        device_type = '$device_type', 
                        device_name = '$device_name', 
                        device_assettag = '$device_assettag',
                        device_brand = '$device_brand', 
                        device_modelnumber = '$device_modelnumber', 
                        device_deviceage = '$device_deviceage', 
                        device_pcname = '$device_pcname',
                        device_macaddress = '$device_macaddress', 
                        device_remarks = '$device_remarks', 
                        device_dateedited = NOW() 
                    WHERE device_id = '$device_id'";

    if (mysqli_query($link, $update_query)) {
        // Log the changes made to the device
        $log_action = "Updated Device: $device_type - $device_name";

        // Prepare to track changes
        $changes = [];

        // Compare old and new data, log changes
        if ($old_device_data['device_type'] !== $device_type) {
            $changes[] = "Device Type: {$old_device_data['device_type']} → $device_type";
        }
        if ($old_device_data['device_name'] !== $device_name) {
            $changes[] = "Device Name: {$old_device_data['device_name']} → $device_name";
        }
        if ($old_device_data['device_assettag'] !== $device_assettag) {
            $changes[] = "Asset Tag: {$old_device_data['device_assettag']} → $device_assettag";
        }
        if ($old_device_data['device_brand'] !== $device_brand) {
            $changes[] = "Brand: {$old_device_data['device_brand']} → $device_brand";
        }
        if ($old_device_data['device_modelnumber'] !== $device_modelnumber) {
            $changes[] = "Model Number: {$old_device_data['device_modelnumber']} → $device_modelnumber";
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
            $log_action .= ": " . implode(", ", $changes);
            $log_query = "INSERT INTO logs (action, date_edited) VALUES ('$log_action', NOW())";
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

<!--main-container-part-->
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
                            <!-- Device Type -->
                            <div class="control-group">
                                <label class="control-label">Device Type :</label>
                                <div class="controls">
                                    <select name="device_type" class="span11" required>
                                        <option value="">Select Device Type</option>
                                        <option value="NAS" <?php echo $device['device_type'] == 'NAS' ? 'selected' : ''; ?>>NAS</option>
                                        <option value="External Storage & HDDs" <?php echo $device['device_type'] == 'External Storage & HDDs' ? 'selected' : ''; ?>>External Storage & HDDs</option>
                                        <option value="Server" <?php echo $device['device_type'] == 'Server' ? 'selected' : ''; ?>>Server</option>
                                        <option value="Router" <?php echo $device['device_type'] == 'Router' ? 'selected' : ''; ?>>Router</option>
                                        <option value="Network Switches" <?php echo $device['device_type'] == 'Network Switches' ? 'selected' : ''; ?>>Network Switches</option>
                                        <option value="Network Tester" <?php echo $device['device_type'] == 'Network Tester' ? 'selected' : ''; ?>>Network Tester</option>
                                        <option value="Tone Tracer" <?php echo $device['device_type'] == 'Tone Tracer' ? 'selected' : ''; ?>>Tone Tracer</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Device Name -->
                            <div class="control-group">
                                <label class="control-label">Device Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Device Name" name="device_name" value="<?php echo $device['device_name']; ?>" required />
                                </div>
                            </div>

                            <!-- Asset Tag -->
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Asset Tag" name="device_assettag" value="<?php echo $device['device_assettag']; ?>" />
                                </div>
                            </div>

                            <!-- Brand -->
                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Brand" name="device_brand" value="<?php echo $device['device_brand']; ?>" />
                                </div>
                            </div>

                            <!-- Model Number -->
                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Model Number" name="device_modelnumber" value="<?php echo $device['device_modelnumber']; ?>" />
                                </div>
                            </div>

                            <!-- Device Age -->
                            <div class="control-group">
                                <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Device Age" name="device_deviceage" value="<?php echo $device['device_deviceage']; ?>" />
                                </div>
                            </div>

                            <!-- PC Name -->
                            <div class="control-group">
                                <label class="control-label">PC Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="PC Name" name="device_pcname" value="<?php echo $device['device_pcname']; ?>" />
                                </div>
                            </div>

                            <!-- MAC Address -->
                            <div class="control-group">
                                <label class="control-label">MAC Address :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="MAC Address" name="device_macaddress" value="<?php echo $device['device_macaddress']; ?>" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="device_remarks"><?php echo isset($keyboard['device_remarks']) ? $keyboard['device_remarks'] : 'None'; ?></textarea>
                                </div>
                            </div>

                            <!-- Alert Display -->
                            <?php if ($alert == "error") { ?>
                                <div class="alert alert-danger" style="margin-top: 20px;">
                                    Failed to update device.
                                </div>
                            <?php } elseif ($alert == "success") { ?>
                                <div class="alert alert-success" style="margin-top: 20px;">
                                    Device updated successfully!
                                </div>
                            <?php } ?>

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
include "footer.php";
?>
