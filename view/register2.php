<?php 
require_once 'conn.php';
require_once('vendor/autoload.php');
use \Firebase\JWT\JWT; 
define('SECRET_KEY','Your-Secret-Key');  /// secret key can be a random string and keep in secret from anyone
define('ALGORITHM','HS512');   // Algorithm used to sign the token, see
                              // https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
//// Suppose you have submitted your form data here with username and password
//$action = $_REQUEST['action']
//if ($username && $password && $action == 'login' ) {
 

                // if there is no error below code run
    /*
		$statement = $config->prepare("select * from login where name = :name" );
                $statement->execute(array(':name' => $_POST['username'])));
		$row = $statement->fetchAll(PDO::FETCH_ASSOC);
                $hashAndSalt = password_hash($password, PASSWORD_BCRYPT);
		if(count($row)>0 && password_verify($row[0]['password'],$hashAndSalt))
		{ */
                    
                    $tokenId    = base64_encode(mcrypt_create_iv(32));
                    $issuedAt   = time();
                    $notBefore  = $issuedAt + 1000;  //Adding 10 seconds
                    $expire     = $notBefore + 31536000; // Adding one year of seconds from issue
                    $serverName = 'http://ec2-54-191-230-33.us-west-2.compute.amazonaws.com'; /// set your domain name 

  					
                    /*
                     * Create the token as an array
                     */
                    $data = [
                        'iat'  => $issuedAt,         // Issued at: time when the token was generated
                        'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                        'iss'  => $serverName,       // Issuer
                        'nbf'  => $notBefore,        // Not before
                        'exp'  => $expire,           // Expire
                        'data' => [                  // Data related to the logged user you can set your required data
				    'id'   => $_POST["reg_no"], // id from the users table
				     'site' => $_POST["site"],
                            'email' => $_POST["email"],
                            'lname' => $_POST["lname"]
                           
                           
                                  ]
                    ];

             /*     $algorithm = array('HS256','HS384','HS512','RS256','RS384','RS384','RS512','ES256','ES384','ES512','PS256','PS384','PS512'  );

$Key_for_algorithm = array_rand($algorithm, 1
                                
$algo_key = $algorithm[$Key_for_algorithm];
*/
                  $secretKey = base64_decode(SECRET_KEY);
                  /// Here we will transform this array into JWT:
                  $jwt = JWT::encode(
                            $data, //Data to be encoded in the JWT
                            $secretKey, // The signing key
                             ALGORITHM 
                           ); 
                 $unencodedArray = ['jwt' => $jwt];

 //send key to db
    mysql_query('INSERT INTO tokenfactory(  secretkey ,  algothrim ,  token ,site ) VALUES ( "'.$secretKey.'", "'.ALGORITHM.'", "'.$jwt.'","'.$_POST["site"].'")');
//token can be printed
//echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}";


//die();
/*
INSERT INTO tokenfactory(  secretkey ,  algothrim ,  token ) VALUES ( "'.$secretKey.'", ALGORITHM, "'.$jwt.'");
             //     echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}";
        /*******************End of JWT code base**********************/


                //  echo  "{'status' : 'success','resp':".json_encode($unencodedArray)."}";

       /*    } else {

                  echo  "{'status' : 'error','msg':'Invalid email or passowrd'}"

                  }
    
     }    */  




?>