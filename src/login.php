
<?php
// Start a session or destroy any existing session to ensure a fresh start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_destroy(); // Destroy any existing session to ensure a fresh start
session_start(); // start session

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!isset($_SESSION["accept"])) {
		$_SESSION["accept"] = 'false';
	}

	$username = $_POST["username"];
	$password = $_POST["password"];

	//sql injection protection
    $query = "SELECT * FROM user_details WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

	if ($result->num_rows === 1) {
		$row = $result->fetch_assoc();
		$_SESSION["accept"] = 'true';
		$_SESSION["userRole"] = $row['role'];
        $_SESSION['user_id'] = $row['id'];
		$_SESSION['firstname'] = $row['first_name'];
		$_SESSION['lastname'] = $row['last_name'];

		header("Location: dashboard.php");
		exit();
	} else {
		echo "<script>alert('Invalid username or password');</script>";
	}
}

?>



<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="assets/css/loginstyles.css">
</head>
<body>

<div class="container">
        <div class="login-header">
            <img src="assets/images/login-logo.svg">
            <h1>Goto Grocery</h1>
        </div>
        <form id="loginForm" method="POST" action="">
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder="username" required>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" placeholder="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>


</body>
</html>
