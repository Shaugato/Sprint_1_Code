<?php
session_start();
session_destroy();

// Redirect to the login page or any other page after logout
header("Location: index.html");
exit();
?>
