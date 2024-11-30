<?php require_once("send_email.php") ?>

<?php
function send_verification_code($email, $username)
{
    $verification_code = rand(100000, 999999);
    $_SESSION['verification_code'] = $verification_code;
    $_SESSION['code_generated_time'] = time();

    $emailTitle = "Verification Code";
    $emailContent = "User: $username,<br><br>" .
        "We received a request to reset the password for your account. Your verification code is: <strong style='font-size: 20px;'>$verification_code</strong>. Please use this code to complete the process within 10 minutes.<br><br>" .
        "If you did not request a password reset, please disregard this email. For your account's security, we recommend reviewing your account settings and updating your password if you suspect any unauthorised activity.<br><br>" .
        "Best regards,<br>DB-Group4";
    $emailOutline = "Your verification code is " . $verification_code;

    $result = sendmail::sendemail($email, $username, $emailTitle, $emailContent, $emailOutline);

    return $result;
}
?>