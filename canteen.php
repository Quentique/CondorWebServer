<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
function openCustomRoxy2(){
  $('#roxyCustomPanel2').dialog({modal:true, width:875,height:600});
}
function closeCustomRoxy2() {
  $('#roxyCustomPanel2').dialog('close');
  $('#link').trigger("change");
}
</script>
<?php
require_once('check_connected.php');
require_once('db_constants.php');
$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
if (isset($_POST['old_link'])) {
	if ($_POST['old_link'] != $_POST['link']) {
		$request = $bdd->prepare("UPDATE " . TABLE_GENERAL . " SET value = :value WHERE name = 'canteen'");
		$request->bindParam(":value", $_POST['link']);
		$request->execute();
		$bdd->query('UPDATE ' . TABLE_GENERAL . ' SET value = CURRENT_TIMESTAMP WHERE name = "canteen_updated"');
				require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
		$logger->log('', 'general', 'Updating canteen file by user ' . $_SESSION['name'], Logger::GRAN_VOID);
		require('request.php');
		doRequest();
	} /*Updating after submuit */
}
$request = $bdd->query("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'canteen'");
$row = $request->fetch();
?>
<form method="post" id="canteen_form">
<input type="hidden" name="old_link" value="<?php echo $row['value']; ?>">
<input required type="text" id="link" name="link" value="<?php echo $row['value']; ?>"><button type="button" onclick="openCustomRoxy2()">Sélectionner</button>
<input type="submit">
</form> <!-- Using Google PDF viewer -->
<iframe src="https://docs.google.com/viewer?url=<?php echo HOST ."uploads/". $row['value']; ?>&embedded=true" style="width:100%; height:500px;" frameborder="0"></iframe>
<div id="roxyCustomPanel2" style="display: none;">
  <iframe src="<?php echo HOST; ?>fileman2/index.html?integration=custom&type=pdf&txtFieldId=link&close=2&url=short" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>