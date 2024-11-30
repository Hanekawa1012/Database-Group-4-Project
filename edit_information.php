<?php include_once("header.php") ?>
<?php require("config/conf.php") ?>
<?php require("my_db_connect.php") ?>

<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    echo "You have not logged in. Please log in.";
    header("refresh:$t_refresh;url=browse.php");
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

<div class="container">
    <h2 class="my-3">Edit Your Profile</h2>

    <form method="POST" action="process_edit.php">
        <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label text-right">Username</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="username" id="username" placeholder=<?php echo htmlspecialchars($_SESSION['username']); ?>>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="email" id="email" placeholder=<?php echo htmlspecialchars($_SESSION['email']); ?>>
            </div>
        </div>
        <div class="form-group row">
            <label for="tel" class="col-sm-2 col-form-label text-right">Telephone</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="tel" placeholder=<?php echo !empty($tel) ? htmlspecialchars($tel) : ''; ?>>
            </div>
        </div>
        <div class="form-group row">
            <label for="address" class="col-sm-2 col-form-label text-right">Address</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="address" placeholder=<?php echo !empty($address) ? htmlspecialchars($address) : ''; ?>>
            </div>
        </div>
        <div class="form-group row">
            <button type="submit" class="btn btn-primary form-control">Save</button>
        </div>
    </form>

    <div class="text-center"> Change password? <a href="change_password.php" class="text-primary">Please click
            here.</a></div>
</div>

<?php include_once("footer.php") ?>