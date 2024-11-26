<?php require("utilities.php") ?>
<?php require("my_db_connect.php") ?>
<?php require("send_email.php") ?>
<?php session_start(); ?>


<?php

if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
    return;
}

// Extract arguments from the POST variables:
$item_id = intval($_POST['arguments']);
$buyer_id = intval($_SESSION['user_id']);


if ($_POST['functionname'] == "add_to_watchlist") {
    // TODO: Update database and return success/failure.
    // NEEDS FIXING!!! I CAN'T FIND WHAT'S WRONG!!! -- TIM
    $sql = "INSERT INTO watchlist (buyer_id, item_id) VALUES ($buyer_id, $item_id);";
    if (mysqli_query($con, $sql)) {
        $res = "success";
    } else {
        $res = "error";
    }
    $sql_item = "SELECT title, details, category, endDate FROM auctions WHERE item_id = $item_id;";
    $fetch_item = mysqli_fetch_array(mysqli_query($con, $sql_item));
    $con->close();
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $title = "New watching item added!";
    $content = "<h3>You added a new item!</h3>";
    $outline = "You added a new item!";
    switch (sendmail::sendemail($email, $username, $title, $content, $outline)) {
        case 'e000':
            $res = "success";
            break;
        case 'e001':
            $res = "error";
            break;
        default:
            $res = "error";
            break;
    }
} else if ($_POST['functionname'] == "remove_from_watchlist") {
    // TODO: Update database and return success/failure.
    //NEEDS FIXING!!! I CAN'T FIND WHAT'S WRONG!!! -- TIM
    $sql = "DELETE FROM watchlist WHERE buyer_id = $buyer_id AND item_id = $item_id;";
    if (mysqli_query($con, $sql)) {
        $res = "success";
    } else {
        $res = "error";
    }
    $con->close();
}

// Note: Echoing from this PHP function will return the value as a string.
// If multiple echo's in this file exist, they will concatenate together,
// so be careful. You can also return JSON objects (in string form) using
// echo json_encode($res).
echo $res;

?>