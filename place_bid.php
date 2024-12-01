<?php session_start(); ?>
<?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false): ?>
    <?php require_once "header.php" ?>
    <div>You are not logged in! <a href="" data-toggle="modal" data-target="#loginModal">Login</a></div>
    <?php require_once "footer.php" ?>
    <?php exit(); ?>

<?php elseif (!isset($_SESSION['account_type']) || $_SESSION['account_type'] == 'seller'): ?>
    <?php require_once "header.php" ?>
    <div>Only buyer-type account can join bidding. If you want to bid for an item, please register a buyer account.</div>
    <?php require_once "footer.php" ?>
    <?php exit(); ?>

<?php else: ?>
    <?php require_once "listing.php" ?>
    <?php require_once "send_email.php" ?>
    <?php require "my_db_connect.php" ?>
    <?php require("config/conf.php") ?>
<?php endif; ?>

<?php
// Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.

if ($_POST['bidPrice'] != "" && is_numeric($_POST['bidPrice'])) {
    $user_id = intval($_SESSION['user_id']);
    $bidPrice = mysqli_real_escape_string($con, $_POST['bidPrice']);
    $bidTime = new DateTime();
    $bidTime = $bidTime->format('y-m-d H:i:s');
    $item_id = intval($_SESSION['viewing']);

    if ($bidPrice <= $current_price) {
        echo ('Error: Bid price shold be greater than current one.');
        exit();
    }
    //email sending to user after bidding
    $sql_item = "SELECT title, details, category, endDate FROM auctions WHERE item_id = $item_id;";
    $fetch_item = mysqli_fetch_array(mysqli_query($con, $sql_item));
    $email = $_SESSION['email'];
    $username = $_SESSION['username'];
    $itemTitle = $fetch_item['title'];
    $title = "New Bid Success";
    $content = "<h3>Bid Receipt</h3>
                    <p>Bidder name: $username</p>
                    <p>Item name: $itemTitle</p>
                    <p>Your bid price: $bidPrice</p>
                    <p>Bid time: $bidTime</p>";
    $outline = "You bidded a new item!";
    switch (sendmail::sendemail($email, $username, $title, $content, $outline)) {
        case 'e000':
            echo "A receipt email sent to your email. Please check.";
            break;
        case 'e001':
            echo "Sending email failed";
            break;
        default:
            echo "Sending email failed";
            break;
    }

    //unset($_SESSION['viewing']);
    $sql = "INSERT INTO bids (buyer_id, item_id, bidPrice, bidTime) 
                VALUES ($user_id, $item_id, '$bidPrice', '$bidTime');";
    if (mysqli_query($con, $sql)) {
        echo "Data insert succeed.\n";

        //email sending to user after bidding
        $sql_item = "SELECT title, details, category, endDate FROM auctions WHERE item_id = $item_id;";
        $fetch_item = mysqli_fetch_array(mysqli_query($con, $sql_item));
        $email = $_SESSION['email'];
        $username = $_SESSION['username'];
        $itemTitle = $fetch_item['title'];
        $title = "New Bid Success";
        $content = "<h3>Bid Receipt</h3>
                        <p>Bidder name: $username</p>
                        <p>Item name: $itemTitle</p>
                        <p>Your bid price: $itemTitle</p>";
        $outline = "You bidded a new item!";
        switch (sendmail::sendemail($email, $username, $title, $content, $outline)) {
            case 'e000':
                echo "A receipt email sent to your email. Please check.";
                break;
            case 'e001':
                echo "Sending email failed";
                break;
            default:
                echo "Sending email failed";
                break;
        }

        //email sending to all user watching this auction
        $sql_watching = "SELECT email FROM user WHERE user_id IN
                   (SELECT buyer_id FROM watchlist WHERE item_id = $item_id);";
        $result_watching = mysqli_query($con, $sql_watching);
        $email_list = [];
        while ($fetch = mysqli_fetch_array($result_watching)) {
            $email_list[] = $fetch['email'];
        }
        $itemTitle = $fetch_item['title'];
        $title = "One of your watching auction has new bid update!";
        $content = "<h3>Auction Update</h3>
                        <p>Bidder name: $username</p>
                        <p>Item name: $itemTitle</p>
                        <p>New bid price: $bidPrice</p>
                        <p>Update time: $bidTime</p>";
        $outline = "You bidded a new item!";
        switch (sendmail::sendemail($email_list, $email_list, $title, $content, $outline)) {
            case 'e000':
                echo "A receipt email sent to your email. Please check.";
                break;
            case 'e001':
                echo "Sending email failed";
                break;
            default:
                echo "Unknown Error";
                break;
        }
    } else {
        echo "Data insert failed.\n" . "<br/>" . $con->error;
    }
    $con->close();
    // If all is successful, let user know.
    echo ('<div class="text-center">Bid successfully created! <a href="mybids.php">View your new bid record.</a></div>');
} else {
    echo ('Error:Bid price required.');
    $con->close();
    include_once("footer.php");
    header("refresh:$t_refresh;url=listing.php?item_id=$item_id");
}
