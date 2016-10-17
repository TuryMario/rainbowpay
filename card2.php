<?php
// ............. included place.php ...............

require 'place.php';

//recieve_name
$name =  $_POST["uname"];


//echo $card;
someLine:

$numx;
$num = array('0','1','7','8','2','3','4','5','6','9');
//create Card
for($i=0;$i<11;$i++){
   // echo $num[rand(0,$i)] ;
    $card .= $num[rand(rand(0,$i),$i)] ;
//concatinate  the card
}
    
//create PIN
$numz;
for($x=0;$x<10;$x++){
   $numz .= $num[rand(rand(0,$x),$x)] ;
//concatinate the pin
}

//dont create pin , becuase already entered by user
  $numx = array('b','c','d','0','y','z','1','2','3','4','5','6','7','8','9','a','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w');
//token
for($i=0;$i<26;$i++){
   // echo $num[rand(0,$i)] ;
    $token .= $numx[rand(rand(0,$i),$i)] ;
//concatinate  the token
}


$agent = 0000;
// echo $numx ;

$oldcard = mysql_query("Select * FROM card_tb where card_no = '".$card."' AND serial = '".$numz."' AND pin = '".$_POST["pin"]."' ");

$numrows = mysql_num_rows($oldcard);


if($numrows == 0){
    $token .= 'card';
    // echo $numx ;
    mysql_query("INSERT INTO card_tb  ( card_no ,  pin ,  date_of_make ,  serial ,  assigned ,  agent_id,owner ) VALUES ( '".$card."', '".$_POST["pin"]."', CURRENT_TIMESTAMP, '".$numz."', '".$name."', '".$agent."','".$token."')");
    
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location) VALUES ( 'card_make', '000', '".$agent."', '".$card."', '".$token."','0','".$_POST["location"]."')");
    
}else{
    
    goto someLine;
    
}

?>