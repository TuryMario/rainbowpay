<?php
require_once 'conn.php';

mysql_query("INSERT INTO moneymessages (text) VALUES ( '".$_POST['text']."')");

//mysql_query("INSERT INTO moneymessages (text) VALUES ( '".$_GET['text']."')");

echo json_encode('success');
?>