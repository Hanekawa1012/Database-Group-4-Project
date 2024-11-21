<?php include_once("header.php")?>
<?php require("my_db_connect.php")?>

<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.


//check if all variables in the from are OK. If OK, innitialize variables here:
if ($_POST["email"] == ""){//check if the email is not filled in
    die("Error:Email needed.");    
}else {
    $email = $_POST["email"];
    if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)){//check if the format is valid.
        die("Error: Email address invalid.");
    }
    $sql = "select * from user where email = '$email';";//check if the email is already registered
    $result = $con->query($sql);
    if(empty($result)){
        die("Account already exists.");
        //TO DO: Add a change_password.php, and choose accountType in account login window
    }
}

if ($_POST["password"] == ""){//check if the password is filled in
    die("Error:Password needed.");
}else{    
    $password = $_POST["password"];
}

if ($_POST['password'] != $_POST['passwordConfirmation']){//check if the passwords match
    die('Error:Passwords do not match.');
}

$accountType = $_POST["accountType"];
$username = "user" . uniqid();//random name. can be edited in user profile


//send insert request to the database
$sql = "insert into user(username, password, email, accountType) values('$username','$password','$email','$accountType')";
if($con->query($sql) == true){
    echo "data insert success.\n";
}else{
    echo "data insert fail.\n"."<br/>".$con->error;
}
$con->close();

header("refresh:3;url=browse.php");
?>