<?php
session_start();
include "session_verification.php";
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
    $query = "INSERT INTO peripherals (equipment_id, keyboard, mouse, printer, avr, peripheral_dateadded, peripheral_dateaedited, peripheral_remarks) 
              VALUES ('" . mysqli_real_escape_string($link, $_POST["equipment_id"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["keyboard"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["mouse"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["printer"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["avr"]) . "',
                      NOW(), 'N/A', 
                      '" . mysqli_real_escape_string($link, $_POST["peripheral_remarks"]) . "')"; // Date Edited is N/A for new records

    if (mysqli_query($link, $query)) {
        // Fetch the pcname associated with the selected equipment_id
        $equipment_id = $_POST["equipment_id"];
        $pcname_query = "SELECT pcname FROM equipment WHERE equipment_id = $equipment_id";
        $pcname_result = mysqli_query($link, $pcname_query);
        $pcname_data = mysqli_fetch_assoc($pcname_result);
        $pcname = $pcname_data['pcname'];

        // Log the action after the successful insertion
        $log_action = "Added new Peripheral for equipment: " . $pcname;
        $log_query = "INSERT INTO logs (pcname, action) VALUES ('" . mysqli_real_escape_string($link, $pcname) . "', '$log_action')";
        mysqli_query($link, $log_query);

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
        <div id="breadcrumb"><a href="peripherals.php" class="tip-bottom"><i class="icon-home"></i> Peripherals</a></div>
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
                                <div class="control-group">
                                    <label class="control-label">Remarks :</label>
                                    <div class="controls">
                                        <textarea class="span11" placeholder="Peripheral Remarks" name="peripheral_remarks" required></textarea>
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
                <?php } elseif ($alert == "deleted") { ?>
                    <div class="alert" style="background-color: gray; color: white; margin-top: 20px;">
                        Peripheral deleted.
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
                                    <th>Remarks</th> <!-- Added Remarks Column -->
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
                                    <td>
                                        <?php 
                                        if ($row["keyboard"] == "None") {
                                            echo $row["keyboard"]; 
                                        } else { 
                                            echo "<a href='keyboard.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["keyboard"] . "</a>"; 
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($row["mouse"] == "None") {
                                            echo $row["mouse"]; 
                                        } else { 
                                            echo "<a href='mouse.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["mouse"] . "</a>"; 
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($row["printer"] == "None") {
                                            echo $row["printer"]; 
                                        } else { 
                                            echo "<a href='printer.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["printer"] . "</a>"; 
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        if ($row["avr"] == "None") {
                                            echo $row["avr"]; 
                                        } else { 
                                            echo "<a href='avr.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["avr"] . "</a>"; 
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row["peripheral_dateadded"]; ?></td>
                                    <td><?php echo $row["peripheral_dateaedited"] ? $row["peripheral_dateaedited"] : 'N/A'; ?></td> <!-- Display N/A if not yet edited -->
                                    <td><?php echo $row["peripheral_remarks"]; ?></td> <!-- Added Remarks in the table -->
                                    <td>
                                        <?php 
                                        if ($row["keyboard"] == "None" && $row["mouse"] == "None" && $row["printer"] == "None" && $row["avr"] == "None") {
                                            echo "Not editable"; 
                                        } else {
                                            echo "<a href='edit_peripheral.php?peripheral_id=" . $row['peripheral_id'] . "' class='btn btn-primary'>Edit</a>";
                                        }
                                        ?>
                                    </td>
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
