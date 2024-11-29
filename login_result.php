<?php require("my_db_connect.php") ?>

<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.

// For now, I will just set session variables and redirect.


if ($_POST['email'] != "" && $_POST['password'] != "") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $accountType = $_POST['accountType'];
    $sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password' AND accountType = '$accountType';";
    $result = mysqli_query($con, $sql);
    $row = mysqli_num_rows($result);
    if (!$row) {
        echo ('<div class="text-center">Error:User does not exists.</div>');
        exit();
    }
} else {
    echo ('<div class="text-center">Error:Login information required.</div>');
    header("refresh:3;url=browse.php");
    exit();
}

$fetch = mysqli_fetch_array($result);
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = $fetch['user_id'];
$_SESSION['username'] = $fetch['username'];
$_SESSION['email'] = $fetch['email'];
$_SESSION['account_type'] = $fetch['accountType'];
echo ('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to browse after 5 seconds
header("refresh:3;url=browse.php");

?>