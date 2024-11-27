<?php // email for who watched an auction
$sql_item = "SELECT title, details, category, endDate FROM auctions WHERE item_id = $item_id;";
$fetch_item = mysqli_fetch_array(mysqli_query($con, $sql_item));
$con->close();
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$title = "New watching auction added!";
$content = "<h3>You added a new auction to your watchlist!</h3>";
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
?>

<?php // email sending to user after bidding
$sql_item = "SELECT title, details, category, endDate FROM auctions WHERE item_id = $item_id;";
$fetch_item = mysqli_fetch_array(mysqli_query($con, $sql_item));
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$itemTitle = $fetch_item['title'];
$title = "New Bid Success";
$content = "<h3>Bid Receipt</h3>
              <p>Bidder name: $username</p>
              <p>Item name: $itemTitle</p>
              <p>Your bid price: $bidPrice</p>";
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
  $sql_watching = "SELECT email, username FROM user WHERE user_id IN
              (SELECT buyer_id FROM watchlist WHERE item_id = $item_id);";
  $result_watching = mysqli_query($con, $sql_watching);
  $email_list = [];
  $username_list = [];
  while($fetch = mysqli_fetch_array($result_watching)){
      $email_list[] = $fetch['email'];
      $username_list[] = $fetch['username'];
  }
  $itemTitle = $fetch_item['title'];
  $title = "One of your watching auction has new bid update!";
  $content = "<h3>Auction Update</h3>
              <p>Bidder name: $username</p>
              <p>Item name: $itemTitle</p>
              <p>New bid price: $bidPrice</p>
              <p>Update time: $bidTime</p>";
  $outline = "You bidded a new item!";
  switch (sendmail::sendemail($email_list, $username_list, $title, $content, $outline)) {
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
?>