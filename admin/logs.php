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

    // Output each log with a newline for better readability
    while ($row = mysqli_fetch_array($result)) {
        // Each log entry will be printed on a new line with a separator for clarity
        echo "Date: " . htmlspecialchars($row['date_edited']) . PHP_EOL;
        echo "Action: " . htmlspecialchars($row['action']) . PHP_EOL;
        echo str_repeat("=", 50) . PHP_EOL; // Line separator for clarity
    }

    // Stop further execution
    exit();
}

// Check if the "Delete Logs" button is clicked
if (isset($_POST['delete_logs'])) {
    // Query to delete all logs
    $deleteQuery = "DELETE FROM logs";
    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION['message'] = "Logs deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting logs: " . mysqli_error($link);
    }

    // Redirect to refresh the page
    header("Location: logs.php");
    exit();
}

// The rest of the script only executes if export or delete is not triggered
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

                <!-- Export and Delete Logs Buttons -->
                <div style="text-align: center; margin: 20px 0;">
                    <form method="post" onsubmit="return confirmAction(event)">
                        <button type="submit" name="export_logs" class="btn btn-primary">Export Logs as .txt</button>
                        <button type="submit" name="delete_logs" class="btn btn-danger">Delete Logs</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end-main-container-part-->

<script>
    function confirmAction(event) {
        const isDeleteAction = event.submitter.name === 'delete_logs';
        const confirmationMessage = isDeleteAction 
            ? 'Are you sure you want to delete all logs? This action cannot be undone.' 
            : 'Are you sure you want to export logs?';
        return confirm(confirmationMessage);
    }
</script>

<?php
include "footer.php";
?>
