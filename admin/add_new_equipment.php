<?php
// Start session at the very top
session_start();

// Include files after the session start
include "header.php";
include "../user/connection.php";

// Handling redirection after form submission to prevent resubmission
if (isset($_POST["submit1"])) {
    mysqli_query($link, "INSERT INTO new_equipment (pcname, cpu, motherboard, ram, hdd, ssd, gpu, psu, pccase, monitor, macaddress, osversion, msversion) VALUES ('" . mysqli_real_escape_string($link, $_POST["pcname"]) . "', '" . mysqli_real_escape_string($link, $_POST["cpu"]) . "', '" . mysqli_real_escape_string($link, $_POST["motherboard"]) . "', '" . mysqli_real_escape_string($link, $_POST["ram"]) . "', '" . mysqli_real_escape_string($link, $_POST["hdd"]) . "', '" . mysqli_real_escape_string($link, $_POST["ssd"]) . "', '" . mysqli_real_escape_string($link, $_POST["gpu"]) . "', '" . mysqli_real_escape_string($link, $_POST["psu"]) . "', '" . mysqli_real_escape_string($link, $_POST["pccase"]) . "', '" . mysqli_real_escape_string($link, $_POST["monitor"]) . "', '" . mysqli_real_escape_string($link, $_POST["macaddress"]) . "', '" . mysqli_real_escape_string($link, $_POST["osversion"]) . "', '" . mysqli_real_escape_string($link, $_POST["msversion"]) . "')");

    $_SESSION["alert"] = "success";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
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
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> Add New Equipment</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <!-- Button to toggle the form -->
                <button id="toggleFormButton" class="btn btn-primary" onclick="toggleForm()">Add New Equipment</button>

                <div id="addEquipmentForm" style="display: none; margin-top: 20px;">
                    <div class="widget-box">
                        <div class="widget-title"> 
                            <span class="icon"> <i class="icon-align-justify"></i> </span>
                            <h5>Add New Equipment</h5>
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
                                    <label class="control-label">CPU :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="CPU" name="cpu" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Motherboard :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Motherboard" name="motherboard" required />
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
                        Equipment already exists or an error occurred!
                    </div>
                <?php } elseif ($alert == "success") { ?>
                    <div class="alert alert-success" style="margin-top: 20px;">
                        Equipment added successfully!
                    </div>
                <?php } ?>

                <div class="widget-content nopadding" style="margin-top: 20px;">
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>PC Name</th>
                                    <th>CPU</th>
                                    <th>Motherboard</th>
                                    <th>RAM</th>
                                    <th>HDD</th>
                                    <th>SSD</th>
                                    <th>GPU</th>
                                    <th>PSU</th>
                                    <th>PC Case</th>
                                    <th>Monitor</th>
                                    <th>MAC Address</th>
                                    <th>OS Version</th>
                                    <th>MS Version</th>
                                    <th>EDIT</th>
                                    <th>DELETE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($link, "SELECT * FROM new_equipment");
                                while ($row = mysqli_fetch_array($res)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row["pcname"]; ?></td>
                                        <td><?php echo $row["cpu"]; ?></td>
                                        <td><?php echo $row["motherboard"]; ?></td>
                                        <td><?php echo $row["ram"]; ?></td>
                                        <td><?php echo $row["hdd"]; ?></td>
                                        <td><?php echo $row["ssd"]; ?></td>
                                        <td><?php echo $row["gpu"]; ?></td>
                                        <td><?php echo $row["psu"]; ?></td>
                                        <td><?php echo $row["pccase"]; ?></td>
                                        <td><?php echo $row["monitor"]; ?></td>
                                        <td><?php echo $row["macaddress"]; ?></td>
                                        <td><?php echo $row["osversion"]; ?></td>
                                        <td><?php echo $row["msversion"]; ?></td>
                                        <td><a href="edit_equipment.php?equipment_id=<?php echo $row["equipment_id"]; ?>">Edit</a></td>
                                        <td><a href="delete_equipment.php?equipment_id=<?php echo $row["equipment_id"]; ?>" onclick="return confirm('Are you sure you want to delete this equipment?');">Delete</a></td>
                                    </tr>
                                <?php
                                }
                                ?>
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
    const form = document.getElementById("addEquipmentForm");
    const button = document.getElementById("toggleFormButton");
    if (form.style.display === "none") {
        form.style.display = "block";
        button.textContent = "Hide Form";
    } else {
        form.style.display = "none";
        button.textContent = "Add New Equipment";
    }
}
</script>

<?php
include "footer.php";
?>
