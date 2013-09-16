<?php
$siteTitle = "Backup MySql data from notizblogg";
//include 'header.php';
require_once ("../conf/settings.php");
require_once ("../conf/.privat/conf-pw.php");
$date = date("Ymd");
$filename = "nb" . $date . ".sql";

	$tmpPath = split('/notizblogg', SITE_URL);
	$backuppath = "mysql/" . $filename;
	$downloadurl = SITE_URL . "/notizblogg/admin/mysql/" . $filename;

if(!is_dir("mysql")){
	$mkDir = shell_exec("mkdir mysql");
}

$mysqldump = shell_exec("mysqldump --opt --user=".$mysqluser." --pass=".$mysqlpasswd." ".$mysqldb." --single-transaction > ". $backuppath ."");



?>


<!DOCTYPE html>
<html>
<head>
	<link type='text/css' rel='stylesheet' media='screen' href='../common/css/screen.css' />
	<script type='text/javascript' src='../common/jquery/jquery-1.7.2.min.js'></script>
</head>
<body>

<div class="lens">
	<div class='download'>
		<form>
			<p>There is a new backup file (<?php echo $filename; ?>) of your mysql database from notizblogg. Here you can download it.</p><br>
			<a href='<?php echo $downloadurl; ?>' class='button'>DOWNLOAD</a>
			<br>
			<br>
			<hr>
			<p>...or go <a href="../admin.php">back</a></p>
		</form>
	</div>

</div>
<script type="text/javascript">
	$("body").css("overflow", "hidden");
	$(".lens").fadeTo("slow", 1);
	$(".lens").css({"width":$(window).width()+"px", "height":$(window).height()+"px", "padding":"22px", "cursor":"move"});
	$(".lens .set").css({"width":"300px"});
</script>
</body>
</html>
