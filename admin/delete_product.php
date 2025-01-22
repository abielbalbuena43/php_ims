<?php
include "../user/connection.php";

// Get the product ID from the URL
$id = $_GET["id"];

// Delete the product from the database
mysqli_query($link, "DELETE FROM new_products WHERE id = $id");
?>

<script type="text/javascript">
    // Redirect to the product listing or add new product page after deletion
    window.location = "add_new_product.php";
</script>
