<script src="./ckeditor/ckeditor.js"></script>
<?php
require_once('check_connected.php');
$file = @file_get_contents("uploads/cvl.html");
?>
<script>
$('document').ready(function() {
	var roxyFileman = 'https://cvlcondorcet.fr/fileman/index.html';
	CKEDITOR.replace("content2", {filebrowserBrowseUrl:roxyFileman,
                                filebrowserImageBrowseUrl:roxyFileman+'?type=image',
                                removeDialogTabs: 'link:upload;image:upload'});
});
</script>
<form id="form_cvl" method="post">
<textarea id="content2" name="cvl_content"><?php if ($file !== false) { echo $file; }?></textarea>
<input type="submit">
</form>

