<?php
require_once('check_connected.php');
require_once('perm_constants.php');
$rights = (int)$_SESSION['rights']; 
?>
<nav>
<ul>
<li><a href="" id="home">ACCUEIL</a></li>
<li><a href="" id="posts">BILLETS</a>
	<ul>
		<li><a href="" id="posts_add">Ajouter</a></li>
		<li><a href="" id="posts_draft">Brouillons</a></li>
		<li><a href="" id="posts_deleted">Supprimés</a></li>
	</ul>
</li>
<?php if (($rights & ABSENCES_PERM) || ($rights & ADMIN_PERM)) { ?>
<!--<li><a href="" id="absences">ABSENCES</a><ul><li><a href="" id="absences_add">Ajouter</a></li><li><a href="" id="absences_deleted">Supprimées</a></li></ul></li>-->
<li><a href="" id="events">ÉVÈNEMENTS</a><ul><li><a href="" id="events_add">Ajouter</a></li></ul></li>
<?php } if ($rights & (CANTEEN_PERM | ADMIN_PERM)) { ?>
<li><a href="" id="canteen">CANTINE</a></li>
<?php } if ($rights & ADMIN_PERM) { ?>
<li><a href="" id="categories">CATÉGORIES</a></li>
<li><a href="" id="general">PARAMÈTRES</a><ul><li><a href="" id="logs">Journaux</a></li><li><a href="" id="cvl">CVL</a></li><li><a href="" id="social_networks">Réseaux sociaux</a></li><li><a href="" id="top_secret2" style="color: red; text-shadow: 1px -1px #ffa100;">CLIENTS</a></li><li><a href="" id="top_secret" style="color: #fff; background: #f00;">URGENCE</a></li></ul></li>
<li><a href="" id="maps">CARTES</a><ul><li><a href="" id="maps_add">Ajouter</a></li></ul></li>
<li><a href="" id="users">UTILISATEURS</a><ul><li><a href="" id="users_add">Ajouter</a></li></ul></li>
<?php } ?>
</nav>