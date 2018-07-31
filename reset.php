<?php

try {
	require_once('db_constants.php');
$conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
}
catch(PDOException $ex) 
    { 
        $msg = "Failed to connect to the database"; 
    } 
    
// Was the form submitted?
if (isset($_POST["ResetPasswordForm"]))
{
	require 'lib/Logger.class.php';
	$logger = new Logger('./logs');
	// Gather the post data
	$email = $_POST["email"];
	$password = $_POST["password"];
	$confirmpassword = $_POST["confirmpassword"];
	$hash = $_POST["q"];

	// Use the same salt from the forgot_password.php file
	$salt = "498#2D83B631%3800EBD!801600D*7E3CC13";

	// Generate the reset key
	$resetkey = hash('sha512', $salt.$email);

	// Does the new reset key match the old one?
	if ($resetkey == $hash)
	{
		if ($password == $confirmpassword)
		{
			//has and secure the password
			$password = password_hash($password, PASSWORD_BCRYPT);

			// Update the user's password
				$query = $conn->prepare('UPDATE '.TABLE_USERS.'  SET password = :password WHERE mail = :email');
				$query->bindParam(':password', $password);
				$query->bindParam(':email', $email);
				$query->execute();
				$conn = null;
			echo "Votre mot de passe a été modifié avec succès, vous pouvez maintenant vous reconnecter à Condor avec votre nouveau mot de passe.";
			$logger->log('', 'connection', 'Password changed for user with mail : '.$email, Logger::GRAN_VOID);
		}
		else
			echo "Les deux mots de passe ne sont pas identiques.";
	}
	else
		echo "Your password reset key is invalid.";
		$logger->log('', 'connection', 'Trying to change password for email : '.$email . ' with wrong key - HACKING', Logger::GRAN_VOID);
}

?>
