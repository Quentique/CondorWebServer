<?php require('check_connected.php'); 
require_once('db_constants.php');
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	$request = $bdd->query('SELECT * FROM '.TABLE_GENERAL.' WHERE name = "emergency" ');
	if ($request->rowCount() == 1) {
		$row = $request->fetch();
	} else {
		$row = array('value' => 'NORMAL');
	}?>
<h2>Gestion de Condor - 
<?php
if ($row['value'] == 'NORMAL') {
	?>
	<span style="color: #090; font-weight: 900;">ONLINE</span>
	<?php
} else {
	?><span style="color: red; font-weight: 900;">OFFLINE</span>
	<?php
}
?></h2>
<p>Bonjour, vous êtes actuellement connecté·e à l'interface de gestion de l'application mobile de la MDL du Lycée Condorcet, Condor.</p>
<p>Nous vous rappelons que toute modification effectuée sur ce site sera synchronisée, dès que vous l'aurez validée, sur les smartphones
possèdant l'application. Ainsi, la plus grande prudence quant à vos interventions vous est demandée.</p>
<p>Rafraîchir les pages (notamment par F5), vous fera perdre les données en cours de saisie.</p>
<p>Pour tout problème, envoyez un mail à l'administrateur : <a href="mailto:cvl.condorcet@gmail.com">cvl.condorcet@gmail.com</a></p>
<div style="font-family: 'Courier New', Courier">
<style>
td {
	padding-right: 10px;
}
</style>
<table>
<tr><th colspan="2" style="text-align: left;" >Journal des modifications</th></tr>
<tr><td>21/02/18</td><td>Ajout de l'état "Brouillon" pour les événements</td></tr>
<tr><td>18/02/18</td><td>Publication des <a href="https://cvlcondorcet.fr/procedures_condor.pdf" target="_blank">procédures de sécurité</a></td></tr>
<tr><td>18/02/18</td><td>Purge de la base de données  | Fin du débug, entrée en production </td></tr>
<tr><td>18/02/18</td><td>Changement accès site : <u>https://cvlcondorcet.fr<strong>/condo</strong></u>, l'ancienne adresse mène aux CGU.</td></tr>
<tr><td>17/02/18</td><td>Sécurisation du serveur production</td></tr>
<tr><td>16/02/18</td><td>Modification de l'interface évènement</td></tr>
<tr><td>15/02/18</td><td>Rajout du générateur de lien ; <a href="https://cvlcondorcet.fr/liens.pdf" target="_blank">infos et aide ici</a></td></tr>
</table>
</div>
