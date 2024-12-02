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
        $sql = "SELECT
            b1.item_id,
            a.title,
            a.details,
            a.startPrice,
            a.endDate,
            SUM(CASE WHEN b1.present = 1 AND b2.present = 1 THEN 1 ELSE 0 END) / 
            (SQRT(SUM(POWER(b1.present, 2))) * SQRT(SUM(POWER(b2.present, 2)))) AS cosine_similarity
        FROM
            (
                SELECT 
                    u.user_id,
                    a.item_id,
                    IF(b.bid_id IS NOT NULL, 1, 0) AS present
                FROM 
                    user u
                CROSS JOIN 
                    auctions a
                LEFT JOIN 
                    bids b ON u.user_id = b.buyer_id AND a.item_id = b.item_id
            ) AS b1
        JOIN
            (
                SELECT 
                    u.user_id,
                    a.item_id,
                    IF(b.bid_id IS NOT NULL, 1, 0) AS present
                FROM 
                    user u
                CROSS JOIN 
                    auctions a
                LEFT JOIN 
                    bids b ON u.user_id = b.buyer_id AND a.item_id = b.item_id
            ) AS b2 ON b1.item_id = b2.item_id AND b1.user_id != b2.user_id
        JOIN
            auctions a ON b1.item_id = a.item_id
        WHERE
            b1.user_id = $user_id AND b2.user_id != $user_id AND a.endDate > NOW()
        GROUP BY
            b1.item_id, a.title, a.details, a.startPrice, a.endDate
        HAVING
            cosine_similarity > 0.3333333
        ORDER BY
            cosine_similarity DESC
        LIMIT 10;";


        // Step 4: Execute the query
        $result_q = mysqli_query($con, $sql);

        // Step 5: Check for results and display them
        if ($result_q && mysqli_num_rows($result_q) > 0) {
            while ($fetch = mysqli_fetch_assoc($result_q)) {
                $item_id = $fetch['item_id'];
                $title = $fetch['title'];
                $description = $fetch['details'];
                $current_price = $fetch['startPrice'];
                $status = $fetch['status'];
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
                print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date, $status);
            }
        } else {
            echo "<p>No recommendations available at the moment.</p>";
        }
        ?>

    </ul>
</div>

<?php include_once("footer.php"); ?>