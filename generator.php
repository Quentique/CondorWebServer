<h3>Génératerur de liens internes</h3>
<table><script>
$('document').ready(function() {
	$('#valid_gen').click(function(e) {
		e.preventDefault();
		if($('#select_gen').val() != "maps") {
			if(!/^\d+$/.test($('#id_gen').val())) {
				alert('Vous devez entrer une donnée numérique !');
			} else {
				$('#result_gen').val('https://app.cvlcondorcet.fr/'+$('#select_gen').val()+'/'+$('#id_gen').val());				
			}
		} else if ($('#id_gen').val() != "") {
			$('#result_gen').val('https://app.cvlcondorcet.fr/'+$('#select_gen').val()+'/'+$('#id_gen').val());
		} else {
			$('#result_gen').val("");
		}
	});
	$('#help').click(function() {
		window.open('https://cvlcondorcet.fr/liens.pdf');
	});
});
</script><tr><td><select id="select_gen">
	<option value="events">Évènement</option>
	<option value="posts">Billet</option>
	<option value="maps">Lieu</option>
</select></td><td>
<input type="text" id="id_gen"/></td>
</tr>
<tr><td><button type="button" id="valid_gen">Générer</button><button type="button" id="help">?</button></td><td><input style="width: 100%" type="url" readOnly id="result_gen"/></td></tr></table>