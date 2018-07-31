<?php
require_once('db_constants.php');
require('check_connected.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
if (isset($_POST['id'])) {
	$array = array();
	for ($i = 0 ; $i < count($_POST['id']) ; $i++) {
		if ($_POST['id'][$i] != "" && $_POST['value'][$i] != ""){
			$array[$_POST['id'][$i]] = $_POST['value'][$i];
		}
	}

	$array = json_encode($array);
	$request = $bdd->prepare('UPDATE ' . TABLE_GENERAL .' SET value = :value WHERE name = "categories"');
	$request->bindParam(":value", $array);
	
			require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
		$logger->log('', 'posts', 'Updating categories by user '.$_SESSION['name'], Logger::GRAN_VOID);
	
	$request->execute();
	$bdd->query("UPDATE ". TABLE_GENERAL . " SET value = CURRENT_TIMESTAMP WHERE name = 'timestamp'");
}
?>
<div id="categories_content">
<form method="post" id="categorie_form">
<table class="table">
<tr>
<th><button type="button" id="add_cat">Ajouter</button></th>
<th>Identifiant</th>
<th>Catégorie</th>
</tr>
<?php

$request = $bdd->prepare("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'categories'");
if ($request->execute()) {
	$row = $request->fetch();
	$table = json_decode($row['value'], true);
	foreach ($table as $key=>$value) {
?>

<tr><td><a href="" class="modify_cat">Modifier</a><a href="delete_cat.php?id=<?php echo $key;?>" class="delete_categorie">Supprimer</a></td>
<td><?php echo $key;?><input type="hidden" id="<?php echo $key;?>" name="id[]" value="<?php echo $key; ?>"></td>
<td><?php echo $value; ?><input type="hidden" name="value[]" value="<?php echo $value; ?>"></td>
</tr>
<?php
	}
}
?>

</table>
<input type="submit">
</form>
</div>