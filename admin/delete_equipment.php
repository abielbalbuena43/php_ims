<?php
include "../user/connection.php";

// Get the  ID from the URL
$id = $_GET["id"];


mysqli_query($link, "DELETE FROM new_equipment WHERE id = $id");
?>

<script type="text/javascript">
    
    window.location = "add_new_equipment.php";
</script>
