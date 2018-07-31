<?php
require('check_connected.php');
if (isset($_POST['name'])) {
	require('db_constants.php');
	$unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', ' '=>'_');
	$str = $_POST['name'];
	$id = strtolower($str);
	$id = strtr( $id, $unwanted_array );
	$bdd = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USERNAME, DB_PASSWORD);
	$request = $bdd->prepare("SELECT * FROM " . TABLE_GENERAL . " WHERE name = 'categories'");
	$request->execute();
	$row = $request->fetch();
	$table = json_decode($row['value'], true);
	if (array_key_exists($id, $table)) {
		echo 'ERROR';
	} else {
		$table[$id] = $str;
		$tablePush = json_encode($table);
		$request = $bdd->prepare("UPDATE " . TABLE_GENERAL . " SET value = :value WHERE name ='categories'");
		$request->bindParam(':value', $tablePush);
		if($request->execute()) {
			echo '{"name":"'.$id.'"}';
		} else {
			echo 'ERROR';
		}
	}
} else {
	echo 'NO VARIABLE';
}
?>