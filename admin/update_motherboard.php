<?php
include "../user/connection.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted data
    $equipment_id = $_POST['equipment_id'];
    $assettag = $_POST['assettag'];
    $brand = $_POST['brand'];
    $modelnumber = $_POST['modelnumber'];
    $ramslot = $_POST['ramslot'];
    $dateacquired = $_POST['dateacquired'];
    $deviceage = $_POST['deviceage'];
    $assigneduser = $_POST['assigneduser'];
    $computername = $_POST['computername'];
    $macaddress = $_POST['macaddress'];
    $remarks = $_POST['remarks'];

    // Update the motherboard details in the database
    $updateQuery = "UPDATE motherboard SET 
                    assettag = '$assettag', 
                    brand = '$brand', 
                    modelnumber = '$modelnumber', 
                    ramslot = '$ramslot', 
                    dateacquired = '$dateacquired', 
                    deviceage = '$deviceage', 
                    assigneduser = '$assigneduser', 
                    computername = '$computername', 
                    macaddress = '$macaddress', 
                    remarks = '$remarks' 
                    WHERE equipment_id = $equipment_id";

    // Execute the query
    if (mysqli_query($link, $updateQuery)) {
        // Redirect back to the motherboard page with the updated equipment_id
        header("Location: motherboard.php?equipment_id=$equipment_id");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($link);
    }
}
?>

