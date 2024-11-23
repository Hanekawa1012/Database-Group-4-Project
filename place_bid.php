<?php include "header.php" ?>
<?php

// TODO: Extract $_POST variables, check they're OK, and attempt to make a bid.
// Notify user of success/failure and redirect/give navigation options.
if(!(isset($_SESSION['logged_in'])&& $_SESSION['logged_in']== true)){
    header("Location:guest_error.php");
    exit();
  }

require "my_db_connect.php";

if(!isset($_SESSION['logged_in'])||$_SESSION['logged_in']==false){
    header("refresh:3;url=register.php");
    exit();
}

if($_POST['bidPrice'] != ""){
    $user_id = intval($_SESSION['user_id']);
    $bidPrice = $_POST['bidPrice'];
    $bidTime = new DateTime();
    $bidTime = $bidTime->format('y-m-d H:i:s');
    $item_id = intval($_SESSION['viewing']);
    unset($_SESSION['viewing']);


    $sql = "insert into bids(user_id, item_id, bidPrice, bidTime) 
            values($user_id, $item_id, '$bidPrice', '$bidTime');";
    if(mysqli_query($con, $sql)){
        echo "data insert success.\n";
    }else{
        echo "data insert fail.\n"."<br/>".$con->error;
    }
    $con->close();
    // If all is successful, let user know.
    echo('<div class="text-center">Bid successfully created! <a href="mybids.php">View your new bid record.</a></div>');
}else{
    echo('<div class="text-center">Error:Bid price required.</div>');
    header("refresh:3;url=listing.php?itemid=$item_id");
    exit();
}
?>