<?php
// Dynamically set the active tab based on the current page
$current_page = basename($_SERVER['PHP_SELF']);
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

<div id="header" style="background-color: #28282B;">


</div>



<!--sidebar-menu-->
<div id="sidebar" style="margin-top: 40px;">
    <ul>
        <!-- Dynamically add "active" class based on the current page -->
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