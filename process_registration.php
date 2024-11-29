<?php include_once("header.php") ?>
<?php require("my_db_connect.php") ?>

<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.


//check if all variables in the from are OK. If OK, innitialize variables here:
if ($_POST["email"] == "") {//check if the email is not filled in
    die("Error: Email needed.");
} else {
    $email = $_POST["email"];
    $accountType = $_POST['accountType'];
    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) { // check if the format is valid.
        die("Error: Email address invalid.");
    }
    $sql = "SELECT * FROM user WHERE email = '$email' AND accountType = '$accountType';"; // check if the email is already registered
    $result = $con->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo mysqli_fetch_array($result);
        die("Account already exists.");
        // TODO: Add a change_password.php, and choose accountType in account login window
    }
}

if ($_POST["password"] == "") { // check if the password is filled in
    die("Error: Password needed.");
} else {
    $password = $_POST["password"];
}

if ($_POST['password'] != $_POST['passwordConfirmation']) { // check if the passwords match
    die('Error: Passwords do not match.');
}

$username = "user" . uniqid(); // random name. can be edited in user profile

// send insert request to the database
// 问题：改成multi_query同时执行并报错
$sql = "INSERT INTO user (username, password, email, accountType) VALUES ('$username','$password','$email','$accountType');";
$sql_id = "INSERT INTO $accountType SELECT user_id FROM user WHERE email = '$email' AND accountType = '$accountType';";
if ($con->query($sql) == true) {
    echo "User data insert succeed.\n";
    if ($con->query($sql_id) == true) {
        echo "account type data insert success.\n";
        header("refresh:3;url=browse.php");
    } else {
        echo "account type data insert fail.\n";
    }
} else {
    echo "data insert failed.\n" . "<br/>" . $con->error;
}
$con->close();

header("refresh:10;url=browse.php");
?>