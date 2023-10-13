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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goto Grocery</title>

    <link rel="shortcut icon" href="assets/images/favicon/gotofav.png" type="image/x-icon">

    <link rel="stylesheet" href="assets/css/main.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
</head>

<body>
    <!-- header -->
    <header class="py-4 shadow-sm bg-white">
        <div class="container flex items-center justify-between">
            <a href="index.html">
                <img src="assets/images/gotologo.png" alt="Logo" class="w-32">
            </a>

            <div class="w-full max-w-xl relative flex">
                <span class="absolute left-4 top-3 text-lg text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" name="search" id="search" class="w-full border border-primary border-r-0 pl-12 py-3 pr-3 rounded-l-md focus:outline-none hidden md:flex" placeholder="search">
                <button class="bg-primary border border-primary text-white px-8 rounded-r-md hover:bg-transparent hover:text-primary transition hidden md:flex">Search</button>
            </div>

            <div class="flex items-center space-x-4">
                <a href="#" class="text-center text-gray-700 hover:text-primary transition relative">
                    <div class="text-2xl">
                        <i class="fa-regular fa-heart"></i>
                    </div>
                    <div class="text-xs leading-3">Wishlist</div>
                    <div class="absolute right-0 -top-1 w-5 h-5 rounded-full flex items-center justify-center bg-primary text-white text-xs">
                        8</div>
                </a>
                <a href="#" class="text-center text-gray-700 hover:text-primary transition relative">
                    <div class="text-2xl">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <div class="text-xs leading-3">Cart</div>
                    <div class="absolute -right-3 -top-1 w-5 h-5 rounded-full flex items-center justify-center bg-primary text-white text-xs">
                        2</div>
                </a>
                <a href="#" class="text-center text-gray-700 hover:text-primary transition relative">
                    <div class="text-2xl">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="text-xs leading-3">Account</div>
                </a>
            </div>
        </div>
    </header>
    <!-- ./header -->

    <!-- navbar -->
    <nav class="bg-gray-800">
        <div class="container flex">
            <div class="px-8 py-4 bg-primary md:flex items-center cursor-pointer relative group hidden">
                <span class="text-white">
                    <i class="fa-solid fa-bars"></i>
                </span>
                <span class="capitalize ml-2 text-white hidden">All Categories</span>

                <!-- dropdown -->
                <div class="absolute w-full left-0 top-full bg-white shadow-md py-3 divide-y divide-gray-300 divide-dashed opacity-0 group-hover:opacity-100 transition duration-300 invisible group-hover:visible">
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
            </div>

            <div class="flex items-center justify-between flex-grow md:pl-12 py-5">
                <div class="flex items-center space-x-6 capitalize">
                    <a href="homepage.php" class="text-gray-200 hover:text-white transition">Home</a>
                    <a href="products.php" class="text-gray-200 hover:text-white transition">Shop</a>
                    <a href="about.php" class="text-gray-200 hover:text-white transition">About us</a>
                    <a href="contact.php" class="text-gray-200 hover:text-white transition">Contact us</a>
                </div>
                <form action="login.php" method="get" class="text-gray-200 hover:text-white transition">
                    <input type="submit" value="Login">
                </form>

            </div>
        </div>
    </nav>
    <!-- ./navbar -->

    <!-- breadcrumb -->
    <div class="container py-4 flex items-center gap-3">
        <a href="../index.html" class="text-primary text-base">
            <i class="fa-solid fa-house"></i>
        </a>
        <span class="text-sm text-gray-400">
            <i class="fa-solid fa-chevron-right"></i>
        </span>
        <p class="text-gray-600 font-medium">Shop</p>
    </div>
    <!-- ./breadcrumb -->

    


        <!-- products -->
        <div class="col-span-3">
            <div class="flex items-center mb-4">
                <select name="sort" id="sort" class="w-44 text-sm text-gray-600 py-3 px-4 border-gray-300 shadow-sm rounded focus:ring-primary focus:border-primary">
                    <option value="">Default sorting</option>
                    <option value="price-low-to-high">Price low to high</option>
                    <option value="price-high-to-low">Price high to low</option>
                    <option value="latest">Latest product</option>
                </select>

                <div class="flex gap-2 ml-auto">
                    <div class="border border-primary w-10 h-9 flex items-center justify-center text-white bg-primary rounded cursor-pointer">
                        <i class="fa-solid fa-grip-vertical"></i>
                    </div>
                    <div class="border border-gray-300 w-10 h-9 flex items-center justify-center text-gray-600 rounded cursor-pointer">
                        <i class="fa-solid fa-list"></i>
                    </div>
                </div>
            </div>

            <div class="container pb-16">
                <h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">SHOP NOW</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="bg-white shadow rounded overflow-hidden group">';
                            echo '<div class="relative">';
                            echo '<img src="' . $row["ImageURL"] . '" alt="' . $row["Name"] . '" class="w-full">';
                            echo '<div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition">';
                            echo '<a href="#" class="text-white text-lg w-9 h-8 rounded-full bg-primary flex items-center justify-center hover:bg-gray-800 transition" title="view product">';
                            echo '<i class="fa-solid fa-magnifying-glass"></i>';
                            echo '</a>';
                            echo '<a href="#" class="text-white text-lg w-9 h-8 rounded-full bg-primary flex items-center justify-center hover:bg-gray-800 transition" title="add to wishlist">';
                            echo '<i class="fa-solid fa-heart"></i>';
                            echo '</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="pt-4 pb-3 px-4">';
                            echo '<a href="#"><h4 class="uppercase font-medium text-xl mb-2 text-gray-800 hover:text-primary transition">' . $row["Name"] . '</h4></a>';
                            echo '<div class="flex items-baseline mb-1 space-x-2">';
                            echo '<p class="text-xl text-primary font-semibold">$' . $row["Price"] . '</p>';
                            echo '<p class="text-sm text-gray-400 line-through">$' . $row["RegularPrice"] . '</p>';
                            echo '</div>';
                            echo '<div class="flex items-center">';
                            echo '<div class="flex gap-1 text-sm text-yellow-400">';
                            for ($i = 1; $i <= 5; $i++) {
                                echo '<span><i class="fa-solid fa-star"></i></span>';
                            }
                            echo '</div>';
                            echo '<div class="text-xs text-gray-500 ml-3">(' . $row["Rating"] . ')</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '<a href="#" class="block w-full py-1 text-center text-white bg-primary border border-primary rounded-b hover:bg-transparent hover:text-primary transition">Add to cart</a>';
                            echo '</div>';
                        }
                    } else {
                        echo "0 results";
                    }
                    ?>
                </div>
            </div>
        </div>
        <!-- ./product -->


        <!-- footer -->
        <footer class="bg-white pt-16 pb-12 border-t border-gray-100">
            <div class="container grid grid-cols-1 ">
                <div class="col-span-1 space-y-4">
                    <!-- <img src="assets/images/gotologo.png" alt="logo" class="w-30"> -->
                    <div class="mr-2">
                        <p class="text-gray-500">
                            Goto Grocery:Your One Stop Grocery Store</p>
                    </div>
                    <div class="flex space-x-5">
                        <a href="#" class="text-gray-400 hover:text-gray-500"><i class="fa-brands fa-facebook-square"></i></a>
                        <a href="#" class="text-gray-400 hover:text-gray-500"><i class="fa-brands fa-instagram-square"></i></a>
                        <a href="#" class="text-gray-400 hover:text-gray-500"><i class="fa-brands fa-twitter-square"></i></a>
                        <a href="#" class="text-gray-400 hover:text-gray-500">
                            <i class="fa-brands fa-github-square"></i>
                        </a>
                    </div>
                </div>

                <div class="col-span-2 grid grid-cols-2 gap-4">
                    <div class="grid grid-cols-2 gap-4 md:gap-8">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Solutions</h3>
                            <div class="mt-4 space-y-4">
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Marketing</a>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Analitycs</a>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Commerce</a>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Insights</a>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Support</h3>
                            <div class="mt-4 space-y-4">
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Pricing</a>
                                <!-- <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Documentation</a> -->
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Guides</a>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">API Status</a>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Solutions</h3>
                            <div class="mt-4 space-y-4">
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Marketing</a>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Analitycs</a>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Commerce</a>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Insights</a>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Support</h3>
                            <div class="mt-4 space-y-4">
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Pricing</a>
                                <!-- <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Documentation</a> -->
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">Guides</a>
                                <a href="#" class="text-base text-gray-500 hover:text-gray-900 block">API Status</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- ./footer -->

        <!-- copyright -->
        <div class="bg-gray-800 py-4">
            <div class="container flex items-center justify-between">
                <p class="text-white">&copy;All Right Reserved</p>
                <div>
                    <img src="assets/images/methods.png" alt="methods" class="h-5">
                </div>
            </div>
        </div>
        <!-- ./copyright -->
</body>

</html>