<!-- Preview of application rendering -->
<style>
@import url('https://fonts.googleapis.com/css?family=Noto+Sans');
h1 {
	font-family: "Noto Sans", arial, sans-serif;
	font-size: 3em;
	font-weight: bold;
	margin: 0;
}</style>
<?php require_once('db_constants.php');?>
<div id="hello" style="background: url('<?php echo HOST . "uploads/". $_GET['cover'];?>') no-repeat; background-position: center; background-size: auto; text-align: center; height: 500px;">
<img src="<?php echo HOST . "uploads/" .$_GET['logo'];?>" style="vertical-align: top; margin-top: 12px; width: 200px; height: 200px;"/>
<h1 style="padding-bottom: 100px; text-shadow: 1px 1px 1px #000; color: <?php echo "#". $_GET['color'];?>">Test</h1>
</div>