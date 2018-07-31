<header>
<?php require_once('check_connected.php'); 
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'name' OR name = 'cover' OR name = 'logo'");
$key = $request->fetchAll(PDO::FETCH_COLUMN, 1);
$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'name' OR name = 'cover' OR name = 'logo'");
$value = $request->fetchAll(PDO::FETCH_COLUMN, 2);
$values = array_combine($key, $value);
/*Retrieving values from db and making an associative array based on 'name' column as key and 'value' column as value. */
?>
<img src="<?php echo HOST.'uploads/'.$values['logo']; ?>" alt="Logo" style="display: inline-block; width: 200px; height: 200px; margin: 15px;"/>
<div style="display: inline-block;" >
	<h1><?php echo $values['name']; ?></h1>
	<h2>Bonjour, <?php echo $_SESSION['name']; ?></h2>
</div>
<span id="disconnect">
	<a href="disconnect.php">Déconnexion</a>
</span>
<style>
header{
	background: url('<?php echo HOST.'uploads/'.$values['cover']; ?>') no-repeat; 
	background-size: 100%;
	background-position: center;
	padding: 30px;
	border: 3px solid white;
	border-radius: 2px;
}
</style>
</header>