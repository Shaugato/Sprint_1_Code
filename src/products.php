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
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
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
<nav class="bg-gray-800">
        <div class="container flex">
            <!-- <div class="px-8 py-4 bg-primary md:flex items-center cursor-pointer relative group hidden">
                <span class="text-white">
                    <i class="fa-solid fa-bars"></i>
                </span>
                <span class="capitalize ml-2 text-white hidden">All Categories</span>

                *** Dropdown
                <div
                    class="absolute w-full left-0 top-full bg-white shadow-md py-3 divide-y divide-gray-300 divide-dashed opacity-0 group-hover:opacity-100 transition duration-300 invisible group-hover:visible">
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="assets/images/icons/sofa.svg" alt="sofa" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Sofa</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="assets/images/icons/terrace.svg" alt="terrace" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Terarce</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="assets/images/icons/bed.svg" alt="bed" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Bed</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="assets/images/icons/office.svg" alt="office" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">office</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="assets/images/icons/outdoor-cafe.svg" alt="outdoor" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Outdoor</span>
                    </a>
                    <a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
                        <img src="assets/images/icons/bed-2.svg" alt="Mattress" class="w-5 h-5 object-contain">
                        <span class="ml-6 text-gray-600 text-sm">Mattress</span>
                    </a>
                </div>
            </div> -->

            <div class="flex items-center justify-between flex-grow md:pl-12 py-5">
                <div class="flex items-center space-x-6 capitalize">
                    <a href="homepage.html" class="text-gray-200 hover:text-white transition">Home</a>
                    <a href="management_dashboard.html" class="text-gray-200 hover:text-white transition">Dashboard</a>
                </div>
                
            </div>
        </div>
    </nav>
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
