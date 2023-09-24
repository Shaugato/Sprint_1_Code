<?php
if (isset($_GET['use_remote_db']) && $_GET['use_remote_db'] == 'true') {
    $servername = "192.168.116.46";  // replace with the IP address of the host machine
} else {
    $servername = "localhost";
}
$username = "root";
$password = "";
$dbname = "user_databse";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle purchase form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'];
    $productID = $_POST['productID'];
    $quantity = $_POST['quantity'];

    // Start a new transaction
    $conn->begin_transaction();

    try {
        // Insert into Purchase table
        $purchase_sql = "INSERT INTO Purchase (UserID, ProductID, Quantity) VALUES ($userID, $productID, $quantity)";
        $conn->query($purchase_sql);

        // Update Inventory table
        $update_sql = "UPDATE Inventory SET StockLevel = StockLevel - $quantity WHERE ProductID = $productID";
        $conn->query($update_sql);

        // Update ReorderLevel in Inventory table based on new StockLevel
        $inventory_sql = "SELECT StockLevel FROM Inventory WHERE ProductID = $productID";
        $result = $conn->query($inventory_sql);
        $row = $result->fetch_assoc();
        $new_stock_level = $row['StockLevel'];
        
        $reorder_level = 'Low';
        if ($new_stock_level < 4) {
            $reorder_level = 'High';
        } elseif ($new_stock_level >= 4 && $new_stock_level <= 7) {
            $reorder_level = 'Medium';
        }

        $reorder_sql = "UPDATE Inventory SET ReorderLevel = '$reorder_level' WHERE ProductID = $productID";
        $conn->query($reorder_sql);

        // Commit the transaction
        $conn->commit();
        echo "New purchase record created successfully, and inventory updated.";
    } catch (Exception $e) {
        // An error occurred; rollback the transaction
        $conn->rollback();
        echo "An error occurred: " . $e->getMessage();
    }
}

$sql = "SELECT * FROM Product";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Listing</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
        }
        .product-card {
            border: 1px solid #ccc;
            margin: 15px;
            padding: 15px;
            width: 30%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product-card h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }
        .product-card p {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h1>Products</h1>
<div class="container">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<h2>' . $row["Name"] . '</h2>';
            echo '<p><strong>Description:</strong> ' . $row["Description"] . '</p>';
            echo '<p><strong>Price:</strong> $' . $row["Price"] . '</p>';
            echo '<p><strong>Category:</strong> ' . $row["Category"] . '</p>';
            
            // Embedded purchase form
            echo '<form method="post">';
            echo '<input type="hidden" name="productID" value="' . $row["ProductID"] . '">';
            echo '<label for="userID">User ID:</label>';
            echo '<input type="number" id="userID" name="userID" required><br>';
            echo '<label for="quantity">Quantity:</label>';
            echo '<input type="number" id="quantity" name="quantity" required><br>';
            echo '<button type="submit">Purchase</button>';
            echo '</form>';

            echo '</div>';
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    ?>
</div>

</body>
</html>
