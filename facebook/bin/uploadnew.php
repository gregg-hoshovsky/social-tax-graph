<?php
//https://simple-window-3181.herokuapp.com/bin/uploadnew.php?username=1455691200&pwd=1455691200&upLoadNew=2,healthcare,1::
	include("util.php");
	set_error_handler("customError"); 
	$username = $_GET['username'];
	$pwd = $_GET['pwd'];
	$upLoadNew = $_GET['upLoadNew'];
	if( $username == '')
	{

		$username = $_POST['username'];
		$pwd = $_POST['pwd'];
		$upLoadNew = $_POST['upLoadNew'];
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
	
	$ch = curl_init();
	$timeout = 5;
	$url_data = $url."?username=".$username."&upLoadNew=". urlencode ($upLoadNew);
	echo $url_data ."<br/>";
	curl_setopt($ch,CURLOPT_URL,$url_data);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
//	curl_setopt($ch, CURLOPT_POST, true);

	$data = array(
		'username' => $username,
		'upLoadNew' =>  $upLoadNew
	);

//	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	$output = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	var_dump($output);
	echo "<br/>";


?>
