<?php

	//header('Content-Type: application/xhtml+xml; charset=utf-8');

	session_start();

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

    //return true if email is valid
	function checkemail($email) {

		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		} else {
			return false;
		}
	}


    // Check to see if we have some POST data, if we do process it
  	if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['retype'])) {

    	unset($_SESSION['email']);

    	$checkemail = checkemail($_POST['email']);

	    if ($checkemail === false) {
			$_SESSION["error"] = "Invalid email.";
			header("Location: sign_up.php");
			return;
		} else if (strlen($_POST['password']) < 5){
			$_SESSION["error"] = "Invalid password. Password must be at least 5 characters";
			header("Location: sign_up.php");
			return;
		} else {

	    	if ($_POST['password'] === $_POST['retype']) {

				$password = $_POST['password'];
				$role = 'normal';

				$sql = "INSERT INTO user_details (username, password, first_name, last_name, email, phone_number, address, city, state, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			    $sth = $conn->prepare($sql);
				if (!$sth) {
					die("Prepare failed: " . $conn->error);
				}
				$sth->bind_param("ssssssssss", $_POST['username'], $password, $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['phone'], $_POST['address'], $_POST['city'], $_POST['state'], $role);

			    if ($sth->execute()) {
			    	$_SESSION["success"] = "New account created successfully";
			    	header("Location: login.php");
				} else {
					error_log("Account Creation Failed: " . $e->getMessage());
					$_SESSION["error"] = "Account creation failed";
					header("Location: sign_up.php");
				}

			} else {
				$_SESSION["error"] = "Passwords Do Not Match.";
				header("Location: sign_up.php");
				return;
			}

		}

	}

    $dbh = null;

?>





<!DOCTYPE html>
<html lang="en">

	<!-- THIS IS THE HOMEPAGE -->
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Goto Grocery</title>

		<link rel="shortcut icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">

		<link rel="stylesheet" href="assets/css/main.css">

		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link
			href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
			rel="stylesheet">

		<link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
	</head>
	<body>

		<header class="py-4 shadow-sm bg-white">
			<div class="container flex items-center justify-between">
				<a href="index.html">
					<img src="assets/images/gotologo.png" alt="Logo" class="w-32">
				</a>
			</div>
		</header>
		<!-- ./header -->

		<!-- navbar -->
		<nav class="bg-gray-800">
			<div class="container flex">
				<div class="flex items-center justify-between flex-grow md:pl-12 py-5">
					<div class="flex items-center space-x-6 capitalize">
						<a href="index.html" class="text-gray-200 hover:text-white transition">Home</a>
						<a href="sign_up.php" class="text-gray-200 hover:text-white transition">Sign up</a>
						<a href="#" class="text-gray-200 hover:text-white transition">About us</a>
						<a href="#" class="text-gray-200 hover:text-white transition">Contact us</a>
					</div>
					<form action="login.php" method="get" class="text-gray-200 hover:text-white transition">
						<input type="submit" value="Login">
					</form>

				</div>
			</div>
		</nav>


		<h1 class="text-center container py-16 text-6xl text-gray-800 font-medium mb-4 capitalize">Sign up Now</h1>

		<section class="items-center" id="create">



			<h2 class="text-2xl font-medium text-gray-800 uppercase mb-6">Create New Account</h2>

			<hr />

			<?php

		        //Flash Message
		        if ( isset($_SESSION['error']) ) {
		          echo('<p style="color: red;">' . htmlentities($_SESSION['error'])."</p>\n");
		          unset($_SESSION['error']);
		        }

		        //Flash Message
		        if ( isset($_SESSION['success']) ) {
		          echo('<p style="color: green;">' . htmlentities($_SESSION['success'])."</p>\n");
		          unset($_SESSION['success']);
		        }
			?>

			<form method="post">
				<h1 class="text-gray-500 py-2">User Details*</h1>

				<label for="firstname">Firstname</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="text" id="firstname" name="firstname" required /><br />
				<label for="lastname">Lastname</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="text" id="lastname" name="lastname" required /><br />
				<label for="phone">Phone Number</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="tel" id="phone" name="phone" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required /><br />
				<label for="email">Email</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="text" id="email" name="email" required /><br />
				<label for="address">Address</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="text" id="address" name="address" required /><br />
				<label for="city">City</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="text" id="city" name="city" required /><br />
				<label for="state">State</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="text" id="state" name="state" required /><br />
				<hr />
				<h1 class="text-gray-500 py-2">Login Details*</h1>
				<label for="username">Username</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="text" id="username" name="username" required /><br />
				<label for="password">Password</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="password" id="password" name="password" required /><br />
				<label for="retype">Re-type Password</label>
				<input class="border border-primary pl-12 py-3 pr-3 rounded-md focus:outline-none hidden md:flex" type="password" id="retype" name="retype" /><br />
				<input class="bg-primary border border-primary text-white px-8 py-3 font-medium rounded-md hover:bg-transparent hover:text-primary" type="submit" onclick="return validateAccount();" name="submit" value="Submit" />
			</form>

			<p id="js_validation_message"></p>

		</section>
			</br>
			</br>
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
