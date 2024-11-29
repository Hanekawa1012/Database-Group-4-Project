<?php include_once("header.php") ?>
<?php require("my_db_connect.php") ?>

<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
    echo 'You are not logged in! Please log in!';
    exit();
}
?>

<?php
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM profile WHERE user_id = '$user_id';";
$result = mysqli_query($con, $sql);
$fetch = mysqli_fetch_array($result);
$tel = $fetch['tel'];
$address = $fetch['address']
?>

<div class="container mt-5">
    <h1 class="mb-4">Your Profile</h1>
    <div class="card pb-3 mb-5">
        <div class="card-body">
            <h5 class="card-title">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h5>
            <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p class="card-text">
                <strong>Phone:</strong>
                <?php
                if (!empty($tel)) {
                    echo htmlspecialchars($tel);
                } else {
                    echo "Haven't set yet. Please click the below button to edit.";
                }
                ?>
            </p>
            <p class="card-text">
                <strong>Address:</strong>
                <?php
                if (!empty($address)) {
                    echo htmlspecialchars($address);
                } else {
                    echo "Haven't set yet. Please click the below button to edit.";
                }
                ?>
            </p>
            <a href="edit_information.php" class="btn btn-primary">Edit Your Profile</a>
        </div>
    </div>
</div>

<?php include_once("footer.php") ?>