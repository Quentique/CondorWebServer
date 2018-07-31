<script src="./ckeditor/ckeditor.js"></script>
<script src="./waitforimages.js"></script>
<script>
function openCustomRoxy2(){
  $('#roxyCustomPanel2').dialog({modal:true, width:875,height:600});
}
function closeCustomRoxy2(){
  $('#roxyCustomPanel2').dialog('close');
  $('#picture').trigger('change');
}
function init() {
	var roxyFileman = 'https://cvlcondorcet.fr/fileman/index.html';
	CKEDITOR.replace('content_textarea',{filebrowserBrowseUrl:roxyFileman,
                                filebrowserImageBrowseUrl:roxyFileman+'?type=image',
                                removeDialogTabs: 'link:upload;image:upload'});
}
$('document').ready(function() {
	
	/*,{filebrowserBrowseUrl:roxyFileman,
                                filebrowserImageBrowseUrl:roxyFileman+'?type=image',
                                removeDialogTabs: 'link:upload;image:upload'} );
								*/
	//$('#content').waitForImages(resize());
	/*$('#picture').change(function() {
		$('#preview').attr('src', $(this).val());
		$('#right_aside').waitForImages(function() {
			resize();
		});*/
		/*var newSrc = $(this).val();
        image = new Image();    

		image.onload = function() {
        $("#preview").hide("puff", function() {
            $(this).attr("src", newSrc).show("fold");
			resize();
        });
		$("#preview").one("load", function() {
        // image loaded here
		}).attr("src", newSrc).show("fold");
    }
    image.src = newSrc;
	resize();*/
	//});
	$('#form_add_post').submit(function(e) {
				$('#content').removeAttr('style');

	});
	
	$('#cat_add').click(function(e) {
		e.preventDefault();
		if ($(this).text() == 'Ajouter') {
			$('#cat_table').append('<tr><td><form method="post" id="cat_add_form"></td><td><input type="text" name="name" id="name" required /></form></td></tr>');
			$(this).text("Soumettre");
		} else {
			$.post('add_category.php', {name: $('#name').val()}).done(function(data) {
			console.log(data);
			console.log("HELLO");
			if (data.indexOf("ERROR")  !== -1) {
			} else {
				var disp = $('#name').val();
				var di = JSON.parse(data);
				$('#cat_table tr:last-child').remove();
				$('#cat_table').append('<tr><td><input type="checkbox" name="cat[]" id="'+di.name+'" value="'+di.name+'"/></td><td><label for="'+di.name+'">'+disp+'</label></td></tr>');
				$('#cat_add').text("Ajouter");
			}
		});
		}
	});
	
	$('#cat_add_form').submit(function(e) {
		e.preventDefault();
		$.post('add_category.php', $(this).serialize()).done(function(data) {
			console.log(data);
			console.log("HELLO");
			console.log((data.indexOf('ERROR') !== -1));
			if (data.indexOf('ERROR') !== -1) {
			} else {
				var disp = $('#name').val();
				$('#cat_table tr:last-child').remove();
				$('#cat_table').append('<tr><td><input type="checkbox" name="cat[]" id="'+data+'" value="'+data+'"/></td><td><label for="'+data+'">'+disp+'</label></td></tr>');
				$('#cat_add').text("Ajouter");
			}
		});
	});
});
</script>
<?php
require_once('check_connected.php');
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$content = "";
if (isset($_GET['id'])) {
	$re = $bdd->prepare("SELECT * FROM " . TABLE_POSTS . " WHERE id = :id");
	$re->bindParam(":id", $_GET['id']);
	if($re->execute()){
		$ligne = $re->fetch();
		$content = $ligne['content'];
		echo "<script>
$('document').ready(function() { 
$('#title').val( '".$ligne['name'] ."'); 
$('option[value=\"". $ligne['state'] ."\"]').prop('selected', true); $('#picture').val( '".$ligne['picture'] ."'); $('#preview').attr('src', '".$ligne['picture'] ."'); 
$('input[type=\"checkbox\"]').prop('checked', false); \n";
		$table = json_decode($ligne['categories'], true);
		foreach ($table as $element) {
			echo " $('#". $element ."').prop('checked', true); \n";
		}
echo "});
</script>";
	}
} else {
	$ligne['content'] = "";
	$ligne['id'] = "";
	$ligne['state'] = 'draft';
}
?>
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
<form method="post" action="handle_posts.php" id="form_add_post">
	<div id="content_principal">
		<input name="id" type="hidden" value="<?php echo $ligne['id']; ?>">
		<input type="text" name="title" id="title" placeholder="Titre du billet..." required >
		<textarea name="content" id="content_textarea" required ><?php echo $ligne['content']; ?></textarea>
	</div>
	<div id="right_aside">
		<div class="div_aside">
			<h3>Publication</h3>
			<select name="state" id="state">
				<?php if ($ligne['state'] == 'draft') { ?>
				<option value="draft">Brouillon</option>
				<?php } ?>
				<option value="published">Publié</option>
				<?php if ($ligne['state'] !== 'draft') {?>
				<option value="deleted">Supprimé</option>
				<?php }?>
			</select>
			<input type="submit">
		</div>
		<div id="checkbox_ort" class="div_aside">
			<h3>Catégories</h3>
			<table class="table" id="cat_table">
			<?php 
try {
	$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'categories'");
	$ligne = $request->fetch();
	$table = json_decode($ligne['value'], true);
	foreach($table as $key=>$row) {
		?>
		<tr><td><input type="checkbox" name="cat[]" value="<?php echo $key; ?>" id="<?php echo $key; ?>"></td><td><label for="<?php echo $key; ?>"><?php echo $row;?></label></td></tr>
		<?php	
	}
} catch (Exception $e){
	echo 'Erreur : ' . $e->getMessage();
}
?>
			</table>
			<button type="button" name="cat_add" id="cat_add">Ajouter</button>
		</div>
		<div class="div_aside">
			<h3>Image</h3>
			<input type="text" name="picture" id="picture">
			<button type="button" id="openE" onclick="openCustomRoxy2()">Sélectionner</button>
			<img id="preview" src="" alt="Image"/>
		</div>
		<div class="div_aside">
		<?php include("generator.php"); ?>
		</div>
	</div>
</form>
<div id="roxyCustomPanel2" style="display: none;">
   <iframe src="<?php echo HOST; ?>fileman2/index.html?integration=custom&type=images&txtFieldId=picture&close=2&url=complete" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>
<script>init();</script>