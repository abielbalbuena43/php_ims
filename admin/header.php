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

<div id="header">
    <h2 style="color: white; position: absolute">
        <a href="dashboard.html" style="color:white; margin-left: 30px; margin-top: 40px">PHP IMS</a>
    </h2>
</div>

<!--sidebar-menu-->
<div id="sidebar">
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

<!-- Logout link -->
<div id="search">
    <a href="logout.php" style="color:white"><i class="icon icon-share-alt"></i><span>LogOut</span></a>
</div>
