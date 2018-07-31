<?php
try {
	require_once('db_constants.php');
$conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
		require 'lib/Logger.class.php';
		$logger = new Logger('./logs');

// Was the form submitted?
if (isset($_POST["ForgotPassword"])) {
	
	// Harvest submitted e-mail address
	if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
		$email = $_POST["email"];
		
	}else{
		echo "L'adresse email donnée n'est pas valide.";
		exit;
	}

	// Check to see if a user exists with this e-mail
	$query = $conn->prepare('SELECT display_name, mail FROM '.TABLE_USERS.' WHERE mail = :email');
	$query->bindParam(':email', $email);
	$query->execute();
	$userExists = $query->fetch(PDO::FETCH_ASSOC);
	$conn = null;
	
	if ($userExists["mail"])
	{
		// Create a unique salt. This will never leave PHP unencrypted.
		$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

		// Create the unique user password reset key
		$password = hash('sha512', $salt.$userExists["mail"]);

		// Create a url which we will direct them to reset their password
		$pwrurl = HOST."change_password_display.php?q=".$password;
		  $headers ='From: "Support Condor" <postmaster@cvlcondorcet.fr>'."\n";
                $headers .='Reply-To: "Support Web" <postmaster@cvlcondorcet.fr>'."\n";
                $headers .='Content-Type: text/html; charset="utf-8"'."\n";
                $headers .='Content-Transfer-Encoding: 8bit';
		// Mail them their key
		$mailbody = "Cher utilisateur,\n\n<br/><br/>Si vous n'avez pas fait la demande de cet e-mail, ignorez-le.<br/> Vous avez demandé la réinitialisation de votre mot de passe sur <u>www.cvlcondorcet.fr</u>\n\n<br/><br/>Pour réinitialiser votre mot de passe, merci de cliquer sur le lien ci-dessous. Si vous ne pouvez pas le cliquer, copier-collez le lien dans la barre d'adresse de votre navigateur.\n\n<br/><a href=\"" . $pwrurl . "\"> " . $pwrurl . "</a>\n\n<br/><br/> Si vous rencontrer quelque problème supplémentaire, vous pouvez répondre à cet e-mail.\n<br/><br/>Merci,\n<br/>L'administration de Condor";
		mail($userExists["mail"], "Condor - Réinitialisation du mot de passe", $mailbody, $headers);
		echo "Le lien de récupération de votre mot de passe a été envoyé sur votre adresse mail.";
		$logger->log('', 'connection', 'Password reset requested for user ' .$userExists['display_name'], Logger::GRAN_VOID );
		
	}
	else {
		echo "Aucun utilisateur n'existe avec cette adresse mail.";
		$logger->log('', 'connection', 'Password reset requested for invalid mail : ' . $_POST['email'], Logger::GRAN_VOID);
	}
}
}
catch(PDOException $ex) 
    { 
	echo 'bouh';
        $msg = "Failed to connect to the database"; 
    } 
?>