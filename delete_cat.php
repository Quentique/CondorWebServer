<?php
require('check_connected.php');
if (isset($_GET['id'])) {
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$request = $bdd->prepare("SELECT id FROM " . TABLE_POSTS . " WHERE categories LIKE :value");
$value = '%'.$_GET['id'].'%'; /* % are used because the category is surrounded by "" due to json format*/
$request->bindParam(":value", $value);
$request->execute();
if($request->rowCount() == 0) { /* Only if no articles is associated with the category itself */
	$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'categories'");
	$row = $request->fetch();
	$array = json_decode($row['value'], true);
	unset($array[$_GET['id']]);
			require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
		$logger->log('', 'posts', 'Deleting category "'.$_GET['id'].'" by user '.$_SESSION['name'], Logger::GRAN_VOID);
	$array = json_encode($array);
	$request =$bdd->prepare("UPDATE " . TABLE_GENERAL . " SET value = :value WHERE name = 'categories'");
	$request->bindParam(":value", $array);
	$request->execute();
}
}