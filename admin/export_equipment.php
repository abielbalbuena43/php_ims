<?php
session_start();
include "../admin/connection.php"; 

// Fetch all equipment details
$query = "SELECT * FROM equipment";
$result = mysqli_query($link, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Equipment</title>
    <style>
    body { 
        font-family: Arial, sans-serif; 
        font-size: 10px; 
        margin: 0; /* Remove default margins */
        padding: 0;
    }
    
    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 10px; 
        table-layout: fixed; /* Ensures proper spacing */
    }
    
    th, td { 
        border: 1px solid black; 
        padding: 4px; /* Reduce padding slightly */
        text-align: left; 
        font-size: 8px; /* Reduce font size for better fit */
        word-wrap: break-word;
        overflow: hidden;
    }
    
    th { 
        background-color: #f2f2f2; 
    }
    
    .print-button { 
        margin: 10px 0; 
        padding: 8px 16px; 
        background-color: #007bff; 
        color: white; 
        border: none; 
        cursor: pointer; 
        font-size: 10px; 
    }
    
    .print-button:hover { 
        background-color: #0056b3; 
    }
    
    @media print {
        .print-button { 
            display: none; /* Hide print button */
        }
        
        body { 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }
        
        table { 
            page-break-inside: avoid; 
        }
        
        @page { 
            size: A4 landscape; /* Force landscape print on A4 */
            margin: 5mm; /* Reduce margins to maximize table space */
        }
    }
</style>

</head>
<body>

    <h2>Equipment List</h2>
    <button class="print-button" onclick="window.print()">Export as PDF</button>

    <table>
        <tr>
            <th>PC Name</th><th>Assigned User</th><th>Processor</th>
            <th>Motherboard</th><th>RAM</th><th>HDD</th><th>SSD</th><th>GPU</th>
            <th>PSU</th><th>PC Case</th><th>Monitor</th><th>LAN Card</th>
            <th>WiFi Card</th><th>MAC Address</th><th>OS Version</th><th>MS Version</th>
            <th>Windows Key</th><th>MS Key</th><th>Date Added</th><th>Date Edited</th><th>Remarks</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['pcname']; ?></td>
            <td><?php echo $row['assigneduser']; ?></td>
            <td><?php echo $row['processor']; ?></td>
            <td><?php echo $row['motherboard']; ?></td>
            <td><?php echo $row['ram']; ?></td>
            <td><?php echo $row['hdd']; ?></td>
            <td><?php echo $row['ssd']; ?></td>
            <td><?php echo $row['gpu']; ?></td>
            <td><?php echo $row['psu']; ?></td>
            <td><?php echo $row['pccase']; ?></td>
            <td><?php echo $row['monitor']; ?></td>
            <td><?php echo $row['lancard']; ?></td>
            <td><?php echo $row['wificard']; ?></td>
            <td><?php echo $row['macaddress']; ?></td>
            <td><?php echo $row['osversion']; ?></td>
            <td><?php echo $row['msversion']; ?></td>
            <td><?php echo $row['windows_key']; ?></td>
            <td><?php echo $row['ms_key']; ?></td>
            <td><?php echo $row['date_added']; ?></td>
            <td><?php echo $row['date_edited']; ?></td>
            <td><?php echo $row['equipment_remarks']; ?></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
