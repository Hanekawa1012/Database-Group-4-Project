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
$sql = "SELECT 
            a.item_id,
            a.title,
            a.details,
            a.category,
            a.startPrice,
            a.reservePrice,
            a.startDate,
            a.endDate,
            a.status,
            u_buyer.user_id AS buyer_id,
            u_buyer.email AS buyer_email,
            u_seller.user_id AS seller_id,
            u_seller.email AS seller_email,
            b.bidPrice AS highest_bid
        FROM 
            auctions a
        JOIN 
            bids b ON a.item_id = b.item_id
        JOIN 
            (SELECT item_id, MAX(bidPrice) AS max_bid FROM bids GROUP BY item_id) max_bids 
            ON b.item_id = max_bids.item_id AND b.bidPrice = max_bids.max_bid
        JOIN 
            buyer bu ON b.buyer_id = bu.user_id
        JOIN 
            user u_buyer ON bu.user_id = u_buyer.user_id
        JOIN 
            seller s ON a.seller_id = s.user_id
        JOIN 
            user u_seller ON s.user_id = u_seller.user_id
        WHERE 
            b.bidPrice >= a.reservePrice AND a.status = 'active'
        ORDER BY 
            a.item_id";
$result = $con->query($sql);

$sql_fail = "SELECT 
            a.item_id,
            a.title,
            a.details,
            a.category,
            a.startPrice,
            a.reservePrice,
            a.startDate,
            a.endDate,
            a.status,
            u_buyer.user_id AS buyer_id,
            u_buyer.email AS buyer_email,
            u_seller.user_id AS seller_id,
            u_seller.email AS seller_email,
            b.bidPrice AS highest_bid
        FROM 
            auctions a
        JOIN 
            bids b ON a.item_id = b.item_id
        JOIN 
            (SELECT item_id, MAX(bidPrice) AS max_bid FROM bids GROUP BY item_id) max_bids 
            ON b.item_id = max_bids.item_id AND b.bidPrice = max_bids.max_bid
        JOIN 
            buyer bu ON b.buyer_id = bu.user_id
        JOIN 
            user u_buyer ON bu.user_id = u_buyer.user_id
        JOIN 
            seller s ON a.seller_id = s.user_id
        JOIN 
            user u_seller ON s.user_id = u_seller.user_id
        WHERE 
            b.bidPrice < a.reservePrice AND a.status = 'active'
        ORDER BY 
            a.item_id";
$result_fail = $con->query($sql_fail);

// Check if there is outcome
if ($result->num_rows > 0) {
    // send email by PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Server setting
        $mailconfig = json_decode('{"Host": "smtp.163.com", "Username": "cdzhj1012@163.com", "Password": "WZbdaNUUc9NKqDTx", "SMTPSecure": "ssl", "Port": 465}');
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
            $userEmail = $row['buyer_email'];
            $username = $row['buyer_email'];
            $itemTitle = $row['title'];
            $itemDetails = $row['details'];
            $itemEndDate = $row['endDate'];
            $itemBidPrice = $row['highest_bid'];
            echo $userEmail;
            $mail->addAddress($userEmail);

            
            $mail->isHTML(true);                                  
            $mail->Subject = 'One bid of yours has ended successfully!.';
            $mail->Body    = "<h3>Order Receipt:</h3>
                                <p>Item name: $itemTitle</p>
                                <p>Item description:$itemDetails</p>
                                <p>Item ends:$itemEndDate</p>
                                <p>Item ending price:$itemBidPrice</p>
                                <p>Please check your bid history or search for auction.</p>";
            $mail->AltBody = 'One auction/bid of yours has ended.';

            $mail->send();
            $mail->clearAddresses();

            $userEmail = $row['seller_email'];
            $username = $row['seller_email'];
            echo $userEmail;
            $mail->addAddress($userEmail);

            
            $mail->isHTML(true);                                  
            $mail->Subject = 'One auction of yours has ended successfully!.';
            $mail->Body    =    "<p>Item name:$itemTitle</p>
                                <p>Item description:$itemDetails</p>
                                <p>Item ends:$itemEndDate</p>
                                <p>Item ending price:$itemBidPrice</p>
                                <p>Please check your bid history or search for auction.</p>";
            $mail->AltBody = 'One auction/bid of yours has ended.';

            $mail->send();
            $mail->clearAddresses();
        }

        // send to every related buyers and sellers(fail)
        while ($row = $result_fail->fetch_assoc()) {
            $userEmail = $row['buyer_email'];
            $username = $row['buyer_email'];
            $itemTitle = $row['title'];
            $itemDetails = $row['details'];
            $itemEndDate = $row['endDate'];
            $itemBidPrice = $row['highest_bid'];
            echo $userEmail;
            $mail->addAddress($userEmail);

            
            $mail->isHTML(true);                                  
            $mail->Subject = 'One bid of yours has ended in fail.';
            $mail->Body    = "<h3>Order Receipt:</h3>
                                <p>Item name:$itemTitle</p>
                                <p>Item description:$itemDetails</p>
                                <p>Item ends:$itemEndDate</p>
                                <p>Item ending price:$itemBidPrice</p>
                                <p>Please check your bid history or search for auction.</p>";
            $mail->AltBody = 'One auction/bid of yours has ended.';

            $mail->send();
            $mail->clearAddresses();

            $userEmail = $row['seller_email'];
            $username = $row['seller_email'];
            echo $userEmail;
            $mail->addAddress($userEmail);

            
            $mail->isHTML(true);                                  
            $mail->Subject = 'One auction of yours has ended in fail.';
            $mail->Body    =    "<p>Item name:$itemTitle</p>
                                <p>Item description:$itemDetails</p>
                                <p>Item ends:$itemEndDate</p>
                                <p>Item ending price:$itemBidPrice</p>
                                <p>Please check your bid history or search for auction.</p>";
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
