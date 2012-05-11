<?php

function login($url,$username,$pwd)
{

	$register="?username=".$username. "&email=".$username. "&password=".$pwd. "&phone_number=".$username;

	echo $url.$register."<br/>";
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url.$register);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	echo $data."<br/>";
	curl_close($ch);
	$jData = json_decode($data, true);
	var_dump($jData);
	echo "<br/>";
    return $jData;
}
function customError($errno, $errstr)
{
  echo "<b>Error:</b> [$errno] $errstr<br />";
}
?>
