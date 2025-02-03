<?php
// Include the database connection
include "../user/connection.php";

// Check if the search query is set
if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($link, $_POST['query']);

    // Search query: Matches closest results using LIKE
    $query = "SELECT * FROM equipment 
              WHERE pcname LIKE '%$search%' 
                 OR assigneduser LIKE '%$search%' 
                 OR processor LIKE '%$search%' 
                 OR motherboard LIKE '%$search%' 
                 OR ram LIKE '%$search%' 
                 OR hdd LIKE '%$search%' 
                 OR ssd LIKE '%$search%' 
                 OR gpu LIKE '%$search%' 
                 OR psu LIKE '%$search%' 
                 OR pccase LIKE '%$search%' 
                 OR monitor LIKE '%$search%' 
                 OR lancard LIKE '%$search%' 
                 OR wificard LIKE '%$search%' 
                 OR macaddress LIKE '%$search%' 
                 OR osversion LIKE '%$search%' 
                 OR msversion LIKE '%$search%'";

    // Execute the query
    $res = mysqli_query($link, $query);

    // Check if any results were returned
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            echo "<tr>
                    <td>{$row['pcname']}</td>
                    <td>{$row['assigneduser']}</td>
                    <td>{$row['processor']}</td>
                    <td>{$row['motherboard']}</td>
                    <td>{$row['ram']}</td>
                    <td>{$row['hdd']}</td>
                    <td>{$row['ssd']}</td>
                    <td>{$row['gpu']}</td>
                    <td>{$row['psu']}</td>
                    <td>{$row['pccase']}</td>
                    <td>{$row['monitor']}</td>
                    <td>{$row['lancard']}</td>
                    <td>{$row['wificard']}</td>
                    <td>{$row['macaddress']}</td>
                    <td>{$row['osversion']}</td>
                    <td>{$row['msversion']}</td>
                    <td>{$row['windows_key']}</td>
                    <td>{$row['ms_key']}</td>
                    <td>{$row['date_added']}</td>
                    <td>{$row['date_edited']}</td>
                    <td><a href='edit_equipment.php?equipment_id={$row['equipment_id']}' class='btn btn-primary'>Edit</a></td>
                    <td><a href='delete_equipment.php?equipment_id={$row['equipment_id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this equipment?\");'>Delete</a></td>
                  </tr>";
        }
    } else {
        // If no results are found
        echo "<tr><td colspan='20' class='text-center'>No matching equipment found</td></tr>";
    }
}
?>
