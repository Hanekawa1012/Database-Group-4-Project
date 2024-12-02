<?php include_once "header.php" ?>
<?php require_once "send_email.php" ?>
<?php
// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
    echo 'You are not logged in! <a href="" data-toggle="modal" data-target="#loginModal">Login</a>';
    header('refresh:3;browse.php');
    exit();
}
if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] == 'seller') {
    echo 'Only buyer-type account can join bidding. If you want to bid for an item, please register a buyer account.';
    header('refresh:3;browse.php');
    exit();
}

require "my_db_connect.php";

if ($_POST['bidPrice'] != "") {
    $user_id = intval($_SESSION['user_id']);
    $bidPrice = mysqli_real_escape_string($con, $_POST['bidPrice']);
    $bidTime = new DateTime();
    $bidTime = $bidTime->format('y-m-d H:i:s');
    $item_id = intval($_SESSION['viewing']);
    $current_price = $_POST['current_price'];

    if ($bidPrice <= $current_price) {
        echo ('Error: Bid price shold be greater than current one.');
        exit();
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

        //email sending to all user watching this auction
        $sql_watching = "SELECT email FROM user WHERE user_id IN
                   (SELECT buyer_id FROM watchlist WHERE item_id = $item_id);";
        $result_watching = mysqli_query($con, $sql_watching);
        $email_list = [];
        while($fetch = mysqli_fetch_array($result_watching)){
            $email_list[] = $fetch['email'];
        }
        $itemTitle = $fetch_item['title'];
        $title = "One of your watching auction has new bid update!";
        $content = "<h3>Auction Update</h3>
                    <p>Bidder name: $username</p>
                    <p>Item name: $itemTitle</p>
                    <p>New bid price: $bidPrice</p>
                    <p>Update time: $bidTime</p>";
        $outline = "One of your watching auction has new bid update!";
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
    exit();
}
?>

<?php include "footer.php" ?>