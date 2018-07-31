<?php
if (isset($_GET['id'])) {
	require_once('db_constants.php');
	//require_once('check_connected.php');
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	$request = $bdd->prepare("DELETE FROM " .TABLE_MAPS . " WHERE id = :id");
	$request->bindParam(':id', $_GET['id']);
	$request->execute();
}