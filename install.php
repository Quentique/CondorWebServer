<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<meta http-equiv="Content-Type" content="text/html"/>
<title>Installation</title>
<style>
h1, h3 {
	text-align: center;
}
div {
	margin: auto;
}
</style>
</head>
<body>
<?php 
if (isset($_POST['sqlname'])) {
	/* Creating file */
	$data = "
	<?php
		define('DB_HOST', '".$_POST['sqlhost']."');
		define('DB_NAME', '".$_POST['sqlname']."');
		define('DB_USERNAME', '".$_POST['sqluser']."');
		define('DB_PASSWORD', '".$_POST['sqlpassword']."');
		define('HOST', '".$_POST['adress']."');
		
		define('TABLE_USERS', 'cdr_users');
		define('TABLE_POSTS', 'cdr_posts');
		define('TABLE_TEACHERS', 'cdr_profs');
		define('TABLE_ABSENCES', 'cdr_absences');
		define('TABLE_GENERAL', 'cdr_gen');
		define('TABLE_CATEGORIES', 'cdr_categories');
		define('TABLE_MAPS', 'cdr_maps');
		define('TABLE_EVENTS', 'cdr_events');
		
		define('SALT', '196eede6266723aee37f390e79de9e0e');
	?>
	";
	file_put_contents('db_constants.php', $data);
	require_once('db_constants.php');
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	/* Creating db tables */
	$bdd->query("CREATE TABLE `cdr_gen` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(500) NOT NULL,
 `value` varchar(500) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1"
);
$bdd->query("CREATE TABLE `cdr_posts` (
 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
 `name` varchar(200) NOT NULL COMMENT 'Nom',
 `content` mediumtext NOT NULL COMMENT 'Contenu de l''article (text/html)',
 `author` int(11) NOT NULL COMMENT 'ID de l''auteur',
 `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Dernière modification',
 `state` varchar(30) NOT NULL COMMENT 'État de l''article',
 `categories` varchar(500) NOT NULL COMMENT 'Catégories (en JSON)',
 `picture` varchar(500) NOT NULL COMMENT 'Lien vers l''URL de l''image de présentation',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
$bdd->query("CREATE TABLE `cdr_profs` (
 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
 `title` varchar(2) NOT NULL COMMENT 'Titre (M. & Mme & Mx)',
 `name` varchar(100) NOT NULL COMMENT 'Nom du professeur',
 `date_begin` datetime NOT NULL COMMENT 'Début de l''absence',
 `date_end` datetime NOT NULL COMMENT 'Fin de l''absence',
 `timestamp` datetime NOT NULL COMMENT 'Timestamp',
 `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Supprimé ?',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
$bdd->query("CREATE TABLE `cdr_users` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user` varchar(30) NOT NULL,
 `display_name` varchar(60) NOT NULL,
 `password` varchar(70) NOT NULL,
 `rights` int(11) NOT NULL,
 `mail` varchar(120) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1");
$bdd->query("CREATE TABLE `cdr_maps` (
 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
 `name` varchar(60) NOT NULL COMMENT 'Identifiant',
 `display_name` varchar(100) NOT NULL COMMENT 'Nom d''affichage',
 `description` varchar(300) NOT NULL COMMENT 'Description de la salle',
 `map` varchar(30) NOT NULL COMMENT 'Nom du fichier à utiliser',
 `pos` varchar(100) NOT NULL COMMENT 'Position sur la page (en %)',
 `mark` varchar(100) NOT NULL COMMENT 'Étage et bâtiment',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
$bdd->query("CREATE TABLE `cdr_events` (
 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
 `name` varchar(200) NOT NULL COMMENT 'Nom de l''évènement',
 `description` text NOT NULL COMMENT 'Description de l''évènement',
 `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Dernière modification',
 `state` varchar(50) NOT NULL DEFAULT 'published' COMMENT 'État de l''évènement',
 `start` datetime NOT NULL COMMENT 'Début de l''évènement',
 `end` datetime NOT NULL COMMENT 'Fin de l''évènement',
 `picture` varchar(300) NOT NULL COMMENT 'Lien vers l''image',
 `place` varchar(300) NOT NULL COMMENT 'Lieu de l''évènement',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1");
$toAdd = array('name', 'adresse', 'tel1', 'tel2', 'mail', 'logo_updated', 'logo', 'cover_updated', 'cover', 'canteen_updated', 'canteen', 'cvl_updated', 'cvl', 'color', 'facebook', 'twitter', 'website', 'timestamp', 'emergency', 'maps_change');
foreach($toAdd as $key) {
	$bdd->query("INSERT INTO " . TABLE_GENERAL . " (name) VALUES('" . $key."')");
} /* Creating blank values needed for configuration */
$bdd->query('INSERT INTO ' . TABLE_GENERAL . ' (name, value) VALUES("categories", "{\"non_classe\":\"Non class\\u00e9\"}")');
$text = password_hash("password", PASSWORD_BCRYPT);
$request = $bdd->prepare("INSERT INTO " . TABLE_USERS . " (id, user, display_name, password, rights, mail) VALUES(1, 'admin', 'Administrator', :password, 7, 'nothing@gmail.com')");
$request->bindParam(":password", $text);
$request->execute();
}
?>
<h1>Installation de Condor</h1>
<?php
if (file_exists('db_constants.php')) { ?>
<div>
<h3>Installation terminée</h3>
Condor a été installé. Les paramètres de connexion du super-utilisateur sont "admin" et "password". À changer rapidement.
</div>
<?php
} else {
	?>
	<h3>Merci de renseigner les paramètres suivants pour configurer Condor à la première utilisation.</h3>
	<form method="post">
	<table>
	<tr><td><label for="adress">Adresse du site et fichiers (avec / final)</label></td><td><input required type="url" id="adress" name="adress" val="<?php echo $_SERVER['SERVER_NAME']; ?>"></td></tr>
	<tr><td><label for="sqlhost">Hôte de la base de données</label></td><td><input required type="text" id="sqlhost" name="sqlhost" ></td></tr>
	<tr><td><label for="sqlname">Nom de la base de données</label></td><td><input required type="text" id="sqlname" name="sqlname"></td></tr>
	<tr><td><label for="sqluser">Identifiant à la base de données</label></td><td><input required type="text" id="sqluser" name="sqluser"></td></tr>
	<tr><td><label for="sqlpassword">Mot de passe à la base de données</label></td><td><input type="text" id="sqlpassword" name="sqlpassword"></td></tr>
	</table>
	<input type="submit">
	</form>
	<?php
}
?>
</body>
</html>