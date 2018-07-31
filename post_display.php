<?php
require_once('check_connected.php');
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
if (isset($_GET['id'])) {
$request = $bdd->prepare("SELECT * FROM " . TABLE_POSTS . " WHERE id = :id");
$request->bindParam(":id", $_GET['id']);
if ($request->execute()) {
$row = $request->fetch();
$request = $bdd->prepare("SELECT display_name FROM " . TABLE_USERS . " WHERE id = :id");
$request->bindParam(":id", $row['author']);
$request->execute();
$ligne = $request->fetch();
$user = $ligne['display_name'];
$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'categories'");
$ligne = $request->fetch();
$categories = json_decode($ligne['value'], true);
?>
<style>
figure {
	display: inline-block;
}
</style>
<h1><?php echo $row['name']; ?></h1>
<div><?php echo 'Rédigé par ' . $user; ?></div>
<div><?php $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date']); echo 'Mis à jour le '; echo $date->format("d/m/Y \à H \h i"); ?></div>
<div><?php echo 'Catégories : '; $table = json_decode($row['categories'], true); $string = '';
			foreach($table as $a) { $string .= $categories[$a] . ', '; }
			echo substr($string, 0, -2); ?></div>
<hr/>
<div>
<figure>
<img src="<?php echo $row['picture'];?>" alt="Image de présentation"/>
<figcaption>Utilisé pour la présentation, non-affichée dans l'article lui-même</figcaption>
</figure><div style="display: inline-block; vertical-align: top;">
<?php echo html_entity_decode($row['content']); ?></div>
</div>

<?php
}

}
?>