<?php
// comment_funcs.php

require_once 'db_connect.php'; // Include your database connection

function addComment($item_id, $buyer_id, $content, $parent_comment_id = null)
{
    global $con;
    $time = date('Y-m-d H:i:s');

    $query = sprintf(
        "INSERT INTO comments (item_id, buyer_id, time, content, parent_comment_id) 
         VALUES ('%d', '%d', '%s', '%s', %s)",
        $item_id,
        $buyer_id,
        $con->real_escape_string($time),
        $con->real_escape_string($content),
        $parent_comment_id === null ? 'NULL' : $parent_comment_id
    );

    return mysqli_query($con, $query);
}

function likeComment($comment_id, $buyer_id)
{
    global $con;

    $query = sprintf(
        "INSERT INTO comment_likes (comment_id, buyer_id) VALUES ('%d', '%d')",
        $comment_id,
        $buyer_id
    );

    return mysqli_query($con, $query);
}

function getComments($item_id, $limit, $offset)
{
    global $con;

    $query = sprintf(
        "SELECT c.comment_id, c.content, c.time, COUNT(cl.buyer_id) AS like_count, c.parent_comment_id, b.username AS buyer_username 
         FROM comments c 
         LEFT JOIN buyer b ON c.buyer_id = b.user_id 
         LEFT JOIN comment_likes cl ON c.comment_id = cl.comment_id 
         WHERE c.item_id = '%d' 
         GROUP BY c.comment_id 
         ORDER BY c.time DESC 
         LIMIT %d OFFSET %d",
        $item_id,
        $limit,
        $offset
    );

    $result = mysqli_query($con, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

?>