<?php
require('check_connected.php');
session_start();
unset($_SESSION['loggedin']);		
unset($_SESSION['id']);
unset($_SESSION['name']);
unset($_SESSION['rights']);
header('Location: connect.php');
?>