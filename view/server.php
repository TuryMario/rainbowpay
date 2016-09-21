<?php
require_once 'conn.php';
require_once "guzzle.phar";
require_once "bugsnag.phar";

$bugsnag = Bugsnag\Client::make("01fc8ca6ff581931e25ecaffa6b890b8");

Bugsnag\Handler::register($bugsnag);
    
$bugsnag->notifyError('ErrorType', 'Test Error');


?>