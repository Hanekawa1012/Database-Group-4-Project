<?php include_once("header.php") ?>

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
    $auctionTitle = mysqli_real_escape_string($con, $_POST['auctionTitle']);
    $auctionDetails = mysqli_real_escape_string($con, $_POST['auctionDetails']);
    $auctionCategory = mysqli_real_escape_string($con, $_POST['auctionCategory']);
    $auctionStartPrice = mysqli_real_escape_string($con, $_POST['auctionStartPrice']);
    $auctionReservePrice = mysqli_real_escape_string($con, $_POST['auctionReservePrice']);
    $auctionEndDate = mysqli_real_escape_string($con, $_POST['auctionEndDate']);
    $auctionSellerID = $_SESSION['user_id'];

    if ($auctionTitle == "") {
        echo "Error: Title required.";
        header("refresh:3;url=create_auction.php");
        exit();
    }
    if ($auctionCategory == "None") {
        echo "Error: Category needed";
        header("refresh:3;url=create_auction.php");
        exit();
    }
    if ($auctionStartPrice == "") {
        echo "Error: Starting price required";
        header("refresh:3;url=create_auction.php");
        exit();
    }
    if ($auctionEndDate == "") {
        echo "Error: End date needed";
        header("refresh:3;url=create_auction.php");
        exit();
    }
    $now = new DateTime();
    $checkEndDate = new DateTime($auctionEndDate);
    if ($checkEndDate <= $now) {
        echo "Error: End date should be later than present time.";
        header("refresh:3;url=create_auction.php");
        exit();
    }

    /* TODO #3: If everything looks good, make the appropriate call to insert
                data into the database. */

    $sql = "INSERT INTO auctions(title, details, category, startPrice, reservePrice, startDate, endDate, seller_id) 
            VALUES ('$auctionTitle', '$auctionDetails', '$auctionCategory', '$auctionStartPrice', 
                    '$auctionReservePrice', NOW(), '$auctionEndDate', '$auctionSellerID');";

    if (mysqli_query($con, $sql)) {
        echo "Auction created successfully.\n";
    } else {
        echo "Create failed.\n" . "<br/>" . $con->error;
    }
    $con->close();
    // If all is successful, let user know.
    echo ('<div class="text-center">Auction successfully created! <a href="mylistings.php">View your new listing.</a></div>');


    ?>

</div>


<?php include_once("footer.php") ?>