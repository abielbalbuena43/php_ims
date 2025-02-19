<?php
// Check if session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "../user/connection.php"; // Include the database connection

$current_page = basename($_SERVER['PHP_SELF']);

// Get the username from the session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";

// Fetch user status from the database
$status = "Active"; // Default status
if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']); // Securely get user ID

    $query = "SELECT status FROM user_registration WHERE user_id = $user_id";
    $result = mysqli_query($link, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $status = $row['status']; // Set status from database
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP IMS</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css"/>
    <link rel="stylesheet" href="css/fullcalendar.css"/>
    <link rel="stylesheet" href="css/matrix-style.css"/>
    <link rel="stylesheet" href="css/matrix-media.css"/>
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/jquery.gritter.css"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

</head>
<body>

<div id="header" style="background-color: #28282B; display: flex; justify-content: space-between; padding: 10px;">
</div>

        <!--sidebar-menu-->
        <div id="sidebar" style="margin-top: 40px;">
            <ul>
                <!-- User Info Section -->
        <li style="padding: 20px; text-align: center; background-color: #333; color: white; border-bottom: 1px solid #444;">
            <!-- User Icon -->
            <div style="width: 60px; height: 60px; background-color: #555; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
                <i class="icon icon-user" style="font-size: 30px; color: white;"></i>
            </div>

            <!-- Username -->
            <strong style="display: block; font-size: 16px;"><?php echo htmlspecialchars($username); ?></strong>

            <!-- User Status -->
            <?php
            $status_color = ($status == 'inactive') ? '#FFA500' : '#0f0'; // Orange for inactive, Green for active
            ?>
            <span style="font-size: 12px; color: <?php echo $status_color; ?>; display: block; margin-top: 5px;">
                <?php echo htmlspecialchars($status); ?>
            </span>
        </li>


        <!-- Navigation Links -->
        <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <a href="dashboard.php"><i class="icon icon-home"></i><span>Dashboard</span></a>
        </li>

        <li class="<?php echo ($current_page == 'add_new_user.php') ? 'active' : ''; ?>">
            <a href="add_new_user.php"><i class="icon icon-user"></i><span>User List</span></a>
        </li>

        <li class="<?php echo ($current_page == 'equipment.php') ? 'active' : ''; ?>">
            <a href="equipment.php"><i class="icon icon-inbox"></i><span>Equipment List</span></a>
        </li>

        <li class="<?php echo ($current_page == 'peripherals.php') ? 'active' : ''; ?>">
            <a href="peripherals.php"><i class="icon icon-headphones"></i><span>Peripherals List</span></a>
        </li>

        <li class="<?php echo ($current_page == 'otherdevices.php') ? 'active' : ''; ?>">
            <a href="otherdevices.php"><i class="icon icon-laptop"></i><span>Other Devices</span></a>
        </li>

        <li class="<?php echo ($current_page == 'software.php') ? 'active' : ''; ?>">
            <a href="software.php"><i class="icon icon-google-plus-sign"></i><span>Software</span></a>
        </li>

        <li class="<?php echo ($current_page == 'logs.php') ? 'active' : ''; ?>">
            <a href="logs.php"><i class="icon icon-cloud"></i><span>Logs</span></a>
        </li>
    </ul>
</div>

<!-- Logout button -->
<div id="search" style="margin-bottom: 5555px;">
    <form action="logout.php" method="post">
        <button type="submit" class="btn btn-danger">
            <i class="icon icon-share-alt"></i> Log Out
        </button>
    </form>
</div>


<style>
/* Search Input */
/* Container for manual positioning */
#searchContainer {
    position: relative;
    width: 100%; /* Adjust as needed */
}

/* Search Input */
#searchInput {
    width: 250px;
    padding: 2x;
    border: 2px solid rgb(120, 120, 120);
    border-radius: 6px;
    font-size: 16px;
    outline: none;
    position: absolute;
    right: 105px; /* Adjust manually */
    bottom: -57px;  /* Adjust manually */
}

/* Search Button */
#searchButton {
    background-color: #17a2b8;
    color: white;
    padding: 5px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    border: none;
    position: absolute;
    right: 35px; /* Adjust manually */
    bottom: -46px;   /* Adjust manually */
}

#toggleFormButton {
    background-color: #17a2b8;
    color: white;
    padding: 5px;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    border: none;
    margin-top: 15px;
    margin-bottom: 5px;
}


</style>