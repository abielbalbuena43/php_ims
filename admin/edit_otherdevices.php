<?php

// Start session
session_start();

// Include files after the session start
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
                        device_dateedited = NOW() 
                    WHERE device_id = '$device_id'";

    if (mysqli_query($link, $update_query)) {
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
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> Edit Device</a></div>
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
