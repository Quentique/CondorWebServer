<?php 
require('check_connected.php');
require_once('db_constants.php');
require_once('perm_constants.php');

if ((int)$_SESSION['rights'] & ADMIN_PERM) { /*Checking perm */
	
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	if (isset($_POST['formular'])) {
		$data = "";
		require 'lib/Logger.class.php';
		$logger = new Logger('./logs');
		if (isset($_POST['check'])) {
			$logger->log('', 'general', 'WARNING - EMERGENCY STATUS ACTIVATED by user ' . $_SESSION['name'], Logger::GRAN_VOID);
			$data = "EMERGENCY";
		} else {
			$data = "NORMAL";
			$logger->log('', 'general', 'WARNING - EMERGENCY STATUS desactivated by user ' . $_SESSION['name'], Logger::GRAN_VOID);
		} /*Updating setting after form submit */
		$bdd->query('UPDATE '.TABLE_GENERAL.' SET value = "'.$data.'" WHERE name = "emergency"' );
	}
	$request = $bdd->query('SELECT * FROM '.TABLE_GENERAL.' WHERE name = "emergency" ');
	if ($request->rowCount() == 1) {
		$row = $request->fetch();
	} else {
		$row = array('value' => 'NORMAL');
	} /* Displaying saved value */
	?>
	<h1>Configuration Externe de Condor</h1>
	<h4>Vous êtes actuellement dans une zone extrêmement sensible de l'administration</h4>
	<?php
	if ($row['value'] == 'NORMAL') {
	?>
	<h2 style="color: #090; font-weight: 900;">La synchronisation est autorisée</h2>
	<p>Les applications clientes peuvent actuellement se synchroniser sur les données serveurs. Tout est dans son état normal.</p>
	<?php
	} else {
		?>
		<h2 style="color: #f00; font-weight: 900;">La synchronisation est désactivée</h2>
		<p>Les applications clientes ne peuvent se synchroniser sur les données serveurs. Toute synchronisation est refusée.</p>
		<?php
	}
	?>
	<form method="post" id="emergency_form">
	<?php if ($row['value'] == 'NORMAL'){
		?><label for="check" style="color: #fff; background: #f00;">En cochant cette case, vous plongez Condor en état d'urgence et <u>désactiver <em>toute</em> synchronisation cliente</u>.</label><?php
	} else { ?>
	<label for="check" style="color:#fff; background: #090;">En décochant cette case, vous ré-activez les synchronisations clientes. Soyez sûr d'avoir régler <em>tout problème</em> avant de <u>remettre Condor en service.</u></label><?php
	}
	?>
	<input type="hidden" name="formular" value="test">
	<input type="checkbox" <?php if ($row['value'] !='NORMAL') echo 'checked'; ?> name="check"><br/>
	<input type="submit">
	</form>
	<?php
	
} else {
?>
<h1>Accès strictement interdit</h1>
<?php
}