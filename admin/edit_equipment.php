<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Get the equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

// Fetch the existing equipment details
$query = "SELECT * FROM equipment WHERE equipment_id = $equipment_id";
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
    $department = mysqli_real_escape_string($link, $_POST["department"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    $processor = mysqli_real_escape_string($link, $_POST["processor"]);
    $motherboard = mysqli_real_escape_string($link, $_POST["motherboard"]);
    $ram = mysqli_real_escape_string($link, $_POST["ram"]);
    $hdd = mysqli_real_escape_string($link, $_POST["hdd"]);
    $ssd = mysqli_real_escape_string($link, $_POST["ssd"]);
    $gpu = mysqli_real_escape_string($link, $_POST["gpu"]);
    $psu = mysqli_real_escape_string($link, $_POST["psu"]);
    $pccase = mysqli_real_escape_string($link, $_POST["pccase"]);
    $monitor = mysqli_real_escape_string($link, $_POST["monitor"]);
    $lancard = mysqli_real_escape_string($link, $_POST["lancard"]);
    $wificard = mysqli_real_escape_string($link, $_POST["wificard"]);
    $macaddress = mysqli_real_escape_string($link, $_POST["macaddress"]);
    $osversion = mysqli_real_escape_string($link, $_POST["osversion"]);
    $msversion = mysqli_real_escape_string($link, $_POST["msversion"]);
    $windows_key = mysqli_real_escape_string($link, $_POST["windows_key"]);
    $ms_key = mysqli_real_escape_string($link, $_POST["ms_key"]);
    $equipment_remarks = mysqli_real_escape_string($link, $_POST["equipment_remarks"]);

    // Fetch previous equipment details for comparison
    $old_pcname = $equipment['pcname'];
    $old_department = $equipment['department'];
    $old_assigneduser = $equipment['assigneduser'];
    $old_processor = $equipment['processor'];
    $old_motherboard = $equipment['motherboard'];
    $old_ram = $equipment['ram'];
    $old_hdd = $equipment['hdd'];
    $old_ssd = $equipment['ssd'];
    $old_gpu = $equipment['gpu'];
    $old_psu = $equipment['psu'];
    $old_pccase = $equipment['pccase'];
    $old_monitor = $equipment['monitor'];
    $old_lancard = $equipment['lancard'];
    $old_wificard = $equipment['wificard'];
    $old_macaddress = $equipment['macaddress'];
    $old_osversion = $equipment['osversion'];
    $old_msversion = $equipment['msversion'];
    $old_windows_key = $equipment['windows_key'];
    $old_ms_key = $equipment['ms_key'];
    $old_equipment_remarks = $equipment['equipment_remarks'];

    // Prepare the update statement
    $query = "UPDATE equipment SET 
                pcname = ?, department = ?, assigneduser = ?, processor = ?, motherboard = ?, 
                ram = ?, hdd = ?, ssd = ?, gpu = ?, psu = ?, pccase = ?, 
                monitor = ?, lancard = ?, wificard = ?, macaddress = ?, osversion = ?, 
                msversion = ?, windows_key = ?, ms_key = ?, equipment_remarks = ?, date_edited = NOW()
                WHERE equipment_id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($link, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssi", 
    $pcname, $department, $assigneduser, $processor, $motherboard, 
    $ram, $hdd, $ssd, $gpu, $psu, $pccase, 
    $monitor, $lancard, $wificard, $macaddress, $osversion, 
    $msversion, $windows_key, $ms_key, $equipment_remarks, $equipment_id);    

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Construct the log action for specific fields updated
        $log_action = " Updated Equipment for ({$equipment['pcname']}): ";

        // Compare each field and log the change if it differs
        if ($old_pcname !== $pcname) $log_action .= "PC Name: $old_pcname → $pcname, ";
        if ($old_department !== $department) $log_action .= "Department: $old_department → $department, ";
        if ($old_assigneduser !== $assigneduser) $log_action .= "Assigned User: $old_assigneduser → $assigneduser, ";
        if ($old_processor !== $processor) $log_action .= "Processor: $old_processor → $processor, ";
        if ($old_motherboard !== $motherboard) $log_action .= "Motherboard: $old_motherboard → $motherboard, ";
        if ($old_ram !== $ram) $log_action .= "RAM: $old_ram → $ram, ";
        if ($old_hdd !== $hdd) $log_action .= "HDD: $old_hdd → $hdd, ";
        if ($old_ssd !== $ssd) $log_action .= "SSD: $old_ssd → $ssd, ";
        if ($old_gpu !== $gpu) $log_action .= "GPU: $old_gpu → $gpu, ";
        if ($old_psu !== $psu) $log_action .= "PSU: $old_psu → $psu, ";
        if ($old_pccase !== $pccase) $log_action .= "PC Case: $old_pccase → $pccase, ";
        if ($old_monitor !== $monitor) $log_action .= "Monitor: $old_monitor → $monitor, ";
        if ($old_lancard !== $lancard) $log_action .= "LAN Card: $old_lancard → $lancard, ";
        if ($old_wificard !== $wificard) $log_action .= "WIFI Card: $old_wificard → $wificard, ";
        if ($old_macaddress !== $macaddress) $log_action .= "MAC Address: $old_macaddress → $macaddress, ";
        if ($old_osversion !== $osversion) $log_action .= "OS Version: $old_osversion → $osversion, ";
        if ($old_msversion !== $msversion) $log_action .= "MS Version: $old_msversion → $msversion, ";
        if ($old_windows_key !== $windows_key) $log_action .= "Windows Key: $old_windows_key → $windows_key, ";
        if ($old_ms_key !== $ms_key) $log_action .= "MS Key: $old_ms_key → $ms_key, ";
        if ($old_equipment_remarks !== $equipment_remarks) {
            $log_action .= "Remarks: $old_equipment_remarks → $equipment_remarks, ";
        }

        // Trim any trailing comma and space
        $log_action = rtrim($log_action, ", ");

        // Insert log into the database with date/time
        mysqli_query($link, "INSERT INTO logs (user_id, action, date_edited) 
                             VALUES ('" . $_SESSION['user_id'] . "', '$log_action', NOW())");

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
        <div id="breadcrumb"><a href="equipment.php" class="tip-bottom"><i class="icon-home"></i> Edit Equipment</a></div>
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
    <label class="control-label">Department :</label>
    <div class="controls">
        <select class="span11" name="department" required>
            <option value="" disabled>Select Department</option>
            <option value="ACFN" <?php if ($equipment['department'] == "ACFN") echo "selected"; ?>>Accounting & Finance (ACFN)</option>
            <option value="ADVT" <?php if ($equipment['department'] == "ADVT") echo "selected"; ?>>Advertising (ADVT)</option>
            <option value="CIRC" <?php if ($equipment['department'] == "CIRC") echo "selected"; ?>>Circulation (CIRC)</option>
            <option value="EDTN" <?php if ($equipment['department'] == "EDTN") echo "selected"; ?>>Editorial-News (EDTN)</option>
            <option value="EDTB" <?php if ($equipment['department'] == "EDTB") echo "selected"; ?>>Editorial-Business (EDTB)</option>
            <option value="HRAD" <?php if ($equipment['department'] == "HRAD") echo "selected"; ?>>HRAD</option>
            <option value="MIS" <?php if ($equipment['department'] == "MIS") echo "selected"; ?>>Management Information System (MIS)</option>
            <option value="OPER" <?php if ($equipment['department'] == "OPER") echo "selected"; ?>>Operations (OPER)</option>
            <option value="SLSM" <?php if ($equipment['department'] == "SLSM") echo "selected"; ?>>Sales and Marketing (SLSM)</option>
        </select>
    </div>
</div>

                            <div class="control-group">
                                <label class="control-label">Assigned User :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="assigneduser" value="<?php echo $equipment['assigneduser']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Processor :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="processor" value="<?php echo $equipment['processor']; ?>" required />
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
                                <label class="control-label">LAN Card :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="lancard" value="<?php echo $equipment['lancard']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">WIFI Card :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="wificard" value="<?php echo $equipment['wificard']; ?>" />
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
                            <div class="control-group">
                                <label class="control-label">Remarks :</label>
                                <div class="controls">
                                <textarea class="span11" placeholder="Remarks" name="equipment_remarks"><?php echo isset($equipment['equipment_remarks']) ? $equipment['equipment_remarks'] : ''; ?></textarea>
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
                                <a href="equipment.php" class="btn">Cancel</a> <!-- Redirects to equipment.php -->
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