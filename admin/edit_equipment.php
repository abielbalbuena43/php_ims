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

    // Update the equipment in the database
    $query = "UPDATE new_equipment SET 
              pcname='$pcname', cpu='$cpu', motherboard='$motherboard', 
              ram='$ram', hdd='$hdd', ssd='$ssd', gpu='$gpu', 
              psu='$psu', pccase='$pccase', monitor='$monitor', 
              macaddress='$macaddress', osversion='$osversion', 
              msversion='$msversion' WHERE equipment_id = $equipment_id";

    if (mysqli_query($link, $query)) {
        // Log the action after successful update
        $log_action = "Updated equipment: $pcname";
        $log_query = "INSERT INTO logs (pcname, action) VALUES ('$pcname', '$log_action')";
        mysqli_query($link, $log_query);

        $_SESSION["alert"] = "success";
        header("Location: edit_equipment.php?equipment_id=$equipment_id"); // Redirect to avoid form resubmission
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
                                <a href="equipment_list.php" class="btn">Cancel</a>
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
