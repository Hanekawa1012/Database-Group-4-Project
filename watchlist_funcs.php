<?php require("utilities.php")?>
<?php require("my_db_connect.php")?>
<?php session_start();?>
<?php

if (!isset($_POST['functionname']) || !isset($_POST['arguments'])) {
  return;
}

// Extract arguments from the POST variables:
$item_id = intval($_POST['arguments']);
$user_id = intval($_SESSION['user_id']);


if ($_POST['functionname'] == "add_to_watchlist") {
  // TODO: Update database and return success/failure.
  //NEEDS FIXING!!! I CAN'T FIND WHAT'S WRONG!!! -- TIM
  $sql = "insert into watchlist(user_id, item_id) 
          values( '$user_id', '$item_id' );";
  if(mysqli_query($con, $sql)){
    $res = "success";
  }else{
    $res = "error";
  }
  $con->close();
}
else if ($_POST['functionname'] == "remove_from_watchlist") {
  // TODO: Update database and return success/failure.
  //NEEDS FIXING!!! I CAN'T FIND WHAT'S WRONG!!! -- TIM
  $sql = "delete from watchlist where user_id = $user_id and item_id = $item_id;";
  if(mysqli_query($con, $sql)){
    $res = "success";
  }else{
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