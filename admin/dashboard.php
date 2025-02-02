<?php
include "../user/connection.php";
$current_page = basename($_SERVER['PHP_SELF']); // Get the current page name

// Query to get count of each equipment category
$query = "
SELECT 'AVR' AS category, COUNT(avr_id) AS count FROM avr UNION ALL
SELECT 'GPU', COUNT(gpu_id) FROM gpu UNION ALL
SELECT 'HDD', COUNT(hdd_id) FROM hdd UNION ALL
SELECT 'Keyboard', COUNT(keyboard_id) FROM keyboard UNION ALL
SELECT 'LAN Card', COUNT(lancard_id) FROM lancard UNION ALL
SELECT 'Monitor', COUNT(monitor_id) FROM monitor UNION ALL
SELECT 'Motherboard', COUNT(mobo_id) FROM motherboard UNION ALL
SELECT 'Mouse', COUNT(mouse_id) FROM mouse UNION ALL
SELECT 'PC Case', COUNT(pccase_id) FROM pccase UNION ALL
SELECT 'Printer', COUNT(printer_id) FROM printer UNION ALL
SELECT 'Processor', COUNT(processor_id) FROM processor UNION ALL
SELECT 'PSU', COUNT(psu_id) FROM psu UNION ALL
SELECT 'RAM', COUNT(ram_id) FROM ram UNION ALL
SELECT 'SSD', COUNT(ssd_id) FROM ssd UNION ALL
SELECT 'Wi-Fi Card', COUNT(wificard_id) FROM wificard
";

$result = mysqli_query($link, $query);

$labels = [];
$values = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['category'];
    $values[] = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PHP IMS</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="css/fullcalendar.css" />
    <link rel="stylesheet" href="css/matrix-style.css" />
    <link rel="stylesheet" href="css/matrix-media.css" />
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/jquery.gritter.css" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js -->
</head>

<body>

    <div id="header">
        <h2 style="color: white; position: absolute">
            <a href="index.php" style="color:white; margin-left: 30px; margin-top: 40px">PHP IMS</a>
        </h2>
    </div>

    <!-- Top Header Menu -->
    <div id="user-nav" class="navbar navbar-inverse">
        <ul class="nav">
            <!-- Removed user profile dropdown -->
        </ul>
    </div>

    <!-- Sidebar Menu -->
    <div id="sidebar">
        <ul>
            <li class="active">
                <a href="dashboard.php"><i class="icon icon-home"></i><span>Dashboard</span></a>
            </li>
            <li class="<?php echo ($current_page == 'add_new_user.php') ? 'active' : ''; ?>">
                <a href="add_new_user.php"><i class="icon icon-user"></i><span>User List</span></a>
            </li>
            <li class="<?php echo ($current_page == 'equipment.php') ? 'active' : ''; ?>">
                <a href="equipment.php"><i class="icon icon-user"></i><span>Equipment List</span></a>
            </li>
            <li class="<?php echo ($current_page == 'peripherals.php') ? 'active' : ''; ?>">
                <a href="peripherals.php"><i class="icon icon-user"></i><span>Peripherals List</span></a>
            </li>
            <li class="<?php echo ($current_page == 'otherdevices.php') ? 'active' : ''; ?>">
                <a href="otherdevices.php"><i class="icon icon-user"></i><span>Other Devices</span></a>
            </li>
            <li class="<?php echo ($current_page == 'logs.php') ? 'active' : ''; ?>">
                <a href="logs.php"><i class="icon icon-user"></i><span>Logs</span></a>
            </li>
            <li class="submenu">
                <a href="#"><i class="icon icon-th-list"></i> <span>Forms</span> <span class="label label-important">3</span></a>
                <ul>
                    <li><a href="form-common.php">Basic Form</a></li>
                    <li><a href="form-validation.php">Form with Validation</a></li>
                    <li><a href="form-wizard.php">Form with Wizard</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <!-- Main Container Part -->
    <div id="content">
        <!-- Breadcrumbs -->
        <div id="content-header">
            <div id="breadcrumb"><a href="index.php" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
        </div>

        <!-- Action Boxes -->
        <div class="container-fluid">
            <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
                <h3>Equipment   Distribution</h3>
                <div style="display: flex; justify-content: space-between;">
                    <!-- Pie chart container -->
                    <div style="width: 50%; height: 500px;">
                        <canvas id="idPieChart"></canvas>
                    </div>
                    <!-- Legend container -->
                    <div style="width: 40%; margin-left: 20px;">
                        <div id="pieChartLegend"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Main Container Part -->

    <!-- Footer Part -->
    <div class="row-fluid">
        <div id="footer" class="span12" style="color:white"> Designed And Developed By: Your Name</div>
    </div>
    <!-- End Footer Part -->

    <!-- JS Scripts -->
    <script>
        var ctx = document.getElementById('idPieChart').getContext('2d');

        var chartData = {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                data: <?php echo json_encode($values); ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#E7E9ED', '#C9CBCF', '#FF5733', '#C70039', '#900C3F', '#581845', '#2ECC71', '#1ABC9C', '#3498DB'],
                borderColor: "#fff",
                borderWidth: 2
            }]
        };

        var options = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Disable default legend
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + ' items';
                        }
                    }
                }
            }
        };

        var myPieChart = new Chart(ctx, {
            type: 'pie', // Pie chart type
            data: chartData,
            options: options
        });

        // Custom legend display
        var legendHtml = '';
        chartData.labels.forEach(function(label, index) {
            legendHtml += '<div><span style="background-color:' + chartData.datasets[0].backgroundColor[index] + '; width: 20px; height: 20px; display: inline-block;"></span> ' + label + '</div>';
        });
        document.getElementById('pieChartLegend').innerHTML = legendHtml;
    </script>

    <script src="js/excanvas.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.ui.custom.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.flot.min.js"></script>
    <script src="js/jquery.flot.resize.min.js"></script>
    <script src="js/jquery.peity.min.js"></script>
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/matrix.js"></script>
    <script src="js/matrix.dashboard.js"></script>
    <script src="js/jquery.gritter.min.js"></script>
    <script src="js/matrix.interface.js"></script>
    <script src="js/matrix.chat.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/matrix.form_validation.js"></script>
    <script src="js/jquery.wizard.js"></script>
    <script src="js/jquery.uniform.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/matrix.popover.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/matrix.tables.js"></script>

</body>

</html>
