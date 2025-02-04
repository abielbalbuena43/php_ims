<?php
// Include database connection
include "../user/connection.php";

// Get the search query from the request
$search = isset($_POST['query']) ? mysqli_real_escape_string($link, $_POST['query']) : '';

// Query to search for software based on the search term
$query = "SELECT s.*, e.pcname FROM software s 
          JOIN equipment e ON s.equipment_id = e.equipment_id 
          WHERE e.pcname LIKE '%$search%' 
             OR s.software_msos LIKE '%$search%' 
             OR s.software_msoffice LIKE '%$search%' 
             OR s.software_adobe LIKE '%$search%' 
             OR s.software_remarks LIKE '%$search%'";

// Execute the query
$res = mysqli_query($link, $query);

// Check if any results were returned
if (mysqli_num_rows($res) > 0) {
    // Loop through the results and display them in a table
    while ($row = mysqli_fetch_array($res)) {
        echo "<tr>
                <td>{$row['pcname']}</td>
                <td>{$row['software_msos']}</td>
                <td>{$row['software_msoffice']}</td>
                <td>{$row['software_adobe']}</td>
                <td>" . ($row['software_dateadded'] ? $row['software_dateadded'] : 'N/A') . "</td>
                <td>" . ($row['software_dateedited'] ? $row['software_dateedited'] : 'N/A') . "</td>
                <td>{$row['software_remarks']}</td>
                <td><a href='edit_software.php?equipment_id={$row['equipment_id']}' class='btn btn-primary'>Edit</a></td>
                <td>
                    <a href='delete_software.php?software_id={$row['software_id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this software?\");'>
                        Delete
                    </a>
                </td>
              </tr>";
    }
} else {
    // If no results found, display a message
    echo "<tr><td colspan='9' class='text-center'><strong>No matching software found.</strong></td></tr>";
}
?>
