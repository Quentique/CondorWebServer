<?php require_once('check_connected.php'); ?>
<div id="users_content">
<table class="table">
<tr>
<th>Actions</th>
<th>ID</th>
<th>Login</th>
<th>Nom</th>
<th>Droits</th>
<th>E-mail</th>
</tr>
<?php 
require_once('db_constants.php');
	try {
			$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
			$stmt = $bdd->prepare("SELECT * FROM ". TABLE_USERS);
			if ($stmt->execute()) {
				while ($row = $stmt->fetch()) {
					?>
					<tr>
					<td><a href="users_add.php?id=<?php echo $row['id']; ?>" class="modify_user">Modifier</a><a class="delete_user" href="users_delete.php?id=<?php echo $row['id'];?>">Supprimer</a></td>
					<td><span><?php echo $row['id'];?></td>
					<td><span><?php echo $row['user']; ?></span></td>
					<td><span><?php echo $row['display_name']; ?></span></td>
					<td><span><?php echo $row['rights']; ?></span></td>
					<td><span><?php echo $row['mail']; ?></span></td>
					</tr>
					<?php
				}				
			}
	} catch (Exception $e) {
		echo 'Erreur : ' . $e->getMessage();
	}
?>
</table>
</div>