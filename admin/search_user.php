<?php
include "../user/connection.php";

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($link, $_POST['query']);
    
    // Search query: Matches closest results using LIKE
    $query = "SELECT * FROM user_registration 
              WHERE firstname LIKE '%$search%' 
                 OR lastname LIKE '%$search%' 
                 OR username LIKE '%$search%' 
                 OR department LIKE '%$search%' 
                 OR role LIKE '%$search%'";

    $res = mysqli_query($link, $query);

    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_array($res)) {
            echo "<tr>
                    <td>{$row['firstname']}</td>
                    <td>{$row['lastname']}</td>
                    <td>{$row['username']}</td>
                    <td>{$row['department']}</td>
                    <td>{$row['role']}</td>
                    <td>{$row['status']}</td>
                    <td><a href='edit_user.php?user_id={$row['user_id']}' class='btn btn-warning'>Edit</a></td>
                    <td><a href='delete_user.php?user_id={$row['user_id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8' class='text-center'>No matching users found</td></tr>";
    }
}
?>
