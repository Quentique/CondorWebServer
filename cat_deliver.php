<?php 
	 require_once ('db_constants.php');
if (isset($_GET['q']) && $_GET['q'] == SALT) {
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name='categories'");
$array = json_decode($request->fetch()['value']);
foreach ($array as $value) {
	$cat[] = $value;
}
echo json_encode($cat);
}
?>