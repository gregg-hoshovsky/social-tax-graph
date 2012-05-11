<?php
	include("util.php");
	set_error_handler("customError"); 
	$username = $_GET['username'];
	$pwd = $_GET['pwd'];
	$upLoadAll = $_GET['upLoadAll'];
	if( $username == '')
	{

		$username = $_POST['username'];
		$pwd = $_POST['pwd'];
		$upLoadAll = $_POST['upLoadAll'];
	}
	$username = urlencode ($username);
	$url="http://votertaxes.cloudfoundry.com/synccontrollers";

	$jData=login($url,$username,$pwd);
	

	if( $jData["_status"] != "Ok"
		&& $username != "anonymous")
	{
		echo "NOT OKAY<br/><br/>";
		var_dump($jData);
		exit;
	}
		

	if( $jData["_message"] != "Registered succesfully."
		&& $username == "anonymous")
	{
		$username.= time();
		$jData=login($url,$username,$pwd);
	}
	
	//{"_androidObject":"","_message":"Registered succesfully.","_status":"Ok","class":"com.greggh.tax_voter.utility.ServerStatus"}
	//{"_androidObject":"","_message":"Already Registered.","_status":"Ok","class":"com.greggh.tax_voter.utility.ServerStatus"}
	//{"_androidObject":"","_message":"The username is already in use with different password.","_status":"Error","class":"com.greggh.tax_voter.utility.ServerStatus"}
	
	
	$ch = curl_init();
	$timeout = 5;
	$url_data = $url."?username=".$username."&upLoadAll=".	$username = urlencode ($upLoadAll);
	echo $url_data ."<br/>";
	curl_setopt($ch,CURLOPT_URL,$url_data);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
//	curl_setopt($ch, CURLOPT_POST, true);

	$data = array(
		'username' => $username,
		'upLoadAll' =>  $upLoadAll
	);

//	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$output = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	var_dump($output);
	echo "<br/>";

?>
