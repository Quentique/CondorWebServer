<?php 
$response = array();
 
require_once('db_constants.php');
if (isset($_GET['q']) && $_GET['q'] == SALT) {
 
// connecting to db
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$clause = 'WHERE state != \'draft\' ';

if (isset($_GET['timestamp']) and $_GET['timestamp'] !== '') {
	$clause .= 'AND date > \'' . $_GET['timestamp'] . '\'';
}
//$result = mysql_query("SELECT * FROM crd_posts " . $clause) or die(mysql_error());
$result = $bdd->query("SELECT * FROM " . TABLE_POSTS . " " . $clause);
if ($result->rowCount() > 0) {
    // looping through all results
    // products node
 		$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name='categories'");
		$row2 = $request->fetch();
		$array2 = json_decode($row2['value'], true);
    while ($row = $result->fetch()) {
        // temp user array
        $product = array();
        $product["id"] = utf8_encode($row["id"]);
        $product["name"] = $row["name"];
        $product["content"] = utf8_encode($row["content"]);
		$product["date"] = utf8_encode($row["date"]);
		$product["state"] = utf8_encode($row["state"]);
		$product["picture"] = utf8_encode($row["picture"]);
		$array = json_decode($row['categories'], true);
		$cat = array();
		foreach($array as $value) {
			$cat[] = $array2[$value];
		}
		$product['categories'] = utf8_encode(json_encode($cat));
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