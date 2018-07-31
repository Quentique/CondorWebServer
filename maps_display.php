<?php
require_once('db_constants.php');
require_once('check_connected.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);

$request = $bdd->query('SELECT * FROM ' . TABLE_MAPS . ' ORDER BY `display_name`');
?>
<h2>Cartes</h2>
<table class="table">
<tr>
	<th>Actions</th>
	<th>ID</th>
	<th>Nom</th>
	<th>Description</th>
	<th>Fichier map</th>
</tr>
<?php
while ($row = $request->fetch()) {
?>
<tr>
<td><a href="maps_add.php?id=<?php echo $row['id']; ?>" class="modify_map">Modifier</a><a href="maps_delete.php?id=<?php echo $row['id']; ?>" class="delete_map">Supprimer</a></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['display_name']; ?></td>
<td><?php echo $row['description']; ?></td>
<td><?php echo $row['map']; ?></td>
</tr>
<?php 
} ?>
</table>