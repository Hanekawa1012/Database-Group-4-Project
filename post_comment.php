<?php
session_start();
include('my_db_connect.php'); // Assuming this file connects to the database

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    die("You must be logged in to post a comment.");
}

// Check if content is provided and item ID is valid
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content']) && !empty(trim($_POST['content']))) {
    $item_id = mysqli_real_escape_string($con, $_POST['item_id']);
    $content = mysqli_real_escape_string($con, $_POST['content']);
    $user_id = $_SESSION['user_id']; // Assuming user ID is stored in the session
    $parent_comment_id = (isset($_POST['parent_comment_id']) and !empty($_POST['parent_comment_id']))
     ? mysqli_real_escape_string($con, $_POST['parent_comment_id']) : 'NULL';

    // Insert comment into the database
    $sql = "INSERT INTO comments (item_id, buyer_id, time, content, parent_comment_id) 
            VALUES ($item_id, $user_id, NOW(), '$content', $parent_comment_id)";

    echo ($sql);

    if (mysqli_query($con, $sql)) {
        // Redirect back to the item page after posting the comment
        header("Location: listing.php?item_id=" . $item_id);
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    echo "Comment content is required.";
}

// Close the database connection
mysqli_close($con);
?>