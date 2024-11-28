<?php require("my_db_connect.php") ?>
<?php require("config/conf.php") ?>



<?php
// Extract $_POST variables, check they're OK, and attempt to login.
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
        echo ('<div class="text-center">Error: Email or password incorrect.</div>');
        $con->close();
        header("refresh:$t_refresh;url=browse.php");
        exit();
    }
} else {
    echo ('<div class="text-center">Error: Login information required.</div>');
    $con->close();
    header("refresh:$t_refresh;url=browse.php");
    exit();
}

$fetch = mysqli_fetch_array($result);
session_start();
$_SESSION['logged_in'] = true;
$_SESSION['user_id'] = $fetch['user_id'];
$_SESSION['username'] = $fetch['username'];
$_SESSION['email'] = $fetch['email'];
$_SESSION['account_type'] = $fetch['accountType'];
$_SESSION['tel'] = $fetch['tel'];
$_SESSION['address'] = $fetch['address'];
echo ('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to browse after n seconds
header("refresh:$t_refresh;url=browse.php");

?>