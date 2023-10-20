<?php
	session_start(); //start session

	//SQL Database
	if (!isset($_SESSION["accept"]) || $_SESSION["accept"] !== 'true') {
			header("Location: login.php");
			exit();
	}

	$management = false;
	$userRole = $_SESSION['userRole'];

	if ($userRole === 'management') {
		$management = True;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="Dashboard for Users" />
	<meta name="keywords" content="HTML, CSS, PHP" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Management Dashboard</title>
    <link rel="stylesheet" type="text/css" href="management_dashboard_styles.css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>
<body>
	<?php
		include_once "navmenu.inc"; // Modular Navigation Menu
	?>

	<h1 class="text-center container py-16 text-6xl text-gray-800 font-medium mb-4 capitalize">
		<?php
		if ($management) {
			echo "Manager Dashboard";
		} else {
			echo "Member Dashboard";
		}
		?>
	</h1>
		<div class="container">
			<h1 class=" text-2xl font-medium text-gray-800 uppercase ">myquicklinks</h1>
			<hr/>
			<br/>
			<?php if ($management): ?>
				<div class="w-10/12 grid grid-cols-1 md:grid-cols-3 gap-6 mx-auto justify-center ">

					<div class="">
						<a class=" border border-primary rounded-sm px-3 py-6 flex justify-center items-center gap-5 hover:bg-red-500 hover:text-white font-medium capitalize text-lg" href="inventory.php">
							<h2>Inventory</h2>
						</a>
					</div>
					<div class="">
						<a class="border border-primary rounded-sm px-3 py-6 flex justify-center items-center gap-5 hover:bg-red-500 hover:text-white font-medium capitalize text-lg" href="reports.php">
							<h2>Reports</h2>
						</a>
					</div>
					<div class="">
						<a class="border border-primary rounded-sm px-3 py-6 flex justify-center items-center gap-5 hover:bg-red-500 hover:text-white font-medium capitalize text-lg" href="management_purchase.php">
						<h2>Point of Sales</h2>
					</a>
				</div>
				</div>
			<?php endif; ?>
</br>
			<div class="w-10/12 grid grid-cols-1 md:grid-cols-3 gap-6 mx-auto justify-center middle">
				<div class="">
					<a class="border border-primary rounded-sm px-3 py-6 flex justify-center items-center gap-5 hover:bg-red-500 hover:text-white font-medium capitalize text-lg" href="order_history.php">
						<h2>Order History</h2>
					</a>
				</div>

				<div class="">
					<a class="border border-primary rounded-sm px-3 py-6 flex justify-center items-center gap-5 hover:bg-red-500 hover:text-white font-medium capitalize text-lg" href="shop.php">
						<h2>Shop Now</h2>
					</a>
				</div>


			</div>
		</div>
    </div>
</br>
	<div class="container py-16">
        <h2 class="text-2xl font-medium text-gray-800 uppercase">hotdeals</h2>
		<hr/>
		<br/>
        <div class="grid grid-cols-3 gap-3">
            <div class="relative rounded-sm overflow-hidden group">
                <img src="assets/images/category/pexels-towfiqu-barbhuiya-11363573.jpg" alt="category 1" class="w-full">
                <a href="#" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-xl text-white font-roboto font-medium group-hover:bg-opacity-60 transition">10% off Eggs</a>
            </div>
            <div class="relative rounded-sm overflow-hidden group">
                <img src="assets/images/category/Panaderia.jpg" alt="category 1" class="w-full">
                <a href="#" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-xl text-white font-roboto font-medium group-hover:bg-opacity-60 transition">2 For 1 in the Bakery</a>
            </div>
            <div class="relative rounded-sm overflow-hidden group">
                <img src="assets/images/category/fresh.jpg" alt="category 1" class="w-full">
                <a href="#" class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-xl text-white font-roboto font-medium group-hover:bg-opacity-60 transition">Save up to 5% on Fresh Produce</a>
            </div>
        </div>
    </div>
</br>
	<div class="bg-gray-800 py-4">
        <div class="container flex items-center justify-between">
            <p class="text-white">© GotoGrocery - All Right Reserved</p>
            <div>
                <img src="assets/images/methods.png" alt="methods" class="h-5">
            </div>
        </div>
    </div>
</body>
</html>
