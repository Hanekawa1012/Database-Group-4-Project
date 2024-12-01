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

// Check if there is outcome
if ($result->num_rows > 0) {
    // send email by PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Server setting
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

        // send to every related buyers and sellers
        while ($row = $result->fetch_assoc()) {
            $userEmail = $row['email'];
            $username = $row['email'];
            $mail->addAddress($userEmail);

            
            $mail->isHTML(true);                                  
            $mail->Subject = 'One auction/bid of yours has ended.';
            $mail->Body    = '<h3>One auction/bid of yours has ended.</h3>
                                <p>Please check your bid history to check.</p>';
            $mail->AltBody = 'One auction/bid of yours has ended.';

            $mail->send();
            $mail->clearAddresses();
        }

       
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
