<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-6.9.3//PHPMailer-6.9.3//src//Exception.php';
require 'PHPMailer-6.9.3//PHPMailer-6.9.3//src//PHPMailer.php';
require 'PHPMailer-6.9.3//PHPMailer-6.9.3//src//SMTP.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions

try {
    //服务器配置
    $mail->CharSet ="UTF-8";                     //设定邮件编码
    $mail->SMTPDebug = 0;                        // 调试模式输出
    $mail->isSMTP();                             // 使用SMTP
    $mail->Host = 'smtp.163.com';                // SMTP服务器
    $mail->SMTPAuth = true;                      // 允许 SMTP 认证
    $mail->Username = '邮箱用户名';                // SMTP 用户名  即邮箱的用户名
    $mail->Password = '密码或者授权码';             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
    $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
    $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

    $mail->setFrom('cdzhj1012@163.com', 'DB-group4');  //发件人
    $mail->addAddress('2393963926@qq.com', 'Tim');  // 收件人
    //$mail->addAddress('ellen@example.com');  // 可添加多个收件人
    $mail->addReplyTo('cdzhj1012@163.com', 'DB-group4'); //回复的时候回复给哪个邮箱 建议和发件人一致
    //$mail->addCC('cc@example.com');                    //抄送
    //$mail->addBCC('bcc@example.com');                    //密送

    //发送附件
    // $mail->addAttachment('../xy.zip');         // 添加附件
    // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名

    //Content
    $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
    $mail->Subject = "New watching auction added!";
    $mail->Body    = "<h1>Now the auction is added to your watchlist. we'll let you know soon if there's any change!</h1>" . date('Y-m-d H:i:s');
    $mail->AltBody = "Now the auction is added to your watchlist. we'll let you know soon if there's any change!";
    echo 'success';
    $mail->send();
    echo 'success';
} catch (Exception $e) {
    echo 'error';
}
?>