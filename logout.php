<?php

session_start();

unset($_SESSION['logged_in']);
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['account_type']);
unset($_SESSION['viewing']);
setcookie(session_name(), "", time() - 3600);
session_destroy();


// Redirect to main page
echo "Logout success, redirecting to main page...";
header("refresh:3;url=browse.php");

?>