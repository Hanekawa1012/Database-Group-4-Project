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
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $accountType = mysqli_real_escape_string($con, $_POST['accountType']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // check if the format is valid.
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
$sql = "INSERT INTO user (password, email, accountType) VALUES (SHA('$password'),'$email','$accountType');";
$sql .= "INSERT INTO $accountType SELECT user_id FROM user WHERE email = '$email' AND accountType = '$accountType';";
$sql .= "INSERT INTO profile (email, username) VALUES ('$email', '$username');";
if ($con->multi_query($sql)) {
    echo "Registration success.\n";
} else {
    echo "Registration failed.\n" . "<br/>" . $con->error;
}
$con->close();

header("refresh:3;url=browse.php");
?>