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
        $sql = "WITH user_item_scores AS (
            SELECT 
                u.user_id,
                a.item_id,
                COALESCE(b.coef, 0) + COALESCE(c.coef, 0) + COALESCE(w.coef, 0) AS coef
            FROM 
                user u
            CROSS JOIN 
                auctions a
            LEFT JOIN (
                SELECT b.buyer_id, b.item_id, 3 AS coef
                FROM bids b
            ) AS b
            ON u.user_id = b.buyer_id AND a.item_id = b.item_id
            LEFT JOIN (
                SELECT c.buyer_id, c.item_id, 2 AS coef
                FROM comments c
            ) AS c
            ON u.user_id = c.buyer_id AND a.item_id = c.item_id
            LEFT JOIN (
                SELECT w.buyer_id, w.item_id, 1 AS coef
                FROM watchlist w
            ) AS w
            ON u.user_id = w.buyer_id AND a.item_id = w.item_id
        ),
        user_magnitude AS (
            SELECT 
                user_id,
                SQRT(SUM(POWER(coef, 2))) AS user_mag
            FROM user_item_scores
            GROUP BY user_id
        ),
        item_magnitude AS (
            SELECT 
                item_id,
                SQRT(SUM(POWER(coef, 2))) AS item_mag
            FROM user_item_scores
            GROUP BY item_id
        ),
        dot_product AS (
            SELECT 
                u.user_id,
                u.item_id,
                SUM(u.coef * v.coef) AS dot_prod
            FROM user_item_scores u
            JOIN user_item_scores v
            ON u.item_id = v.item_id
            GROUP BY u.user_id, u.item_id
        ),
        cosine_similarity_scores AS (
            SELECT 
                dp.user_id,
                dp.item_id,
                dp.dot_prod / (um.user_mag * im.item_mag) AS cosine_similarity
            FROM 
                dot_product dp
            JOIN user_magnitude um ON dp.user_id = um.user_id
            JOIN item_magnitude im ON dp.item_id = im.item_id
        )
        SELECT 
            a.item_id,
            a.title,
            a.details,
            a.startPrice,
            a.endDate,
            a.status
        FROM 
            cosine_similarity_scores cs
        JOIN auctions a ON cs.item_id = a.item_id
        WHERE 
            cs.user_id = $user_id
            AND a.endDate > NOW()
            AND a.status = 'active'
            AND cs.cosine_similarity > 0.25
        ORDER BY 
            cs.cosine_similarity DESC
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