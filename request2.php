<?php 
if (isset($_GET["name"])) {
	$curl = curl_init("https://fcm.googleapis.com/fcm/send");
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	$var = array(
		'to' => '/topics/condor541951236',
		'data' => array('what' => $_GET['name'])
	);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:key=***REMOVED***'));
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($var));
	$answer = curl_exec($curl);
	curl_close($curl);
}
?>