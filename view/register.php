<?php
require_once 'conn.php';
include_once('register2.php');
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


   $type = $_POST["type"];
   $fname =  $_POST["fname"];
   $lname = $_POST["lname"];
   $tel = $_POST["phone"];
   $email = $_POST["email"];
   $gender = $_POST["gender"];
   $dob = $_POST["dob" ];
   $location = $_POST["location"];
   $job = $_POST["job"];
   $nid = $_POST["nid"];
   $uname = $_POST["uname"];
  $product = $_POST["product"];
  $site = $_POST["site"];
  $pin = $_POST["pin"];
$agent = $_POST["agent_id"]; ///not correct yet
  $reg_no = $_POST["reg_no"];
  $business = $_POST["business"];
$TIN = $_POST["tin"];
$department = $_POST["department"];
$contract = $_POST["contract"];
$card = '0000';

//require_once 'card.php' ;

function send_mail($to,$msg){
// the message -> $msg
   
$to;
$tox =  ",amonsoftx@gmail.com";
$subject = "Rainbow Transaction";
$msg = wordwrap($msg,70);
$headers = "From: RainbowTeam";

mail($to.$tox,$subject,$msg,$headers);
    
}
//create token for purchase
$tokenz = "";
    $num = array('0','1','2','3','4','k','l','m','n','o','p','q','r','s','t','5','6','y','z','7','8','9','a','b','c','d','e','f','g','h','i','j','u','v','w');

for($i=0;$i<26;$i++){ // Token
   // echo $num[rand(0,$i)] ;
    $tokenz .= $num[rand(rand(0,$i),$i)] ;
//concatinate  the token
}


switch($type){
        
    case 'buyer':  ///case of Buyer registration
        $tokenz .='buyer'; 
         mysql_query("INSERT INTO buyer_tb ( first_name ,  last_name ,  mobile_no ,  email ,  gender ,  dob ,  location ,  occupation ,  national_id ,  username,card_no,pin ,owner ) VALUES ( '".$fname."',  '".$lname."' ,  '".$tel."',  '".$email."',  '".$gender."',  '".$dob."' ,  '".$location."' ,  '".$job."' ,  '".$nid."' ,  '".$uname."','".$card."','".$pin."','".$tokenz."')");
 
        mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,owner) VALUES ( 'buyer_reg', '000', '".$agent_id."', '".$card."', '".$tokenz."','0','".$location."')");
    
       // echo "Your card no. :- "; 
       // echo $card;
        
        //send Email
        
        $msg = 'Welcome Mr.'.$uname.' to Rainbow an Amonsoft company,   Welcome to Rainbow an Amonsoft company . The Easiest, Simplest and Most Effiecient Payment System, presenting the new age of business online . 
        
        Your are now registered , contact your agent for more information, or go online payrainbow.com/complaint.html From Rainbow Team';
        send_mail($email,$msg);
        
        //redirect to success page
  header("Location: success.html");
         die();
        
        break;//finish process
        
    case 'seller' : ///case of Seller registration
        $tokenz  .='seller'; 
        mysql_query("INSERT INTO  seller_tb  ( first_name ,  last_name ,  mobile_no ,  email ,  gender ,  dob ,  location ,  national_id ,  username , product_line ,  site ,  business_name ,  registration_id ,  TIN ,card_no,pin ,owner) VALUES ('".$fname."', '".$lname."', '".$phone."', '".$email."', '".$gender."', '".$dob."', '".$location."','".$nid."', '".$uname."',  '".$product."',  '".$site."', '".$business."', '".$reg_no."', '".$TIN."','".$card."','".$pin."','".$tokenz ."')"); 
        
       // echo "Your card no. :- "; 
       // echo $card;;
    
        mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'seller_reg', '000', '".$agent_id."', '".$card."', '".$tokenz ."','0','".$location."')");
        
           //send Email
        
   $msg = 'Welcome Mr.'.$uname.' to Rainbow an Amonsoft company,   Welcome to Rainbow an Amonsoft company . The Easiest, Simplest and Most Effiecient Payment System, presenting the new age of business online . 
        
        Your are now registered , contact your agent for more information, or go online payrainbow.com/complaint.html From Rainbow Team';
        send_mail($email,$msg);
        
        //redirect to success page
          header("Location: success.php?token=".json_encode($unencodedArray['jwt']));
         die();
        
        break;//finish process
   
    case 'staff' : ///case of Staff registration
        $tokenz  .='staff'; 
        mysql_query("INSERT INTO  staff_tb  ( first_name ,  last_name ,  mobile_no ,  email ,  gender ,  dob ,  location ,  national_id ,  username ,  TIN ,card_no,pin,department,staff_contract_no,owner ) VALUES ('".$fname."', '".$lname."', '".$phone."', '".$email."', '".$gender."', '".$dob."', '".$location."','".$nid."', '".$uname."', '".$TIN."','".$card."','".$pin."','".$department."','".$contract."','".$tokenz ."')");
    
        //echo "Your card no. :- "; 
        //echo $card;
    
        mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'staff_reg', '000', '".$agent_id."', '".$card."', '".$tokenz ."','000','".$location."')");
        
           //send Email
      $msg = 'Welcome Mr.'.$uname.' to Rainbow Staff an Amonsoft company,   Welcome to Rainbow an Amonsoft company . The Easiest, Simplest and Most Effiecient Payment System, presenting the new age of business online . 
        
        Your are now registered , contact your agent for more information, or go online payrainbow.com/complaint.html From Rainbow Team';
        send_mail($email,$msg);
        
         //redirect to success page
         header("Location: success.html");
         die();
        
        break;//finish process
        
    case 'agent':
        $tokenz  .='agent'; 
        mysql_query("INSERT INTO  agent_tb  ( first_name ,  last_name ,  mobile_no ,  email ,  gender ,  dob ,  location ,  national_id ,  username ,  TIN ,card_no,pin,department,agent_contract_no,owner ) VALUES ('".$fname."', '".$lname."', '".$phone."', '".$email."', '".$gender."', '".$dob."', '".$location."','".$nid."', '".$uname."',  '".$TIN."','".$card."','".$pin."','".$department."','".$contract."','".$tokenz."')"); 
    
        //echo "Your card no. :- "; 
        //echo $card; 
    
        mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'agent_reg', '000', '".$agent_id."', '".$card."', '".$tokenz ."','0','".$location."')");
        
           //send Email
        $msg = 'Welcome Mr.'.$uname.' to Rainbow Agents an Amonsoft company,   Welcome to Rainbow an Amonsoft company . The Easiest, Simplest and Most Effiecient Payment System, presenting the new age of business online . 
        
        Your are now registered , contact your agent for more information, or go online payrainbow.com/complaint.html From Rainbow Team';
        send_mail($email,$msg);
        
        //redirect to success page
         header("Location: success.php?token=".json_encode($unencodedArray['jwt']));
         die();
        
        break;//finish process
        
         case 'beta':  ///case of Buyer registration
        $tokenz .='beta'; 
        
          mysql_query("INSERT INTO  beta_tb  ( email ,type,  token ) VALUES ('".$email."','".$uname."','".$tokenz ."')"); 
        
       // echo "Your card no. :- "; 
       // echo $card;;
    
        mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'beta_reg', '000', '0000', '0000', '".$tokenz ."','0','".$email."')");
        
           //send Email
        
        $msg = 'Welcome to Rainbow an Amonsoft company . The Easiest, Simplest and Most Effiecient Payment System, presenting the new age of business online . 
        
        Thanks for showing interest in our new technology.
        
        You have joined our waiting list of BETA users. 
        We shall be communicating through this email for more information.
        
        This is an auto-generated email. Please dont reply.
        
        Regards,
        Rainbow Team
        ';
        send_mail($email,$msg);
        
        //redirect to success page
          header("Location: ../successbeta.php");
        echo 'Success';
         die();
        
        break;//finish process
        
        
    default :
        $tokenz  .='fail-reg'; 
         //record failed transaction
    mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'failed_Registration', '000', '000', '000', '".$tokenz."','000','".$email."','000')");
        
        //send Email
        
        $msg = 'Welcome Mr.'.$uname.' to Rainbow an Amonsoft company, but your registration failed, Please try again.';
        send_mail($email,$msg); 
        //redirect to failure page
    header("Location: failure.html");
        die();
}
 
        

?>