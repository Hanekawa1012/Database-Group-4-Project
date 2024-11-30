<?php include_once("header.php") ?>
<?php require_once "send_email.php" ?>

<div class="container my-5">

    <?php
    // This function takes the form data and adds the new auction to the database.

    /* TODO #1: Connect to MySQL database (perhaps by requiring a file that
                already does this). */
    require "my_db_connect.php";

    /* TODO #2: Extract form data into variables. Because the form was a 'post'
                form, its data can be accessed via $POST['auctionTitle'], 
                $POST['auctionDetails'], etc. Perform checking on the data to
                make sure it can be inserted into the database. If there is an
                issue, give some semi-helpful feedback to user. */
    $item_id = (int)$_SESSION['viewing'];

    $sql = "UPDATE auctions SET status = 3 WHERE item_id = $item_id;";

    if (mysqli_query($con, $sql)) {
        echo "Auction cancel succeed.\n";
    } else {
        echo "Auction cancel failed.\n" . "<br/>" . $con->error;
    }
    $sql_item = "SELECT title, details, category, endDate FROM auctions WHERE item_id = $item_id;";
    $fetch_item = mysqli_fetch_array(mysqli_query($con, $sql_item));
    $email = $_SESSION['email'];
    $username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
    $item_title = htmlspecialchars($fetch_item['title'], ENT_QUOTES, 'UTF-8');
    $item_details = htmlspecialchars($fetch_item['details'], ENT_QUOTES, 'UTF-8');
    $item_cat = htmlspecialchars($fetch_item['category'], ENT_QUOTES, 'UTF-8');
    $item_endDate = htmlspecialchars($fetch_item['endDate'], ENT_QUOTES, 'UTF-8');

    $title = "You deleted an auction.";
    $content = "<h3>Auction Receipt</h3>
                <p>Auction id: $item_id</p>
                <p>Name: $item_title</p>
                <p>Details: $item_details</p>
                <p>Category: $item_cat</p>
                <p>End date: $item_endDate</p>";
    $outline = "You deleted an auction.";
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
    // If all is successful, let user know.
    echo ('<div class="text-center">Auction successfully canceled. <a href="mylistings.php">View your listing.</a></div>');
    $con->close();

    ?>

</div>


<?php include_once("footer.php") ?>