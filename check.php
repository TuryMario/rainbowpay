<?php
require 'place.php';


$status = $_GET["status"];
$id = $_GET["id"];
$invoice = $_GET["invoice"];

//create token for purchase
    $num = array('b','c','d','0','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','y','z','1','2','3','4','5','6','7','8','9','a','e','f','g');
for($i=0;$i<26;$i++){
   // echo $num[rand(0,$i)] ;
    $token .= $num[rand(rand(0,$i),$i)] ;
//concatinate  the token
}


if($status == "approved"){
    $token .='approved';
    //update pending status
   mysql_query("UPDATE  pending_tb SET status = 'Approved' WHERE id = '".$id."' ");
   
    $sql_pending = mysql_query("SELECT * FROM pending_tb WHERE id = '".$id."' ");
    
    $row_pending = mysql_fetch_array($sql_pending);
    
    //insert as payment
    mysql_query("INSERT INTO payments_tb( seller, amount, time_stamp, card_no, manager, pending_id, invoice_no, charge, purchase_id, owner ) 
VALUES ( '".$row_pending['seller']."', '".$row_pending['amount']."', CURRENT_TIMESTAMP, '".$row_pending['card_no_seller']."', 'admin', '".$row_pending['id']."', '".$invoice."', '00', '".$row_pending['purchase_id']."','".$token."') ");

    ////record transactions for bank with new balance
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'approval', '".$row_pending['amount']."', 'admin', '".$row_pending['card_no_seller']."', '".$token."','00','".$invoice."','0000')");
    //pay seller
    
         $sql_balance = mysql_query("SELECT balance FROM seller_tb WHERE card_no = '".$row_pending['card_no_seller']."'");
         $row_balance = mysql_fetch_array($sql_balance);
        
    
         $new_balance = $row_balance['balance'] + $row_pending['amount'];
        
    mysql_query("UPDATE  seller_tb SET balance = '".$new_balance."' WHERE card_no = '".$row_pending['card_no_seller']."'");  
    
    
    echo "Success";
    
}else if($status == "decline"){
   
    $token .='declined';
     mysql_query("UPDATE  pending_tb SET status = 'Declined' WHERE id = '".$id."' ");
    
     //insert as payment
        mysql_query("INSERT INTO payments_tb ( seller, amount, time_stamp, card_no, manager, pending_id, invoice_no, charge, purchase_id,owner) VALUES ( '".$row_pending['seller']."', '0000', CURRENT_TIMESTAMP, '".$row_pending['card_no_seller']."', 'admin', '".$row_pending['id']."', '".$invoice."', '00', '".$row_pending['purchase_id']."','".$token."')");
    
  echo "failed";
}else {
    echo "failed no data ";
}

//header("Location:./approve.php");
?>