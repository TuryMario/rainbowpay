<?php
require 'place.php';
include_once('register2.php');


   $type = $_POST["type"];
   $fname =  $_POST["fname"];
   $lname = $_POST["lname"];
   $tel = $_POST["phone"];
   $email = $_POST["email"];
   $gender = $_POST["gender"];
   $dob = $_POST["dob" ];
   $location = $_POST["location"];
   $job = $_POST["job"];
   //......................removed national id .........................//
   //$nid = $_POST["nid"];
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

//check if exists
/***seller check****/
$email_check = mysql_query("SELECT * FROM seller_tb WHERE email = '".$email."' OR mobile_no = '".$tel."'");
$email_row = mysql_fetch_assoc($email_check); 

/***buyer*****/
/***seller check****/
$emaily_check = mysql_query("SELECT * FROM buyer_tb WHERE email = '".$email."' OR mobile_no = '".$tel."' ");
$emaily_row = mysql_fetch_assoc($emaily_check); 

 //staff_tb
$emailyz_check = mysql_query("SELECT * FROM staff_tb WHERE email = '".$email."' OR mobile_no = '".$tel."' ");
$emailyz_row = mysql_fetch_assoc($emailyz_check); 

//agent_tb
$emailyx_check = mysql_query("SELECT * FROM agent_tb WHERE email = '".$email."' OR mobile_no = '".$tel."' ");
$emailyx_row = mysql_fetch_assoc($emailyx_check);

//beta_tb
$emailyc_check = mysql_query("SELECT * FROM beta_tb WHERE email = '".$email."'");
$emailyc_row = mysql_fetch_assoc($emailyc_check);


//if nobody is registered with such details or alike registers the person accordingly 
if($email_row == 0 && $emaily_row == 0 && $emailyz_row == 0 && $emailyx_row == 0 && $emailyc_row == 0 ){

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
}else{
     $tokenz  .='fail-reg'; 
         //record failed transaction
    mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'failed_Registration', '000', '000', '000', '".$tokenz."','000','".$email."','000')");
        
        //send Email
        
        $msg = 'Welcome Mr.'.$uname.' to Rainbow an Amonsoft company, but your registration failed, Seems you are already registered.';
        send_mail($email,$msg); 
        //redirect to failure page
    header("Location: failure.html");
        die();
}
 
//end of code
        

?>