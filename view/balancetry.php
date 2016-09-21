<?php
require_once 'conn.php';

//echo ;
//mysql_query("select * from ber");
$buyer_check = mysql_query("SELECT * FROM buyer_tb WHERE email = '".$_POST['email']."' ");

$buyer_row = mysql_fetch_assoc($buyer_check);
echo json_encode($buyer_row['balance']);
 /*

//mysql_query("select * from ber");
$buyer_check = mysql_query("SELECT * FROM buyer_tb WHERE email = '".$_POST['email']."' ");

$buyer_row = mysql_fetch_assoc($buyer_check);
echo $buyer_row['balance'];
*/
?>