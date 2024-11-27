<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'PHPMailer-6.9.3\src\Exception.php';
require_once 'PHPMailer-6.9.3\src\PHPMailer.php';
require_once 'PHPMailer-6.9.3\src\SMTP.php';

// 数据库连接设置
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db-group4";

// 创建数据库连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("Connect failed: " . $conn->connect_error);
}

// 获取当前时间
$currentDate = date('Y-m-d H:i:s');

// 查询过期交易
$sql = "SELECT email, username FROM user WHERE user_id IN
        (SELECT seller_id FROM auctions WHERE endDate <= '$currentDate' AND status = 0)
        UNION
        SELECT email, username FROM user WHERE user_id IN
        (SELECT buyer_id FROM watchlist WHERE item_id IN
        (SELECT item_id  FROM auctions WHERE endDate <= '$currentDate' AND status = 0))";
$result = $conn->query($sql);

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
            $username = $row['username'];
            $mail->addAddress($userEmail);

            // 内容
            $mail->isHTML(true);                                  // 设置邮件格式为HTML
            $mail->Subject = '交易结束通知';
            $mail->Body    = '您的交易已结束。感谢您的使用！';
            $mail->AltBody = '您的交易已结束。感谢您的使用！';

            $mail->send();
            $mail->clearAddresses();
        }

        // 更新交易记录，标记为已通知
        $updateSql = "UPDATE auctions SET status = 1 WHERE endDate <= '$currentDate' AND status = 0";
        $conn->query($updateSql);

        echo 'Mail sent success.';
    } catch (Exception $e) {
        echo "Mail sent failed: {$mail->ErrorInfo}";
    }
} else {
    echo "No outdated auctions yet.";
}
$conn->close();
?>
