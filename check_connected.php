<?php 
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: connect.php');
	exit;
}
?>