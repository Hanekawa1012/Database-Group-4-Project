<?php include_once("header.php")?>
<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.

//check if all variables in the from are OK. If OK, innitialize variables here:
if ($_POST["email"] == ""){
    die("Email needed.");    
}else{//If not, let process die
    $email = $_POST["email"];
}

if ($_POST["password"] == ""){
    die("Password needed.");
}else{    
    $password = $_POST["password"];
}

if ($_POST['password'] != $_POST['passwordConfirmation']){
    die('Passwords do not match.');
}

$accountType = $_POST["accountType"];
$username = "user" . uniqid();//random name. can be edited in user profile


//connect to database
$mydbhost = "localhost";
$mydbuser = "root";
$mydbpasswd = "";
$dbname = "db-group4";

$con = new mysqli($mydbhost, $mydbuser, $mydbpasswd, $dbname);

if ($con->connect_error){
  die("connect error" . $con->connect_error);
}

echo "conneted successful.\n";


//send insert request to the database
$sql = "insert into user(username, password, email, accountType) values('$username','$password','$email','$accountType')";
if($con->query($sql) == true){
    echo "data insert success.\n";
}else{
    echo "data insert fail.\n"."<br/>".$con->error;
}
$con->close();
?>