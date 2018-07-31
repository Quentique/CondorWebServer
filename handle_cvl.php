<?php
require_once('check_connected.php');
require_once('db_constants.php');
if (isset($_POST['cvl_content'])) {
		require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
		$logger->log('', 'general', 'Updating CVL information by user ' . $_SESSION['name'], Logger::GRAN_VOID);
		$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	file_put_contents('uploads/cvl.html', $_POST['cvl_content']);
	$bdd->query("UPDATE " . TABLE_GENERAL . " SET value = CURRENT_TIMESTAMP WHERE name = 'cvl_updated'");
	$bdd->query("UPDATE " . TABLE_GENERAL . " SET value = CURRENT_TIMESTAMP WHERE name = 'timestamp'");
	require('request.php');
	doRequest();
}
?>