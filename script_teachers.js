$(document).on('click','a[href="modify"]', function(e) {
	/* Transforms hard-coded values into editable values in input fields */
		e.preventDefault();
		var gender = $(this).parent().next().text();
		var name = $(this).parent().next().next().text();
		var begin = $(this).parent().next().next().next().text();
		var end = $(this).parent().next().next().next().next().text();
		$(this).css('display', 'none');
		$(this).parent().parent().find('span').css('display', 'none');
		$(this).parent().next().append('<select class="gender" name="gender[]"><option value="F">Mme</option><option value="M">M.</option><option value="X">Mx</option></select>');
		$(this).parent().next().next().append('<input type="text" name="name[]" placeholder="Nom sans le titre" required/>');
		$(this).parent().next().next().next().append('<input class="date" min="<?php echo date("Y-m-d", time() - 5356800);?>" max="<?php echo date("Y-m-d", time() +  31536000);?>" type="date" name="begin[]" required/><input step="1800" type="time" value="00:00" name="beginH[]" min="08:00" max="18:00" required/>');
		$(this).parent().next().next().next().next().append('<input class="dateD" type="date" min="<?php echo date("Y-m-d");?>" max="<?php echo date("Y-m-d", time() +  31536000);?>" name="end[]" required/><input step="1800" type="time" value="00:00" name="endH[]" min="08:00" max="18:00" required />');
		$(this).parent().next().find('option[value="'+gender+'"]').prop('selected', true);
		$(this).parent().next().find('input').prop("disabled", false);
		$(this).parent().next().next().find('input').val(name);
		$(this).parent().next().next().next().find('input[type="date"]').val(begin.substr(0, 10));
		$(this).parent().next().next().next().find('input[type="time"]').val(begin.substr(11, 19));
		$(this).parent().next().next().next().next().find('input[type="date"]').val(end.substr(0, 10));
		$(this).parent().next().next().next().next().find('input[type="time"]').val(end.substr(11, 19));
		$(this).parent().next().next().next().find('input[type="date"]').trigger("change");
	});
	
	
	$(document).on('change', 'input[type="date"]', function() {
		console.log("hello");
		/* Restriction to get coherent values */ 
		var res = $(this).parent().parent().find('input[type="date"]');
		if (res[0].value !== res[1].value) {
			$(this).parent().parent().find('input[type="time"]').prop("disabled", true);
			$(this).parent().parent().find('input[type="time"]').val("00:00");
		} else {
			$(this).parent().parent().find('input[type="time"]').prop("disabled", false);
		}
	});
	/*$('#profs-display').on('change', 'input[type="date"]', function() {
			console.log("hello2");
		var res = $(this).parent().parent().find('input[type="date"]');
		if (res[0].value !== res[1].value) {
			$(this).parent().parent().find('input[type="time"]').prop("disabled", true);
			$(this).parent().parent().find('input[type="time"]').val("00:00");
		} else {
			$(this).parent().parent().find('input[type="time"]').prop("disabled", false);
		}
});*/