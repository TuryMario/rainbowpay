<?php

$ch = curl_init("https://fcm.googleapis.com/fcm/send");
$header=array('Content-Type: application/json',
"Authorization: key=AIzaSyDiHeotIxZFBwNA9-tBCFwNbdVjG6BxkvE");
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

curl_setopt($ch, CURLOPT_POST, 1);
$data = array("title" => "Rainbow App Test","text" => "message");
$data2 = array("notification" => $data,"to" => "e6m58J1Ro38:APA91bGuB3pVbLh8OYNHLspINB8xIMhebY3hFX-mnEBnJFmS6vPfPUfLnX63n66W2n0oPY4DivEw0IV3Til_Yh_B5FJqYgOJIEpviFAGa6BXCE4vf8VVjyUDjBsvzWVqNulsiTjHJYcc");
$payload = json_encode( $data2 );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );


curl_exec($ch);
curl_close($ch);


/*
$data = array("title" => "Rainbow App Test","text" => "welcome Customer");
$data2 = array("notification" => $data,"to" => "dXmAN4b6un4:APA91bEt91mtIvCOlbT04dvOLTs1YcbZqJFAryxW0cn2HnM7rMhlPile5oZYLT0kSl9F7dU3JJ9bXu1lfkyUBlQIqW4wzd913Y-4oVlPVEoXhb6G4CEhzdbWbRmgd7rF_LfsyXHoM4I4");
$payload = json_encode( array( "customer"=> $data2 ) );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

*/

/*
curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": {    \"title\": \"Rainbow App Test\",    \"text\": \"welcome Customer\"  },    \"to\" : \"dXmAN4b6un4:APA91bEt91mtIvCOlbT04dvOLTs1YcbZqJFAryxW0cn2HnM7rMhlPile5oZYLT0kSl9F7dU3JJ9bXu1lfkyUBlQIqW4wzd913Y-4oVlPVEoXhb6G4CEhzdbWbRmgd7rF_LfsyXHoM4I4\"}");


*/
?>