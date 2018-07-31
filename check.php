
<meta charset="utf-8"/>
<?php 
if (isset($_GET['q'])) {
	require_once('db_constants.php');
	if ($_GET['q'] == SALT) {
		try {
		$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
		$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'emergency'");
			$row = $request->fetch();
			if ($row["value"] == "NORMAL") {
				echo "200";
			} else {
				echo "<strong>418 - I'm a teapot.</strong><br/>Ladies and gentlemen, server is down for some reasons. Please excuse us for the inconvenience.";
			}
		} catch (Exception $e) {
			echo "<strong>503 - Service Unavailable</strong><br/>Ladies and gentlemen, database is unavailable, this may be unknown to the administrator.<br/> Please report with the following error(s) : " . utf8_encode($e->getMessage());
		}
	} else {
		echo "<strong>403 - Forbidden</strong><br/>Your key is likely outdated. Please update your app.";
		include 'lib/Logger.class.php';
	$logger = new Logger('./logs');
	$logger->log('', 'client_connection', 'Wrong client connection - Wrong key (outdated/hacked) ; from host : '.$_SERVER['REMOTE_ADDR'].' ; reversed: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']). " ; port " . $_SERVER['REMOTE_PORT'] . " and user agent : " . $_SERVER['HTTP_USER_AGENT'], Logger::GRAN_VOID);
	}
} else {
	echo "<strong>403 - Forbidden</strong><br/>Sorry, you're trying to hack the app, but I think I've just beaten you to it :')";
	include 'lib/Logger.class.php';
	$logger = new Logger('./logs');
	$logger->log('', 'client_connection', 'Wrong client connection - HACKING ; from host : '.$_SERVER['REMOTE_ADDR'].' ; reversed: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']). " ; port " . $_SERVER['REMOTE_PORT'] . " and user agent : " . $_SERVER['HTTP_USER_AGENT'], Logger::GRAN_VOID);
}