<?php include_once("header.php"); ?>
<?php require("utilities.php"); ?>
<?php require("my_db_connect.php"); ?>

<div class="container">

    <h2 class="my-3">Recommendations for you</h2>

    <ul class="list-group">

        <?php
        // Step 1: Check user's credentials (cookie/session)
        // session_start();
        if (!isset($_SESSION['user_id'])) {
            echo "<p>Please log in to see recommendations.</p>";
        }

        // Step 2: Get the current user ID from the session
        $user_id = $_SESSION['user_id'];

        // Step 3: Create the SQL query to retrieve recommended items based on cosine similarity
        $sql = "
            SELECT
                a.item_id,
                a.title,
                a.details,
                a.startPrice,
                a.endDate
            FROM
                auctions a
            JOIN
                (
                    SELECT
                        b1.item_id,
                        SUM(b1.bidPrice * b2.bidPrice) / 
                        (SQRT(SUM(POWER(b1.bidPrice, 2))) * SQRT(SUM(POWER(b2.bidPrice, 2)))) AS cosine_similarity
                    FROM
                        bids b1
                    JOIN
                        bids b2 ON b1.buyer_id = b2.buyer_id AND b1.item_id != b2.item_id
                    WHERE
                        b1.buyer_id = $user_id
                    GROUP BY
                        b1.item_id, b2.item_id
                    HAVING
                        cosine_similarity > 0.5
                    ORDER BY
                        cosine_similarity DESC
                    LIMIT 10
                ) AS recommended_items ON a.item_id = recommended_items.item_id
        ";

        // Step 4: Execute the query
        $result_q = mysqli_query($con, $sql);

        // Step 5: Check for results and display them
        if ($result_q && mysqli_num_rows($result_q) > 0) {
            while ($fetch = mysqli_fetch_assoc($result_q)) {
                $item_id = $fetch['item_id'];
                $title = $fetch['title'];
                $description = $fetch['details'];
                $current_price = $fetch['startPrice'];
                $end_date = $fetch['endDate'];

                // Fetch the current highest bid price for the item
                $bid_sql = "SELECT bidPrice FROM bids WHERE item_id = $item_id ORDER BY bidPrice DESC LIMIT 1";
                $bid_result = mysqli_query($con, $bid_sql);
                if ($bid_result && mysqli_num_rows($bid_result) > 0) {
                    $current_price = mysqli_fetch_assoc($bid_result)['bidPrice'];
                }

                // Fetch the number of bids for the item
                $num_bids = mysqli_num_rows($bid_result);

                // Print each item as a list item
                print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date);
            }
        } else {
            echo "<p>No recommendations available at the moment.</p>";
        }
        ?>

    </ul>
</div>

<?php include_once("footer.php"); ?>
