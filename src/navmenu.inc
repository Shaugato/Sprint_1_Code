<?php
	if (session_status() === PHP_SESSION_NONE) {
    session_start();
	}
	$userRole = $_SESSION['userRole'];
	if ($userRole === 'management') {
		$management = True;
	}
?>
<head>
	<link rel="stylesheet" href="assets/css/main.css">
</head>
<header class="py-4 shadow-sm bg-white">
	<div class="container flex items-center justify-between">
		<a href="index.html">
			<img src="assets/images/gotologo.png" alt="Logo" class="w-32">
		</a>

	</div>
</header>
<nav class="bg-gray-800">
	<div class="container flex">
		<div class="px-8 py-4 bg-primary md:flex items-center cursor-pointer relative group hidden">
			<img src="assets/images/icons/menu.png" alt="menu" class="w-5 h-5 object-contain">
			<span class="capitalize ml-2 text-white hidden">All Categories</span>

			<div
				class="absolute w-full left-0 top-full bg-white shadow-md py-3 divide-y divide-gray-300 divide-dashed opacity-0 group-hover:opacity-100 transition duration-300 invisible group-hover:visible">
				<a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
					<span class="ml-6 text-gray-600 text-sm">Shop</span>
				</a>
				<a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
					<span class="ml-6 text-gray-600 text-sm">Order History</span>
				</a>
				<?php if ($management): ?>
				<a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
					<span class="ml-6 text-gray-600 text-sm">Sales Reports</span>
				</a>
				<a href="#" class="flex items-center px-6 py-3 hover:bg-gray-100 transition">
					<span class="ml-6 text-gray-600 text-sm">Inventory</span>
				</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="flex items-center justify-between flex-grow md:pl-12 py-5">
			<div class="flex items-center space-x-6 capitalize">
				<a href="dashboard.php" class="text-gray-200 hover:text-white transition">Dashboard</a>
			</div>
			<div class="flex items-center space-x-6 capitalize">
				<a href="logout.php" class="text-gray-200 hover:text-white transition">Sign Out</a>
			</div>
		</div>
	</div>
</nav>
