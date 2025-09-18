<?php
session_start();
include "../admin/connection.php"; 

// Check if equipment_id is provided
if (!isset($_GET['equipment_id']) || empty($_GET['equipment_id'])) {
    die("No equipment ID provided.");
}

$equipment_id = intval($_GET['equipment_id']); // sanitize input

// Fetch the specific equipment details
$query = "SELECT * FROM equipment WHERE equipment_id = $equipment_id";
$result = mysqli_query($link, $query);

// Check if equipment exists
if (mysqli_num_rows($result) === 0) {
    die("No equipment found with ID $equipment_id.");
}
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
            margin: 0;
            padding: 0;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
            table-layout: fixed; 
        }
        
        th, td { 
            border: 1px solid black; 
            padding: 4px; 
            text-align: left; 
            font-size: 8px; 
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
                display: none;
            }
            body { 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
            }
            table { 
                page-break-inside: avoid; 
            }
            @page { 
                size: A4 landscape;
                margin: 5mm;
            }
        }
    </style>
</head>
<body>

    <button class="print-button" onclick="window.print()">Export as PDF</button>

    <table>
        <tr>
            <th>PC Name</th><th>Department</th><th>Assigned User</th><th>Processor</th>
            <th>Motherboard</th><th>RAM</th><th>HDD</th><th>SSD</th><th>GPU</th>
            <th>PSU</th><th>PC Case</th><th>Monitor</th>
            <th>MAC Address</th><th>OS Version</th><th>MS Version</th>
            <th>Windows Key</th><th>MS Key</th><th>Date Added</th><th>Date Edited</th><th>Remarks</th>
        </tr>
        
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['pcname']); ?></td>
            <td><?php echo htmlspecialchars($row['department']); ?></td>
            <td><?php echo htmlspecialchars($row['assigneduser']); ?></td>
            <td><?php echo htmlspecialchars($row['processor']); ?></td>
            <td><?php echo htmlspecialchars($row['motherboard']); ?></td>
            <td><?php echo htmlspecialchars($row['ram']); ?></td>
            <td><?php echo htmlspecialchars($row['hdd']); ?></td>
            <td><?php echo htmlspecialchars($row['ssd']); ?></td>
            <td><?php echo htmlspecialchars($row['gpu']); ?></td>
            <td><?php echo htmlspecialchars($row['psu']); ?></td>
            <td><?php echo htmlspecialchars($row['pccase']); ?></td>
            <td><?php echo htmlspecialchars($row['monitor']); ?></td>
            <td><?php echo htmlspecialchars($row['macaddress']); ?></td>
            <td><?php echo htmlspecialchars($row['osversion']); ?></td>
            <td><?php echo htmlspecialchars($row['msversion']); ?></td>
            <td><?php echo htmlspecialchars($row['windows_key']); ?></td>
            <td><?php echo htmlspecialchars($row['ms_key']); ?></td>
            <td><?php echo htmlspecialchars($row['date_added']); ?></td>
            <td><?php echo htmlspecialchars($row['date_edited']); ?></td>
            <td><?php echo htmlspecialchars($row['equipment_remarks']); ?></td>
        </tr>
        <?php } ?>
    </table>

</body>
</html>
