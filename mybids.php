<?php include_once("header.php") ?>
<?php require("utilities.php") ?>
<?php require("my_db_connect.php") ?>

<div class="container">

    <h2 class="my-3">My bids</h2>

    <?php
    // This page is for showing a user the auctions they've bid on.
    // It will be pretty similar to browse.php, except there is no search bar.
    // This can be started after browse.php is working with a database.
    // Feel free to extract out useful functions from browse.php and put them in
    // the shared "utilities.php" where they can be shared by multiple files.


    // TODO: Check user's credentials (cookie/session).
    if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
        header("Location:guest_error.php");
        exit();
    }
    // TODO: Perform a query to pull up the auctions they've bidded on.

    // TODO: Loop through results and print them out as list items.

    ?>

    <?php include_once "search_bar.php" ?>


</div>


<?php
// Retrieve these from the URL
if (!isset($_GET['keyword'])) {
    // TODO: Define behavior if a keyword has not been specified.
    $keyword = "";
} else {
    $keyword = mysqli_real_escape_string($con, $_GET['keyword']);
}

if (!isset($_GET['cat'])) {
    // TODO: Define behavior if a category has not been specified.
    $category = "";
} else {
    $category = mysqli_real_escape_string($con, $_GET['cat']);
}

if (!isset($_GET['order_by'])) {
    // TODO: Define behavior if an order_by value has not been specified.
    $ordering = "";
} else {
    $ordering = mysqli_real_escape_string($con, $_GET['order_by']);
}

if (!isset($_GET['search_type'])) {
    // TODO: Define behavior if a search_type has not been specified; in union/intersection search
    $search_type = "intersection";
} else {
    $search_type = mysqli_real_escape_string($con, $_GET['search_type']);
}

if (!isset($_GET['page'])) {
    $curr_page = 1;
} else {
    $curr_page = (int)$_GET['page'];
}
/* TODO: Use above values to construct a query. Use this query to 
   retrieve data from the database. (If there is no form data entered,
   decide on appropriate default value/default query to make. */
$buyer_id = $_SESSION['user_id'];
$sql = "SELECT * FROM (
            SELECT * FROM auctions 
            WHERE item_id IN (
                SELECT item_id FROM bids WHERE buyer_id = $buyer_id
            )
        ) AS B";
if (isset($_GET['keyword']) || $keyword != "") {
    $sql .= " WHERE LOCATE('$keyword', title) > 0";
    if ($category != "") {
        $sql .= " UNION SELECT * FROM auctions WHERE category = '$category'";
    }
} else if ($category != "") {
    $sql .= " WHERE category = '$category'";
}
if ($ordering != "") {
    $sql .= " ORDER BY $ordering";
}
$result = mysqli_query($con, $sql);

/* For the purposes of pagination, it would also be helpful to know the
   total number of results that satisfy the above query */
$num_results = mysqli_num_rows($result);
$results_per_page = 3;
$max_page = ceil($num_results / $results_per_page);
?>

<div class="container mt-5">



    <!-- TODO: If result set is empty, print an informative message. Otherwise... -->

    <ul class="list-group">

        <!-- TODO: Use a while loop to print a list item for each auction listing
     retrieved from the query -->


        <?php
        if ($result->num_rows <= 0) {
            echo "No accessible auctions for now.<a href='browse.php'>Bid in an auction to start your own bidding!</a>";
            exit();
        }
        $result = mysqli_query($con, $sql);
        while ($fetch = mysqli_fetch_array($result)) {
            $item_id = $fetch['item_id'];
            $title = $fetch['title'];
            $description = $fetch['details'];

            $bid_sql = "SELECT bidPrice, bidTime FROM bids WHERE item_id = $item_id ORDER BY bidPrice DESC";
            if ($curr_page != "") {
                $bid_sql .= " LIMIT " . (($curr_page - 1) * $results_per_page) . ", $results_per_page";
            }
            $bid_result = mysqli_query($con, $bid_sql);
            $num_bids = mysqli_num_rows($bid_result);
            $end_date = $fetch['endDate'];
            while ($bid_row = mysqli_fetch_assoc($bid_result)) {
                $bidPrice = $bid_row['bidPrice'];
                $bidTime = $bid_row['bidTime'];
                print_bid_listing_li($item_id, $title, $description, $bidPrice, $bidTime, $end_date);
            }
        }
        $num_results = mysqli_num_rows($bid_result);
        $results_per_page = 3;
        $max_page = ceil($num_results / $results_per_page);
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
      <a class="page-link" href="mybids.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
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
      <a class="page-link" href="mybids.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
            }

            if ($curr_page != $max_page) {
                echo ('
    <li class="page-item">
      <a class="page-link" href="mybids.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
            }
            ?>

        </ul>
    </nav>


</div>

<?php include_once("footer.php") ?>