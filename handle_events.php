<?php
require_once('check_connected.php');
if (isset($_POST['title'])) {
				require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
		
$picture = (isset($_POST['picture'])) ? $_POST['picture'] : ""; /* Adding a value in case of non-given picture */
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
if (!isset($_POST['id']) || $_POST['id'] == "") {
$request = $bdd->prepare("INSERT INTO " . TABLE_EVENTS . " (name, description, start, end, place, picture, state) VALUES (:name, :description, :start, :end, :place, :picture, :state)");
$logger->log('', 'events', 'Adding event name : "'.strip_tags($_POST['title']).'" by user ' . $_SESSION['name'], Logger::GRAN_VOID);
} else {
$request = $bdd->prepare("UPDATE " . TABLE_EVENTS . " SET name = :name, description = :description, date = CURRENT_TIMESTAMP, start = :start, end = :end, place = :place, picture = :picture, state = :state WHERE id = :id");
$request->bindParam(":id", $_POST['id']);
$logger->log('', 'events', 'Updating event ID ' . $_POST['id'] . ', name : "'.strip_tags($_POST['title']).'" by user ' . $_SESSION['name'], Logger::GRAN_VOID);
}
$request->bindParam(":name", htmlentities($_POST['title']));
$request->bindParam(":description", $_POST['content']);
$request->bindParam(":start", $_POST['start']);
$request->bindParam(":end", $_POST['end']);
$request->bindParam(":place", htmlentities($_POST['place']));
$request->bindParam(":picture", $_POST['picture']);
$request->bindParam(":state", $_POST['state']);
$request->execute();
require('request.php');
doRequest();
}