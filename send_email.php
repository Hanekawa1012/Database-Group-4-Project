<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class sendmail
{
    static function sendemail($toEmail, $toName, $emailTitle, $emailContent, $emailOutline)
    {
        require 'PHPMailer-6.9.3//src//Exception.php';
        require 'PHPMailer-6.9.3//src//PHPMailer.php';
        require 'PHPMailer-6.9.3//src//SMTP.php';
        $mail = new PHPMailer(true);
        try {
            //服务器配置
            $mailconfig = json_decode('{"Host":"smtp.163.com","Username":"cdzhj1012@163.com","Password":"WZbdaNUUc9NKqDTx","SMTPSecure":"ssl","Port":465}');
            $mail->CharSet = "UTF-8";                                // 设定邮件编码
            $mail->SMTPDebug = 0;                                   // 调试模式输出
            $mail->isSMTP();                                        // 使用SMTP
            $mail->Host = $mailconfig->Host;                        // SMTP服务器
            $mail->SMTPAuth = true;                                 // 允许 SMTP 认证
            $mail->Username = $mailconfig->Username;                // SMTP 用户名  即邮箱的用户名
            $mail->Password = $mailconfig->Password;                // SMTP 密码  部分邮箱是授权码(例如163邮箱)
            $mail->SMTPSecure = $mailconfig->SMTPSecure;            // 允许 TLS 或者ssl协议
            $mail->Port = $mailconfig->Port;                        // 服务器端口 25 或者465 具体要看邮箱服务器支持
            $mail->setFrom($mailconfig->Username, 'DB-group4-Tim'); // 发件人
            $mail->addAddress($toEmail, $toName);                   // 收件人
            //$mail->addAddress('ellen@example.com');// 可添加多个收件人
            $mail->addReplyTo($mailconfig->Username, 'DB-group4-Tim');        //回复的时候回复给哪个邮箱 建议和发件人一致
            //$mail->addCC('cc@example.com');//抄送
            //$mail->addBCC('bcc@example.com');//密送

            //发送附件
            // $mail->addAttachment('../xy.zip');// 添加附件
            // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');// 发送附件并且重命名

            //Content
            $mail->isHTML(true);                            // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            $mail->Subject = $emailTitle;
            $mail->Body = $emailContent;
            $mail->AltBody = $emailOutline;

            $mail->send();
            return "e000";
        } catch (Exception $e) {
            return "e001";
        }
    }
}

?>