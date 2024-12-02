<?php include_once("header.php") ?>
<?php require("utilities.php") ?>
<?php require("my_db_connect.php") ?>

<div class="container">

	<h2 class="my-3">Browse listings</h2>

	<?php include_once "search_bar.php" ?>

</div>

<?php
// Retrieve these from the URL
if (!isset($_GET['keyword'])) {
	// TODO: Define behavior if a keyword has not been specified.
	$keyword = "";
} else {
	$keyword = $_GET['keyword'];
}

if (!isset($_GET['cat'])) {
	// TODO: Define behavior if a category has not been specified.
	$category = "";
} else {
	$category = $_GET['cat'];
}

if (!isset($_GET['order_by'])) {
	// TODO: Define behavior if an order_by value has not been specified.
	$ordering = "";
} else {
	$ordering = $_GET['order_by'];
}

if (!isset($_GET['search_type'])) {
	// TODO: Define behavior if a search_type has not been specified; in union/intersection search
	$search_type = "intersection";
} else {
	$search_type = $_GET['search_type'];
}

if (!isset($_GET['page'])) {
	$curr_page = 1;
} else {
	$curr_page = $_GET['page'];
}

$keyword = mysqli_real_escape_string($con, $keyword);
$category = mysqli_real_escape_string($con, $category);
$ordering = mysqli_real_escape_string($con, $ordering);
$search_type = mysqli_real_escape_string($con, $search_type);
$curr_page = (int)$curr_page; 

/* TODO: Use above values to construct a query. Use this query to 
   retrieve data from the database. (If there is no form data entered,
   decide on appropriate default value/default query to make. */
$sql = "SELECT * FROM auctions";

// if ($search_type = "union") {
// 	$sql .= " WHERE title LIKE '%$keyword%' OR details LIKE '%$keyword%'";
// } else {
// 	$sql .= " WHERE LOCATE('$keyword', title) > 0 AND category = '$category'";
// }

//存疑 回头看
if (isset($_GET['keyword']) || $keyword != "") {
	$sql .= " WHERE (title LIKE '%$keyword%' OR details LIKE '%$keyword%')";
	if ($category != "") {
		if ($search_type == "union") {
			$sql .= " UNION SELECT * FROM auctions WHERE category = '$category'";
		} else {
			$sql .= " AND category = '$category'";
		}
	}
} else if ($category != "") {
	$sql .= " WHERE category = '$category'";
}
if ($ordering != "") {
	$sql .= " ORDER BY $ordering";
}

$result_q = mysqli_query($con, $sql);

/* For the purposes of pagination, it would also be helpful to know the
   total number of results that satisfy the above query */
$num_results = mysqli_num_rows($result_q);
$results_per_page = 10;
$max_page = ceil($num_results / $results_per_page);
?>

<div class="container mt-5">



	<!-- TODO: If result set is empty, print an informative message. Otherwise... -->

	<ul class="list-group">

		<!-- TODO: Use a while loop to print a list item for each auction listing
	 retrieved from the query -->


		<?php
		if ($result_q->num_rows <= 0) {
			echo "No accessible auctions.";
		} else {
			if ($curr_page != "") {
				$sql .= " LIMIT " . (($curr_page - 1) * $results_per_page) . ", $results_per_page";
			}
			$result_q = mysqli_query($con, $sql);
			while ($fetch = mysqli_fetch_array($result_q)) {
				$item_id = $fetch['item_id'];
				$title = $fetch['title'];
				$description = $fetch['details'];
				// sql search for newest price and number of bids
				$bid_sql = "SELECT bidPrice FROM bids WHERE item_id = $item_id ORDER BY bidPrice DESC";
				$bid_result = mysqli_query($con, $bid_sql);
				$num_bids = mysqli_num_rows($bid_result);
				if ($num_bids > 0) {
					$current_price = mysqli_fetch_array($bid_result)[0];
				} else {
					$current_price = $fetch['startPrice'];
				}
				$status = $fetch['status'];
				$end_date = $fetch['endDate'];
				print_listing_li($item_id, $title, $description, $current_price, $num_bids, $end_date, $status);
			}
		}
		?>

	</ul>

	<!-- Pagination for results listings -->
	<nav aria-label="Search results pages" class="mt-5">
		<ul class="pagination justify-content-center">

			<?php
			if ($result_q->num_rows > 0) {
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
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
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
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
				}

				if ($curr_page != $max_page) {
					echo ('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
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



<?php include_once("footer.php") ?>