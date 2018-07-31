<?php
$response = array();
 
//require_once __DIR__ . '/db_connect.php';
require_once('db_constants.php');
if (isset($_GET['q']) && $_GET['q'] == SALT) {
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
//$clause = 'WHERE deleted = 0 ';
$clause = 'WHERE date_end > CURRENT_TIMESTAMP ';
if (isset($_GET['timestamp']) and $_GET['timestamp'] !== '') {
	$clause .= ' AND timestamp > \'' . $_GET['timestamp'] . '\'';
}
//$result = mysql_query("SELECT * FROM crd_profs " . $clause) or die(mysql_error());
$result = $bdd->query("SELECT * FROM " . TABLE_TEACHERS . " " .  $clause);
if ($result != false && $result->rowCount() > 0 && false) {
    // looping through all results
    // products node
 
    while ($row = $result->fetch()) {
        // temp user array
        $product = array();
        $product["id"] = utf8_encode($row["id"]);
		$product["title"] = utf8_encode($row["title"]);
        $product["name"] = utf8_encode($row["name"]);
		$product["begin_date"] = utf8_encode($row["date_begin"]);
		$product["end_date"] = utf8_encode($row["date_end"]);
		$product["deleted"] = utf8_encode($row["deleted"]);
 
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
?>