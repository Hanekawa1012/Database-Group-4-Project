<?php include_once("header.php") ?>
<?php require("utilities.php") ?>
<?php require("my_db_connect.php") ?>
<?php // session_start(); 
?>

<?php
// TODO: add features for
// [Details] [Bid history] [Comments]


// Get info from the URL:
if (!isset($_GET['item_id'])) {
    $item_id = $_SESSION['viewing'];
} else {
    $item_id = $_GET['item_id'];
}

$_SESSION['viewing'] = $item_id;

// Use item_id to make a query to the database.
$sql = "SELECT * FROM auctions WHERE item_id = '$item_id';";
$result = mysqli_query($con, $sql);
$fetch = mysqli_fetch_array($result);

$title = $fetch['title'];
$status = $fetch['status'];
$description = $fetch['details'];
$seller_id = $fetch['seller_id'];
$bid_sql = "SELECT bidPrice FROM bids WHERE item_id = $item_id ORDER BY bidPrice DESC";
$bid_result = mysqli_query($con, $bid_sql);
$num_bids = mysqli_num_rows($bid_result);
if ($num_bids > 0) {
    $current_price = mysqli_fetch_assoc($bid_result)['bidPrice'];
} else {
    $current_price = $fetch['startPrice'];
}
$end_time = new DateTime($fetch['endDate']);

//       Note: Auctions that have ended may pull a different set of data,
//       like whether the auction ended in a sale or was cancelled due
//       to lack of high-enough bids. Or maybe not.

// Calculate time to auction end:
$now = new DateTime();

if ($now < $end_time) {
    $time_to_end = date_diff($now, $end_time);
    $time_remaining = ' (in ' . display_time_remaining($time_to_end) . ')';
}

//       If the user has a session, use it to make a query to the database
//       to determine if the user is already watching this item.
if (isset($_SESSION['user_id'])) {
    $has_session = true;
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT buyer_id, item_id FROM watchlist WHERE buyer_id = '$user_id' AND item_id = '$item_id'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_num_rows($result);
    if (!$row) {
        $watching = false;
    } else {
        $watching = true;
    }
} else {
    $has_session = false;
    $watching = false;
}
?>


<div class="container">

    <div class="row"> <!-- Row #1 with auction title + watch button -->
        <div class="col-sm-8"> <!-- Left col -->
            <h2 class="my-3"><?php echo ($title); ?></h2>
        </div>
        <div class="col-sm-4 align-self-center"> <!-- Right col -->
            <?php
            /* The following watchlist functionality uses JavaScript, but could
               just as easily use PHP as in other places in the code */
            if ($now < $end_time):
            ?>
                <div id="watch_nowatch" <?php if ($has_session && $watching) {
                                            echo 'style="display:none"';
                                        } ?>>
                    <!-- <button type="button" class="btn btn-success btn-sm" disabled>Watching</button> -->
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addToWatchlist()">+ Add to
                        watchlist</button>
                </div>
                <div id="watch_watching" <?php if (!($has_session && $watching)) {
                                                echo 'style="display:none"';
                                            } ?>>

                    <button type="button" class="btn btn-danger btn-sm" onclick="removeFromWatchlist()">Remove
                        watch</button>
                </div>
            <?php endif/* Print nothing otherwise */ ?>
        </div>
    </div>

    <div class="row"> <!-- Row #2 with auction description + bidding info -->
        <div class="col-sm-8"> <!-- Left col with item info -->

            <div class="itemDescription">
                <?php echo ($description); ?>
            </div>

        </div>

        <div class="col-sm-4"> <!-- Right col with bidding info -->

            <p>
                <?php if ($status == 3): ?>
                    This auction was cancelled by its owner.
                    <!-- TODO: Print the result of the auction here? -->
                <?php elseif ($now > $end_time): ?>
                    This auction ended <?php echo (date_format($end_time, 'y-m-d H:i:s')) ?>
                    <!-- TODO: Print the result of the auction here? -->
                <?php else: ?>
                    Auction ends <?php echo (date_format($end_time, 'y-m-d H:i:s') . $time_remaining) ?>
            </p>
        <?php endif; ?>
        <p class="lead">Current bid: £<?php echo (number_format($current_price, 2)) ?></p>

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && $_SESSION['account_type'] == 'buyer'): ?>
            <!-- Bidding form -->
            <form method="POST" action="place_bid.php">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">£</span>
                    </div>
                    <input type="number" class="form-control" name="bidPrice" id="bid">
                </div>
                <button type="submit" class="btn btn-primary form-control">Place bid</button>
            </form>
        <?php elseif (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && $_SESSION['account_type'] == 'seller' && $_SESSION['user_id'] == $seller_id): ?>
            <form method="GET" action="cancel_auction.php">
                <button type="submit" class="btn btn-danger form-control">Cancel auction</button>
            </form>
        <?php endif ?>


        </div> <!-- End of right col with bidding info -->

    </div> <!-- End of row #2 -->


</div>

<?php $con->close(); ?>
<?php include_once("footer.php") ?>


<script>
    // JavaScript functions: addToWatchlist and removeFromWatchlist.

    function addToWatchlist(button) {
        console.log("Function call success.");

        // This performs an asynchronous call to a PHP function using POST method.
        // Sends item ID as an argument to that function.
        $.ajax('watchlist_funcs.php', {
            type: "POST",
            data: {
                functionname: 'add_to_watchlist',
                arguments: <?php echo ($item_id); ?>
            },

            success: function(obj, textstatus) {
                // Callback function for when call is successful and returns obj
                console.log("Success");

                var objT = obj.trim();

                if (objT == "success") {

                    $("#watch_nowatch").hide();
                    $("#watch_watching").show();
                } else {
                    if (document.getElementById("error_add_text") == null) {
                        var mydiv = document.getElementById("watch_nowatch");
                        // mydiv.appendChild(document.createElement("br"));
                        alarm_text = document.createElement("small");
                        alarm_text.setAttribute("class", "sm-button-alm");
                        alarm_text.setAttribute("id", "error_add_text");
                        mydiv.appendChild(alarm_text);
                        alarm_text.appendChild(document.createTextNode("Add failed. "));
                    }
                }
            },

            error: function(obj, textstatus) {
                console.log("Error");
            }
        }); // End of AJAX call

    } // End of addToWatchlist func

    function removeFromWatchlist(button) {
        console.log("Function call success.");
        // This performs an asynchronous call to a PHP function using POST method.
        // Sends item ID as an argument to that function.
        $.ajax('watchlist_funcs.php', {
            type: "POST",
            data: {
                functionname: 'remove_from_watchlist',
                arguments: <?php echo ($item_id); ?>
            },

            success: function(obj, textstatus) {
                // Callback function for when call is successful and returns obj
                console.log("Success");

                var objT = obj.trim();
                console.log(objT);
                if (objT == "success") {
                    $("#watch_watching").hide();
                    $("#watch_nowatch").show();
                } else {
                    if (document.getElementById("error_remove_text") == null) {
                        var mydiv = document.getElementById("watch_watching");
                        // mydiv.appendChild(document.createElement("br"));
                        alarm_text = document.createElement("small");
                        alarm_text.setAttribute("class", "sm-button-alm");
                        alarm_text.setAttribute("id", "error_remove_text");
                        mydiv.appendChild(alarm_text);
                        alarm_text.appendChild(document.createTextNode("Removal failed. "));
                    }
                }
            },
        });
    } // End of addToWatchlist func
</script>
</div>


<div class="container" id="mybids">

    <?php
    if (!isset($_GET['page'])) {
        $curr_page = 1;
    } else {
        $curr_page = $_GET['page'];
    }
    /* TODO: Use above values to construct a query. Use this query to 
       retrieve data from the database. (If there is no form data entered,
       decide on appropriate default value/default query to make. */
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true && $_SESSION['account_type'] == 'buyer') {
        echo "<h2 class='my-3'>My Bidding History</h2>";
        $buyer_id = $_SESSION['user_id'];
        $sql = "SELECT auctions.item_id, auctions.title, auctions.details, auctions.endDate, b.bidTime, b.bidPrice 
            FROM (SELECT item_id, bidTime, bidPrice FROM bids WHERE bids.buyer_id = $buyer_id and bids.item_id = $item_id) as b
            INNER JOIN auctions
            ON b.item_id = auctions.item_id
            ORDER BY bidPrice DESC";


        $result = mysqli_query($con, $sql);

        /* For the purposes of pagination, it would also be helpful to know the
           total number of results that satisfy the above query */
        $num_results = mysqli_num_rows($result);
        $results_per_page = 3;
        $max_page = ceil($num_results / $results_per_page);
    ?>

        <div class="container mt-5" id="mybids">



            <!-- TODO: If result set is empty, print an informative message. Otherwise... -->

            <ul class="list-group">

                <!-- TODO: Use a while loop to print a list item for each auction listing
     retrieved from the query -->


                <?php
                if ($result->num_rows <= 0) {
                    echo "No accessible auctions for now.<a href='browse.php'>Bid in an auction to start your own bidding!</a>";
                    exit();
                }
                $sql .= " LIMIT " . (($curr_page - 1) * $results_per_page) . ", $results_per_page";
                $result = mysqli_query($con, $sql);
                while ($fetch = mysqli_fetch_array($result)) {
                    $item_id = $fetch['item_id'];
                    $title = $fetch['title'];
                    $description = $fetch['details'];

                    $end_date = $fetch['endDate'];
                    $bidPrice = $fetch['bidPrice'];
                    $bidTime = $fetch['bidTime'];
                    print_bid_listing_li($item_id, $title, $description, $bidPrice, $bidTime, $end_date);
                }
                ?>

            </ul>

            <!-- Pagination for results listings -->
            <nav aria-label="Search results pages" class="mt-5">
                <ul class="pagination justify-content-center">

                <?php

                // Copy any currently-set GET variables to the URL.
                $querystring = "";
                foreach ($_GET as $key => $value) {
                    if ($key != "page") {
                        $querystring .= "$key=$value&amp;";
                    }
                }

                $high_page_boost = max(3 - $curr_page, 0);
                $low_page_boost = max(2 - ($max_page - $curr_page), 0);
                $low_page = max(1, $curr_page - 2 - $low_page_boost);
                $high_page = min($max_page, $curr_page + 2 + $high_page_boost);

                if ($curr_page != 1) {
                    echo ('
    <li class="page-item">
      <a class="page-link" href="listing.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
                }

                for ($i = $low_page; $i <= $high_page; $i++) {
                    if ($i == $curr_page) {
                        // Highlight the link
                        echo ('
    <li class="page-item active">');
                    } else {
                        // Non-highlighted link
                        echo ('
    <li class="page-item">');
                    }

                    // Do this in any case
                    echo ('
      <a class="page-link" href="listing.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
                }

                if ($curr_page != $max_page) {
                    echo ('
    <li class="page-item">
      <a class="page-link" href="listing.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
                }
            }
                ?>

                </ul>
            </nav>
        </div>

</div>

<?php
// Displaying comments for the auction:
$comments_sql = "SELECT c.comment_id, c.buyer_id, c.time, c.content, u.username, c.parent_comment_id
                 FROM comments c
                 JOIN profile u ON c.buyer_id = u.user_id
                 WHERE c.item_id = $item_id
                 ORDER BY c.time DESC";

$comments_result = mysqli_query($con, $comments_sql);
?>

<div class="container mt-5" id="comments-section">
    <h2>Comments</h2>

    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true): ?>
        <!-- Comment submission form -->
        <form method="POST" action="post_comment.php">
            <div class="form-group">
                <!-- <label for="commentContent">Write a comment:</label> -->
                <textarea class="form-control" id="commentContent" name="content" placeholder="Write a comment..." required></textarea>
            </div>
            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
            <button type="submit" class="btn btn-primary">Post Comment</button>
        </form>
    <?php else: ?>
        <p>Please log in to post a comment.</p>
    <?php endif; ?>

    <!-- Displaying existing comments -->
    <ul class="list-group mt-3">
        <?php if (mysqli_num_rows($comments_result) > 0): ?>
            <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                <li class="list-group-item">
                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                    <span class="text-muted"><?php echo date("Y-m-d H:i:s", strtotime($comment['time'])); ?></span>
                    <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>

                    <div class="comment-actions">
                        <!-- Star-based rating (with placeholder logic) -->
                        <button class="btn btn-link" onclick="likeComment(<?php echo $comment['comment_id']; ?>)">Like</button>
                        <?php
                        // search in comment_likes table to see how many likes this comment has
                        $sql = 'SELECT COUNT(*) AS likes FROM comment_likes WHERE comment_id = ' . $comment['comment_id'];
                        $result = mysqli_query($con, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $comment['likes'] = $row['likes'];
                        ?>
                        <span><?php echo $comment['likes'] . " like(s)"; ?></span>
                        <!-- Reply functionality -->
                        <button class="btn btn-link" onclick="showReplyForm(<?php echo $comment['comment_id']; ?>)">Reply</button>
                    </div>

                    <!-- Reply form (hidden by default) -->
                    <div id="replyForm-<?php echo $comment['comment_id']; ?>" style="display:none;">
                        <form method="POST" action="post_comment.php">
                            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                            <input type="hidden" name="parent_comment_id" value="<?php echo $comment['comment_id']; ?>">
                            <div class="form-group">
                                <!-- <label for="replyContent-<?php echo $comment['comment_id']; ?>">Write a reply:</label> -->
                                <textarea class="form-control" id="replyContent-<?php echo $comment['comment_id']; ?>" name="content" placeholder="Reply..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Reply</button>
                        </form>
                    </div>
                </li>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>
    </ul>
</div>

<script>
    // JavaScript for toggling reply forms
    function showReplyForm(commentId) {
        var form = document.getElementById('replyForm-' + commentId);
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }

    // JavaScript for liking a comment (requires backend logic)
    function likeComment(commentId) {
        $.ajax('comment_funcs.php', {
            type: "POST",
            data: {
                functionname: 'like_comment',
                arguments: commentId
            },
            success: function(response) {
                if (response.trim() === "success") {
                    alert("Comment liked!");
                    location.reload(); // Refresh to show updated like count
                } else {
                    alert("Failed to like the comment. Try again later.");
                }
            },
            error: function() {
                console.log("Error liking the comment.");
            }
        });
    }
</script>