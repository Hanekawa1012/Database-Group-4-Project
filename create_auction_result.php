<?php include_once("header.php")?>

<div class="container my-5">

<?php

// This function takes the form data and adds the new auction to the database.

/* TODO #1: Connect to MySQL database (perhaps by requiring a file that
            already does this). */
require_once "my_db_connect.php";

/* TODO #2: Extract form data into variables. Because the form was a 'post'
            form, its data can be accessed via $POST['auctionTitle'], 
            $POST['auctionDetails'], etc. Perform checking on the data to
            make sure it can be inserted into the database. If there is an
            issue, give some semi-helpful feedback to user. */
$auctionTitle = $_POST['auctionTitle'];
$auctionDetails = $_POST['auctionDetails'];
$auctionCategory = $_POST['auctionCategory'];
$auctionStartPrice = $_POST['auctionStartPrice'];
$auctionReservePrice = $_POST['auctionReservePrice'];
$auctionEndDate = $_POST['auctionEndDate'];

if($auctionTitle == ""){
    echo "Error:Title required.";
    exit();
}
if($auctionCategory == ""){
    echo "Error:category needed";
    exit();
}
if($auctionStartPrice == ""){
    echo "Error:starting price required";
    exit();
}
if($auctionEndDate == ""){
    echo "Error:end date needed";
    exit();
}


/* TODO #3: If everything looks good, make the appropriate call to insert
            data into the database. */

$sql = "insert into auctions(title, details, category, startPrice, reservePrice, endDate) 
        values('$auctionTitle','$auctionDetails','$auctionCategory','$auctionStartPrice','$auctionReservePrice','$auctionEndDate')";
if($con->query($sql) == true){
    echo "data insert success.\n";
}else{
    echo "data insert fail.\n"."<br/>".$con->error;
}
$con->close();
// If all is successful, let user know.
echo('<div class="text-center">Auction successfully created! <a href="mylistings.php">View your new listing.</a></div>');


?>

</div>


<?php include_once("footer.php")?>