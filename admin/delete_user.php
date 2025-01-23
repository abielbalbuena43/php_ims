<?php
include "../user/connection.php";
$user_id = $_GET["user_id"];
mysqli_query($link, "DELETE FROM user_registration WHERE user_id = $user_id");
?>

<script type="text/javascript">
    window.location = "add_new_user.php";
</script>
