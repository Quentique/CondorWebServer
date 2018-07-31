<?php 
$response = array();
require_once('db_constants.php');
if (isset($_GET['q']) && $_GET['q'] == SALT) {
// connecting to db
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$clause = ' WHERE (state = \'published\' OR state = \'deleted\') ';
if (isset($_GET['timestamp'])) {
	$clause .= " AND date > '" . $_GET['timestamp'] . "'";
}
$result = $bdd->query("SELECT * FROM " . TABLE_EVENTS . $clause);
if ($result->rowCount() > 0) {
	while ($row = $result->fetch()) {
		$product = array();
		$product['id'] = utf8_encode($row['id']);
		$product['name'] = utf8_encode($row['name']);
		$product['description'] = utf8_encode($row['description']);
		$product['start'] = utf8_encode($row['start']);
		$product['end'] = utf8_encode($row['end']);
		$product['place'] = utf8_encode($row['place']);
		$product['picture'] = utf8_encode($row['picture']);
		$product['state'] = utf8_encode($row['state']);
		
		array_push($response, $product);
	}
}
echo json_encode($response);
}
?>