<?php
session_start();
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
    (SELECT COUNT(wificard_id) FROM wificard) AS count UNION ALL
SELECT 'Total Other Devices', COUNT(device_id) FROM otherdevices
";

$result = mysqli_query($link, $query);

$totalPeripherals = 0;
$totalOtherDevices = 0;


$totalPeripherals = 0;
$totalEquipment = 0;
$totalOtherDevices = 0;
$labels = [];
$values = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Exclude "Total Peripherals," "Total Equipment," and "Total Other Devices" from Pie Chart
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


// Make sure we exclude Total Other Devices from final total
$totalCount = $totalEquipment + $totalPeripherals;
?>

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

    <style>
        .dashboard-summary {
            display: flex;
            justify-content: space-around;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .summary-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            width: 30%;
        }

        .summary-box h4 {
            margin: 10px 0;
            color: #333;
        }

        .summary-box p {
            font-size: 20px;
            font-weight: bold;
            color: #115486;
        }

        .chart-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 40px; /* 👈 Increase space between charts */
        padding: 10px;
        }



        .chart-box {
        width: 75%;
        min-height: 300px;
        padding: 70px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 300px;
        }

        @media (max-width: 768px) {
            .dashboard-summary {
                flex-direction: column;
                align-items: center;
            }

            .logs-box {
            width: 35%; /* Half the size of charts */
            min-height: 50px; /* Reduce height */
            padding: 35px; /* Reduce padding */
            background: #fff; /* White background like charts */
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            }

}
            .summary-box {
                width: 80%;
                margin-bottom: 15px;
            }

            
    </style>
</head>

<body>

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="dashboard.php" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
    </div>

    <form method="post" action="#">
    <div class="container-fluid">
        <h3>Equipment Overview</h3>

        <div class="dashboard-summary">
        <div class="summary-box">
            <h4>Total Equipment</h4>
            <p><?php echo number_format($totalCount); ?></p>
        </div>
        <div class="summary-box">
            <h4>Total Peripherals</h4>
            <p><?php echo $totalPeripherals; ?></p>
        </div>
        <div class="summary-box">
            <h4>Total Other Devices</h4>
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
    <div class="logs-box">
    <h4>Recent Logs</h4>
    <div class="widget-box">
        <div class="widget-title">
            <span class="icon"><i class="icon-align-justify"></i></span>
            <h5>Action Logs</h5>
        </div>
        <div class="widget-content nopadding">
            <table style="width: 100%; border-collapse: collapse;">
                <tbody>
                    <?php
                    $logQuery = "SELECT * FROM logs ORDER BY date_edited DESC";
                    $logResult = mysqli_query($link, $logQuery);
                    while ($logRow = mysqli_fetch_array($logResult)) {
                        echo "<tr style='border: none;'>";
                        echo "<td style='padding: 10px; border: none;'>";
                        echo htmlspecialchars($logRow['date_edited']) . " " . htmlspecialchars($logRow['action']);
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </form>
</div>


<!-- JavaScript for Charts -->
<script>
    var ctxPie = document.getElementById('idPieChart').getContext('2d');
    var ctxBar = document.getElementById('idBarChart').getContext('2d');

    var chartData = {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($values); ?>,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#E7E9ED', '#C9CBCF', '#FF5733', '#C70039', '#900C3F', '#581845', '#2ECC71', '#1ABC9C', '#3498DB'],
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
            legend: { 
                display: true, 
                position: 'right' // Moves legend to the right for better readability
            },
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
                    backgroundColor: '#115486',
                    borderColor: '#115486',
                    borderWidth: 1
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                scales: { 
                    y: { 
                        beginAtZero: true, 
                        ticks: { 
                            stepSize: 1 // Ensures only whole numbers appear in Y-axis
                        } 
                    } 
                },
                plugins: {
                    legend: { 
                        display: true 
                    },
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