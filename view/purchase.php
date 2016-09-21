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



if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	/* special ajax here */

 
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

//varaibles passed
   $card =  $_POST["card"]; /***/
   $amount = $_POST["amount"];  /***/
   $seller = $_POST["seller"];  /***/
   $product =$_POST["product"];
   $quantity = $_POST["quantity"];
   $name = $_POST["name"];  /***/
   $pin = $_POST["pin"];  /***/
     //recieve key or token
    $keyz = $_POST['key'];
//check card and user and seller

/**card check **/
$card_check = mysql_query("SELECT * FROM card_tb WHERE card_no = '".$card."'");
$card_row = mysql_fetch_assoc($card_check);
// card exists

/***name check ***/
$buyer_check = mysql_query("SELECT * FROM buyer_tb WHERE username = '".$name."' && card_no = '".$card."' && pin = '".$pin."' ");
$buyer_row = mysql_fetch_assoc($buyer_check);
// user exists

//check the amount balance is enough
$check_money = $buyer_row['balance'] + 500  ;
($check_money > $amount);

/***seller check****/
$seller_check = mysql_query("SELECT * FROM seller_tb WHERE id = '".$seller."'");
$seller_row = mysql_fetch_assoc($seller_check);
//user exists

    //check the key of sender to validate the sender securely
    /************************************code base for jwt AUth*********************/
    
    
    try{
               $secretKey = base64_decode('Your-Secret-Key'); 
               $DecodedDataArray = JWT::decode($keyz, $secretKey, array('HS512'));
 
if( $card_row != 0  &&  $buyer_row != 0  && $seller_row != 0 && $check_money > $amount  ){ // means all exist

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
    $token .='purcahse'; 
    //insert new purchase
     mysql_query("INSERT INTO  purchase_tb  ( item ,  amount ,  seller ,  buyer ,  quantity ,  card_no ,   token ) VALUES ( '".$product."', '".$seller_cash."', '".$seller."', '".$name."', '".$quantity."', '".$card."', '".$token."')");
    
    //insert new pending account
     mysql_query("INSERT INTO pending_tb  (  seller ,  amount , card_no_seller ,  product ,  status,purchase_token ) VALUES (  '".$seller."', '".$seller_cash."', '".$seller_row['card_no']."', '".$product."', 'new','".$token."')");
    
    //update buyers account    
    $old_buyer_balance = $buyer_row['balance'];//get old balance
    $new_buyer_balance = $old_buyer_balance - $amount ;
     mysql_query("UPDATE  buyer_tb SET balance = '".$new_buyer_balance."' WHERE card_no = '".$card."' && username = '".$name."' ");
    
     //insert new transaction
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'purchase', '".$amount."', '0000', '".$card."', '".$token."','".$charge."','".$seller."','".$new_buyer_balance."')");
    echo json_encode($token.'success');
    
    //update Token watching system
    mysql_query('INSERT INTO token_transact (token, trans_type, amount) VALUES ( "'.$keyz.'", "purchase", "'.$amount.'")');
    
}else{
     
    //update Token watching system
    mysql_query('INSERT INTO token_transact (token, trans_type, amount) VALUES ( "'.$keyz.'", "purchase", "'.$amount.'")');
    
    //do otherwise
    //create token for failed_purchase
    $num = array('0','1','2','q','r','s','t','u','v','w','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','y','z','0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','y','z');

for($i=0;$i<52;$i++){
   // echo $num[rand(0,$i)] ;
    $token .= $num[rand(rand(0,$i),$i)] ;
//concatinate  the token
}
    
    $token .='fail-purcahse'; 
     //insert failed transaction
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'failed_purchase', '".$amount."', '0000', '".$card."', '".$token."','0000','".$seller."','".$buyer_row['balance']."')");
    
 echo json_encode($token.'failed');
}
    
               } catch (Exception $e) {
       echo "{'status' : 'fail' ,'msg':'Unauthorized'}";
        
    die();
               }
        
    
    /*************End of JWT code base*****************************/
    
    

}
?>