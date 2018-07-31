<?php
require_once('db_constants.php');
function doRequest() {
	
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	$request = $bdd->query('SELECT * FROM '.TABLE_GENERAL.' WHERE name = "emergency" ');
	
	if ($request->rowCount() == 1) {
		$row = $request->fetch();
	} else {
		$row= array('value' => 'NORMAL');
	}
	if ($row['value'] == 'NORMAL') {
		$curl = curl_init("https://fcm.googleapis.com/fcm/send");
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		$var = array(
			'to' => '/topics/condor541951236',
			'data' => array('what' => 'sync')
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:key=***REMOVED***'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($var));
		$answer = curl_exec($curl);
		curl_close($curl);
	}
}
?>