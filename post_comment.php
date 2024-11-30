<?php
// post_comment.php

require_once 'my_db_connect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_comment') {
        $item_id = intval($_POST['item_id']);
        $buyer_id = intval($_POST['buyer_id']);
        $content = trim($_POST['content']);
        $parent_comment_id = isset($_POST['parent_comment_id']) ? intval($_POST['parent_comment_id']) : null;
        $time = date('Y-m-d H:i:s');

        if (!empty($content)) {
            $query = sprintf(
                "INSERT INTO comments (item_id, buyer_id, time, content, parent_comment_id) 
                 VALUES ('%d', '%d', '%s', '%s', %s)",
                $item_id,
                $buyer_id,
                $con->real_escape_string($time),
                $con->real_escape_string($content),
                $parent_comment_id === null ? 'NULL' : $parent_comment_id
            );

            if (mysqli_query($con, $query)) {
                echo json_encode(["success" => true, "message" => "Comment added successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to add comment."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Comment content cannot be empty."]);
        }

    } elseif ($action === 'like_comment') {
        $comment_id = intval($_POST['comment_id']);
        $buyer_id = intval($_POST['buyer_id']);

        $query = sprintf(
            "INSERT INTO comment_likes (comment_id, buyer_id) VALUES ('%d', '%d')",
            $comment_id,
            $buyer_id
        );

        if (mysqli_query($con, $query)) {
            echo json_encode(["success" => true, "message" => "Comment liked successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to like comment."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid action."]);
    }
}

$con->close();

?>