<?php
require('check_connected.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="Content-Type" content="text/html"/>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="script.js"></script>
		<script src="script_users.js"></script>
		<script src="script_categories.js"></script>
		<title>Condor</title>
	</head>
	<body>
		<?php
		include_once('header.php');
		require_once('nav.php');
		?>
		<div id="content">
		</div>
		<div id="loadingDiv"></div>
	</body>
</html>