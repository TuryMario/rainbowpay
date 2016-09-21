<?php
require_once('vendor/autoload.php');
use \Firebase\JWT\JWT; 
try{
               $secretKey = base64_decode('Your-Secret-Key'); 
               $DecodedDataArray = JWT::decode('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE0NzA0ODUyNTcsImp0aSI6IlptMkJWNnlINzIraXRIcVB4QjJMUFdzc0x5WUsxS1NiSEVGdjdTRmoxREU9IiwiaXNzIjoiaHR0cDpcL1wvZWMyLTU0LTE5MS0yMzAtMzMudXMtd2VzdC0yLmNvbXB1dGUuYW1hem9uYXdzLmNvbSIsIm5iZiI6MTQ3MDQ4NTI2NywiZXhwIjoxNDcwNDkyNDY3LCJkYXRhIjp7ImlkIjoiMTIiLCJuYW1lIjoiYW1vbiJ9fQ.Sl3JVIop9naUxHm0TK9sHtp7luiWLb4v4gaL_7JS_1tTx80UXTEpJGqaKkcWTGF4sWtUllkKEIdjrWDeShcAgw', $secretKey, array('HS512'));
 
               echo  "{'status' : 'success' ,'data':".json_encode($DecodedDataArray)." }";
die();
 
    
               } catch (Exception $e) {
       echo "{'status' : 'fail' ,'msg':'Unauthorized'}";
    die();
               }
 
   
?>