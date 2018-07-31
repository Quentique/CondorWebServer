<?php
require('check_connected.php');
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
if (isset($_POST['name'])) { /* Updating settings after form submit */
		require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
	$request = $bdd->prepare("UPDATE " . TABLE_GENERAL . " SET value = :value WHERE name = :name");
	$request->bindParam(":name", $name);
	$request->bindParam(":value", $value);
	
	$name='name';
	$value=$_POST['name'];
	$request->execute();
	
	$name='adresse';
	$value=$_POST['adresse'];
	$request->execute();
	
	$name='tel1';
	$value=$_POST['tel1'];
	$request->execute();
	
	$name='tel2';
	$value=$_POST['tel2'];
	$request->execute();
	
	$name='mail';
	$value=$_POST['mail'];
	$request->execute();
	
	if($_POST['logo'] != $_POST['old_logo']) {
		$name='logo';
		$value=$_POST['logo'];
		$request->execute();
		$bdd->query("UPDATE " . TABLE_GENERAL . " SET value = CURRENT_TIMESTAMP WHERE name = 'logo_updated'"); /* Updating timestamp for client application */
	}
	
	if($_POST['cover'] != $_POST['old_cover']) {
		$name='cover';
		$value=$_POST['cover'];
		$request->execute();
		$bdd->query("UPDATE " . TABLE_GENERAL . " SET value = CURRENT_TIMESTAMP WHERE name = 'cover_updated'");/* Updating timestamp for client application */
	}
	
	$name='color';
	$value=$_POST['color'];
	$request->execute();
	
	$name='facebook';
	$value=$_POST['facebook'];
	$request->execute();
	
	$name='twitter';
	$value=$_POST['twitter'];
	$request->execute();
	
	$name='website';
	$value=$_POST['website'];
	$request->execute();
	
	$name='ent_link';
	$value=$_POST['ent_link'];
	$request->execute();
	
	$bdd->query("UPDATE " . TABLE_GENERAL . " SET value = CURRENT_TIMESTAMP WHERE name = 'timestamp'");
	$logger->log('', 'general', 'Updating general settings by user ' . $_SESSION['name'], Logger::GRAN_VOID);
	require('request.php');
	doRequest();
}
?>
<script>
function openCustomRoxy2(){ /* These functions are used for RoxyFileman, file-manager */
	$('#roxyCustomPanel2').dialog({modal:true, width:875,height:600});
}
function closeCustomRoxy2(){
	$('#roxyCustomPanel2').dialog('close');
	$('#color').trigger("change");	
}
function openCustomRoxy3(){
	$('#roxyCustomPanel3').dialog({modal:true, width:875,height:600});
}
function closeCustomRoxy3(){
    $('#roxyCustomPanel3').dialog('close');
	$('#color').trigger("change");
}
$('document').ready(function() { /* Updating real-time preview */
	$('input[type="url"], input[type="color"]').change(function() {
		$('#iframe').attr("src", "<?php echo HOST; ?>test.php?cover=" + $('#cover').val() + "&logo=" + $('#logo').val() + "&color=" + $('#color').val().substr(1));
	});
});
</script>
<?php
$request = $bdd->prepare("SELECT * FROM " . TABLE_GENERAL);
if ($request->execute() && $request->rowCount() > 10) { /* Checking if all rows exist */
	$key = $request->fetchAll(PDO::FETCH_COLUMN, 1);
	$request->execute();
	$values = $request->fetchAll(PDO::FETCH_COLUMN, 2);
	
$row = array_combine($key, $values); /* Displaying saved settings in form */
echo '<script>$("document").ready(function() {
	$("#name").val("'.$row['name'].'");
	$("#adresse").val("'.$row['adresse'].'");
	$("#tel1").val("'.$row['tel1'].'");
	$("#tel2").val("'.$row['tel2'].'");
	$("#mail").val("'.$row['mail'].'");
	$("#old_logo").val("'.$row['logo'].'");
	$("#logo").val("'.$row['logo'].'");
	$("#old_cover").val("'.$row['cover'].'");
	$("#cover").val("'.$row['cover'].'");
	$("#color").val("'.$row['color'].'");
	$("#iframe").attr("src", "'.HOST.'test.php?cover='.$row['cover'].'&logo='.$row['logo'].'&color='.substr($row['color'],1).'");
	$("#facebook").val("'.$row['facebook'].'");
	$("#twitter").val("'.$row['twitter'].'");
	$("#website").val("'.$row['website'].'");
	$("#ent_link").val("'.$row['ent_link'].'");
});
</script>
';
}
?>

<style>
table, tr {
	background: rgba(0,0,0,0);
}
input { width: 50%; text-align: center;}
</style>
<form method="post" id="general_form">
	<fieldset>
		<legend>Infos générales</legend>
		<table class="table"><tr><td>
			<label for="name">Nom du lycée : </label></td><td>
			<input type="text" name="name" id="name" required ></td></tr><tr><td>
			<label for="adresse">Adresse du lycée : </label></td><td>
			<input type="text" name="adresse" id="adresse"></td></tr><tr><td>
			<label for="tel1">Tél. 1 : </label></td><td>
			<input type="tel" name="tel1" id="tel1"></td></tr><tr><td>
			<label for="tel2">Tél. 2 : </label></td><td>
			<input type="tel" name="tel2" id="tel2"></td></tr><tr><td>
			<label for="mail">Mél. : </label></td><td>
			<input type="email" name="mail" id="mail"></td></tr><tr><td>
			<label for="ent_link">ENT : </label></td><td>
			<input type="url" name="ent_link" id="ent_link"></td></tr></table>
	</fieldset><fieldset>
		<legend>Présentation</legend><table class="table"><tr><td>
			<label for="logo">Logo : </label></td><td>
			<input type="text" name="logo" id="logo" required><input type="hidden" name="old_logo" id="old_logo"><button type="button" id="logo_button" onclick="openCustomRoxy2()">Sélectionner</button></td></tr><tr><td>
			<label for="cover">Fond (cover) : </label></td><td>
			<input type="text" name="cover" id="cover" required><input type="hidden" name="old_cover" id="old_cover"><button type="button" id="cover_button" onclick="openCustomRoxy3()">Sélectionner</button></td></tr><tr><td>
			<label for="color">Couleur du nom : </label></td><td>
			<input type="color" name="color" id="color"></td></tr></table>
		<iframe src="" id="iframe" style="width: 60%; height: 500px;"></iframe>
	</fieldset><fieldset><legend>Réseaux Sociaux</legend><table class="table"><tr><td>
			<label for="facebook">ID de la page Facebook : </label></td><td>
			<input type="text" name="facebook" id="facebook"></td></tr><tr><td>
			<label for="twitter">Lien vers compte Twitter : </label></td><td>
			<input type="url" name="twitter" id="twitter"></td></tr><tr><td>
			<label for="website">Site du lycée (avec un / final) : </label></td><td>
			<input type="url" name="website" id="website"></td></tr></table>
	</fieldset>
	<input type="submit">
</form>
<div id="roxyCustomPanel2" style="display: none;"><!-- Filemanager -->
  <iframe src="<?php echo HOST; ?>fileman2/index.html?integration=custom&type=images&txtFieldId=logo&close=2&url=short" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>
<div id="roxyCustomPanel3" style="display: none;">
  <iframe src="<?php echo HOST; ?>fileman2/index.html?integration=custom&type=images&txtFieldId=cover&close=3&url=short" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>