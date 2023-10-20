<?php
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order History</title>

    <link rel="stylesheet" href="assets/css/order_history_styles.css">
</head>
<body>
	<?php
		include_once "navmenu.inc"; // Modular Navigation Menu
	?>
    <header>
        <h1>Order History for <?php echo $firstname; echo " "; echo $lastname; ?></h1>
    </header>

    <main>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Order Date</th>
            </tr>
            <?php
            $sql = "SELECT o.order_id, p.Name, o.quantity, o.order_date FROM order_history o INNER JOIN product p ON o.product_id = p.ProductID WHERE o.user_id = '$userId'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['order_id'] . "</td>";
                echo "<td>" . $row['Name'] . "</td>";
                echo "<td>" . $row['quantity'] . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </main>

    <footer>
        <p>Copyright © 2023 - Your Company</p>
    </footer>
</body>
</html>
