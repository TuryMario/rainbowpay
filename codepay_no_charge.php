<?php
require 'place.php'; //connection link

//header('Allow-Control-Allow-Origin:*');//allowing
header('Access-Control-Allow-Origin: *');
//external resource access
require_once('vendor/autoload.php');
use \Firebase\JWT\JWT;
//for JWT auth

//Algorithm FOr code payment

//recieve Code
//$token = $_POST['token'];
$amount = $_POST['amount'];
$card = $_POST['card'];
$email = $_POST['email'];
$tokeny = $_POST['token'];
//$card_buyer = $_POST['card_buyer'];

//recieve key or token
   // $keyz = $_POST['key'];
//goto system and check the card
/***name check ***/
$buyer_check = mysql_query("SELECT * FROM buyer_tb WHERE  email = '".$email."'");
$buyer_row = mysql_fetch_assoc($buyer_check);
// user exists

/**card check **/
$card_check = mysql_query("SELECT * FROM card_tb WHERE card_no = '".$buyer_row['card_no']."'");
$card_row = mysql_fetch_assoc($card_check);


//check the amount balance is enough
$check_money = $buyer_row['balance'] + 500  ;
($check_money > $amount);

/***seller check(in this case meaning buyer _student****/
$seller_check = mysql_query("SELECT * FROM buyer_tb WHERE card_no = '".$card."'");
$seller_row = mysql_fetch_assoc($seller_check); 
//user exists

 
  
if( $card_row != 0  && $seller_row != 0 && $check_money > $amount  ){ // means all exist

    //compute charge
    $charge_rate = 0.00;
    $charge = $amount * $charge_rate;
    
    //seller cash
    $seller_cash = $amount - $charge;
    
    //create token for purchase
    $num = array('0','1','2','q','r','s','t','u','v','w','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','y','z','0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','y','z');

for($i=0;$i<52;$i++){ // Token
   // echo $num[rand(0,$i)] ;
    $token .= $num[rand(rand(0,$i),$i)] ;
//concatinate  the token
}
    $token .='purcahse_app'; 
    //insert new purchase
     mysql_query("INSERT INTO  purchase_tb  ( item ,  amount ,  seller ,  buyer ,  quantity ,  card_no ,   token ) VALUES ( 'un_charged_depo', '".$seller_cash."', '0000', 'un_charged_depo', '00', '".$buyer_row['card_no']."', '".$token."')");
    
    
    //insert new pending account
     mysql_query("INSERT INTO pending_tb  (  seller ,  amount , card_no_seller ,  product ,  status,purchase_token ) VALUES (  '0000', '".$seller_cash."', '".$card."', 'un_charged_depo', 'new','".$token."')");
    
    //update buyers account    
    $old_buyer_balance = $buyer_row['balance'];//get old balance
    $new_buyer_balance = $old_buyer_balance - $amount ;
     mysql_query("UPDATE  buyer_tb SET balance = '".$new_buyer_balance."' WHERE card_no = '".$buyer_row['card_no']."' ");
    
     //insert new transaction
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'un_charged_depo', '".$amount."', '0000', '".$buyer_row['card_no']."', '".$token."','".$charge."','0000','".$new_buyer_balance."')");
    echo json_encode('success'.$buyer_row['card_no']);
    
    //update Token watching system
    mysql_query('INSERT INTO token_transact (token, trans_type, amount) VALUES ( "'.$keyz.'", "un_charged_depo", "'.$amount.'")');
    
      //update Token watching system
    mysql_query('INSERT INTO device (token,card_seller,email_buyer) VALUES ( "'.$tokeny.'","'.$card.'","'.$buyer_row['email'].'",)');
    
//update student buyer balance
    //first get old balance
    $student_old_balance = $seller_row['balance'];
    //compute new balance
    $student_new_balance =  $student_old_balance + $seller_cash ;
    //run update
mysql_query("UPDATE  buyer_tb SET balance = '".$student_new_balance."' WHERE card_no = '".$card."'");
   
    $t = "Deposit Made";
    $m = "Your Deposit has been made to".$card;
    /* 
    notify buyer
    
*/
    
$ch = curl_init("https://fcm.googleapis.com/fcm/send");
$header=array('Content-Type: application/json',
"Authorization: key=AIzaSyDiHeotIxZFBwNA9-tBCFwNbdVjG6BxkvE");
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

curl_setopt($ch, CURLOPT_POST, 1);
$data = array("title" => "Rainbow App Test","text" => $m);
$data2 = array("notification" => $data,"to" => $tokeny);
$payload = json_encode( $data2 );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );


curl_exec($ch);
curl_close($ch);

    
    //notify seller
    
      //send notification to buyer
      // $ta = "Rinabow";
    $ma = "Your have recieved ".$amount ."by".$buyer_row['card_no'];
    
  $seller_token = mysql_query("SELECT * FROM device_seller WHERE email = '".$_POST['email']."')");
$device_token = mysql_fetch_assoc($seller_token); 
   $tokenya =  $device_token['token'];
    
    $cha = curl_init("https://fcm.googleapis.com/fcm/send");
$header=array('Content-Type: application/json',
"Authorization: key=AIzaSyDiHeotIxZFBwNA9-tBCFwNbdVjG6BxkvE");
curl_setopt($cha, CURLOPT_HTTPHEADER, $header);
curl_setopt( $cha,CURLOPT_SSL_VERIFYPEER, false );

curl_setopt($cha, CURLOPT_POST, 1);
    
$dataa = array("title" => "Rainbow Payment","text" => $m);
$data2a = array("notification" => $dataa,"to" => $tokenya);
$payloada = json_encode( $data2a );
curl_setopt( $cha, CURLOPT_POSTFIELDS, $payloada );

curl_exec($cha);
curl_close($cha);
  
    
}else{
     
    //update Token watching system
    mysql_query('INSERT INTO token_transact (token, trans_type, amount) VALUES ( "000", "un_charged_depo_failed", "'.$_POST['amount'].'")');
    
    //do otherwise
    //create token for failed_purchase
    $num = array('0','1','2','q','r','s','t','u','v','w','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','y','z','0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','y','z');

for($i=0;$i<52;$i++){
   // echo $num[rand(0,$i)] ;
    $token .= $num[rand(rand(0,$i),$i)] ;
//concatinate  the token
}
    
    $token .='fail_app_purcahse'; 
     //insert failed transaction
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'un_charged_depo_failed', '".$_POST['amount']."', '0000', '".$buyer_row['card_no']."', '".$token."','0000','000','".$buyer_row['balance']."')");

 echo json_encode('failed'.$buyer_row['card_no']);
        
}

 

    
?>
