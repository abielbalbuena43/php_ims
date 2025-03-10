<?php
session_start();
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $_SESSION["message"] = "You must be logged in to add equipment.";
    header("Location: login.php"); // Redirect to login page if user is not logged in
    exit();
}

// Handling redirection after form submission to prevent resubmission
if (isset($_POST["submit1"])) {
    // Insert new equipment into the database with default values for Windows and MS Product Keys
    $windows_key = empty($_POST["windows_key"]) ? '0' : $_POST["windows_key"];
    $ms_key = empty($_POST["ms_key"]) ? '0' : $_POST["ms_key"];

    $query = "INSERT INTO equipment (pcname, assigneduser, processor, motherboard, ram, hdd, ssd, gpu, psu, pccase, monitor, lancard, wificard, macaddress, osversion, msversion, windows_key, ms_key, date_added, date_edited) 
              VALUES ('" . mysqli_real_escape_string($link, $_POST["pcname"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["assigneduser"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["processor"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["motherboard"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["ram"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["hdd"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["ssd"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["gpu"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["psu"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["pccase"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["monitor"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["lancard"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["wificard"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["macaddress"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["osversion"]) . "', 
                      '" . mysqli_real_escape_string($link, $_POST["msversion"]) . "', 
                      '" . mysqli_real_escape_string($link, $windows_key) . "', 
                      '" . mysqli_real_escape_string($link, $ms_key) . "', 
                      NOW(), NOW())";  // Current time for Date Added and Date Edited

    if (mysqli_query($link, $query)) {
        // Log the action after the successful insertion
        $log_action = "Added new Equipment: " . $_POST["pcname"];
        
        // Insert the log entry with user_id
        $insert_log_query = "INSERT INTO logs (user_id, action, date_edited) 
                             VALUES (?, ?, NOW())";
        $stmt_log = mysqli_prepare($link, $insert_log_query);
        mysqli_stmt_bind_param($stmt_log, "is", $_SESSION['user_id'], $log_action);
        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);

        $_SESSION["alert"] = "success";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION["alert"] = "error";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

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
        <div id="breadcrumb"><a href="equipment.php" class="tip-bottom"><i class="icon-home"></i> Equipment</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">

                <!-- Search bar and live results -->
                <div id="searchContainer">
                    <input type="text" id="searchInput" placeholder="Search equipment..." onkeyup="searchEquipment()">
                </div>
                <div id="searchResults"></div>

                <!-- Button to toggle the form -->
                <button id="toggleFormButton" class="btn btn-primary" onclick="toggleForm()">Add New Equipment</button>

                <div id="addEquipmentForm" style="display: none; margin-top: 20px;">
                    <div class="widget-box">
                        <div class="widget-title"> 
                            <span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>Equipment</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form name="form1" action="" method="post" class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label">PC Name :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="PC Name" name="pcname" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Assigned User :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Assigned User" name="assigneduser" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Processor :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Processor" name="processor" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Motherboard :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Motherboard" name="motherboard" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">LAN Card :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="LAN Card" name="lancard" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">WIFI Card :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="WIFI Card" name="wificard" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">RAM :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="RAM" name="ram" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">HDD :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="HDD" name="hdd" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">SSD :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="SSD" name="ssd" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">GPU :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="GPU" name="gpu" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">PSU :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="PSU" name="psu" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">PC Case :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="PC Case" name="pccase" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Monitor :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Monitor" name="monitor" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">MAC Address :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="MAC Address" name="macaddress" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">OS Version :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="OS Version" name="osversion" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">MS Version :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="MS Version" name="msversion" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Windows Product Key :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Windows Product Key" name="windows_key" />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">MS Product Key :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="MS Product Key" name="ms_key" />
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
                        Equipment added successfully!
                    </div>
                <?php } elseif ($alert == "deleted") { ?>
                    <div class="alert" style="background-color: gray; color: white; margin-top: 20px;">
                        Equipment deleted.
                    </div>
                <?php } ?>

                <div class="widget-content nopadding" style="margin-top: 20px;">
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>PC Name</th>
                                    <th>Assigned User</th>
                                    <th>Processor</th>
                                    <th>Motherboard</th>
                                    <th>RAM</th>
                                    <th>HDD</th>
                                    <th>SSD</th>
                                    <th>GPU</th>
                                    <th>PSU</th>
                                    <th>PC Case</th>
                                    <th>Monitor</th>
                                    <th>LAN Card</th>
                                    <th>WIFI Card</th>
                                    <th>MAC Address</th>
                                    <th>OS Version</th>
                                    <th>MS Version</th>
                                    <th>Windows Product Key</th>
                                    <th>MS Product Key</th>
                                    <th>Date Added</th>
                                    <th>Date Edited</th>
                                    <th>Remarks</th>
                                    <th>EDIT</th>
                                    <th>DELETE</th>
                                </tr>
                            </thead>
                            <tbody id="equipmentTableBody">
                                <?php
                                $res = mysqli_query($link, "SELECT * FROM equipment");
                                while ($row = mysqli_fetch_array($res)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row["pcname"]; ?></td>
                                        <td><?php echo $row["assigneduser"]; ?></td>
                                        <td>
                                            <?php 
                                            if ($row["processor"] == "None") {
                                                echo $row["processor"];
                                            } else {
                                                echo "<a href='processor.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["processor"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["motherboard"] == "None") {
                                                echo $row["motherboard"];
                                            } else {
                                                echo "<a href='motherboard.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["motherboard"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["ram"] == "None") {
                                                echo $row["ram"];
                                            } else {
                                                echo "<a href='ram.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["ram"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["hdd"] == "None") {
                                                echo $row["hdd"];
                                            } else {
                                                echo "<a href='hdd.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["hdd"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["ssd"] == "None") {
                                                echo $row["ssd"];
                                            } else {
                                                echo "<a href='ssd.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["ssd"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["gpu"] == "None") {
                                                echo $row["gpu"];
                                            } else {
                                                echo "<a href='gpu.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["gpu"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["psu"] == "None") {
                                                echo $row["psu"];
                                            } else {
                                                echo "<a href='psu.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["psu"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["pccase"] == "None") {
                                                echo $row["pccase"];
                                            } else {
                                                echo "<a href='pccase.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["pccase"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["monitor"] == "None") {
                                                echo $row["monitor"];
                                            } else {
                                                echo "<a href='monitor.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["monitor"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["lancard"] == "None") {
                                                echo $row["lancard"];
                                            } else {
                                                echo "<a href='lancard.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["lancard"] . "</a>";
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php 
                                            if ($row["wificard"] == "None") {
                                                echo $row["wificard"];
                                            } else {
                                                echo "<a href='wificard.php?equipment_id=" . urlencode($row['equipment_id']) . "'>" . $row["wificard"] . "</a>";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $row["macaddress"]; ?></td>
                                        <td><?php echo $row["osversion"]; ?></td>
                                        <td><?php echo $row["msversion"]; ?></td>
                                        <td><?php echo $row["windows_key"]; ?></td>
                                        <td><?php echo $row["ms_key"]; ?></td>
                                        <td><?php echo $row["date_added"]; ?></td>
                                        <td><?php echo $row["date_edited"]; ?></td>
                                        <td><?php echo $row["equipment_remarks"]; ?></td>
                                        <td><a href="edit_equipment.php?equipment_id=<?php echo $row['equipment_id']; ?>" class="btn btn-primary">Edit</a></td>
                                        <td>
                                            <a href="delete_equipment.php?equipment_id=<?php echo $row['equipment_id']; ?>" 
                                            class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this equipment?');">
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

<!--end-main-container-part-->
<script>
    function toggleForm() {
        const form = document.getElementById('addEquipmentForm');
        form.style.display = (form.style.display === 'none') ? 'block' : 'none';
    }

    function searchEquipment() {
        let searchQuery = document.getElementById("searchInput").value;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "search_equipment.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("equipmentTableBody").innerHTML = xhr.responseText;
            }
        };

        xhr.send("query=" + searchQuery);
    }
</script>

<?php
include "footer.php";
?>
