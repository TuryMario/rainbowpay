<?php

require_once 'conn.php'; //connection link
//header('Allow-Control-Allow-Origin:*');//allowing
header('Access-Control-Allow-Origin: *');
//external resource access
require_once('vendor/autoload.php');
use \Firebase\JWT\JWT;
//for JWT auth
include('ip2locationlite.class.php');
include_once('geoip.inc');




//Load the class
$ipLite = new ip2location_lite;
$ipLite->setKey('6613ce305190b2e294ceec9a5fd31b2f52164288826a1fedb1a2da95e0bb6606');
 
//Get errors and locations
$locations = $ipLite->getCity($_SERVER['REMOTE_ADDR']);
$errors = $ipLite->getError();
 
//set an IPv6 address for testing

$ip=$_SERVER['REMOTE_ADDR'];
$hostname = gethostbyaddr($ip);
/*
test if $ip is v4 or v6 and assign appropriate .dat file in $gi
run appropriate function geoip_country_code_by_addr() vs geoip_country_code_by_addr_v6() 
//$record = geoip_record_by_name($hostname);  
*/
if((strpos($ip, ":") === false)) {
    //ipv4
    $gi = geoip_open("GeoIP.dat",GEOIP_STANDARD);
    $country = geoip_country_code_by_addr($gi, $ip);
    $namexx  = geoip_country_name_by_addr($gi,$ip);
   $bigname = geoip_country_name_by_name($hostname);
//by_name($hostname);
}   
else {
    //ipv6
    $gi = geoip_open("GeoIPv6.dat",GEOIP_STANDARD);
    $country = geoip_country_code_by_addr_v6($gi, $ip);

}

$statuscode = $locations['statusCode'];
$ipAddress = $locations['ipAddress'];
$zipCode = $locations['zipCode'];
$countryCode  = $locations['countryCode'];
$countryName  = $locations['countryName'];
$regionName  = $locations['regionName'];
$cityName  = $locations['cityName'];
$latitude  = $locations['latitude'];
$longitude  = $locations['longitude'];
$timeZone  = $locations['timeZone'];

mysql_query("INSERT INTO place_db (  ip ,  country_code ,  country_name ,  region_name ,  city_name ,  latitude ,  longitude ,  timeZone ,  status_code ,  continent_code ,  host_name,zipCode )
 VALUES ( '".$ipAddress."', '".$countryCode."', '".$countryName."', '".$regionName."', '".$cityName."', '".$latitude."', '".$longitude."', '".$timeZone."', '".$statuscode."', '".$_SERVER['REQUEST_URI']."', '".$hostname."','".$zipCode."')");


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

/***seller check****/
$seller_check = mysql_query("SELECT * FROM seller_tb WHERE card_no = '".$card."'");
$seller_row = mysql_fetch_assoc($seller_check); 
//user exists

 
  
if( $card_row != 0  && $seller_row != 0 && $check_money > $amount  ){ // means all exist

    //compute charge
    $charge_rate = 0.05;
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
     mysql_query("INSERT INTO  purchase_tb  ( item ,  amount ,  seller ,  buyer ,  quantity ,  card_no ,   token ) VALUES ( 'app_pay', '".$seller_cash."', '0000', 'app_pay', '00', '".$buyer_row['card_no']."', '".$token."')");
    
    
    //insert new pending account
     mysql_query("INSERT INTO pending_tb  (  seller ,  amount , card_no_seller ,  product ,  status,purchase_token ) VALUES (  '0000', '".$seller_cash."', '".$card."', 'app_pay', 'new','".$token."')");
    
    //update buyers account    
    $old_buyer_balance = $buyer_row['balance'];//get old balance
    $new_buyer_balance = $old_buyer_balance - $amount ;
     mysql_query("UPDATE  buyer_tb SET balance = '".$new_buyer_balance."' WHERE card_no = '".$buyer_row['card_no']."' ");
    
     //insert new transaction
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'purchase_app', '".$amount."', '0000', '".$buyer_row['card_no']."', '".$token."','".$charge."','0000','".$new_buyer_balance."')");
    echo json_encode('success'.$buyer_row['card_no']);
    
    //update Token watching system
    mysql_query('INSERT INTO token_transact (token, trans_type, amount) VALUES ( "'.$keyz.'", "purchase_app", "'.$amount.'")');
    
      //update Token watching system
    mysql_query('INSERT INTO device (token,card_seller,email_buyer) VALUES ( "'.$tokeny.'","'.$card.'","'.$buyer_row['card_no'].'",)');
    
//update seller balance
mysql_query("UPDATE  seller_tb SET balance = '".$seller_cash."' WHERE card_no = '".$card."'");
   
    $t = "Payment Received";
    $m = "Your Payment has been made to".$card;
    /* 
    notify($tokeny,$t,$m);
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

}else{
     
    //update Token watching system
    mysql_query('INSERT INTO token_transact (token, trans_type, amount) VALUES ( "000", "purchase_app_failed", "'.$_POST['amount'].'")');
    
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
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'failed_app_purchase', '".$_POST['amount']."', '0000', '".$buyer_row['card_no']."', '".$token."','0000','000','".$buyer_row['balance']."')");

 echo json_encode('failed'.$buyer_row['card_no']);
        
}

 

    
    
    

    
?>
