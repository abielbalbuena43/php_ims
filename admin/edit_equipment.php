<?php
session_start();
include "header.php";
include "../user/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch the existing equipment details
$query = "SELECT * FROM new_equipment WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);
$equipment = mysqli_fetch_array($result);

// Handling alert messages for success or error
if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]);
} else {
    $alert = null;
}

// Handle form submission to update the equipment
if (isset($_POST["submit1"])) {
    // Get the form data and escape special characters
    $pcname = mysqli_real_escape_string($link, $_POST["pcname"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $cpu = mysqli_real_escape_string($link, $_POST["cpu"]);
    $motherboard = mysqli_real_escape_string($link, $_POST["motherboard"]);
    $ram = mysqli_real_escape_string($link, $_POST["ram"]);
    $hdd = mysqli_real_escape_string($link, $_POST["hdd"]);
    $ssd = mysqli_real_escape_string($link, $_POST["ssd"]);
    $gpu = mysqli_real_escape_string($link, $_POST["gpu"]);
    $psu = mysqli_real_escape_string($link, $_POST["psu"]);
    $pccase = mysqli_real_escape_string($link, $_POST["pccase"]);
    $monitor = mysqli_real_escape_string($link, $_POST["monitor"]);
    $macaddress = mysqli_real_escape_string($link, $_POST["macaddress"]);
    $osversion = mysqli_real_escape_string($link, $_POST["osversion"]);
    $msversion = mysqli_real_escape_string($link, $_POST["msversion"]);
    $windows_key = mysqli_real_escape_string($link, $_POST["windows_key"]);
    $ms_key = mysqli_real_escape_string($link, $_POST["ms_key"]);

    // Fetch previous equipment details for comparison
    $old_pcname = $equipment['pcname'];
    $old_assigneduser = $equipment['assigneduser'];
    $old_cpu = $equipment['cpu'];
    $old_motherboard = $equipment['motherboard'];
    $old_ram = $equipment['ram'];
    $old_hdd = $equipment['hdd'];
    $old_ssd = $equipment['ssd'];
    $old_gpu = $equipment['gpu'];
    $old_psu = $equipment['psu'];
    $old_pccase = $equipment['pccase'];
    $old_monitor = $equipment['monitor'];
    $old_macaddress = $equipment['macaddress'];
    $old_osversion = $equipment['osversion'];
    $old_msversion = $equipment['msversion'];
    $old_windows_key = $equipment['windows_key'];
    $old_ms_key = $equipment['ms_key'];

    // Prepare the update statement
    $query = "UPDATE new_equipment SET 
              pcname = ?, assigneduser = ?, cpu = ?, motherboard = ?, 
              ram = ?, hdd = ?, ssd = ?, gpu = ?, psu = ?, pccase = ?, 
              monitor = ?, macaddress = ?, osversion = ?, msversion = ?, 
              windows_key = ?, ms_key = ? 
              WHERE equipment_id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($link, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssssssssssssssi", $pcname, $assigneduser, $cpu, $motherboard, $ram, $hdd, $ssd, $gpu, $psu, $pccase, $monitor, $macaddress, $osversion, $msversion, $windows_key, $ms_key, $equipment_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Construct the log action for specific fields updated
        $log_action = "Updated equipment: ";

        // Compare each field and log the change if it differs
        if ($old_pcname !== $pcname) $log_action .= "PC Name: $old_pcname → $pcname, ";
        if ($old_assigneduser !== $assigneduser) $log_action .= "Assigned User: $old_assigneduser → $assigneduser, ";
        if ($old_cpu !== $cpu) $log_action .= "CPU: $old_cpu → $cpu, ";
        if ($old_motherboard !== $motherboard) $log_action .= "Motherboard: $old_motherboard → $motherboard, ";
        if ($old_ram !== $ram) $log_action .= "RAM: $old_ram → $ram, ";
        if ($old_hdd !== $hdd) $log_action .= "HDD: $old_hdd → $hdd, ";
        if ($old_ssd !== $ssd) $log_action .= "SSD: $old_ssd → $ssd, ";
        if ($old_gpu !== $gpu) $log_action .= "GPU: $old_gpu → $gpu, ";
        if ($old_psu !== $psu) $log_action .= "PSU: $old_psu → $psu, ";
        if ($old_pccase !== $pccase) $log_action .= "PC Case: $old_pccase → $pccase, ";
        if ($old_monitor !== $monitor) $log_action .= "Monitor: $old_monitor → $monitor, ";
        if ($old_macaddress !== $macaddress) $log_action .= "MAC Address: $old_macaddress → $macaddress, ";
        if ($old_osversion !== $osversion) $log_action .= "OS Version: $old_osversion → $osversion, ";
        if ($old_msversion !== $msversion) $log_action .= "MS Version: $old_msversion → $msversion, ";
        if ($old_windows_key !== $windows_key) $log_action .= "Windows Key: $old_windows_key → $windows_key, ";
        if ($old_ms_key !== $ms_key) $log_action .= "MS Key: $old_ms_key → $ms_key, ";

        // Trim any trailing comma and space
        $log_action = rtrim($log_action, ", ");

        // Insert log into the database with date/time
        $log_query = "INSERT INTO logs (action, date_added) VALUES ('$log_action', NOW())";
        mysqli_query($link, $log_query);

        $_SESSION["alert"] = "success";
        header("Location: edit_equipment.php?equipment_id=$equipment_id");
        exit();
    } else {
        $_SESSION["alert"] = "error";
        header("Location: edit_equipment.php?equipment_id=$equipment_id");
        exit();
    }
}
?>

<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> Edit Equipment</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Equipment</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">PC Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="pcname" value="<?php echo $equipment['pcname']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" value="<?php echo $equipment['assigneduser']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">CPU :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="cpu" value="<?php echo $equipment['cpu']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Motherboard :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="motherboard" value="<?php echo $equipment['motherboard']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">RAM :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="ram" value="<?php echo $equipment['ram']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">HDD :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="hdd" value="<?php echo $equipment['hdd']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">SSD :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="ssd" value="<?php echo $equipment['ssd']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">GPU :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="gpu" value="<?php echo $equipment['gpu']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">PSU :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="psu" value="<?php echo $equipment['psu']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">PC Case :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="pccase" value="<?php echo $equipment['pccase']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Monitor :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="monitor" value="<?php echo $equipment['monitor']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">MAC Address :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="macaddress" value="<?php echo $equipment['macaddress']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">OS Version :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="osversion" value="<?php echo $equipment['osversion']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">MS Version :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="msversion" value="<?php echo $equipment['msversion']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Windows Product Key :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="windows_key" value="<?php echo $equipment['windows_key']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">MS Product Key :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="ms_key" value="<?php echo $equipment['ms_key']; ?>" />
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

                            <div class="form-actions">
                                <button type="submit" name="submit1" class="btn btn-success">Save Changes</button>
                                <a href="add_new_equipment.php" class="btn">Cancel</a> <!-- Redirects to add_new_equipment.php -->
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
