<?php
// Start session
session_start();

// Include files after the session start
include "header.php";
include "../user/connection.php";

// Handling redirection after form submission to prevent resubmission
if (isset($_POST["submit1"])) {
    // Insert new device into the database
    $query = "INSERT INTO otherdevices (od_name, od_pcname, od_assettag, od_brand, od_modelnumber, od_deviceage, od_macaddress, od_dateacquired, od_dateedited, od_remarks) 
              VALUES ('" . mysqli_real_escape_string($link, $_POST["od_name"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["od_pcname"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["od_assettag"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["od_brand"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["od_modelnumber"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["od_deviceage"]) . "',
                      '" . mysqli_real_escape_string($link, $_POST["od_macaddress"]) . "',
                      NOW(), NOW(), 
                      '" . mysqli_real_escape_string($link, $_POST["od_remarks"]) . "')";

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
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> Other Devices</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">

                <!-- Search bar and button -->
                <div style="margin-bottom: 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 11px;">
                    <input type="text" id="searchInput" class="span5" placeholder="Search device...">
                    <button class="btn btn-info" onclick="searchDevices()">Search</button>
                </div>

                <!-- Button to toggle the form -->
                <button id="toggleFormButton" class="btn btn-primary" onclick="toggleForm()">Add New Device</button>

                <div id="addDeviceForm" style="display: none; margin-top: 20px;">
                    <div class="widget-box">
                        <div class="widget-title"> 
                            <span class="icon"><i class="icon-align-justify"></i></span>
                            <h5>Other Devices</h5>
                        </div>
                        <div class="widget-content nopadding">
                            <form name="form1" action="" method="post" class="form-horizontal">
                                <div class="control-group">
                                    <label class="control-label">Device Name :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Device Name" name="od_name" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">PC Name :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="PC Name " name="od_pcname" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Asset Tag :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Asset Tag" name="od_assettag" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Brand :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Brand" name="od_brand" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Model Number :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Model Number" name="od_modelnumber" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Device Age :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="Device Age" name="od_deviceage" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">MAC Address :</label>
                                    <div class="controls">
                                        <input type="text" class="span11" placeholder="MAC Address" name="od_macaddress" required />
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Remarks :</label>
                                    <div class="controls">
                                        <textarea class="span11" placeholder="Remarks" name="od_remarks"></textarea>
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
                        Device added successfully!
                    </div>
                <?php } ?>

                <div class="widget-content nopadding" style="margin-top: 20px;">
                    <div style="overflow-x: auto;">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Device Name</th>
                                    <th>PC Name</th>
                                    <th>Asset Tag</th>
                                    <th>Brand</th>
                                    <th>Model Number</th>
                                    <th>Device Age</th>
                                    <th>MAC Address</th>
                                    <th>Date Acquired</th>
                                    <th>Date Edited</th>
                                    <th>Remarks</th>
                                    <th>EDIT</th>
                                    <th>DELETE</th>
                                </tr>
                            </thead>
                            <tbody id="deviceTableBody">
                                <?php
                                $res = mysqli_query($link, "SELECT * FROM otherdevices");
                                while ($row = mysqli_fetch_array($res)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row["od_name"]; ?></td>
                                        <td><?php echo $row["od_pcname"]; ?></td>
                                        <td><?php echo $row["od_assettag"]; ?></td>
                                        <td><?php echo $row["od_brand"]; ?></td>
                                        <td><?php echo $row["od_modelnumber"]; ?></td>
                                        <td><?php echo $row["od_deviceage"]; ?></td>
                                        <td><?php echo $row["od_macaddress"]; ?></td>
                                        <td><?php echo $row["od_dateacquired"]; ?></td>
                                        <td><?php echo $row["od_dateedited"] ? $row["od_dateedited"] : 'N/A'; ?></td>
                                        <td><?php echo $row["od_remarks"]; ?></td>
                                        <td><a href="edit_otherdevices.php?od_id=<?php echo $row['od_id']; ?>" class="btn btn-primary">Edit</a></td>
                                        <td>
                                            <a href="delete_otherdevices.php?od_id=<?php echo $row['od_id']; ?>" class="btn btn-danger"
                                               onclick="return confirm('Are you sure you want to delete this device?');">
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
        let form = document.getElementById('addDeviceForm');
        let button = document.getElementById('toggleFormButton');

        // Toggle form visibility
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            button.innerText = 'Hide Form'; // Change button text
        } else {
            form.style.display = 'none';
            button.innerText = 'Add New Device'; // Reset button text
        }
    }

    function searchDevices() {
        let searchQuery = document.getElementById("searchInput").value;
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "search_otherdevices.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("deviceTableBody").innerHTML = xhr.responseText;
            }
        };

        xhr.send("query=" + searchQuery);
    }
</script>

<?php
include "footer.php";
?>
