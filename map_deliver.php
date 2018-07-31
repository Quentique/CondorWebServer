<?php
$answer = array();
require_once('db_constants.php');
if (isset($_GET['q']) && $_GET['q'] == SALT) {
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$request = $bdd->query("SELECT * FROM " .TABLE_GENERAL . " WHERE name = 'maps_change'");
$row = $request->fetch();
if (!isset($_GET['timestamp']) || strtotime($row['value']) != strtotime($_GET['timestamp'])) {
$request = $bdd->query("SELECT * FROM " . TABLE_MAPS);

while ($row = $request->fetch()) {
	$product = array();
	$product['id'] = $row['id'];
	$product['name'] = $row['name'];
	$product['display_name'] = $row['display_name'];
	$product['description'] = $row['description'];
	$product['map'] = $row['map'];
	$product['pos'] = $row['pos'];
	$product['mark'] = $row['mark'];
	array_push($answer, $product);
}
}
echo json_encode($answer);
}
?>