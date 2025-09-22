<?php
session_start();
include "../admin/connection.php"; 

// Check if equipment_id is provided
if (!isset($_GET['equipment_id']) || empty($_GET['equipment_id'])) {
    die("No equipment ID provided.");
}

$equipment_id = intval($_GET['equipment_id']); // Sanitize input

// Fetch the specific equipment details
$query = "SELECT * FROM equipment WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);

// Check if equipment exists
if (mysqli_num_rows($result) === 0) {
    die("No equipment found with ID $equipment_id.");
}

// Fetch the row as an array
$row = mysqli_fetch_assoc($result);

// Get PC Name for filename and sanitize it
$pc_name = preg_replace('/[^A-Za-z0-9_\-]/', '_', $row['pcname']); 
if (empty($pc_name)) {
    $pc_name = "equipment";
}

// Reset pointer so we can reuse $row later
mysqli_data_seek($result, 0);

// Set headers to export as Excel file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename={$pc_name}_export.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Start table
echo "<table border='1'>";
echo "<tr>
        <th>PC Name</th>
        <th>Department</th>
        <th>Assigned User</th>
        <th>Processor</th>
        <th>Motherboard</th>
        <th>RAM</th>
        <th>HDD</th>
        <th>SSD</th>
        <th>GPU</th>
        <th>PSU</th>
        <th>PC Case</th>
        <th>Monitor</th>
        <th>MAC Address</th>
        <th>OS Version</th>
        <th>MS Version</th>
        <th>Windows Key</th>
        <th>MS Key</th>
        <th>Date Added</th>
        <th>Date Edited</th>
        <th>Remarks</th>
      </tr>";

// Output the single equipment row
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>".htmlspecialchars($row['pcname'])."</td>
            <td>".htmlspecialchars($row['department'])."</td>
            <td>".htmlspecialchars($row['assigneduser'])."</td>
            <td>".htmlspecialchars($row['processor'])."</td>
            <td>".htmlspecialchars($row['motherboard'])."</td>
            <td>".htmlspecialchars($row['ram'])."</td>
            <td>".htmlspecialchars($row['hdd'])."</td>
            <td>".htmlspecialchars($row['ssd'])."</td>
            <td>".htmlspecialchars($row['gpu'])."</td>
            <td>".htmlspecialchars($row['psu'])."</td>
            <td>".htmlspecialchars($row['pccase'])."</td>
            <td>".htmlspecialchars($row['monitor'])."</td>
            <td>".htmlspecialchars($row['macaddress'])."</td>
            <td>".htmlspecialchars($row['osversion'])."</td>
            <td>".htmlspecialchars($row['msversion'])."</td>
            <td>".htmlspecialchars($row['windows_key'])."</td>
            <td>".htmlspecialchars($row['ms_key'])."</td>
            <td>".htmlspecialchars($row['date_added'])."</td>
            <td>".htmlspecialchars($row['date_edited'])."</td>
            <td>".htmlspecialchars($row['equipment_remarks'])."</td>
          </tr>";
}

echo "</table>";
?>
