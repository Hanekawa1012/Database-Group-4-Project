<?php
session_start();
include('my_db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        die("You must be logged in to delete comments.");
    }

    $user_id = $_SESSION['user_id'];
    $comment_id = mysqli_real_escape_string($con, $_POST['comment_id']);

    // Check if the comment belongs to the user
    $check_sql = "SELECT * FROM comments WHERE comment_id = $comment_id AND buyer_id = $user_id";
    $check_result = mysqli_query($con, $check_sql);

    if (mysqli_num_rows($check_result) == 0) {
        die("You can only delete your own comments.");
    }

    // Delete the comment
    $delete_sql = "DELETE FROM comments WHERE comment_id = $comment_id";
    if (mysqli_query($con, $delete_sql)) {
        header("Location: listing.php?item_id=" . $_GET['item_id']);
    } else {
        echo "Error: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>
