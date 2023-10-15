<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <link rel="stylesheet" href="styles/inventory.css">
</head>
<body>
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
