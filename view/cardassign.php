<?php
require_once 'conn.php';

//check who is being assigned

$type = $_POST['type'];
$nid = $_POST['nid'];
$tel = $_POST['tel'];
$email = $_POST['email'];
$agent = $_POST['agent_id'];
$card = $_POST['card'];

//token for transaction
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
         mysql_query("Update  buyer_tb  SET card_no = '".$card."'  where   email = '".$email."' AND national_id =  '".$nid."'");
 
         mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'card_assignment_buyer', '000', '".$_POST["agent_id"]."', '".$card."', '".$tokenz."','0','".$email."')");
    
       // echo "Your card no. :- "; 
       // echo $card;
        
        //send Email
     
        
        //redirect to success page
  header("Location: success.html");
         die();
        
        break;//finish process
        
    case 'seller' : ///case of Seller registration
        $tokenz  .='seller'; 
         mysql_query("Update  seller_tb  SET card_no = '".$card."'  where  email = '".$email."' AND national_id =  '".$nid."'");
 
         mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'card_assignment_seller', '000', '".$_POST["agent_id"]."', '".$card."', '".$tokenz."','0','".$email."')");
    
        //redirect to success page
            header("Location: success.html");
         die();
        
        break;//finish process
   
    case 'staff' : ///case of Staff registration
        $tokenz  .='staff'; 
         mysql_query("Update  staff_tb  SET card_no = '".$card."'  where   email = '".$email."' AND national_id =  '".$nid."'");
 
         mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'card_assignment_staff', '000', '".$_POST["agent_id"]."', '".$card."', '".$tokenz."','0','".$email."')");
    
        
         //redirect to success page
         header("Location: success.html");
         die();
        
        break;//finish process
        
    case 'agent':
        $tokenz  .='agent'; 
         mysql_query("Update  agent_tb  SET card_no = '".$card."'  where   email = '".$email."' AND national_id =  '".$nid."' ");
 
         mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'card_assignment_agent', '000', '".$_POST["agent_id"]."', '".$card."', '".$tokenz."','0','".$email."')");
    
        //redirect to success page
        header("Location: success.html");
         die();
        
        break;//finish process
        
    default :
        $tokenz  .='fail-reg'; 
         //record failed transaction
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'card_assignment_failed', '000', '".$_POST["agent_id"]."', '".$card."', '".$tokenz."','0','".$email."')");
        
     
        //redirect to failure page
    header("Location: failure.html");
        die();
}    
?>