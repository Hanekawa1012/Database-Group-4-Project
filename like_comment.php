<?php
session_start();
include('my_db_connect.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        echo json_encode(["success" => false, "message" => "You must be logged in to like comments."]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $comment_id = mysqli_real_escape_string($con, $input['comment_id']);

    // Check if user has already liked the comment
    $check_sql = "SELECT * FROM comment_likes WHERE buyer_id = $user_id AND comment_id = $comment_id";
    $check_result = mysqli_query($con, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo json_encode(["success" => false, "message" => "You have already liked this comment."]);
        exit;
    }

    // Insert like into the database
    $insert_sql = "INSERT INTO comment_likes (buyer_id, comment_id) VALUES ($user_id, $comment_id)";
    if (mysqli_query($con, $insert_sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to like comment."]);
    }

    mysqli_close($con);
}
?>