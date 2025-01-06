<?php
session_start();

// Destroy session to log out the user
session_destroy();

// Redirect to login page or homepage
header("Location: Userlogin.php");
exit();
?>
