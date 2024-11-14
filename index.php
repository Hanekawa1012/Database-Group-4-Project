<?php
  // For now, index.php just redirects to browse.php, but you can change this
  // if you like.
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "user";

  $conn = new mysqli($servername, $username, $password, $dbname);


  if ($conn->connect_error){
    die("connect error" . $conn->connect_error);
  }

  echo "conneted successful.";

  header("Location: browse.php");
?>