<?php
session_start();
include "session_verification.php";
include "header.php";   
include "../user/connection.php";

echo '<link rel="stylesheet" href="css/dashboard.css">';

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
$totalCount = 0;
$maxCategory = "";
$minCategory = "";
$maxValue = 0;
$minValue = PHP_INT_MAX;

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['category'];
    $values[] = $row['count'];
    $totalCount += $row['count'];

    // Find most common category
    if ($row['count'] > $maxValue) {
        $maxValue = $row['count'];
        $maxCategory = $row['category'];
    }

    // Find least common category
    if ($row['count'] < $minValue && $row['count'] > 0) {
        $minValue = $row['count'];
        $minCategory = $row['category'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>PHP IMS - Dashboard</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/matrix-style.css" />
    <link rel="stylesheet" href="css/matrix-media.css" />
    <link rel="stylesheet" href="font-awesome/css/font-awesome.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js -->
    <style>
        .dashboard-summary {
            display: flex;
            justify-content: space-around;
            text-align: center;
            padding: 20px;
            background: #f4f4f4;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .summary-box {
            background: #fff;
            padding: 20px;
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
    </style>
</head>

<body>

    <div id="content">
        <div id="content-header">
            <div id="breadcrumb"><a href="dashboard.php" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
        </div>

        <div class="container-fluid">
            <h3>Equipment Overview</h3>

            <!-- Dashboard Summary -->
            <div class="dashboard-summary">
                <div class="summary-box">
                    <h4>Total Equipment</h4>
                    <p><?php echo number_format($totalCount); ?></p>
                </div>
                <div class="summary-box">
                    <h4>Most Common</h4>
                    <p><?php echo $maxCategory . " (" . $maxValue . ")"; ?></p>
                </div>
                <div class="summary-box">
                    <h4>Least Common</h4>
                    <p><?php echo $minCategory . " (" . $minValue . ")"; ?></p>
                </div>
            </div>

            <!-- Charts -->
            <div style="display: flex; justify-content: space-between;">
                <!-- Pie Chart -->
                <div style="width: 48%;">
                    <h4>Equipment Distribution</h4>
                    <canvas id="idPieChart"></canvas>
                </div>
                
                <!-- Bar Chart -->
                <div style="width: 48%;">
                    <h4>Equipment Count Comparison</h4>
                    <canvas id="idBarChart"></canvas>
                </div>
            </div>
        </div>
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

        var myPieChart = new Chart(ctxPie, {
    type: 'pie',
    data: chartData,
    options: {
        responsive: true,
        maintainAspectRatio: true,  // Keeps a proper size
        aspectRatio: 2,  // Ensures a reasonable width-to-height ratio
        layout: {
            padding: 20 // Adds padding to prevent overflow
        },
        plugins: {
            legend: { display: true },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw + ' items';
                    }
                }
            }
        }
    }
});


        var myBarChart = new Chart(ctxBar, {
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
        maintainAspectRatio: true, /* Ensures the chart does not stretch */
        aspectRatio: 2, /* Adjusts the width-to-height ratio */
        scales: {
            y: { beginAtZero: true }
        }
    }
});

    </script>

</body>

</html>

<?php
include "footer.php";
?>
