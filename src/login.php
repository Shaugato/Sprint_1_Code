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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    $sql = "SELECT * FROM user_details WHERE username = '$inputUsername' AND password = '$inputPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userRole = $row['role'];

        if ($userRole === 'management') {
            header("Location: dashboard.html");
        } else {
            header("Location: homepage.html");
        }
        exit();
    } else {
        echo "<script>alert('Invalid username or password');</script>";
    }
}



$conn->close();
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
