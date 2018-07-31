<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$('document').ready(function() {
	$('#image').click(function(e) {
    var elOffsetX = $(this).offset().left,
        elOffsetY = $(this).offset().top,
        clickOffsetX = e.pageX - elOffsetX,
        clickOffsetY = e.pageY - elOffsetY;
	var percentWidth = clickOffsetX / $(this).width();
	var percentHeight = clickOffsetY / $(this).height();
	
	$('#x_pos').val(Math.round(percentWidth*10000000000000)/10000000000000);
	$('#y_pos').val(Math.round(percentHeight*10000000000000)/10000000000000);

    e.preventDefault();
	});
	
	$('#map').change(function(e) {
		$('#image').attr('src', 'maps/'+$(this).val()+'.png');
	});
});
</script>
<?php
require_once('check_connected.php');
if (isset($_GET['id'])) {
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$request = $bdd->prepare("SELECT * FROM " .TABLE_MAPS . " WHERE id = :id");
$request->bindParam(':id', $_GET['id']);
$request->execute();
$row = $request->fetch();
$floor = json_decode($row['mark'], true);
$pos = json_decode($row['pos'], true);
echo "<script>
$('document').ready(function() {
	$('#id').val('".$row['id']."');
	$('#name').val('".$row['name']."');
	$('#display_name').val('".$row['display_name']."');
	$('#description').val('".$row['description']."');
	$('#floor').val('".$floor[0]."');
	$('#building').val('".$floor[1]."');
	$('#map').val('".substr($row['map'], 0,-4)."');
	$('#x_pos').val('".$pos['x']."');
	$('#y_pos').val('".$pos['y']."');
});</script>";
}

?>
<link rel="stylesheet" href="style.css">
<form method="post" id="add_map_form"><input type="hidden" id="id" name="id" value="">
<table class="table">
<tr><td><label for="name">Identifiant</label></td><td><input type="text" id="name" name="name" required></td></tr>
<tr><td><label for="display_name">Nom</label></td><td><input type="text" id="display_name" name="display_name" required></td></tr>
<tr><td><label for="description">Description</label></td><td><input type="text" id="description" name="description"></td></tr>
<tr><td colspan="2"><label for="floor">Étage</label><input type="number" min="-1" max="3" id="floor" name="floor" style="margin: 0 15px;"><label for="building">Bâtiment</label><select style="margin: 0 15px;" id="building" name="building"><option value="IN">Internat</option><option value="PL">Petit Lycée</option><option value="GL">Grand Lycée</option><option value="NU"></option></select></td></tr>
<tr><td><label for="map">Plan à utiliser</label></td><td><select name="map" id="map"><optgroup label="Grand Lycée"><option value="0EGL">Rez-de-chaussée</option><option value="1EGL">1er étage</option><option value="2EGL">2ème étage</option><option value="3EGL">3ème étage</option></optgroup><optgroup label="Petit Lycée"><option value="0EPL">Rez-de-chaussée</option><option value="1EPL">1er étage</option><option value="2EPL">2ème étage</option><option value="3EPL">3ème étage</option></optgroup><optgroup label="Internat"><option value="0EIN">Rez-de-chaussée</option><option value="1EIN">1er étage</option></optgroup><option value="GEN">Général</option></select></td></tr>
<tr><td><label for="x_pos">Position X (%)</label></td><td><input type="number" step="0.0000000000001" name="x_pos" id="x_pos" required></td></tr>
<tr><td><label for="y_pos">Position Y (%)</label></td><td><input type="number" step="0.0000000000001" name="y_pos" id="y_pos" required></td></tr>
<tr><td colspan="2"><p>Cliquer à l'endroit désiré sur le plan et les coordonnées seront calculées.</p><img src="maps/0EGL.png" id="image" style="margin: 0; width: 100%;"/></td></tr>
<tr><td><input type="submit"></td><td></td></tr>
</table>