<?php
//require_once('check_connected.php');
require_once('db_constants.php');
if (isset($_POST['name'])) {
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	$mark = array();
	$mark[] = $_POST['floor'];
	$mark[] = $_POST['building'];
	$mark_formatted = json_encode($mark);

	$pos = array();
	$pos['x'] = $_POST['x_pos'];
	$pos['y'] = $_POST['y_pos'];
	$pos_formatted = json_encode($pos);

	$file = $_POST['map'] . '.pdf';
	if(isset($_POST['id']) && !empty($_POST['id'])) {
		$request = $bdd->prepare("UPDATE " . TABLE_MAPS . " SET name = :name, display_name = :display_name, description = :description, map = :map, pos = :pos, mark = :mark WHERE id = :id");
		$request->bindParam(":id", $_POST['id']);
	} else {
		$request = $bdd->prepare("INSERT INTO " . TABLE_MAPS . " (name, display_name, description, map, pos, mark) VALUES (:name, :display_name, :description, :map, :pos, :mark)");
	}
	$request->bindParam(':name', $_POST['name']);
	$request->bindParam(':display_name', $_POST['display_name']);
	$request->bindParam(':description', $_POST['description']);
	$request->bindParam(':map', $file);
	$request->bindParam(':pos', $pos_formatted);
	$request->bindParam(':mark', $mark_formatted);
	
	$request->execute();
	$bdd->query("UPDATE " . TABLE_GENERAL . " SET value = CURRENT_TIMESTAMP WHERE name = 'maps_change'");
}
?>