<?php
session_start();
include "header.php";
include "../user/connection.php";

// Get the otherdevice ID from the URL
$od_id = $_GET["od_id"];

// Fetch the existing otherdevice details
$query = "SELECT * FROM otherdevices WHERE od_id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'i', $od_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$otherdevice = mysqli_fetch_array($result);
mysqli_stmt_close($stmt);

// Handling alert messages for success or error
if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]);
} else {
    $alert = null;
}

// Handle form submission to update the otherdevice
if (isset($_POST["submit1"])) {
    // Get the form data and escape special characters
    $od_name = mysqli_real_escape_string($link, $_POST["od_name"]);
    $od_pcname = mysqli_real_escape_string($link, $_POST["od_pcname"]);
    $od_assettag = mysqli_real_escape_string($link, $_POST["od_assettag"]);
    $od_brand = mysqli_real_escape_string($link, $_POST["od_brand"]);
    $od_modelnumber = mysqli_real_escape_string($link, $_POST["od_modelnumber"]);
    $od_deviceage = mysqli_real_escape_string($link, $_POST["od_deviceage"]);
    $od_macaddress = mysqli_real_escape_string($link, $_POST["od_macaddress"]);
    $od_remarks = mysqli_real_escape_string($link, $_POST["od_remarks"]);

    // Fetch previous otherdevice details for comparison
    $old_od_name = $otherdevice['od_name'];
    $old_od_pcname = $otherdevice['od_pcname'];
    $old_od_assettag = $otherdevice['od_assettag'];
    $old_od_brand = $otherdevice['od_brand'];
    $old_od_modelnumber = $otherdevice['od_modelnumber'];
    $old_od_deviceage = $otherdevice['od_deviceage'];
    $old_od_macaddress = $otherdevice['od_macaddress'];
    $old_od_remarks = $otherdevice['od_remarks'];

    // Prepare the update statement
    $query = "UPDATE otherdevices SET 
              od_name = ?, od_pcname = ?, od_assettag = ?, od_brand = ?, 
              od_modelnumber = ?, od_deviceage = ?, od_macaddress = ?, od_remarks = ? 
              WHERE od_id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($link, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssssssi", 
    $od_name, $od_pcname, $od_assettag, $od_brand, 
    $od_modelnumber, $od_deviceage, $od_macaddress, $od_remarks, $od_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Construct the log action for specific fields updated
        $log_action = "Updated otherdevice ($od_name): ";

        // Compare each field and log the change if it differs
        if ($old_od_name !== $od_name) $log_action .= "Device Name: $old_od_name → $od_name, ";
        if ($old_od_pcname !== $od_pcname) $log_action .= "PC Name: $old_od_pcname → $od_pcname, ";
        if ($old_od_assettag !== $od_assettag) $log_action .= "Asset Tag: $old_od_assettag → $od_assettag, ";
        if ($old_od_brand !== $od_brand) $log_action .= "Brand: $old_od_brand → $od_brand, ";
        if ($old_od_modelnumber !== $od_modelnumber) $log_action .= "Model Number: $old_od_modelnumber → $od_modelnumber, ";
        if ($old_od_deviceage !== $od_deviceage) $log_action .= "Device Age: $old_od_deviceage → $od_deviceage, ";
        if ($old_od_macaddress !== $od_macaddress) $log_action .= "MAC Address: $old_od_macaddress → $od_macaddress, ";
        if ($old_od_remarks !== $od_remarks) $log_action .= "Remarks: $old_od_remarks → $od_remarks, ";

        // Trim any trailing comma and space
        $log_action = rtrim($log_action, ", ");

        // Insert log into the database with date/time
        $log_query = "INSERT INTO logs (action, date_added) VALUES ('$log_action', NOW())";
        mysqli_query($link, $log_query);

        $_SESSION["alert"] = "success";
        header("Location: edit_otherdevices.php?od_id=$od_id");
        exit();
    } else {
        $_SESSION["alert"] = "error";
        header("Location: edit_otherdevices.php?od_id=$od_id");
        exit();
    }
}
?>

<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> Edit Other Device</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <span class="icon"><i class="icon-align-justify"></i></span>
                        <h5>Edit Other Device</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">Device Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="od_name" value="<?php echo $otherdevice['od_name']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">PC Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="od_pcname" value="<?php echo $otherdevice['od_pcname']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Asset Tag :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="od_assettag" value="<?php echo $otherdevice['od_assettag']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Brand :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="od_brand" value="<?php echo $otherdevice['od_brand']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Model Number :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="od_modelnumber" value="<?php echo $otherdevice['od_modelnumber']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Device Age :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="od_deviceage" value="<?php echo $otherdevice['od_deviceage']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">MAC Address :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="od_macaddress" value="<?php echo $otherdevice['od_macaddress']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                    <textarea class="span11" name="od_remarks"><?php echo $otherdevice['od_remarks']; ?></textarea>
                                </div>
                            </div>

                            <!-- Alert Display -->
                            <?php if ($alert == "error") { ?>
                                <div class="alert alert-danger">
                                    Failed to update the device.
                                </div>
                            <?php } elseif ($alert == "success") { ?>
                                <div class="alert alert-success">
                                    Device updated successfully!
                                </div>
                            <?php } ?>

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

<?php include "footer.php"; ?>
