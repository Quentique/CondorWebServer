<?php
require_once('check_connected.php');
function dirTree($dir) {
    $d = dir($dir);
	$arDir = array();
    while (false !== ($entry = $d->read())) {
        if($entry != '.' && $entry != '..' && is_dir($dir.$entry)) {
            $arDir[$entry] = dirTree($dir.$entry.'/');
		}
    }
    $d->close();
    return $arDir;
}
function dirToArray($dir) { 
   
   $result = array(); 

   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 
         } 
         else 
         { 
            $result[] = $value; 
         } 
      } 
   } 
   
   return $result; 
} 

function printTree($array, $level=0, $before="") {
    foreach($array as $key => $value) {
        ?>
		
		<?php
        if(is_array($value)) {
            printTree($value, $level+1, $before.$key.'/');
		} else if ($value != ".htaccess") {?>
			<option value="<?php echo $before.$value;?>"><?php echo $value; ?></option>
		<?php
		}
    }
}
$dir = "./logs/";
$array = dirToArray($dir);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$('document').ready(function() {
$('#log_file').change(function() {
	$('#frame_log').attr('src', './logs/'+$(this).val());
});
});
</script>
<select name="log_file" id="log_file">
<?php printTree($array); ?>
</select><br/><br/>
<iframe src="" id="frame_log" style="width: 100%; height: 300px"></iframe>