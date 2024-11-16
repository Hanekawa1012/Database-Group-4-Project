<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to login.
// Notify user of success/failure and redirect/give navigation options.

// For now, I will just set session variables and redirect.

//connect to database (if YOURS in XAMPP is DIFFERENT PLEASE CHANGE)
$mydbhost = "localhost";
$mydbuser = "root";
$mydbpasswd = "";
$dbname = "db-group4";

$con = new mysqli($mydbhost, $mydbuser, $mydbpasswd, $dbname);

if ($con->connect_error){
  die("connect error" . $con->connect_error);
}

echo "conneted successful.\n";



if($_POST['email'] != "" && $_POST['password'] != ""){
    session_start();
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "select user_id, username, password from user where email='$email' and password='$password';";
    $result = mysqli_query($con, $sql);
    $row = mysqli_num_rows($result);
    if(!$row){
        echo('<div class="text-center">Error:User does not exists.</div>');
        exit();
    }else{
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $_POST['email'];
    }
}else{
    echo('<div class="text-center">Error:Login information required.</div>');
    header("refresh:5;url=index.php");
    exit();
}


echo('<div class="text-center">You are now logged in! You will be redirected shortly.</div>');

// Redirect to index after 5 seconds
header("refresh:5;url=index.php");

?>