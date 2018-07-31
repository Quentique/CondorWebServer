<?php require_once('check_connected.php'); ?>
<style>
#er {
	display:none;
}
#form_tab tr:first-child .delete {
	display: none;
}
</style>
<script>
$('document').ready(function () {
	$(".double").clone(true, true).removeClass().appendTo("#form_tab");
	/* Setting date to get coherent values */
	var fullDate = new Date();
	var twoDigitMonth = fullDate.getMonth()+1+"";if(twoDigitMonth.length==1)	twoDigitMonth="0" +twoDigitMonth;
	var twoDigitDate = fullDate.getDate()+"";if(twoDigitDate.length==1)	twoDigitDate="0" +twoDigitDate;
	var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" +twoDigitDate;
	$('.date').val(currentDate);
	$('.dateD').val(currentDate);
	$('select').focus();

	$(".checkbox").change(function() {
		/* Restricting user's entry to get coherent content */
		if(this.checked) {
			$(this).parent().next().next().find("input").prop("disabled", true);
			$(this).parent().next().next().find("input").val($(this).parent().next().find("input").val());
			$(this).parent().next().next().next().find("input").prop("disabled", false);
			$(this).parent().next().next().next().next().find("input").prop("disabled", false);
			$(this).parent().next().next().next().next().next().find("input").prop("disabled", false);
			$(this).parent().next().next().next().next().next().next().find("input").prop("disabled", false);
		} else {
			$(this).parent().next().next().find("input").prop("disabled", false);
			$(this).parent().next().next().next().find("input").prop("disabled", true);
			$(this).parent().next().next().next().next().find("input").prop("disabled", true);
			$(this).parent().next().next().next().next().next().find("input").prop("disabled", true);
			$(this).parent().next().next().next().next().next().next().find("input").prop("disabled", true);
		}
	});
	$(".morning").change(function() { /* Restricting user's entry to get coherent content */
		if (this.checked) {
			$(this).parent().next().next().find("input").val("08:00");
			if (!$(this).parent().next().find("input").prop("checked")) {
				$(this).parent().next().next().next().find("input").val("13:00");
			}
		} else {
			if ($(this).parent().next().find("input").prop("checked")) {
				$(this).parent().next().next().find("input").val("13:00");
			} else {
				$(this).parent().next().next().find("input").val("--:--");
				$(this).parent().next().next().next().find("input").val("--:--");
			}
		}
	});
	$(".end").change(function() { /* Restricting user's entry to get coherent content */
		if(this.checked) {
			$(this).parent().next().next().find("input").val("18:00");
			if (!$(this).parent().prev().find("input").prop("checked")) {
				$(this).parent().next().find("input").val("13:00");
			} 
		} else {
			if ($(this).parent().prev().find("input").prop("checked")) {
				$(this).parent().next().next().find("input").val("13:00");
			} else {
				$(this).parent().next().next().find("input").val("--:--");
				$(this).parent().next().find("input").val("--:--");
			} 
		}
	});
	$(".date").change(function() { /* Restricting user's entry to get coherent content */
		if ($(this).parent().prev().find("input").prop("checked")) {
			$(this).parent().next().find("input").val($(this).val());
		}
	});
	$(document).keydown(function (e) { /* Adding new line (keyboard shortcut) */
        if (e.which === 107){
            $(".double").clone(true, true).removeClass().appendTo("#form_tab");
			$("select").focus();
            e.preventDefault();
        }
    });
	$('.gender').keydown(function (e) { /* Keyboard shorcut to select gender */
		if (e.which === 70){
			$(this).find("option[value='F']").prop('selected', true);
		} else if (e.which === 72) {
			$(this).find("option[value='M']").prop('selected', true);
		} else if (e.which === 88) {
			$(this).find("option[value='X']").prop('selected', true);
		}
	});
	$('form').submit(function(e) { /* Enabling fields so that they're actually transmitted */
		$('input').prop("disabled", false);
		$('input[type="checkbox"]').prop('disabled', true);
	});
	$('.delete_absenceT').on('click', function() { /* Removing row */
		$(this).parent().parent().remove();
	});
});
</script>
<table id="er" class="table">
	<tr class="double">
		<td><select class="gender" name="gender[]">
				<option value="F">Mme</option>
				<option value="M">M.</option>
				<option value="X">Mx</option>
			</select>
		</td>
		<td><input type="text" name="name[]" placeholder="Nom sans le titre" required/></td>
		<td><input class="checkbox" type="checkbox" /></td>
		<td><input class="date" min="<?php echo date("Y-m-d", time() - 5356800);?>" max="<?php echo date("Y-m-d", time() +  31536000);?>" type="date" name="begin[]" required/></td>
		<td><input class="dateD" type="date" min="<?php echo date("Y-m-d");?>" max="<?php echo date("Y-m-d", time() +  31536000);?>" name="end[]" required/></td>
		<td><input class="morning" type="checkbox" name="morning[]" disabled /></td>
		<td><input class="end" type="checkbox" disabled /></td>
		<td><input step="1800" type="time" value="00:00" name="beginH[]" min="08:00" max="18:00" required disabled /></td>
		<td><input step="1800" type="time" value="00:00" name="endH[]" min="08:00" max="18:00" required disabled /></td>
		<td><span class="delete_absenceT">Delete</span></td>
	</tr>
</table>
<p>Pour saisir les absences, indiquer le genre par les touches "H" pour les hommes, "F" pour les femmes ou bien "X" pour les individus de sexe neutre, utiliser la touche M pour naviguer dans les genres. 
Pour faciliter la saisie, déplacer vous dans les champs avec la touche <strong>Tab</strong>. Arriver à la fin de la dernière colonne, un appui sur la touche <strong>+</strong> du pavé numérique recréera une nouvelle ligne pour resaisir une absence.</p>
<form action="handle_teachers.php" method="post" id="absence_form">
	<table id="form_tab" class="table">
		<tr>
			<td>Genre</td>
			<td>Nom</td>
			<td>Absence journalière</td>
			<td>Début</td>
			<td>Fin</td>
			<td>Matin</td>
			<td>Après-midi</td>
			<td>Heure début</td>
			<td>Heure fin</td>
		</tr>
	</table>
	<input type="submit"/>
</form>