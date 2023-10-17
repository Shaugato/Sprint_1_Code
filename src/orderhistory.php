<?php
if (isset($_GET['use_remote_db']) && $_GET['use_remote_db'] == 'true') {
    $servername = "192.168.116.46";  // replace with the IP address of the host machine
} else {
    $servername = "localhost";
}
$username = "root";
$password = "";
$dbname = "user_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$filterUserID = '';
$sql = "SELECT u.username, p.ProductID, p.Name as ProductName, p.Price, pu.Quantity
        FROM Purchase pu
        INNER JOIN user_details u ON pu.UserID = u.UserID
        INNER JOIN Product p ON pu.ProductID = p.ProductID";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filterUserID = $_POST['userID'];
    if ($filterUserID !== '') {
        $sql .= " WHERE u.UserID = $filterUserID";
    }
}

$sql .= " ORDER BY pu.UserID";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        h1 {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background-color: #fff;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

<h1>Order History</h1>

<!-- Filter form -->
<form method="post">
    <label for="userID">Filter by User ID:</label>
    <input type="number" id="userID" name="userID">
    <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">Filter</button>
</form>

<!-- List all order history records -->
<table>
    <tr>
        <th>User Name</th>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total Cost</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_cost = $row["Price"] * $row["Quantity"];
            echo "<tr>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["ProductID"] . "</td>";
            echo "<td>" . $row["ProductName"] . "</td>";
            echo "<td>" . $row["Price"] . "</td>";
            echo "<td>" . $row["Quantity"] . "</td>";
            echo "<td>" . $total_cost . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No order history records found</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>
