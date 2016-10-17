<?php
require 'place.php';

// someLine:

$numx;
$num = array('0','1','3','4','5','6','7','8','9','a','e','f','g','h','i','j','k','l','m','n','7','8','2','3','4','5','6','9','b','c','d','0','y','z','1','2','o','p','q','r','s','t','u','v','w');
//create Card
for($i=0;$i<count($num);$i++){
   // echo $num[rand(0,$i)] ;
    $key .= $num[rand(rand(0,$i),$i)] ;
//concatinate  the card
    if($i == 10){
        break;
    }
}

$oldcard = mysql_query("Select * FROM tokenfactory where secretkey = '".$key."'");

$numrows = mysql_num_rows($oldcard);

$algorithm = array('HS256','HS384','HS512','RS256','RS384','RS384','RS512','ES256','ES384','ES512','PS256','PS384','PS512'  );

$Key_for_algorithm = array_rand($algorithm, 1);
$algo_key = $algorithm[$Key_for_algorithm];
    /********Althogrim types ****************
    
      +--------------+-------------------------------+--------------------+
   | "alg" Param  | Digital Signature or MAC      | Implementation     |
   | Value        | Algorithm                     | Requirements       |
   +--------------+-------------------------------+--------------------+
   | HS256        | HMAC using SHA-256            | Required           |
   | HS384        | HMAC using SHA-384            | Optional           |
   | HS512        | HMAC using SHA-512            | Optional           |
   | RS256        | RSASSA-PKCS1-v1_5 using       | Recommended        |
   |              | SHA-256                       |                    |
   | RS384        | RSASSA-PKCS1-v1_5 using       | Optional           |
   |              | SHA-384                       |                    |
   | RS512        | RSASSA-PKCS1-v1_5 using       | Optional           |
   |              | SHA-512                       |                    |
   | ES256        | ECDSA using P-256 and SHA-256 | Recommended+       |
   | ES384        | ECDSA using P-384 and SHA-384 | Optional           |
   | ES512        | ECDSA using P-521 and SHA-512 | Optional           |
   | PS256        | RSASSA-PSS using SHA-256 and  | Optional           |
   |              | MGF1 with SHA-256             |                    |
   | PS384        | RSASSA-PSS using SHA-384 and  | Optional           |
   |              | MGF1 with SHA-384             |                    |
   | PS512        | RSASSA-PSS using SHA-512 and  | Optional           |
   |              | MGF1 with SHA-512             |                    |
   | none         | No digital signature or MAC   | Optional           |
   |              | performed                     |                    |
   +--------------+-------------------------------+--------------------+
    **************************************************/

/*
if($numrows == 0){

   $keyz; //key for secret key holds water
    $algo_key; //key for algotrithm to use holds water

}else{
    
    goto someLine;
    
}
*/

?>