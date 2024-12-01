<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer-6.9.3\src\Exception.php';
require_once 'PHPMailer-6.9.3\src\PHPMailer.php';
require_once 'PHPMailer-6.9.3\src\SMTP.php';

require_once "my_db_connect.php";

// get current time 
$currentDate = date('Y-m-d H:i:s');

// look up for outdated auctions, seller who created it and all buyers watching it

$sql = "SELECT email FROM user WHERE user_id IN
        (SELECT seller_id FROM auctions WHERE endDate <= '$currentDate' AND status = 'active')
        UNION ALL
        SELECT email FROM user WHERE user_id IN
        (SELECT buyer_id FROM watchlist WHERE item_id IN
        (SELECT item_id  FROM auctions WHERE endDate <= '$currentDate' AND status = 'active'))";
$result = $con->query($sql);

// 检查是否有结果
if ($result->num_rows > 0) {
    // 使用PHPMailer发送邮件
    $mail = new PHPMailer(true);
    try {
        // 服务器设置
        $mailconfig = json_decode('{"Host":"smtp.163.com","Username":"cdzhj1012@163.com","Password":"WZbdaNUUc9NKqDTx","SMTPSecure":"ssl","Port":465}');
        $mail->CharSet = "UTF-8";                                // 设定邮件编码
        $mail->SMTPDebug = 0;                                   // 调试模式输出
        $mail->isSMTP();                                        // 使用SMTP
        $mail->Host = $mailconfig->Host;                        // SMTP服务器
        $mail->SMTPAuth = true;                                 // 允许 SMTP 认证
        $mail->Username = $mailconfig->Username;                // SMTP 用户名  即邮箱的用户名
        $mail->Password = $mailconfig->Password;                // SMTP 密码  部分邮箱是授权码(例如163邮箱)
        $mail->SMTPSecure = $mailconfig->SMTPSecure;            // 允许 TLS 或者ssl协议
        $mail->Port = $mailconfig->Port;  

        // 发件人信息
        $mail->setFrom($mailconfig->Username, 'DB-group4');

        // 发送邮件给每个卖家
        while ($row = $result->fetch_assoc()) {
            $userEmail = $row['email'];
            $username = $row['email'];
            $mail->addAddress($userEmail);

            // 内容
            $mail->isHTML(true);                                  // 设置邮件格式为HTML
            $mail->Subject = 'One auction/bid of yours has ended.';
            $mail->Body    = '<h3>One auction/bid of yours has ended.</h3>';
            $mail->AltBody = 'One auction/bid of yours has ended.';

            $mail->send();
            $mail->clearAddresses();
        }

        // 更新交易记录，标记为已通知
        $updateSql = "UPDATE auctions SET status = 'closed' WHERE endDate <= '$currentDate' AND status = 'active'";
        $con->query($updateSql);

        echo 'Mail sent success.';
    } catch (Exception $e) {
        echo "Mail sent failed: {$mail->ErrorInfo}";
    }
} else {
    echo "No outdated auctions yet.";
}
$con->close();
?>
