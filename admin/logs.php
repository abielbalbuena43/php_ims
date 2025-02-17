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

// Insert specific log actions when updating equipment or devices
if (isset($_POST["submit1"])) {
    // Get the form data and escape special characters
    $pcname = mysqli_real_escape_string($link, $_POST["pcname"]);
    $assigneduser = mysqli_real_escape_string($link, $_POST["assigneduser"]);
    // Add other form fields here...

    // Fetch the existing equipment details to compare
    $equipment_id = $_GET["equipment_id"];
    $query = "SELECT * FROM equipment WHERE equipment_id = $equipment_id";
    $result = mysqli_query($link, $query);
    $equipment = mysqli_fetch_array($result);

    // Prepare the update statement
    $query = "UPDATE equipment SET 
                pcname = ?, assigneduser = ?, 
                date_edited = NOW()
                WHERE equipment_id = ?";

    // Prepare the statement
    $stmt = mysqli_prepare($link, $query);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssi", 
        $pcname, $assigneduser, $equipment_id);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // Log action
        $old_pcname = $equipment['pcname'];
        $old_assigneduser = $equipment['assigneduser'];

        $log_action = " Updated equipment (ID: {$equipment_id}): ";
        if ($old_pcname !== $pcname) $log_action .= "PC Name: $old_pcname → $pcname, ";
        if ($old_assigneduser !== $assigneduser) $log_action .= "Assigned User: $old_assigneduser → $assigneduser, ";

        // Trim trailing comma
        $log_action = rtrim($log_action, ", ");

        // Insert log into the database
        $insert_log_query = "INSERT INTO logs (user_id, action, date_edited) 
                             VALUES ('" . $_SESSION['user_id'] . "', '$log_action', NOW())";
        mysqli_query($link, $insert_log_query);

        $_SESSION["alert"] = "success";
        header("Location: edit_equipment.php?equipment_id=$equipment_id");
        exit();
    } else {
        $_SESSION["alert"] = "error";
        header("Location: edit_equipment.php?equipment_id=$equipment_id");
        exit();
    }
}
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