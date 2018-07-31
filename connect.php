<?php
session_start();
	try {
		if (! @include_once('db_constants.php')) 
			throw new Exception ("db_constants.php not available");
	} catch (Exception $e) {
		header('Location: install.php');
	}
	if (isset($_POST['user'])) {
		require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
		try {
			$message = "";
			$title ="";
			$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
			$req = $bdd->prepare('SELECT * FROM '.TABLE_USERS . ' WHERE user = ?');
			$req->execute(array($_POST['user']));
			if ($req->rowCount() == 1) {
				$data = $req->fetch();
				if (password_verify($_POST['password'], $data['password'])) {
					$_SESSION['loggedin'] = true;
					$_SESSION['id'] = $data['id'];
					$_SESSION['name'] = $data['display_name'];
					$_SESSION['rights'] = $data['rights'];
					$logger->log('', 'connection', '"'.$data['display_name'].'" USER AUTHENTIFIED ; from host : '.$_SERVER['REMOTE_ADDR'].' ; reversed: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']). " ; port " . $_SERVER['REMOTE_PORT'] . " and user agent : " . $_SERVER['HTTP_USER_AGENT'], Logger::GRAN_VOID);
					$title = "Connexion réussie";
					$message = $data['display_name'] . ", vous avez été authentifié·e avec succès.\nVous allez être redirigé·e.";
					header("refresh:3; url=index.php");
				} else {
					$title ="Mauvais mot de passe";
					$message ="Le mot de passe renseigné est erroné.";
					$logger->log('', 'connection', 'Trying to connect as "'.$data['display_name'].'" - WRONG PASSWORD ; from host : '.$_SERVER['REMOTE_ADDR'].' ; reversed: ' .gethostbyaddr($_SERVER['REMOTE_ADDR']). " ; port " . $_SERVER['REMOTE_PORT'] . " and user agent : " . $_SERVER['HTTP_USER_AGENT'], Logger::GRAN_VOID);
				}
			} else {
				$title ="Utilisateur inexistant";
				$message = "L'utilisateur est inexistant, désolé.";
				$logger->log('', 'connection', 'Trying to connect as inexistant user "'.$_POST['user'].'" ; from host : '.$_SERVER['REMOTE_ADDR'].' ; reversed: '  .gethostbyaddr($_SERVER['REMOTE_ADDR']). " ; port " . $_SERVER['REMOTE_PORT'] . " and user agent : " . $_SERVER['HTTP_USER_AGENT'], Logger::GRAN_VOID);

			}
		} catch (Exception $e) {
			$title = "Erreur système";
			$message = 'Erreur : ' . $e->getMessage();
			die('Erreur : ' . $e->getMessage());
		}
		?>
		<div id="dialog" title="<?php echo $title;?>" style="width: auto;">
			<?php echo $message; ?>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
	$( function() {
		$( "#dialog" ).dialog({
			modal: true,
			maxWidth:600,
			maxHeight: 500,
			width: 500,
			height: 250,
			buttons: {
				Ok: function() {
				$( this ).dialog( "close" );
				}
			}
		});
	});
</script>
	<?php
	}
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8"/>
		<title>Connection</title>
		<meta name="author" content="Quentin DE MUYNCK"/>
		<link rel="stylesheet" href="style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script>
		$(document).on('click', '#forgot_password', function(e) {
			e.preventDefault();
			$('#form').load('reset_password.php');
		});
		$(document).ready(function() {
			$('#user').focus();
		});
		</script>
		<style>
		#form {
			position: absolute; /* postulat de départ */
			top: 50%; left: 50%; /* à 50%/50% du parent référent */
			transform: translate(-50%, -50%);
		}
		#form table tr{
			background: #fff;
		}
		#form table tr:first-child {
			border: none;
		}
		#form td {
			padding: 20px;
			margin: 2px;
		}
		#form table {
			width: auto;
		}
		h1 {
			text-align: center;
		}
		#achtung {
			padding: 20px;
			position: absolute; /* postulat de départ */
			top: -55%;
			text-align: center;
			vertical-align: middle;
			color: white;
			font-weight: bold;
			border: red solid 1px;
			border-radius: 8px;
			background-color: #ff1a1a;
		}
		</style>
	</head>
	<body>
	<div id="form">
		<form action="connect.php" method="post">
		<noscript>
			<div id="achtung">ATTENTION - Javascript est requis pour le bon fonctionnement du site, vous ne serez pas en mesure de vous connecter si Javascript n'est pas activé.</div>
		</noscript>
		<table class="table">
		<tr><td colspan="2"><h1>Condor</h1></td></tr>
		<tr><td>
			<label for="user">Utilisateur :</label></td>
			<td><input type="text" name="user" id="user" required /> </td></tr><tr>
			<td><label for="psw">Mot de passe :</label></td>
			<td><input type="password" id="psw" name="password" required /></td></tr><tr>
			<td></td><td><input type="submit" name="submit" value="Connection"/></td></tr></table>
		</form>
		<a href="" id="forgot_password">Mot de passe oublié ?</a>
		</div>
	</body>
</html>