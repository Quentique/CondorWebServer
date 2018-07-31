<?php 
require_once('db_constants.php');
require_once('perm_constants.php');
require_once('check_connected.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);

$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'categories' ");
$row = $request->fetch();
$categories = json_decode($row['value'], true);
$request = $bdd->query('SELECT id, display_name FROM ' . TABLE_USERS);
$keys = $request->fetchAll(PDO::FETCH_COLUMN, 0);
$request = $bdd->query('SELECT id, display_name FROM ' . TABLE_USERS);
$values = $request->fetchAll(PDO::FETCH_COLUMN, 1);
$users = array_combine($keys, $values);
$state = array("draft" => "Brouillon",
				"published" => "Publié",
				"deleted" => "Supprimé");
if (isset($_GET['draft'])) { /* Restricting choice because of client sync */
	$var = " WHERE state = 'draft'";
} else if (isset($_GET['deleted'])) {
	$var = " WHERE state = 'deleted'";
} else {
	$var = " WHERE state != 'deleted'";
}
$request = $bdd->query("SELECT id, name, date, author, categories, state FROM " . TABLE_POSTS . $var . " ORDER BY date DESC");
?>
<h2>Billets</h2>
<table class="table">
<tr>
<th>Nom</th>
<th>ID</th>
<th>Date</th>
<th>État</th>
<th>Auteur</th>
<th>Catégories</th>
</tr>
<?php 
while ($row = $request->fetch()) {
	?>
	<tr>
	<td><span><a class="display_link" href="post_display.php?id=<?php echo $row['id'];?>"><?php echo $row['name'];?></a></span><?php if ($_SESSION['id'] == $row['author'] || (int)$_SESSION['rights'] & ADMIN_PERM) {?><div><a href="posts_add.php?id=<?php echo $row['id'];?>" class="modify_post">Modifier</a><?php if (!isset($_GET['deleted']) && ($row['state'] != 'draft')) {?><a href="posts_delete.php?id=<?php echo $row['id'];?>" class="delete_post">Supprimer</a><?php }?></div><?php } ?></td>
	<td><?php echo $row['id']; ?></td>
	<td><?php $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date']); echo $date->format("d/m/Y H \h i"); ?></td>
	<td><?php echo $state[$row['state']]; ?></td>
	<td><?php echo $users[$row['author']]; ?></td>
	<td><?php $table = json_decode($row['categories'], true); $string = '';
			foreach($table as $a) { $string .= $categories[$a] . ', '; }
			echo substr($string, 0, -2); ?></td></tr>
	<?php
}?>