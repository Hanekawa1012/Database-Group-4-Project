<?php include_once("header.php") ?>
<?php require_once("send_code.php") ?>
<?php require_once("my_db_connect.php") ?>

<?php
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    die("You haven't logged in. Please log in.");
}

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email'];
$username = $_SESSION['username'];
$step = isset($_GET['step']) ? $_GET['step'] : 1;
$error_message = '';
$success_message = '';

if ($step == 1) {
    if (isset($_POST['send_code'])) {
        $result = send_verification_code($email, $email);
        if ($result == "e000") {
            $success_message = "Verification code has been sent to your email. Please go to your email to check.";
            $step = 2;
        } else {
            $error_message = "Varification code was sent failed. Please try again later.";
        }
    }
}

if ($step == 2) {
    if (isset($_POST['verify_code'])) {
        $entered_code = (int)$_POST['verification_code'];
        $code_generated_time = isset($_SESSION['code_generated_time']) ? $_SESSION['code_generated_time'] : 0;

        if ($entered_code == $_SESSION['verification_code'] && (time() - $code_generated_time) < 600) {
            $success_message = "Verified successfully! Please enter your new password.";
            $step = 3;
            unset($_SESSION['verification_code']);
            unset($_SESSION['code_generated_time']);
        } else {
            if ($entered_code != $_SESSION['verification_code']) {
                $error_message = "Verification code is wrong. Please check again.";
            } else {
                $error_message = "Verification code has expired. Please return to send a new code.";
            }
        }
    }
}

if ($step == 3) {
    if (isset($_POST['change_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            $sql = "UPDATE user SET password = SHA('$new_password') WHERE user_id = '$user_id'";
            if ($con->query($sql) === TRUE) {
                $success_message = "Password was changed successfully.";
                header("refresh:3;url=user_info.php");
            } else {
                $error_message = "Fail to change password. Please try again.";
            }
        } else {
            $error_message = "The two passwords you typed in are not same. Please check again.";
        }
    }
}
?>

<div class="container">
    <h2 class="my-3">Change Password</h2>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if ($step == 1): ?>
        <form method="POST" action="change_password.php?step=1">
            <p>Please click "Send Code" button. We will send an email with verification code to
                <?php echo htmlspecialchars($email); ?></p>
            <button type="submit" name="send_code" class="btn btn-primary">Send Code</button>
        </form>
    <?php endif; ?>

    <?php if ($step == 2): ?>
        <form method="POST" action="change_password.php?step=2">
            <div class="form-group">
                <label for="verification_code">Verification Code</label>
                <input type="text" class="form-control" name="verification_code" id="verification_code"
                    placeholder="Please enter the code" required>
                <small class="form-text text-muted">The code will expire in ten minutes. Please enter it as soon as
                    possible.</small>
            </div>
            <button type="submit" name="verify_code" class="btn btn-primary">Verify</button>
        </form>
    <?php endif; ?>

    <?php if ($step == 3): ?>
        <form method="POST" action="change_password.php?step=3">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" class="form-control" name="new_password" id="new_password"
                    placeholder="Please enter your new password." required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                    placeholder="Please enter your new password again" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
        </form>
    <?php endif; ?>
</div>

<?php include_once("footer.php") ?>