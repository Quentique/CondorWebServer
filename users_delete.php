<?php
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
if (isset($_POST['delete'])){ /*Re-attributing articles to administrator before deleting user */
	$request = $bdd->prepare("UPDATE " . TABLE_POSTS . " SET author = 1 WHERE author = :id");
	$request->bindParam(":id", $_POST['id']);
	$request->execute();
	$request = $bdd->prepare("DELETE FROM " . TABLE_USERS . " WHERE id = :id");
	$request->bindParam(":id", $_POST['id']);
	$request->execute();
	header('Location: users_display.php');
}
if (isset($_GET['id']) && $_GET['id'] != "1") { 
	$request = $bdd->query("SELECT id FROM " . TABLE_POSTS . " WHERE author = '".$_GET['id']."'");
	if ($request->rowCount() == 0) { /* Deleting user if no article is attributed */
		$request = $bdd->prepare("DELETE FROM " . TABLE_USERS . " WHERE id = :id");
		$request->bindParam(":id", $_GET['id']);
		$request->execute();
		header('Location: users_display.php');
	} else { /* Let user chooses if conflict exists */
	echo $request->rowCount() . ' articles ont pour auteur : ID ' .$_GET['id'];
	?>
	<p>Cocher la case pour ré-attribuer les articles à l'administrateur principal et supprimer l'utilisateur, sinon, ne cochez pas la case et validez le formulaire</p>
	<form method="post" id="form_delete">
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
	<input type="checkbox" name="delete"><input type="submit">
	</form>
	<?php
	}
} else {
	header('Location: users_display.php');
}

?>