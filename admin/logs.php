<?php
include "../user/connection.php";

// Start session to capture alerts
session_start();

// Check if the "Export Logs" button is clicked
if (isset($_POST['export_logs'])) {
    // Clean any previous output
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Generate the dynamic filename
    $filename = "imslog-" . date("Y-m-d") . ".txt";

    // Query to retrieve logs
    $query = "SELECT date_edited, action FROM logs ORDER BY date_edited DESC";
    $result = mysqli_query($link, $query);

    // Set headers for the file download
    header('Content-Type: text/plain');
    header("Content-Disposition: attachment; filename=\"$filename\"");

    // Output only the logs in plain text
    while ($row = mysqli_fetch_array($result)) {
        echo htmlspecialchars($row['date_edited']) . " " . htmlspecialchars($row['action']) . PHP_EOL;
    }

    // Stop further execution
    exit();
}

// The rest of the script only executes if export is not triggered
include "header.php";

// Query to retrieve logs for display
$query = "SELECT * FROM logs ORDER BY date_edited DESC";
$result = mysqli_query($link, $query);
?>

<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="index.html" class="tip-bottom"><i class="icon-home"></i> Logs</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <span class="icon"><i class="icon-align-justify"></i></span>
                        <h5>Action Logs</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <!-- Logs Display -->
                        <table style="width: 100%; border-collapse: collapse;">
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr style='border: none;'>";
                                    echo "<td style='padding: 10px; border: none;'>";
                                    echo htmlspecialchars($row['date_edited']) . " " . htmlspecialchars($row['action']);
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Export Logs Button -->
                <div style="text-align: center; margin: 20px 0;">
                    <form method="post">
                        <button type="submit" name="export_logs" class="btn btn-primary">Export Logs as .txt</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end-main-container-part-->

<?php
include "footer.php";
?>
