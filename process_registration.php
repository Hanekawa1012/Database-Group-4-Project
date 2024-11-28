<?php include_once("header.php") ?>
<?php require("my_db_connect.php") ?>
<?php require("config/conf.php") ?>

<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to create
// an account. Notify user of success/failure and redirect/give navigation 
// options.


//check if all variables in the form are OK. If OK, innitialize variables here:
if ($_POST["email"] == "") { //check if the email is not filled in
    echo "Error: Empty email address is not allowed. ";
    $con->close();
    header("refresh:$t_refresh;url=browse.php");
    exit();
} else {
    $email = $_POST["email"];
    $accountType = $_POST['accountType'];
    if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) { // check if the format is valid.
        echo "Error: Invalid email address " . $email;
        $con->close();
        header("refresh:$t_refresh;url=browse.php");
        exit();
    }
    $sql = "SELECT * FROM user WHERE email = '$email' AND accountType = '$accountType';"; // check if the email is already registered
    $result = $con->query($sql);
    if (mysqli_num_rows($result) > 0) {
        echo "Account " . $_POST["email"] . " already exists.";
        $con->close();
        header("refresh:$t_refresh;url=browse.php");
        exit();
        // TODO: Add a change_password.php, and choose accountType in account login window
    }
}

if ($_POST["password"] == "") { // check if the password is filled in
    echo "Error: Password needed.";
    $con->close();
    header("refresh:$t_refresh;url=browse.php");
    exit();
} else {
    $password = $_POST["password"];
}

if ($_POST['password'] != $_POST['passwordConfirmation']) { // check if the passwords match
    echo "Error: Passwords do not match.";
    $con->close();
    header("refresh:$t_refresh;url=browse.php");
    exit();
}

$username = "user" . uniqid(); // random name. can be edited in user profile

// send insert request to the database
$sql = "INSERT INTO user (username, password, email, accountType) VALUES ('$username','$password','$email','$accountType'); INSERT INTO $accountType SELECT user_id FROM user WHERE email = '$email' AND accountType = '$accountType';";
if ($con->multi_query($sql) == true) {
    echo "User data insert succeed.\n";
} else {
    echo "data insert failed.\n" . "<br/>" . $con->error;
}
$con->close();
header("refresh:$t_refresh;url=browse.php");
?>