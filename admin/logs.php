<?php
session_start();
include "session_verification.php";
include "header.php";
include "../user/connection.php";

// Ensure user is logged in
$user_id = $_SESSION['user_id'] ?? null;

// Check if the "Export Logs" button is clicked
if (isset($_POST['export_logs'])) {
    if (ob_get_level()) {
        ob_end_clean();
    }

    $filename = "imslog-" . date("Y-m-d") . ".txt";

    // Query to retrieve logs with user_id and username
    $query = "SELECT l.date_edited, l.user_id, COALESCE(u.username, 'Unknown User') AS username, l.action 
              FROM logs l
              LEFT JOIN user_registration u ON l.user_id = u.user_id
              ORDER BY l.date_edited DESC";
              
    $result = mysqli_query($link, $query);

    header('Content-Type: text/plain');
    header("Content-Disposition: attachment; filename=\"$filename\"");

    while ($row = mysqli_fetch_array($result)) {
        echo "User: " . htmlspecialchars($row['username']) . PHP_EOL;
        echo "Action: " . htmlspecialchars($row['action']) . PHP_EOL;
        echo "Date: " . htmlspecialchars($row['date_edited']) . PHP_EOL;
        echo str_repeat("=", 50) . PHP_EOL;
    }

    exit();
}

// Check if the "Delete Logs" button is clicked
if (isset($_POST['delete_logs'])) {
    $deleteQuery = "DELETE FROM logs";
    if (mysqli_query($link, $deleteQuery)) {
        $_SESSION['message'] = "Logs deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting logs: " . mysqli_error($link);
    }

    header("Location: logs.php");
    exit();
}

// Query to retrieve logs with user_id and username
$query = "SELECT l.date_edited, l.user_id, 
                 COALESCE(u.username, 'Unknown User') AS username, 
                 l.action 
          FROM logs l
          LEFT JOIN user_registration u ON l.user_id = u.user_id
          ORDER BY l.date_edited DESC";

$result = mysqli_query($link, $query);
?>

<!--main-container-part-->
<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="logs.php" class="tip-bottom"><i class="icon-home"></i> Logs</a></div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid" style="background-color: white; min-height: 1000px; padding:10px;">
            <div class="span12">
                <div class="widget-box">
                    
                    <div class="widget-content nopadding">
                        <!-- Logs Display -->
                        <table style="width: 100%; border-collapse: collapse;">
                            <tbody>
                                <?php
                                while ($row = mysqli_fetch_array($result)) {
                                    echo "<tr style='border: none;'>";
                                    echo "<td style='padding: 10px; border: none;'>";
                                    echo "<strong>" . htmlspecialchars($row['username']) . "</strong>: ";
                                    echo htmlspecialchars($row['action']) . " (" . htmlspecialchars($row['date_edited']) . ")";
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