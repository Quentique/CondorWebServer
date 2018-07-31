<script src="./ckeditor/ckeditor.js"></script>
<?php
require('check_connected.php');
require_once('perm_constants.php');

if ((int)$_SESSION['rights'] & ADMIN_PERM) {
?>
<script>
	$('#button_posts').click(function(e) {
			if(confirm('Êtes-vous sûr·e de vouloir effectuer cette commande ?')) {
				$.get('request2.php?name=deletePosts').done(function(e) {
					alert('Commande effectuée');
				});
			}
	});
	$('#button_sync').click(function(e) {
				$.get('request2.php?name=sync').done(function(e) {
					alert('Commande effectuée');
				});
	});
	$('#button_events').click(function(e) {
		if(confirm('Êtes-vous sûr·e de vouloir effectuer cette commande ?')) {
			$.get('request2.php?name=deleteEvents').done(function(e) {
					alert('Commande effectuée');
				});
		}
	});
	$('#button_maps').click(function(e) {
		if(confirm('Êtes-vous sûr·e de vouloir effectuer cette commande ?')) {
			$.get('request2.php?name=deleteMaps').done(function(e) {
					alert('Commande effectuée');
				});
		}
	});
	$('#delete_ele').click(function(e) {
		if(confirm('Êtes-vous sûr·e de vouloir effectuer cette commande ?')) {
			$.get('request2.php?name=purge').done(function(e) {
					alert('Commande effectuée');
				});
		}
	});
</script>
<h1>Actions externes</h1>
<h4>Vous êtes actuellement dans une zone extrêmement sensible de l'administration</h4>
<p style="font-style: italic;">Cette zone vous permet de supprimer à distance certaines données de toutes les applications Condor clientes. Ces actions ne doivent être utilisés qu'à des fins de maintenance ou d'urgence (diffusion de contenu illicte par exemple).</p>
<hr/>
<table>
<tr ><td>SYNC</td><td>Inviter les utilisateurs à se synchroniser (sync manuel)</td><td><button type="button" id="button_sync">ENVOYER</button></td></tr>
<tr><td>BILLETS</td><td>Supprime l'ensemble des billets</td><td><button type="button" id="button_posts">SUPPRIMER</td></tr>
<tr><td>ÉVÈNEMENTS</td><td>Supprime l'ensemble des évènements</td><td><button type="button" id="button_events">SUPPRIMER</td></tr>
<tr><td>CARTES</td><td>Supprime l'ensemble des emplacements sur les cartes</td><td><button type="button" id="button_maps">SUPPRIMER</td></tr>
<tr ><td>GÉNÉRAL</td><td>Supprime toutes les données et les fichiers téléchargés</td><td style="font-weight:bold;"><button type="button" id="delete_ele"style="background:red;"><strong>SUPPRIMER</strong></button></td></tr>
</table>
<?php
}
?>
