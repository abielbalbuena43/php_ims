<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate"); // Prevent caching
header("Pragma: no-cache"); // For HTTP/1.0 compatibility
header("Expires: 0"); // Ensure content is not cached
include "session_verification.php";
include "header.php";
include "../user/connection.php";

echo '<link rel="stylesheet" href="css/dashboard.css">';

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
SELECT 'Wi-Fi Card', COUNT(wificard_id) FROM wificard UNION ALL
SELECT 'Total Peripherals', 
    (SELECT COUNT(keyboard_id) FROM keyboard) + 
    (SELECT COUNT(mouse_id) FROM mouse) + 
    (SELECT COUNT(printer_id) FROM printer) + 
    (SELECT COUNT(avr_id) FROM avr) AS count UNION ALL
SELECT 'Total Equipment', 
    (SELECT COUNT(processor_id) FROM processor) + 
    (SELECT COUNT(mobo_id) FROM motherboard) + 
    (SELECT COUNT(ram_id) FROM ram) + 
    (SELECT COUNT(hdd_id) FROM hdd) + 
    (SELECT COUNT(ssd_id) FROM ssd) + 
    (SELECT COUNT(gpu_id) FROM gpu) + 
    (SELECT COUNT(psu_id) FROM psu) + 
    (SELECT COUNT(pccase_id) FROM pccase) + 
    (SELECT COUNT(monitor_id) FROM monitor) + 
    (SELECT COUNT(lancard_id) FROM lancard) + 
    (SELECT COUNT(wificard_id) FROM wificard) 
    - (SELECT COUNT(keyboard_id) FROM keyboard)
    - (SELECT COUNT(mouse_id) FROM mouse)
    - (SELECT COUNT(printer_id) FROM printer)
    - (SELECT COUNT(avr_id) FROM avr) AS count UNION ALL
SELECT 'Total Other Devices', COUNT(device_id) FROM otherdevices
";
    

$result = mysqli_query($link, $query);

$totalPeripherals = 0;
$totalEquipment = 0;
$totalOtherDevices = 0;
$labels = [];
$values = [];

while ($row = mysqli_fetch_assoc($result)) {
    if (!in_array($row['category'], ['Total Peripherals', 'Total Equipment', 'Total Other Devices'])) {
        $labels[] = $row['category'];
        $values[] = $row['count'];
    }

    if ($row['category'] === 'Total Peripherals') {
        $totalPeripherals = (int) $row['count'];
    } elseif ($row['category'] === 'Total Equipment') {
        $totalEquipment = (int) $row['count'];
    } elseif ($row['category'] === 'Total Other Devices') {
        $totalOtherDevices = (int) $row['count'];
    }
}

$totalCount = $totalEquipment + $totalPeripherals;
?>

<!-- Start Form -->
<form method="post">
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/matrix-style.css" />
    <link rel="stylesheet" href="css/matrix-media.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="dashboard.php" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
    </div>

    <div class="container-fluid">
        <div class="dashboard-summary">
            <div class="summary-box">
                <h4>TOTAL EQUIPMENT</h4>
                <p><?php echo number_format($totalCount); ?></p>
            </div>
            <div class="summary-box">
                <h4>TOTAL PERIPHERALS</h4>
                <p><?php echo $totalPeripherals; ?></p>
            </div>
            <div class="summary-box">
                <h4>TOTAL OTHER DEVICES</h4>
                <p><?php echo $totalOtherDevices; ?></p>
            </div>
        </div>

        <!-- Charts -->
        <div class="chart-container">
            <div class="chart-box">
                <h4>Equipment Distribution</h4>
                <canvas id="idPieChart"></canvas>
            </div>

            <div class="chart-box">
                <h4>Equipment Count Comparison</h4>
                <canvas id="idBarChart"></canvas>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="logs-container">
            <div class="logs-box">
                <h4>Recent Logs</h4>
                <div class="widget-box">
                    <div class="widget-content nopadding">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tbody>
                                <?php
                                $logQuery = "SELECT l.date_edited, COALESCE(u.username, 'Unknown User') AS username, l.action 
                                            FROM logs l
                                            LEFT JOIN user_registration u ON l.user_id = u.user_id
                                            ORDER BY l.date_edited DESC LIMIT 5";

                                $logResult = mysqli_query($link, $logQuery);

                                while ($logRow = mysqli_fetch_array($logResult)) {
                                    echo "<tr style='border: none;'>";
                                    echo "<td style='padding: 10px; border: none;'>";
                                    echo "<strong>" . htmlspecialchars($logRow['username']) . ":</strong> "; // Display username
                                    echo htmlspecialchars($logRow['action']) . " ";
                                    echo "(" . htmlspecialchars($logRow['date_edited']) . ")"; // Date at the end
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
<!-- End Form -->

<script>
    var totalEquipment = <?php echo $totalEquipment; ?>;
    var totalPeripherals = <?php echo $totalPeripherals; ?>;
    var totalOtherDevices = <?php echo $totalOtherDevices; ?>;

    var ctxPie = document.getElementById('idPieChart').getContext('2d');
    var ctxBar = document.getElementById('idBarChart').getContext('2d');

    const chartColors = [
    '#FF0000', // Red (Primary)
    '#0000FF', // Blue (Primary)
    '#FFFF00', // Yellow (Primary)
    '#008000', // Green (Secondary)
    '#FFA500', // Orange (Secondary)
    '#800080', // Purple (Secondary)
    '#00CED1', // Dark Turquoise (Extra)
    '#FF4500', // Orange-Red (Extra)
    '#1E90FF', // Dodger Blue (Extra)
    '#32CD32', // Lime Green (Extra)
    '#FFD700', // Gold (Extra)
    '#8A2BE2', // Blue Violet (Extra)
    '#DC143C', // Crimson (Extra)
    '#4682B4', // Steel Blue (Extra)
    '#DA70D6'  // Orchid (Extra)
];



    var chartData = {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($values); ?>,
            backgroundColor: chartColors.slice(0, <?php echo count($labels); ?>),
            borderColor: "#fff",
            borderWidth: 2
        }]
    };

    new Chart(ctxPie, {
        type: 'pie',
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }, // Hides legend in Pie Chart
                tooltip: { 
                    callbacks: { 
                        label: function(tooltipItem) { 
                            return tooltipItem.label + ": " + tooltipItem.raw + " items"; 
                        } 
                    } 
                }
            }
        }
    });

    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Equipment Count',
                data: <?php echo json_encode($values); ?>,
                backgroundColor: chartColors.slice(0, <?php echo count($labels); ?>),
                borderColor: chartColors.slice(0, <?php echo count($labels); ?>),
                borderWidth: 1
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false, 
            scales: { 
                y: { beginAtZero: true, ticks: { stepSize: 1 } } 
            },
            plugins: {
                legend: { display: true }, // Keeps legend in Bar Chart
                tooltip: { 
                    callbacks: { 
                        label: function(tooltipItem) { 
                            return tooltipItem.label + ": " + tooltipItem.raw + " items"; 
                        } 
                    } 
                }
            }
        }
    });
</script>



<?php include "footer.php"; ?>

</body>
</html>