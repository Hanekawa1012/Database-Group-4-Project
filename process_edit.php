<?php
include_once("header.php");
require("my_db_connect.php");

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    die("You haven't logged in. Please log in.");
}

$user_id = $_SESSION['user_id']; 

$errors = [];

$email = isset($_POST['email']) ? trim($_POST['email']) : null;
$tel = isset($_POST['tel']) ? trim($_POST['tel']) : null;
$address = isset($_POST['address']) ? trim($_POST['address']) : null;

if (!empty($email)) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "The email address you typed in is invalid.";
    } else {
        $check_email_sql = "SELECT user_id FROM user WHERE email = '$email' AND user_id != '$user_id'";
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

$sql = "UPDATE user SET ";
$updates = [];

if (!empty($email) && $email !== $_SESSION['email']) {
    $updates[] = "email = '$email'";
    $_SESSION['email'] = $email;
}
if (!empty($tel) && $tel !== $_SESSION['tel']) {
    $updates[] = "tel = '$tel'";
    $_SESSION['tel'] = $tel;
}
if (!empty($address) && $address !== $_SESSION['address']) {
    $updates[] = "address = '$address'";
    $_SESSION['address'] = $address;
}

if (!empty($updates)) {
    $sql .= implode(", ", $updates);
    $sql .= " WHERE user_id = '$user_id'";

    if ($con->query($sql) === TRUE) {
        echo "<div class='container my-3'>";
        echo "<div class='alert alert-success' role='alert'>";
        echo "<p>Your profile has been updated</p>";
        echo "</div>";
        echo "<a href='user_info.php' class='btn btn-primary'>Return Profile</a>";
        echo "</div>";
        header("refresh:3;url=user_info.php");

    } else {
        echo "<div class='container my-3'>";
        echo "<div class='alert alert-danger' role='alert'>";
        echo "<p>Oops something went wrong " . $con->error . "</p>";
        echo "</div>";
         echo "<a href='user_info.php' class='btn btn-primary'>Try again</a>";
        echo "</div>";
    }
} else {
    echo "<div class='container my-3'>";
    echo "<div class='alert alert-info' role='alert'>";
    echo "<p>You didn't make any change.</p>";
    echo "</div>";
    echo "<a href='user_info.php' class='btn btn-primary'>Return Profile</a>";
    echo "</div>";
     header("refresh:3;url=user_info.php");
}

$con->close();
include_once("footer.php");
?>