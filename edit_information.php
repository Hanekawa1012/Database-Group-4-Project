<?php include_once("header.php") ?>
<?php require("my_db_connect.php") ?>

<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    die("You haven't logged in. Please log in.");
}
?>

<?php
$email = $_SESSION['email'];
$sql = "SELECT * FROM profile WHERE email = '$email';";
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
                <input type="text" class="form-control" name="username" id="username" value=<?php echo htmlspecialchars($_SESSION['username']); ?>>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="email" id="email" value=<?php echo htmlspecialchars($_SESSION['email']); ?>>
            </div>
        </div>
        <div class="form-group row">
            <label for="tel" class="col-sm-2 col-form-label text-right">Telephone</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="tel" value=<?php echo !empty($tel) ? htmlspecialchars($tel) : ''; ?>>
            </div>
        </div>
        <div class="form-group row">
            <label for="address" class="col-sm-2 col-form-label text-right">Address</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="address" value=<?php echo !empty($address) ? htmlspecialchars($address) : ''; ?>>
            </div>
        </div>
        <div class="form-group row">
            <button type="submit" class="btn btn-primary form-control">Save</button>
        </div>
    </form>

    <div class="text-center">Want to change password? <a href="change_password.php" class="text-primary">Please click
            here.</a></div>
</div>

<?php include_once("footer.php") ?>