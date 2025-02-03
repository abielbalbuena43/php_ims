<?php
// Include database connection
include "../user/connection.php";

// Get the search query from the request
$search = isset($_POST['query']) ? mysqli_real_escape_string($link, $_POST['query']) : '';

// Query to search for peripherals based on the search term
$query = "SELECT p.*, e.pcname FROM peripherals p 
          JOIN equipment e ON p.equipment_id = e.equipment_id 
          WHERE e.pcname LIKE '%$search%' 
             OR p.keyboard LIKE '%$search%' 
             OR p.mouse LIKE '%$search%' 
             OR p.printer LIKE '%$search%' 
             OR p.avr LIKE '%$search%'";

// Execute the query
$res = mysqli_query($link, $query);

// Check if any results were returned
if (mysqli_num_rows($res) > 0) {
    // Loop through the results and display them in a table
    while ($row = mysqli_fetch_array($res)) {
        echo "<tr>
                <td>{$row["pcname"]}</td>
                <td>{$row["keyboard"]}</td>
                <td>{$row["mouse"]}</td>
                <td>{$row["printer"]}</td>
                <td>{$row["avr"]}</td>
                <td>{$row["peripheral_dateadded"]}</td>
                <td>" . ($row["peripheral_dateaedited"] ? $row["peripheral_dateaedited"] : 'N/A') . "</td>
                <td><a href='edit_peripheral.php?peripheral_id={$row['peripheral_id']}' class='btn btn-primary'>Edit</a></td>
                <td>
                    <a href='delete_peripheral.php?peripheral_id={$row['peripheral_id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this peripheral?\");'>
                        Delete
                    </a>
                </td>
              </tr>";
    }
} else {
    // If no results found, display a message
    echo "<tr><td colspan='9' class='text-center'><strong>No matching peripherals found.</strong></td></tr>";
}
?>
