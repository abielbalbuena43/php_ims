<?php
include "header.php";
include "../user/connection.php";

// Start session for alert messages
session_start();

// Get the product ID from the URL
$id = $_GET["id"];

// Fetch the product details from the database
$query = "SELECT * FROM new_products WHERE id = $id";
$result = mysqli_query($link, $query);
$product = mysqli_fetch_array($result);

// Alert logic for success/error messages
if (isset($_SESSION["alert"])) {
    $alert = $_SESSION["alert"];
    unset($_SESSION["alert"]); // Clear alert after page load
} else {
    $alert = null;
}

if (isset($_POST["submit1"])) {
    // Get the form data
    $pcname = $_POST['pcname'];
    $cpu = $_POST['cpu'];
    $motherboard = $_POST['motherboard'];
    $ram = $_POST['ram'];
    $hdd = $_POST['hdd'];
    $ssd = $_POST['ssd'];
    $gpu = $_POST['gpu'];
    $psu = $_POST['psu'];
    $pccase = $_POST['pccase'];
    $monitor = $_POST['monitor'];
    $macaddress = $_POST['macaddress'];
    $osversion = $_POST['osversion'];
    $msversion = $_POST['msversion'];

    // Update the product details in the database
    $query = "UPDATE new_products SET 
                pcname='$pcname', cpu='$cpu', motherboard='$motherboard', 
                ram='$ram', hdd='$hdd', ssd='$ssd', gpu='$gpu', 
                psu='$psu', pccase='$pccase', monitor='$monitor', 
                macaddress='$macaddress', osversion='$osversion', 
                msversion='$msversion' 
              WHERE id=$id";
    
    if (mysqli_query($link, $query)) {
        $_SESSION["alert"] = "success";
        header("Location: edit_product.php?id=$id"); // Redirect to avoid form resubmission
        exit();
    } else {
        $_SESSION["alert"] = "error";
        header("Location: edit_product.php?id=$id");
        exit();
    }
}
?>

<!--main-container-part-->
<div id="content">
    <!--breadcrumbs-->
    <div id="content-header">
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> Edit Product</a></div>
    </div>
    <!--End-breadcrumbs-->

    <!--Action boxes-->
    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> 
                        <span class="icon"> <i class="icon-align-justify"></i> </span>
                        <h5>Edit Product</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <form name="form1" action="" method="post" class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label">PC Name :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="pcname" value="<?php echo $product['pcname']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">CPU :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="cpu" value="<?php echo $product['cpu']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Motherboard :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="motherboard" value="<?php echo $product['motherboard']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">RAM :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="ram" value="<?php echo $product['ram']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">HDD :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="hdd" value="<?php echo $product['hdd']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">SSD :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="ssd" value="<?php echo $product['ssd']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">GPU :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="gpu" value="<?php echo $product['gpu']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">PSU :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="psu" value="<?php echo $product['psu']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">PC Case :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="pccase" value="<?php echo $product['pccase']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Monitor :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="monitor" value="<?php echo $product['monitor']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">MAC Address :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="macaddress" value="<?php echo $product['macaddress']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">OS Version :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="osversion" value="<?php echo $product['osversion']; ?>" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">MS Version :</label>
                                <div class="controls">
                                    <input type="text" class="span11" name="msversion" value="<?php echo $product['msversion']; ?>" />
                                </div>
                            </div>

                            <!-- Alert Display -->
                            <?php if ($alert == "error") { ?>
                                <div class="alert alert-danger">
                                    Failed to update product.
                                </div>
                            <?php } elseif ($alert == "success") { ?>
                                <div class="alert alert-success">
                                    Product updated successfully!
                                </div>
                            <?php } ?>

                            <div class="form-actions">
                                <button type="submit" name="submit1" class="btn btn-success">Save Changes</button>
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
