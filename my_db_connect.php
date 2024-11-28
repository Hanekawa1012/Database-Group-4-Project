<?php
//connect to database (if YOURS in XAMPP is DIFFERENT PLEASE CHANGE)
$mydbhost = "localhost";
$mydbuser = "root";
$mydbpasswd = "";
$dbname = "db-group4";

try {
    $con = new mysqli($mydbhost, $mydbuser, $mydbpasswd, $dbname);
} catch (Exception $e) {
    echo "Error: Database not function well. ";
    echo "Details: " . $e;
    exit();
}

if ($con->connect_error) {
    die("connect error" . $con->connect_error);
}
