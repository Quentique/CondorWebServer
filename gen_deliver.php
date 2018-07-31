<?php
$response = array();
require_once ('db_constants.php');
if (isset($_GET['q']) && $_GET['q'] == SALT) {
	// include db connect class
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);

	$request = $bdd->query("SELECT value FROM ". TABLE_GENERAL . " WHERE name = 'timestamp'");
	$row = $request->fetch();
 
	// get all products from products table
	/*$pre = mysql_query("SELECT value FROM cdr_gen WHERE name = 'timestamp'") or die(mysql_error());
	$re = mysql_fetch_array($pre);*/

	if (!isset($_GET['timestamp']) or strtotime($row['value']) != strtotime($_GET['timestamp'])) {
		$clause = '';


		//$result = mysql_query("SELECT * FROM cdr_gen") or die(mysql_error());
		$request = $bdd->query("SELECT * FROM " .TABLE_GENERAL);
		// check for empty result
		//if (mysql_num_rows($result) > 0) {
		if($request->rowCount() > 0) {
		// looping through all results
		// products node
 
			//while ($row = mysql_fetch_array($result)) {
			while ($row = $request->fetch()) {
				// temp user array
				$product = array();
				$product["id"] = utf8_encode($row["id"]);
				$product["name"] = utf8_encode($row["name"]);
				$product["value"] = $row["value"];
 
				// push single product into final response array
				array_push($response, $product);
			}
			// success
			// echoing JSON response
			echo json_encode($response);
		} else {
			// no products found
			// echo no uses JSON
			echo json_encode($response);
		}
	}
}
?>