<?php


// Start session at the very top
session_start();

// Include files after the session start
include "header.php";
include "../user/connection.php";

// Fetch existing equipment for dropdown
$equipment_result = mysqli_query($link, "SELECT equipment_id, pcname FROM equipment");
$equipment_options = "";
while ($row = mysqli_fetch_assoc($equipment_result)) {
    $equipment_options .= "<option value='" . $row['equipment_id'] . "'>" . $row['pcname'] . "</option>";
}

// Handling form submission
if (isset($_POST["submit1"])) {
    // Insert a new peripheral with peripheral_dateaedited as 'N/A' when new
    $query = "INSERT INTO peripherals (equipment_id, keyboard, mouse, printer, avr, peripheral_dateadded, peripheral_dateaedited) 
              VALUES ('" . mysqli_real_escape_string($link, $_POST["equipment_id"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["keyboard"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["mouse"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["printer"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["avr"]) . "', 
                      NOW(), 'N/A')"; // Date Edited is N/A for new records

    if (mysqli_query($link, $query)) {
        $_SESSION["alert"] = "success";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION["alert"] = "error";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$alert = $_SESSION["alert"] ?? null;
unset($_SESSION["alert"]);
?>

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> Peripherals</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <!-- Search bar and button -->
                <div style="margin-top: 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 11px;">
                    <input type="text" id="searchInput" class="span5" placeholder="Search peripherals...">
                    <button class="btn btn-info" onclick="searchPeripherals()">Search</button>
                </div>

                <!-- Button to toggle the form -->
                <button id="toggleFormButton" class="btn btn-primary" onclick="toggleForm()">Add New Peripheral</button>

                <div id="addPeripheralForm" style="display: none; margin-top: 20px;">
                    <div class="widget-box">
                        <div class="widget-title"> 
                            <span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>Peripherals</h5>
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
                                        <input type="text" class="span11" placeholder="Keyboard" name="keyboard" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Mouse :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Mouse" name="mouse" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Printer :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Printer" name="printer" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">AVR :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="AVR" name="avr" required />
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" name="submit1" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Alert section -->
                <?php if ($alert == "error") { ?>
                    <div class="alert alert-danger" style="margin-top: 20px;">
                        An error occurred while processing your request.
                    </div>
                <?php } elseif ($alert == "success") { ?>
                    <div class="alert alert-success" style="margin-top: 20px;">
                        Peripheral added successfully!
                    </div>
                <?php } ?>

                <div class="widget-content nopadding" style="margin-top: 20px;">
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>PC Name</th>
                                    <th>Keyboard</th>
                                    <th>Mouse</th>
                                    <th>Printer</th>
                                    <th>AVR</th>
                                    <th>Date Added</th>
                                    <th>Date Edited</th>
                                    <th>EDIT</th>
                                    <th>DELETE</th>
                                </tr>
                            </thead>
                            <tbody id="peripheralTableBody">
                                <?php
                                // Fetch peripherals with PC name by joining peripherals and equipment tables
                                $res = mysqli_query($link, "SELECT p.*, e.pcname FROM peripherals p 
                                                            JOIN equipment e ON p.equipment_id = e.equipment_id");
                                while ($row = mysqli_fetch_array($res)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row["pcname"]; ?></td>
                                        <td><a href="keyboard.php?equipment_id=<?php echo urlencode($row['equipment_id']); ?>"><?php echo $row["keyboard"]; ?></a></td>
                                        <td><a href="mouse.php?equipment_id=<?php echo urlencode($row['equipment_id']); ?>"><?php echo $row["mouse"]; ?></a></td>
                                        <td><a href="printer.php?equipment_id=<?php echo urlencode($row['equipment_id']); ?>"><?php echo $row["printer"]; ?></a></td>
                                        <td><a href="avr.php?equipment_id=<?php echo urlencode($row['equipment_id']); ?>"><?php echo $row["avr"]; ?></a></td>
                                        <td><?php echo $row["peripheral_dateadded"]; ?></td>
                                        <td><?php echo $row["peripheral_dateaedited"] ? $row["peripheral_dateaedited"] : 'N/A'; ?></td> <!-- Display N/A if not yet edited -->
                                        <td><a href="edit_peripheral.php?peripheral_id=<?php echo $row['peripheral_id']; ?>" class="btn btn-primary">Edit</a></td>
                                        <td>
                                            <a href="delete_peripheral.php?peripheral_id=<?php echo $row['peripheral_id']; ?>" 
                                            class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this peripheral?');">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleForm() {
        const form = document.getElementById('addPeripheralForm');
        form.style.display = (form.style.display === 'none') ? 'block' : 'none';
    }

    function searchPeripherals() {
        let searchQuery = document.getElementById("searchInput").value;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "search_peripheral.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("peripheralTableBody").innerHTML = xhr.responseText;
            }
        };

        xhr.send("query=" + searchQuery);
    }
</script>

<?php
include "footer.php";
?>
