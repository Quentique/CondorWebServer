<?php 
require_once('perm_constants.php');
require_once('db_constants.php');
require_once('check_connected.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
if (isset($_POST['username'])) {
	$rights = 0;
	if (isset($_POST['admin'])) {
		$rights |= ADMIN_PERM;
	}
	if (isset($_POST['canteen'])) {
		$rights |= CANTEEN_PERM;
	}
	if (isset($_POST['absences'])) {
		$rights |= ABSENCES_PERM;
	} /* Building final permission */
	
	if ($_POST['id'] == "") { /*Checking whether it's a modification or not */
		$request = $bdd->prepare("INSERT INTO " . TABLE_USERS . " (user, display_name, password, rights, mail) VALUES (:user, :display_name, :password, :rights, :mail)");
	} else {
		$add = (isset($_POST['psw']) && $_POST['psw'] !== "") ? "password = :password, " : "";
		$request = $bdd->prepare("UPDATE " . TABLE_USERS . " SET user = :user, display_name = :display_name, " . $add . "rights = :rights, mail = :mail WHERE id = :id");
		$request->bindParam(":id", $_POST['id']);
		if ($_POST['id'] == "1") {
			$rights |= ADMIN_PERM;
		}
	}
	$request->bindParam(":user", $user);
	$request->bindParam(":display_name", $dpName);
	$request->bindParam(":mail", $mail);
	$request->bindParam(":rights", $rights);
	$user = $_POST['username'];
	$dpName = $_POST['display_name'];
	$mail = $_POST['email'];
	echo $user;
	echo $dpName;
	echo $mail;
	echo $rights;
	
	if (isset($_POST['psw']) && $_POST['psw'] !== "") {
		$request->bindParam(":password", $password);
		$password = password_hash($_POST['psw'], PASSWORD_BCRYPT); /*Hashing password */
		echo $password;
		
	}
	$request->execute();
}
if (isset($_GET['id'])) {
	try { /*Displating user settings for editing */
			$stmt = $bdd->prepare("SELECT * FROM ". TABLE_USERS . " WHERE id = :id");
			$stmt->bindParam(":id", $_GET['id']);
			if ($stmt->execute()) {
				$row = $stmt->fetch();
				$toAdd = "";
				if ((int)$row['rights'] & ADMIN_PERM) {
					$toAdd .= '$("#admin").prop("checked", true); ';
				} 
				if ((int)$row['rights'] & CANTEEN_PERM) {
					$toAdd .= '$("#canteen").prop("checked", true); ';
				}
				if ((int)$row['rights'] & ABSENCES_PERM) {
					$toAdd .= '$("#absences").prop("checked", true); ';
				}
				echo '<script>
				$("document").ready(function() {
					$("#idS").text("'.$row['id'].'");
					$("#id").val("'.$row['id'].'");
					$("#user").val("'.$row['user'].'");
					$("#display").val("'.$row['display_name'].'");
					$("#mail").val("'.$row['mail'].'");
					' . $toAdd . ' 
				});
				</script>			
				';

								
			}
	} catch (Exception $e) {
		echo 'Erreur : ' . $e->getMessage();
	}
} else {
	echo '<script>$("document").ready(function() { $("#password").prop("required", true); $("#password").attr("placeholder", ""); });</script>';
}?>
<script>
$('document').ready(function() {
	$('form').submit(function(e) {
		if ($('#id').val() == "" && $('#password').val() == "") {
			e.preventDefault();
			alert('Le mot de passe ne peut pas être vide !');
		}
	});
});
</script>
<form method="post" id="add_user_form">
<table class="table"><tr><td><label for="id">ID : </label></td><td><span id="idS"></span><input id="id" type="hidden" name="id"></td></tr>
<tr><td><label for="user">Username :</label></td><td><input id="user" type="text" name="username" required /></td></tr>
<tr><td><label for="display">Nom :</label></td><td><input id="display" type="text" name="display_name" required /></td></tr>
<tr><td><label for="password" >Mot de passe :</label></td><td><input min="6" id="password" type="password" name="psw" placeholder="Laissez blanc si pas de modifications"/></td></tr>
<tr><td>Droits: </td><td><table><tr><td><label for="admin">Administrateur</label></td><td><input class="rights" value="1" type="checkbox" name="admin" id="admin"/></td></tr>
<tr><td><label for="canteen">Cantine</label></td><td><input class="rights" value="2" type="checkbox" name="canteen" id="canteen"/></td></tr>
<tr><td><label for="absences">Absences</label></td><td><input class="rights" value="4" type="checkbox" name="absences" id="absences"/></td></tr></table></td></tr>
<tr><td><label for="mail">E-mail:</label></td><td><input id="mail" type="email" name="email" required /></td></tr>
<tr><td></td><td><input type="submit" value="Envoyez"/></td></tr>
</table>
</form>
