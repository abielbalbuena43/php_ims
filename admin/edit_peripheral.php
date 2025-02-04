<?php
session_start();
include "session_verification.php";
include "header.php";
include "../user/connection.php";

// Get the peripheral ID from the URL
$peripheral_id = $_GET["peripheral_id"];

// Fetch the existing peripheral details
$query = "SELECT * FROM peripherals WHERE peripheral_id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'i', $peripheral_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$peripheral = mysqli_fetch_array($result);
mysqli_stmt_close($stmt);

// Fetch equipment for dropdown (equipment ID and PC Name)
$equipment_result = mysqli_query($link, "SELECT equipment_id, pcname FROM equipment");
$equipment_options = "";
while ($row = mysqli_fetch_assoc($equipment_result)) {
    $equipment_options .= "<option value='" . $row['equipment_id'] . "'" . 
                          ($peripheral['equipment_id'] == $row['equipment_id'] ? ' selected' : '') . 
                          ">" . $row['pcname'] . "</option>";
}

// Handling alert messages for success or error
if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]);
} else {
    $alert = null;
}

// Handle form submission to update the peripheral
if (isset($_POST["submit1"])) {
    // Get the form data and escape special characters
    $equipment_id = mysqli_real_escape_string($link, $_POST["equipment_id"]);
    $keyboard = mysqli_real_escape_string($link, $_POST["keyboard"]);
    $mouse = mysqli_real_escape_string($link, $_POST["mouse"]);
    $printer = mysqli_real_escape_string($link, $_POST["printer"]);
    $avr = mysqli_real_escape_string($link, $_POST["avr"]);

    // Validate that equipment_id exists AFTER retrieving it
    $query = "SELECT equipment_id, pcname FROM equipment WHERE equipment_id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, 'i', $equipment_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $equipment = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) == 0) {
        $_SESSION["alert"] = "error";
        $_SESSION["error_message"] = "Error: The selected equipment does not exist.";
        header("Location: edit_peripheral.php?peripheral_id=$peripheral_id");
        exit();
    }
    mysqli_stmt_close($stmt);

    // Fetch previous peripheral details before updating
    $query = "SELECT * FROM peripherals WHERE peripheral_id = ?";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "i", $peripheral_id);
    mysqli_stmt_execute($stmt);
    $old_data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);

    // Prepare log details
    $log_action = "Updated peripheral ({$equipment['pcname']}): ";
    $changes = [];

    if ($old_data['keyboard'] !== $keyboard) {
        $changes[] = "Keyboard: {$old_data['keyboard']} → $keyboard";
    }
    if ($old_data['mouse'] !== $mouse) {
        $changes[] = "Mouse: {$old_data['mouse']} → $mouse";
    }
    if ($old_data['printer'] !== $printer) {
        $changes[] = "Printer: {$old_data['printer']} → $printer";
    }
    if ($old_data['avr'] !== $avr) {
        $changes[] = "AVR: {$old_data['avr']} → $avr";
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

    // Prepare the update statement (do NOT include peripheral_dateadded)
    $query = "UPDATE peripherals SET 
              keyboard = ?, mouse = ?, printer = ?, avr = ?, equipment_id = ?, peripheral_dateaedited = NOW() 
              WHERE peripheral_id = ?";
    $stmt = mysqli_prepare($link, $query);

    // Corrected parameter binding (matching six `?` placeholders)
    mysqli_stmt_bind_param($stmt, "ssssii", $keyboard, $mouse, $printer, $avr, $equipment_id, $peripheral_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION["alert"] = "success";
        header("Location: edit_peripheral.php?peripheral_id=$peripheral_id");
        exit();
    } else {
        $_SESSION["alert"] = "error";
        $_SESSION["error_message"] = "Error: " . mysqli_stmt_error($stmt);
        error_log("MySQL Error: " . mysqli_stmt_error($stmt)); // Log the error
        header("Location: edit_peripheral.php?peripheral_id=$peripheral_id");
        exit();
    }
}
?>

<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="peripherals.php" class="tip-bottom"><i class="icon-home"></i> Edit Peripheral</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Peripheral</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">Equipment (PC Name) :</label>
                                <div class="controls">
                                    <select name="equipment_id" class="span11" required>
                                        <option value="">Select Equipment</option>
                                        <?php echo $equipment_options; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Keyboard :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Keyboard" name="keyboard" value="<?php echo $peripheral['keyboard']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Mouse :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Mouse" name="mouse" value="<?php echo $peripheral['mouse']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Printer :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="Printer" name="printer" value="<?php echo $peripheral['printer']; ?>" required />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">AVR :</label>
                                <div class="controls">
                                    <input type="text" class="span11" placeholder="AVR" name="avr" value="<?php echo $peripheral['avr']; ?>" required />
                                </div>
                            </div>

                            <!-- Alert Display -->
                            <?php if ($alert == "error") { ?>
                                <div class="alert alert-danger" style="margin-top: 20px;">
                                    Failed to update peripheral. <?php echo $_SESSION["error_message"]; ?>
                                </div>
                            <?php } elseif ($alert == "success") { ?>
                                <div class="alert alert-success" style="margin-top: 20px;">
                                    Peripheral updated successfully!
                                </div>
                            <?php } ?>

                            <div class="form-actions">
                                <button type="submit" name="submit1" class="btn btn-success">Save Changes</button>
                                <a href="peripherals.php" class="btn">Cancel</a>
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
