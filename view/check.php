<?php
require_once 'conn.php';
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
    $name  = geoip_country_name_by_addr($gi,$ip);
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