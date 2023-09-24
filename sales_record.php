<?php
if (isset($_GET['use_remote_db']) && $_GET['use_remote_db'] == 'true') {
    $servername = "192.168.116.46";  // replace with the IP address of the host machine
} else {
    $servername = "localhost";
}
$username = "root";
$password = "";
$dbname = "user_databse";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM SalesRecord";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Sales Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>

<h1>Sales Records</h1>

<table>
    <tr>
        <th>Sales ID</th>
        <th>User ID</th>
        <th>Product ID</th>
        <th>Quantity</th>
        <th>Total Amount</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["SalesID"] . '</td>';
            echo '<td>' . $row["UserID"] . '</td>';
            echo '<td>' . $row["ProductID"] . '</td>';
            echo '<td>' . $row["Quantity"] . '</td>';
            echo '<td>$' . $row["TotalAmount"] . '</td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='5'>No sales records found</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>
