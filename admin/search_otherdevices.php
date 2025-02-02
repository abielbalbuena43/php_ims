<?php
include "../user/connection.php";

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($link, $_POST['query']);

    // Search query: Matches closest results using LIKE
    $query = "SELECT * FROM otherdevices 
              WHERE od_name LIKE '%$search%' 
                 OR od_pcname LIKE '%$search%' 
                 OR od_assettag LIKE '%$search%' 
                 OR od_brand LIKE '%$search%' 
                 OR od_modelnumber LIKE '%$search%' 
                 OR od_macaddress LIKE '%$search%'";

    $res = mysqli_query($link, $query);
    
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            echo "<tr>
                    <td>{$row["od_name"]}</td>
                    <td>{$row["od_pcname"]}</td>
                    <td>{$row["od_assettag"]}</td>
                    <td>{$row["od_brand"]}</td>
                    <td>{$row["od_modelnumber"]}</td>
                    <td>{$row["od_deviceage"]}</td>
                    <td>{$row["od_macaddress"]}</td>
                    <td>{$row["od_dateacquired"]}</td>
                    <td>" . ($row["od_dateedited"] ? $row["od_dateedited"] : 'N/A') . "</td>
                    <td>{$row["od_remarks"]}</td>
                    <td><a href='edit_otherdevices.php?od_id={$row['od_id']}' class='btn btn-primary'>Edit</a></td>
                    <td><a href='delete_otherdevices.php?od_id={$row['od_id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='12' class='text-center'><strong>No matching devices found.</strong></td></tr>";
    }
}
?>
