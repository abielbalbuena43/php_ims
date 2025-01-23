<?php
include "../user/connection.php";

// Get the Equipment ID from the URL
$equipment_id = $_GET["equipment_id"];

mysqli_query($link, "DELETE FROM new_equipment WHERE equipment_id = $equipment_id");
?>

<script type="text/javascript">
    window.location = "add_new_equipment.php";
</script>
