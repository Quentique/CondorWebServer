<?php
require_once('check_connected.php');
if (isset($_POST['title'])) {
				require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
$categories = "";
if (isset($_POST['cat'])) {
	$table = array();
	foreach($_POST['cat'] as $cat) {
		$table[] = $cat;
	} /* Concataining values into an array to get json object */
	$categories = json_encode($table);
} else {
	$categories = json_encode(array("non_classe")); /* If no category, adding non_classe category as default */
}
$picture = (isset($_POST['picture'])) ? $_POST['picture'] : ""; /* Adding a value in case of non-given picture */
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
if (isset($_POST['id']) && $_POST['id'] != '') { /* Determining whether it's a modification or not*/
	$request = $bdd->prepare("UPDATE " . TABLE_POSTS . " SET name = :name, content = :content, author = :author, date = CURRENT_TIMESTAMP, state = :state, categories = :categories , picture = :picture WHERE id = :id");
	$request->bindParam(":id", $_POST['id']);
	$logger->log('', 'posts', 'Updating post ID ' . $_POST['id'] . ', name : "'.strip_tags($_POST['title']).'" by user ' . $_SESSION['name'], Logger::GRAN_VOID);
} else {
	$request = $bdd->prepare("INSERT INTO " . TABLE_POSTS . " (name, content, author, state, categories, picture) VALUES (:name, :content, :author, :state, :categories, :picture)");
	$logger->log('', 'posts', 'Adding post name : "'.strip_tags($_POST['title']).'" by user ' . $_SESSION['name'], Logger::GRAN_VOID);
}
$title = strip_tags($_POST['title']); /* Security stuff */
$content = $_POST['content'];
$request->bindParam(":name", $title);
$request->bindParam(":content", $content);
$request->bindParam(":author", $_SESSION['id']);
$request->bindParam(":state", $_POST['state']);
$request->bindParam(":categories", $categories);
$request->bindParam(":picture", $picture);
$request->execute(); /*Applying modification / adding */
if ($_POST['state'] == 'published') {
require('request.php');
doRequest();
}
} //stripslashes(trim(
?>
