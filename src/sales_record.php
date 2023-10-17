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
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
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
