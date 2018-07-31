<?php require_once('check_connected.php'); ?>

<script>
$('document').ready(function() {
	$('a[href="modify"]').on('click', function(e) {
		e.preventDefault();
		/* Changing hard-coded values into editable values in inputs */
		var gender = $(this).parent().next().text();
		var name = $(this).parent().next().next().text();
		var begin = $(this).parent().next().next().next().text();
		var end = $(this).parent().next().next().next().next().text();
		$(this).css('display', 'none');
		$(this).parent().parent().find('span').css('display', 'none');
		$(this).parent().next().append('<select name="gender[]"><option value="F">Mme</option><option value="M">M.</option><option value="X">Mx</option></select>');
		$(this).parent().next().next().append('<input type="text" name="name[]" placeholder="Nom sans le titre" required/>');
		$(this).parent().next().next().next().append('<input class="date" min="<?php echo date("Y-m-d", time() - 5356800);?>" max="<?php echo date("Y-m-d", time() + 31536000);?>" type="date" name="begin[]" required/><input step="1800" type="time" value="00:00" name="beginH[]" min="08:00" max="18:00" required/>');
		$(this).parent().next().next().next().next().append('<input class="dateD" type="date" min="<?php echo date("Y-m-d", time()-5356800);?>" max="<?php echo date("Y-m-d", time() + 31536000);?>" name="end[]" required/><input step="1800" type="time" value="00:00" name="endH[]" min="08:00" max="18:00" required />');
		$(this).parent().next().find('option[value="'+gender+'"]').prop('selected', true);
		$(this).parent().next().find('input').prop("disabled", false);
		$(this).parent().next().next().find('input').val(name);
		$(this).parent().next().next().next().find('input[type="date"]').val(begin.substr(0, 10));
		$(this).parent().next().next().next().find('input[type="time"]').val(begin.substr(11, 19));
		$(this).parent().next().next().next().next().find('input[type="date"]').val(end.substr(0, 10));
		$(this).parent().next().next().next().next().find('input[type="time"]').val(end.substr(11, 19));
		$(this).parent().next().next().next().find('input[type="date"]').trigger("change");
	});
	
	
	$('input[type="date"]').change(function() {
		/* Restriction for data entering */
		console.log("hello");
		var res = $(this).parent().parent().find('input[type="date"]');
		if (res[0].value !== res[1].value) {
			$(this).parent().parent().find('input[type="time"]').prop("disabled", true);
			$(this).parent().parent().find('input[type="time"]').val("00:00");
		} else {
			$(this).parent().parent().find('input[type="time"]').prop("disabled", false);
		}
	});
	$('#profs-display').on('change', 'input[type="date"]', function() {
			/* Restriction for data entering */
		var res = $(this).parent().parent().find('input[type="date"]');
		if (res[0].value !== res[1].value) {
			$(this).parent().parent().find('input[type="time"]').prop("disabled", true);
			$(this).parent().parent().find('input[type="time"]').val("00:00");
		} else {
			$(this).parent().parent().find('input[type="time"]').prop("disabled", false);
		}
});

$('form').submit(function(e) { /* Enabling fields in order that they are actually transmitted */
	$('input[type="time"]').prop('disabled', false);
});
});

</script>
<form method="post" action="handle_teachers.php" id="absence_form">
<input type="hidden" name="modify" value="true"/>
<table id="profs-display" class="table">
<tr>
<th>Action</th>
<th>Genre</th>
<th>Nom</th>
<th>Début</th>
<th>Fin</th>
</tr>
<?php 
	require_once('db_constants.php');
	try {
			$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
			$e = (isset($_GET['deleted']) && $_GET['deleted'] == 1) ? 1 : 0;
			$stmt = $bdd->prepare("SELECT * FROM ". TABLE_TEACHERS . " WHERE deleted = ? AND date_end > CURRENT_TIMESTAMP");
			/* Displaying pre-existing absences */
			if ($stmt->execute(array($e))) {
				while ($row = $stmt->fetch()) {
					?>
					<tr>
					<td><?php if(isset($_GET['deleted'])) { echo '<a href="change.php?id='.$row['id'].'&state=0" class="delete_absence">Restaurer</a>'; }else{ echo '<a href="modify" >Modifier</a> <a href="change.php?id='. $row['id'].'&state=1" class="delete_absence">Supprimer</a>';}?></td>
					<td><span><?php echo $row['title']; ?></span><input type="hidden" name="id[]" value="<?php echo $row['id']; ?>" disabled /></td>
					<td><span><?php echo $row['name']; ?></span></td>
					<td><span><?php echo $row['date_begin']; ?></span></td>
					<td><span><?php echo $row['date_end']; ?></span></td>
					</tr>
					<?php
				}				
			}
	} catch (Exception $e) {
		echo 'Erreur : ' . $e->getMessage();
	}
?>
</table>
<input type="submit"/>
</form>