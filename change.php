<?php 
require_once('check_connected.php');
require_once('db_constants.php');
if (isset($_GET['id'])) {
	try{
		$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
		$request = $bdd->prepare("UPDATE " . TABLE_TEACHERS . " SET deleted = :state, timestamp = CURRENT_TIMESTAMP WHERE id = :id");
		$request->bindParam(":id", $_GET['id']);
		$request->bindParam(":state", $_GET['state']);
		$request->execute();
		require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
		$text = (($_GET['state'] == 0) ? 'Recovering' : 'Deleting');
		$logger->log('', 'teachers', $text . ' teacher absence ID ' . $_GET['id'] . ' by user ' . $_SESSION['name'], Logger::GRAN_VOID);
	}catch (Exception $e) {
		echo 'Erreur : ' . $e->getMessage();
	}
}