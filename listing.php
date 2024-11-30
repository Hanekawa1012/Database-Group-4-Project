<?php include_once("header.php") ?>
<?php require("utilities.php") ?>
<?php require("my_db_connect.php") ?>
<?php // session_start(); 
?>

<?php
// TODO: add tags for switching between:
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
$description = $fetch['details'];
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
                <?php if ($now > $end_time): ?>
                    This auction ended <?php echo (date_format($end_time, 'y-m-d H:i:s')) ?>
                    <!-- TODO: Print the result of the auction here? -->
                <?php else: ?>
                    Auction ends <?php echo (date_format($end_time, 'y-m-d H:i:s') . $time_remaining) ?>
            </p>
            <p class="lead">Current bid: £<?php echo (number_format($current_price, 2)) ?></p>

            <!-- Bidding form -->
            <form method="POST" action="place_bid.php">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">£</span>
                    </div>
                    <input type="number" class="form-control" name="bidPrice" id="bid" required>
                </div>
                <button type="submit" class="btn btn-primary form-control">Place bid</button>
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

            error: function(obj, textstatus) {
                console.log("Error");
            }
        }); // End of AJAX call

    } // End of addToWatchlist func
</script>