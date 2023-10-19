<?php
if (isset($_GET['use_remote_db']) && $_GET['use_remote_db'] == 'true') {
    $servername = "192.168.116.46";  // replace with the IP address of the host machine
} else {
    $servername = "localhost";
}
$username = "root";
$password = "";
$dbname = "user_database";

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
    <link rel="stylesheet" type="text/css" href="assets/css/navbar.css">
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

    <script>
        function redirectToDashboard() {
          window.location.href = "dashboard.html"; // Replace with the actual URL of your dashboard page
        }
    </script>
    <div class="navbar">
        <a href="index.html">Home</a>
        <div class="dropdown">
          <button onclick="redirectToDashboard()" class="dropbtn">Dashboard</button>
          <div class="dropdown-content">
            <a href="inventory.php">Inventory</a>
            <a href="reports.php">Reports</a>
            <a href="sales_record.php">Sales</a>
            <a href="orderhistory.php">Order History</a>
            <a href="purchase.php">Purchase</a>
            <a href="products.php">Products</a>
          </div>
        </div> 
        <a href="logout.php" id="logout">Logout</a>
      </div>

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
