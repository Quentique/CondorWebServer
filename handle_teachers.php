<?php
require_once('check_connected.php');
require_once('db_constants.php');
if (isset($_POST)) {
	try {
		require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
		$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
		if (isset($_POST['modify'])) { /*checks if it's a modification or not */
			$request = $bdd->prepare("UPDATE " . TABLE_TEACHERS . " SET title = :title, name = :name, date_begin = :date_begin, date_end = :date_end, timestamp = CURRENT_TIMESTAMP WHERE id = :id");
			$request->bindParam(':title', $title); /*Preparing request */
			$request->bindParam(':name', $name);
			$request->bindParam(':date_begin', $date_begin);
			$request->bindParam(':date_end', $date_end);
			$request->bindParam(':id', $id);
			
			for ($i = 0 ;$i<count($_POST['gender']); $i++) { /*For each teacher absences */
				$title = $_POST['gender'][$i];
				$name = ucwords($_POST['name'][$i], " \t\r\n\f\v-");
				$date_begin = $_POST['begin'][$i] .' ' . $_POST['beginH'][$i];
				$date_end = $_POST['end'][$i].' ' . $_POST['endH'][$i];
				$id = $_POST['id'][$i];
				$request->execute();
				$logger->log('', 'teachers', 'Modifiying teacher absence ID ' . $id . ' of teacher ' . $name . ' by user '.$_SESSION['name'], Logger::GRAN_VOID);
			}
		} else {
		$request = $bdd->prepare("INSERT INTO ". TABLE_TEACHERS . " (title, name, date_begin, date_end) VALUES (:title, :name, :date_begin, :date_end)");
		$request->bindParam(':title', $title);
		$request->bindParam(':name', $name);
		$request->bindParam(':date_begin', $date_begin);
		$request->bindParam(':date_end', $date_end);
		
		for ($i = 0; $i < count($_POST['gender']); $i++) { /*For each teacher absences */
			$title = $_POST['gender'][$i];
			$name = ucwords($_POST['name'][$i], " \t\r\n\f\v-");
			$date_begin = $_POST['begin'][$i] .' ' . $_POST['beginH'][$i];
			$date_end = $_POST['end'][$i].' ' . $_POST['endH'][$i];
			$request->execute();
			$logger->log('', 'teachers', 'Adding teacher absence of teacher ' . $name . ' by user ' . $_SESSION['name'], Logger::GRAN_VOID);
		}
			$bdd->query("DELETE FROM " . TABLE_TEACHERS . " WHERE date_end < CURRENT_TIMESTAMP");
		}
	} catch (Exception $e) {
		echo 'Erreur : ' . $e->getMessage();
	}
}
?>