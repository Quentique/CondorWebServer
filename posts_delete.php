<?php
if (isset($_GET['id'])) {
	require_once('db_constants.php');
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	$request = $bdd->prepare("UPDATE " . TABLE_POSTS . " SET state = 'deleted', date = CURRENT_TIMESTAMP WHERE id = :id");
	$request->bindParam(":id", $_GET['id']);
	if($request->execute()) { echo 'TRUE';}else {echo 'FALSE';}
}
?>