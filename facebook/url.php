<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
$user_id=$_GET["user_id"];

$str="http://votertaxes.cloudfoundry.com/synccontrollers?username=".$user_id."&email=".$user_id."&phone_number=".$user_id."&password=".$user_id;

$ch = curl_init($str);
//$fp = fopen("example_homepage.txt", "w");

//curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
//fclose($fp);
?>
