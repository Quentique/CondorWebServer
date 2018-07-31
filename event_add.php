<script src="./ckeditor/ckeditor.js"></script>
<?php
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$content = "";
if (isset($_GET['id'])) {
	$re = $bdd->prepare("SELECT * FROM " . TABLE_EVENTS . " WHERE id = :id");
	$re->bindParam(":id", $_GET['id']);
	if($re->execute()){
		$ligne = $re->fetch();
		$ligne['start'][10] = 'T';
		$ligne['end'][10] = 'T';
		echo "<script>
$('document').ready(function() { 
$('#title').val( '".html_entity_decode($ligne['name']) ."'); 
$('#picture').val( '".$ligne['picture'] ."'); $('#preview').attr('src', '".$ligne['picture'] ."'); 
$('#start').val('".$ligne['start']."'); $('#end').val('".$ligne['end']."'); $('#place').val('".html_entity_decode($ligne['place'])."'); 
$('#state option[value=\"".$ligne['state']."\"]').prop('selected', true); \n";
echo "});
</script>";
	}
} else {
	$ligne['description'] = "";
	$ligne['id'] = "";
	$ligne['state'] = "draft";
}
?>
<script>
function openCustomRoxy2(){
  $('#roxyCustomPanel2').dialog({modal:true, width:875,height:600});
}
function closeCustomRoxy2(){
  $('#roxyCustomPanel2').dialog('close');
  $('#picture').trigger('change');
}
$('document').ready(function() {
	var roxyFileman = 'https://cvlcondorcet.fr/fileman/index.html';
	CKEDITOR.replace('content12',{filebrowserBrowseUrl:roxyFileman,
                                filebrowserImageBrowseUrl:roxyFileman+'?type=image',
                                removeDialogTabs: 'link:upload;image:upload'}).on('instanceReady', resize());
								
	$('#form_add_event').submit(function(e) {
		if($('#state').val() == 'published') {
			if(confirm('Êtes-vous sûr·e de vouloir publier cet article ?\nAprès publication, l\'article sera synchronisé sur les appareils clients et vous ne pourrez plus revenir en arrière.')) {
			} else {
				e.preventDefault();
			}
		}
	});							
});
</script>
<style>
#title {
	color: #72777c;
	font-size: 1.7em;
	padding: 11px 10px;
	cursor: text;
	vertical-align: middle;
	margin-bottom: 15px;
	line-height: 100%;
	width: 100%;
	height: 1.7em;
}
.table tr td {
	padding: 6px 4px 9px 4px;
	text-align: center;
	margin: 0 auto;
}
#preview {
	display: block;
	max-width: 300px;
	margin: 0;
}
#content 
{
	background: none;
	border: none;
}
</style>
<form method="post" id="form_add_event" action="handle_events.php">
	<div id="content_principal">
		<input name="id" type="hidden" value="<?php echo $ligne['id']; ?>">
		<input type="text" name="title" id="title" placeholder="Titre du billet..." max="200" required >
		<textarea name="content" id="content12" required ><?php echo $ligne['description']; ?></textarea>
	</div>
	<div id="right_aside">
	<div class="div_aside">
		<h3 style="margin-bottom: 0;">Paramètres</h3>
<span style="font-style: italic; display:block; margin-bottom: 10px; font-size: 90%;">Ex. format : 2018-02-19 08:00</span>
		<table>
		<tr><td>Début</td><td><input type="datetime-local" name="start" id="start" placeholder="yyyy-mm-dd hh:mm"></td></tr>
		<tr><td>Fin</td><td><input type="datetime-local" name="end" id="end" placeholder="yyyy-mm-dd hh:mm"></td></tr>
		<tr><td>Lieu</td><td><input type="text" name="place" id="place"></td></tr>
		<tr><td><select name="state" id="state"><?php if ($ligne['state'] != 'published') { ?><option value="draft">Brouillon</option><? } ?><option value="published">Publié</option></select></td><td><input type="submit" id="sub"></td></tr>
		</table>
	</div>
	<div class="div_aside">
	<h3>Image</h3>
	<table>
	<tr><td><input type="url" name="picture" id="picture"><button type="button" id="bouton" onclick="openCustomRoxy2()">Sélectionner</button></td></tr>
	<tr><td colspan="2"><img src="" id="preview"/></td></tr></table>
	</div>
		<div class="div_aside">
		<?php include("generator.php"); ?>
		</div>
</div>
<div id="roxyCustomPanel2" style="display: none;">
   <iframe src="<?php echo HOST; ?>fileman2/index.html?integration=custom&type=images&txtFieldId=picture&close=2&url=complete" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>