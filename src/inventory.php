<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link rel="stylesheet" type="text/css" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/inventory.css">
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


    <header>
        <h1>Inventory Management System</h1>
    </header>

    <section id="inventory-list">
        <h2>Current Inventory</h2>
        <!-- PHP code to fetch inventory data from the database -->
        <?php
        $conn = new mysqli("localhost", "root", "", "user_database");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT i.InventoryID, i.ProductID, p.Name, i.StockLevel, i.ReorderLevel 
                FROM inventory i 
                JOIN product p ON i.ProductID = p.ProductID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>Inventory ID</th>
                            <th>Product Name</th>
                            <th>Stock Level</th>
                            <th>Reorder Level</th>
                        </tr>
                    </thead>
                    <tbody>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["InventoryID"]. "</td>
                        <td>" . $row["Name"]. "</td>
                        <td>" . $row["StockLevel"]. "</td>
                        <td>" . $row["ReorderLevel"]. "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "No inventory data found.";
        }
        $conn->close();
        ?>
    </section>

    <footer>
        <p>&copy; 2023 Inventory Management System</p>
    </footer>
</body>
</html>
