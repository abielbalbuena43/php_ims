<?php
include "../user/connection.php";

// Check if the search query is set
if (isset($_POST['query'])) {
    // Escape the search query to prevent SQL injection
    $search = mysqli_real_escape_string($link, $_POST['query']);

    // Query to search in the fields of the 'otherdevices' table
    $query = "SELECT * FROM otherdevices 
              WHERE device_type LIKE '%$search%' 
                 OR device_name LIKE '%$search%' 
                 OR device_assettag LIKE '%$search%' 
                 OR device_brand LIKE '%$search%' 
                 OR device_modelnumber LIKE '%$search%' 
                 OR device_deviceage LIKE '%$search%' 
                 OR device_pcname LIKE '%$search%' 
                 OR device_macaddress LIKE '%$search%'";

    // Execute the query
    $res = mysqli_query($link, $query);
    
    // Check if any records match the search query
    if (mysqli_num_rows($res) > 0) {
        // Loop through each result and display it in the table format
        while ($row = mysqli_fetch_array($res)) {
            echo "<tr>
                    <td>{$row['device_type']}</td>
                    <td>{$row['device_name']}</td>
                    <td>{$row['device_assettag']}</td>
                    <td>{$row['device_brand']}</td>
                    <td>{$row['device_modelnumber']}</td>
                    <td>{$row['device_deviceage']}</td>
                    <td>{$row['device_pcname']}</td>
                    <td>{$row['device_macaddress']}</td>
                    <td>{$row['device_dateacquired']}</td>
                    <td>" . ($row['device_dateedited'] ? $row['device_dateedited'] : 'N/A') . "</td>
                    <td><a href='edit_otherdevices.php?od_id={$row['device_id']}' class='btn btn-primary'>Edit</a></td>
                    <td><a href='delete_otherdevices.php?od_id={$row['device_id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this device?\")'>Delete</a></td>
                  </tr>";
        }
    } else {
        // If no records are found, show a message
        echo "<tr><td colspan='12' class='text-center'><strong>No matching devices found.</strong></td></tr>";
    }
}
?>
