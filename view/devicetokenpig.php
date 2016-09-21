<?php
require_once 'conn.php';

mysql_query("INSERT INTO device (token,email_buyer) VALUES ( '".$_POST['token']."','".$_POST['email_buyer']."')");

mysql_query("UPDATE buyer_tb SET device_token = '".$_POST['token']."' WHERE email ='".$_POST['email_buyer']."'");

         //record transactions for buyer with new balance
     mysql_query("INSERT INTO transact_tb ( type, amount, agent_id, card_no, mm_code, charge, location,balance) VALUES ( 'device_token_reg', '000', 'app_login_token', '000', '".$_POST['token']."','0000','$_POST['email_buyer']','000')");


?>