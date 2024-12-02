<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class sendmail
{
    static function sendemail($toEmail, $toName, $emailTitle, $emailContent, $emailOutline, $sendTime = null)
    {
        // TODO: remove after bug fixed
        return 'e000';
        // TODO: remove after bug fixed
        require_once 'PHPMailer-6.9.3\src\Exception.php';
        require_once 'PHPMailer-6.9.3\src\PHPMailer.php';
        require_once 'PHPMailer-6.9.3\src\SMTP.php';
        $mail = new PHPMailer(true);
        try {
            $mailconfig = json_decode('{"Host":"smtp.163.com","Username":"cdzhj1012@163.com","Password":"WZbdaNUUc9NKqDTx","SMTPSecure":"ssl","Port":465}');
            $mail->CharSet = "UTF-8";                                
            $mail->SMTPDebug = 0;                                  
            $mail->isSMTP();                                       
            $mail->Host = $mailconfig->Host;                        
            $mail->SMTPAuth = true;                              
            $mail->Username = $mailconfig->Username;                
            $mail->Password = $mailconfig->Password;              
            $mail->SMTPSecure = $mailconfig->SMTPSecure;            
            $mail->Port = $mailconfig->Port;                        
            $mail->setFrom($mailconfig->Username, 'DB-group4'); 
            if (is_array($toEmail) && is_array($toName)) {
                $length = min(count($toEmail), count($toName));
                for ($i = 0; $i < $length; $i++) {
                    echo $toEmail[$i];
                    $mail->addAddress($toEmail[$i], $toName[$i]);
                }
            } else if (is_array($toEmail) || is_array($toName)) {
                echo "Unknown message sending error. Please check your variables are of same length and type.";
                return 1;
            } else {
                $mail->addAddress($toEmail, $toName);
            }
            //$mail->addAddress('ellen@example.com');
            $mail->addReplyTo($mailconfig->Username, 'DB-group4');        
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //发送附件
            // $mail->addAttachment('../xy.zip');
            // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');
            
            // Send email at specific time
            if (!is_null($sendTime)) {
                $mail->sendDate = $sendDate;
            }

            //Content
            $mail->isHTML(true);                            
            $mail->Subject = $emailTitle;
            $mail->Body = $emailContent;
            $mail->AltBody = $emailOutline;

            $mail->send();
            return "e000";
        } catch (Exception $e) {
            echo $e->getMessage();
            return "e001";
        }
    }
}
