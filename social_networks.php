<?php require('check_connected.php');
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
$request = $bdd->prepare("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'social_networks'");
?>
	 <style>
	#sortable { list-style-type: none; margin: 0; padding: 0; width: 450px; }
	#sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 100px; word-wrap: break-word; text-align: center; background-size: contain; background-repeat: no-repeat;}
	#sortable li:hover {cursor: pointer; }
	#content-list {
		width: 80%;
		min-height: 120px;
		margin: 10px;
		padding: 10px;
		background-color: #fff9ff;
		border: 2px dashed #000000;
	}
	#content-list:after {
		content: "";
		display: table;
		clear: both;
	}
	[disabled] { 
		color:#933;
		background-color:#ffc;
	}
	#form_social {
		margin: 20px;
		border: 1px solid black;
		border-radius: 5px;
		padding: 0 15px;
		padding-bottom: 15px;
		max-width: 500px;
	}
	.selected {
		border: 2px solid black;
	}
	label {
		margin-right: 5px;
	}
	#picture {
		margin-right: 5px;
	}
  </style>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script>
  $(function() {
		$('#utils #delete, #modify').prop('disabled', true);
		$( "#sortable" ).sortable();
		$( "#sortable" ).disableSelection();
		$(document).on('click', '#sortable li', function() {
		  $('#sortable li').removeClass('selected');
		  $(this).addClass('selected');
		  $("#modify, #delete").prop('disabled', false);
		});
		$('#form_social').hide();
		$('#add').click(function() {
		  $('#addmod').val('add');
		  $('#form_social').slideDown();
		  $('#name').prop('disabled', false);
		  $('#soc_form')[0].reset();
		});
		$('#modify').click(function() {
			$('#name').val($('.selected').attr('data-name'));
			$('#name').prop('disabled', true);
			$('#url').val($('.selected').attr('data-href'));
			$('#picture').val($('.selected').attr('data-src'));
			$('#form_social').slideDown();
			$('#addmod').val("mod");
		});
		$('#delete').click(function() {
			$('.selected').remove();
			$('#soc_form')[0].reset();
			$('#form_social').slideUp();
			$("#modify, #delete").prop('disabled', true);
		});
		function escapeHtml(unsafe) {
		return unsafe
         .replace(/&/g, "\\&");
		}
		$('#save').click(function() {
			named = $('#sortable').sortable("toArray", {attribute: 'data-name'});
			url = $('#sortable').sortable("toArray", {attribute: 'data-href'});
			picture = $('#sortable').sortable("toArray", {attribute: 'data-src'});
			var array="[";
			for (i = 0; i < url.length; i++) { 
				array += "{\"name\": \""+named[i]+"\", \"link\": \""+url[i]+"\", \"image\": \""+picture[i]+"\"},";
			}
			array = array.slice(0, -1);
			array += "]";
			$.ajax({
				url: 'handle_soc.php',
				type: 'POST',
				data: "data="+encodeURIComponent(array),
				success: function(data, status) {
					$('#social_networks')[0].click();
				}
			});
		});
		$('#soc_form').off("submit").on('submit', function(e) {
			e.preventDefault();
			/*$.post('handle_soc.php', $(this).serialize()).done(function(data) {
				$('#social_networks')[0].click();
			});*/
			if($('#addmod').val() == 'add') {
				$('#sortable').append('	<li data-name="'+$('#name').val()+'" data-href="'+$('#url').val()+'" data-src="'+$('#picture').val()+'" style="background-image: url(\'<?php echo HOST.'uploads/'; ?>'+$('#picture').val()+'\');">'+$('#name').val()+'</li>');
				$('#form_social').slideUp();
				$('#soc_form')[0].reset();
			} else {
				var to_add = $('li[data-name="'+$('.selected').attr('data-name')+'"]');
				$(to_add).attr('data-href',$('#url').val());
				$(to_add).attr('data-src',$('#picture').val());
				$('#soc_form')[0].reset();
				$('#form_social').slideUp();
				$("#modify, #delete").prop('disabled', true);
			}
		});
	});
  	function openCustomRoxy2(){ /* These functions are used for RoxyFileman, file-manager */
		$('#roxyCustomPanel2').dialog({modal:true, width:875,height:600});
	}
	function closeCustomRoxy2(){
		$('#roxyCustomPanel2').dialog('close');
	}
  </script>
  <?php
if ($request->execute()) {
	$row = $request->fetch();
	$table = json_decode($row['value'], true);
	if ($table == null) { $table = array(); }
	?>
<button type="button" id="save">Sauvegarder</button>
  <div id="content-list">
	<ul id="sortable"><?php
	foreach ($table as $key=>$value) {
		
		?>
		<li data-name="<?php echo $value['name']; ?>" data-href="<?php echo $value['link']; ?>" data-src="<?php echo $value['image']; ?>" style="background-image: url('<?php echo HOST.'uploads/'.$value['image']; ?>');"><?php echo $value['name']; ?></li> 
	<?php
	}
?>
</ul>
</div>
<span id="utils"><button type="button" id="delete">Supprimer</button><button type="button" id="add">Ajouter</button><button type="button" id="modify">Modifier</button></span>
<div id="form_social">
<h2>Édition / Ajout</h2>
<form id="soc_form">
<table>
<input type="hidden" id="addmod" value="add"/>
<tr><td><label for="name">Nom : </label></td><td><input required type="text" id="name" name="name"/></td></tr>
<tr><td><label for="url">Lien :</label></td><td><input required type="text" id="url" name="url"/> </td></tr>
<tr><td><label for="picture">Photo (format carré) :</label></td><td><input required type="text" id="picture" name="picture"/><button type="button" onclick="openCustomRoxy2()">Choisir</button></td></tr>
<tr><td><button type="submit" id="submit_social">Envoyer</button></td></tr></table></form>
</div>
<div id="roxyCustomPanel2" style="display: none;"><!-- Filemanager -->
  <iframe src="<?php echo HOST; ?>fileman2/index.html?integration=custom&type=images&txtFieldId=picture&close=2&url=short" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>
<?php } ?>