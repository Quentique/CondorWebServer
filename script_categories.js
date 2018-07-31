	$(document).on('click','.modify_cat', function(e) {
		e.preventDefault();
		var d = $(this).parent().next().next().text();
		$(this).parent().next().next().text("");
		$(this).parent().next().next().append('<input type="text" name="value[]" value="'+d+'">');
	});
	
	$(document).on('click',"#add_cat", function() { /*Dynamically adding row */
		$('table').append('<tr><td></td><td><input class="new" type="text" name="id[]"></td><td><input type="text" name="value[]"></td></tr>');
	});
	
	$(document).on('keyup', '.new', function() {
		/* Checking if category id is correct for db entry */
		var e = /^([a-z]|_|-)+$/;
		if (!e.test($(this).val())) {
			$(this).effect('shake');
			$(this).effect('highlight', {color:"#ff0000f0"}, 1000);
		}
	});