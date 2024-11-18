<?php
//connect to database (if YOURS in XAMPP is DIFFERENT PLEASE CHANGE)
$mydbhost = "localhost";
$mydbuser = "root";
$mydbpasswd = "";
$dbname = "db-group4";

$con = new mysqli($mydbhost, $mydbuser, $mydbpasswd, $dbname);

if ($con->connect_error){
  die("connect error" . $con->connect_error);
}
?>