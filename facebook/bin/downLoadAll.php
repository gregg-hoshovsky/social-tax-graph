<?php
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	//header('Content-type: application/json');
	$user_id=$_GET["user_id"];

	if( $user_id=="anonymous")
		$str="http://votertaxes.cloudfoundry.com/synccontrollers?downLoadAll=10";
	else
		$str="http://votertaxes.cloudfoundry.com/synccontrollers?downLoadYours=".urlencode ($user_id);

	//echo $str."<br/>";
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$str);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	//var_dump($data);
	$jData = json_decode($data, true);
	$defense = array();
	$welfare = array();
	$defenseId = array();
	$welfareId = array();
	$defensePercent = array();
	$welfarePercent = array();
	$max_child_id = -1;
	//var_dump($jData);
	foreach ($jData["_androidObject"] as $key => $item)
	{
		if($item['parent_id']==1)
		{
			array_push($defense,$item['name']);
			array_push($defenseId,$item['parent_id'].",".$item['child_id'].",".$item['name']);
			array_push($defensePercent,$item['percent']);
		}	
		if($item['parent_id']==2)
		{
		array_push($welfare,$item['name']);
		array_push($welfareId,$item['parent_id'].",".$item['child_id'].",".$item['name']);
		array_push($welfarePercent,$item['percent']);
		}
		$max_child_id= max($max_child_id,$item['child_id']);

	}
	$sizeof_defense = sizeof($defense);
	$sizeof_welfare = sizeof($welfare);
?>
<table  cellspacing="10">
<tr align="center"><th colspan ="2">Defense Expenditures</th><th colspan ="2">Welfare Expenditures</th></tr>

<?php 
	$count = max($sizeof_welfare,$sizeof_defense);
	for ($i=0; $i<$count; $i++) {
		echo "<tr><td>";
		if( isset($defense[$i]))
			echo $defense[$i];
		echo "</td><td> ";
		if( isset($defense[$i]))
		{
?>
 %<input type="text" name="<?php echo $defenseId[$i]; ?>" id="<?php echo $defenseId[$i]; ?>" size="2" value="<?php echo $defensePercent[$i]; ?>" class="text-input" />
 <?php
	}
	echo "</td>";
	echo "<td>";
	if( isset($welfare[$i]))
		echo $welfare[$i];
	echo "</td><td>";
	if( isset($welfare[$i]))
?>
%<input type="text" name="<?php echo $welfareId[$i]; ?>" id="<?php echo $welfareId[$i]; ?>" size="2" value="<?php echo $welfarePercent[$i]; ?>" class="text-input" />
 <?php
	echo "</td></tr>";
}
?>
</table>
<hr/>

