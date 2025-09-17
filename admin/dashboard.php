<?php
session_start();
header("Cache-Control: no-cache, no-store, must-revalidate"); // Prevent caching
header("Pragma: no-cache"); // For HTTP/1.0 compatibility
header("Expires: 0"); // Ensure content is not cached
include "session_verification.php";
include "header.php";
include "../admin/connection.php";

echo '<link rel="stylesheet" href="css/dashboard.css">';

/* ===============================
   BAR CHART QUERY (Unchanged)
   =============================== */
$query = "
SELECT 'AVR' AS category, COUNT(avr_id) AS count FROM avr UNION ALL
SELECT 'GPU', COUNT(gpu_id) FROM gpu UNION ALL
SELECT 'HDD', COUNT(hdd_id) FROM hdd UNION ALL
SELECT 'Keyboard', COUNT(keyboard_id) FROM keyboard UNION ALL
SELECT 'Monitor', COUNT(monitor_id) FROM monitor UNION ALL
SELECT 'Motherboard', COUNT(mobo_id) FROM motherboard UNION ALL
SELECT 'Mouse', COUNT(mouse_id) FROM mouse UNION ALL
SELECT 'PC Case', COUNT(pccase_id) FROM pccase UNION ALL
SELECT 'Printer', COUNT(printer_id) FROM printer UNION ALL
SELECT 'Processor', COUNT(processor_id) FROM processor UNION ALL
SELECT 'PSU', COUNT(psu_id) FROM psu UNION ALL
SELECT 'RAM', COUNT(ram_id) FROM ram UNION ALL
SELECT 'SSD', COUNT(ssd_id) FROM ssd UNION ALL
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
    (SELECT COUNT(monitor_id) FROM monitor) -
    (SELECT COUNT(keyboard_id) FROM keyboard) -
    (SELECT COUNT(mouse_id) FROM mouse) -
    (SELECT COUNT(printer_id) FROM printer) -
    (SELECT COUNT(avr_id) FROM avr) AS count UNION ALL
SELECT 'Total Other Devices', COUNT(device_id) FROM otherdevices
";

$result = mysqli_query($link, $query);

$totalPeripherals = 0;
$totalEquipment = 0;
$totalOtherDevices = 0;
$labelsBar = [];
$valuesBar = [];

// Process bar chart data
while ($row = mysqli_fetch_assoc($result)) {
    if (!in_array($row['category'], ['Total Peripherals', 'Total Equipment', 'Total Other Devices'])) {
        $labelsBar[] = $row['category'];
        $valuesBar[] = $row['count'];
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

/* ===============================
   PIE CHART QUERY (Modified)
   =============================== */
$pieQuery = "
SELECT equipment_remarks AS category, COUNT(*) AS count
FROM equipment
WHERE equipment_remarks IN ('Available', 'In Use', 'Defective', 'For Repair', 'Under Repair', 'For Disposal')
GROUP BY equipment_remarks
";

$pieResult = mysqli_query($link, $pieQuery);

$labelsPie = [];
$valuesPie = [];

while ($pieRow = mysqli_fetch_assoc($pieResult)) {
    $labelsPie[] = $pieRow['category'];
    $valuesPie[] = $pieRow['count'];
}
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
                <h4>Equipment Remarks Distribution</h4>
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
                                    echo "<strong>" . htmlspecialchars($logRow['username']) . ":</strong> ";
                                    echo htmlspecialchars($logRow['action']) . " ";
                                    echo "(" . htmlspecialchars($logRow['date_edited']) . ")";
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
    var ctxPie = document.getElementById('idPieChart').getContext('2d');
    var ctxBar = document.getElementById('idBarChart').getContext('2d');

    const chartColors = [
        '#FF0000', '#0000FF', '#FFFF00', '#008000',
        '#FFA500', '#800080', '#00CED1', '#FF4500',
        '#1E90FF', '#32CD32', '#FFD700', '#8A2BE2'
    ];

    /* PIE CHART DATA */
    var pieData = {
        labels: <?php echo json_encode($labelsPie); ?>,
        datasets: [{
            data: <?php echo json_encode($valuesPie); ?>,
            backgroundColor: chartColors.slice(0, <?php echo count($labelsPie); ?>),
            borderColor: "#fff",
            borderWidth: 2
        }]
    };

    /* BAR CHART DATA */
    var barData = {
        labels: <?php echo json_encode($labelsBar); ?>,
        datasets: [{
            label: 'Equipment Count',
            data: <?php echo json_encode($valuesBar); ?>,
            backgroundColor: chartColors.slice(0, <?php echo count($labelsBar); ?>),
            borderColor: chartColors.slice(0, <?php echo count($labelsBar); ?>),
            borderWidth: 1
        }]
    };

    /* PIE CHART */
    new Chart(ctxPie, {
        type: 'pie',
        data: pieData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
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

    /* BAR CHART */
    new Chart(ctxBar, {
        type: 'bar',
        data: barData,
        options: { 
            responsive: true, 
            maintainAspectRatio: false, 
            scales: { 
                y: { beginAtZero: true, ticks: { stepSize: 1 } } 
            },
            plugins: {
                legend: { display: true },
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
