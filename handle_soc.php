<?php
if (isset($_POST['data'])) {
	require_once('check_connected.php');
	require_once('db_constants.php');
	require 'lib/Logger.class.php';
	$logger = new Logger('./logs');
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	$logger->log('', 'general', 'Updating new social network by user ' . $_SESSION['name'], Logger::GRAN_VOID);
	$request = $bdd->prepare("UPDATE " . TABLE_GENERAL . " SET value = :data WHERE name = 'social_networks'");
	$request->bindParam(":data", $_POST['data']);
	if ($request->execute()) {
		$bdd->query("UPDATE ". TABLE_GENERAL . " SET value = CURRENT_TIMESTAMP WHERE name = 'timestamp'");
	}
} ?>