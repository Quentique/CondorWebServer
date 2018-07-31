<?php 
require_once('db_constants.php');
require_once('perm_constants.php');
require_once('check_connected.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);

$request = $bdd->query("SELECT * FROM " . TABLE_EVENTS . " WHERE state = 'published' OR state = 'draft' ORDER BY start DESC");
?>
<h2>Évènements</h2>
<table class="table">
<tr>
	<th>Actions</th>
	<th>ID</th>	
	<th>Nom</th>
	<th>Lieu</th>
	<th>Début</th>
	<th>Fin</th>
	<th>État</th>
	<th>Dernière modification</th>
</tr>
<?php
while ($row = $request->fetch()) { ?>
<tr>
	<td><a class="event_modify" href="event_add.php?id=<?php echo $row['id'];?>">Modifier</a><a class="delete_event" href="event_change.php?state=0&id=<?php echo $row['id'];?>">Supprimer</a></td>
	<td><?php echo $row['id']; ?></td>
	<td><?php echo $row['name']; ?></td>
	<td><?php echo $row['place']; ?></td>
	<td><?php echo $row['start']; ?></td>
	<td><?php echo $row['end']; ?></td>
	<td><?php echo ($row['state'] == 'draft') ? 'Brouillon' : 'Publié'; ?></td>
	<td><?php echo $row['date']; ?></td>
</tr>
<?php }
?>