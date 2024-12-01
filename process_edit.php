<?php
include_once("header.php");
require("my_db_connect.php");

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    die("You haven't logged in. Please log in.");
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['email'];
$accountType = $_SESSION['account_type'];

$errors = [];

$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$tel = isset($_POST['tel']) ? trim($_POST['tel']) : null;
$address = isset($_POST['address']) ? trim($_POST['address']) : null;

if (!empty($email)) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "The email address you typed in is invalid.";
    } else {
        $check_email_sql = "SELECT user_id FROM user WHERE email = '$email' AND user_id != '$user_id' AND accountType = '$accountType'";
        $check_email_result = $con->query($check_email_sql);
        if ($check_email_result->num_rows > 0) {
            $errors[] = "This email address has been registered. Please choose another one.";
        }
    }
}

if (!empty($errors)) {
    echo "<div class='container my-3'>";
    echo "<div class='alert alert-danger' role='alert'>";
    foreach ($errors as $error) {
        echo "<p>" . $error . "</p>";
    }
    echo "</div>";
    echo "<a href='user_info.php' class='btn btn-primary'>Return to check again</a>";
    echo "</div>";
    include_once("footer.php");
    exit;
}

$sql_info_before_edit = "SELECT * FROM `profile` WHERE email = '$user_email';";
$result_before_edit = mysqli_query($con, $sql_info_before_edit);
$fetch_before_edit = mysqli_fetch_array($result_before_edit);
if (!is_null($fetch_before_edit['tel'])){
    $tel_before = $fetch_before_edit['tel'];
}else{
    $tel_before = "";
}
if (!is_null($fetch_before_edit['address'])){
    $address_before = $fetch_before_edit['address'];
}else{
    $address_before = "";
}

$sql_user = "UPDATE user SET ";
$sql_profile = "UPDATE profile SET ";
$updates_user = [];
$updates_profile = [];
$result_edit_user = false;
$result_edit_profile = false;

//TODO
if (!empty($username) && $username !== $_SESSION['username']) {
    $updates_profile[] = "username = '$username'";
    $_SESSION['username'] = $username;
}
if (!empty($email) && $email !== $_SESSION['email']) {
    $updates_user[] = "email = '$email'";
    $_SESSION['email'] = $email;
}
if (!empty($tel) && $tel !== $tel_before) {
    $updates_profile[] = "tel = '$tel'";
    $_SESSION['tel'] = $tel;
}
if (!empty($address) && $address !== $address_before) {
    $updates_profile[] = "address = '$address'";
    $_SESSION['address'] = $address;
}

if (empty($updates_user) and empty($updates_profile)) {
    echo "<div class='container my-3'>";
    echo "<div class='alert alert-info' role='alert'>";
    echo "<p>You didn't make any change.</p>";
    echo "</div>";
    echo "<a href='user_info.php' class='btn btn-primary'>Return Profile</a>";
    echo "</div>";
    header("refresh:3;url=user_info.php");
    exit();
    
} else {
    if (!empty($updates_user)) {
        $sql_user .= implode(", ", $updates_user);
        $sql_user .= " WHERE user_id = '$user_id'";
        $result_edit_user = $con->query($sql_user);
    }

    if (!empty($updates_profile)) {
        $sql_profile .= implode(", ", $updates_profile);
        $sql_profile .= " WHERE email = '$email'";
        $result_edit_profile = $con->query($sql_profile);
    }

    if ($result_edit_user === TRUE or $result_edit_profile === TRUE) {
        echo "<div class='container my-3'>";
        echo "<div class='alert alert-success' role='alert'>";
        echo "<p>Your profile has been updated</p>";
        echo "</div>";
        echo "<a href='user_info.php' class='btn btn-primary'>Return Profile</a>";
        echo "</div>";
    } else {
        echo "<div class='container my-3'>";
        echo "<div class='alert alert-danger' role='alert'>";
        echo "<p>Oops something went wrong " . $con->error . "</p>";
        echo "</div>";
        echo "<a href='user_info.php' class='btn btn-primary'>Try again</a>";
        echo "</div>";
    }
}

$con->close();
include_once("footer.php");
?>