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

// Add a new purchase record when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'];
    $productID = $_POST['productID'];
    $quantity = $_POST['quantity'];

    $sql = "INSERT INTO Purchase (UserID, ProductID, Quantity) VALUES ($userID, $productID, $quantity)";
    if ($conn->query($sql) === TRUE) {
        echo "New purchase record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all purchase records
$sql = "SELECT * FROM Purchase";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="assets/css/navbar.css">
    <title>Purchase Records</title>
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

<h1>Purchase Records</h1>

<!-- List all purchase records -->
<table border="1">
    <tr>
        <th>Purchase ID</th>
        <th>User ID</th>
        <th>Product ID</th>
        <th>Quantity</th>
        <th>Date</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["PurchaseID"] . "</td>";
            echo "<td>" . $row["UserID"] . "</td>";
            echo "<td>" . $row["ProductID"] . "</td>";
            echo "<td>" . $row["Quantity"] . "</td>";
            echo "<td>" . $row["Date"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No purchase records found</td></tr>";
    }
    ?>
</table>



</body>
</html>
