<?php
// FIXME: At the moment, I've allowed these values to be set manually.
// But eventually, with a database, these should be set automatically
// ONLY after the user's login credentials have been verified via a 
// database query.
session_start();
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap and FontAwesome CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom CSS file -->
    <link rel="stylesheet" href="css/custom.css">

    <title>DB-Group4 Project</title>
</head>


<body>

    <!-- Navbars -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light mx-2">
        <a class="navbar-brand" href="browse.php">UCL Database Project - Group 4 <!--CHANGEME!--></a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">

                <?php
                // Displays either login or logout on the right, depending on user's
                // current status (session).
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                    echo 'Welcome, <a href="user_info.php" class="nav-link d-inline">' . $_SESSION['username'] . '</a>';
                    echo " Logged in as: " . $_SESSION['account_type'];
                    echo '<a class="nav-link" href="logout.php">Logout</a>';
                } else {
                    echo '<a class="nav-link" href="register.php">Sign up</a>';
                    echo '<button type="button" class="btn nav-link" data-toggle="modal" data-target="#loginModal">Login</button>';
                }
                ?>

            </li>
        </ul>
    </nav>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <ul class="navbar-nav align-middle">
            <li class="nav-item mx-1">
                <a class="nav-link" href="browse.php">Browse</a>
            </li>
            <?php
            if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'buyer') {
                echo ('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mybids.php">My Bids</a>
    </li>
  <li class="nav-item mx-1">
      <a class="nav-link" href="mywatchlist.php">My Watchlist</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link" href="recommendations.php">Recommended</a>
    </li>');
            }
            if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'seller') {
                echo ('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mylistings.php">My Listings</a>
    </li>
	<li class="nav-item ml-3">
      <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
    </li>');
            }
            ?>
        </ul>
    </nav>

    <!-- Login modal -->
    <div class="modal fade" id="loginModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Login</h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form method="POST" action="login_result.php">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" id="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Password">
                        </div>
                        <div class="form-check form-check-inline">
                          <!---label for="accountType">Login as:</label--->
                          <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer"
                        checked>
                          <label class="form-check-label" for="accountBuyer">Buyer</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
                            <label class="form-check-label" for="accountSeller">Seller</label>
                        </div>
                        <button type="submit" class="btn btn-primary form-control">Sign in</button>
                    </form>
                    <div class="text-center">or <a href="register.php">create an account</a></div>
                    <div class="text-center"> Forget password? <a href="forget_password.php" class="text-primary">Please
                            click here.</a></div>
                </div>

            </div>
        </div>
    </div> <!-- End modal -->