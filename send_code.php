<?php require_once("send_email.php")?>

<?php
function send_verification_code($email, $username) {
    $verification_code = rand(100000, 999999);
    $_SESSION['verification_code'] = $verification_code;
    $_SESSION['code_generated_time'] = time();

    $emailTitle = "Verification Code";
    $emailContent =  "Dear, " . $username . "Hello<br><br>You are trying to change password. Your verification code is <strong style='font-size: 20px;'>" . $verification_code . "</strong> . Please verify in 10 minutes.<br><br>Best wishes,<br>DB-Group4";
    $emailOutline = "Your verification code is " . $verification_code;

    $result = sendmail::sendemail($email, $username, $emailTitle, $emailContent, $emailOutline);

    return $result;
}
?>