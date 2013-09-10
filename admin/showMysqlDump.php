<?php
$siteTitle = "Export MySql-Dump";
//include 'header.php';
require_once ("../conf/settings.php");
require_once ("../conf/.privat/conf-pw.php");
$date = date("Ymd");
$filename = "nb" . $date . ".sql";

	$tmpPath = split('/notizblogg', SITE_URL);
	$backuppath = "../conf/mysql/" . $filename;
	$downloadurl = SITE_URL . "/notizblogg/conf/mysql/" . $filename;

//$output = shell_exec("mysqldump --opt --user=".$mysqluser." --pass=".$mysqlpasswd." ".$mysqldb." --single-transaction > ".$filename."");

/*
echo $tmpPath[0];
echo "<br>";
echo $backuppath;
echo "<br>";
echo $downloadurl;
echo "<br>";
$command = "mysqldump --opt --user=".$mysqluser." --pass=".$mysqlpasswd." ".$mysqldb." --single-transaction > ". $backuppath ."";

//$command = "mysqldump --allow-keywords --opt -u ". $mysqluser ." --password=". $mysqlpasswd ." --single-transaction ". $mysqldb ." > ". $backuppath ."";
echo $command;
echo "<br>";
*/
if(is_dir("../conf/mysql"){
	echo "existiert"; 
}
$mkDir = shell_exec
$mysqldump = shell_exec("mysqldump --opt --user=".$mysqluser." --pass=".$mysqlpasswd." ".$mysqldb." --single-transaction > ". $backuppath ."");
	echo "<a href='" . $downloadurl . "'>DOWNLOAD</a>";

//shell_exec("mysqldump --allow-keywords --opt -u luhmann --password=zettelkasten --single-transaction notizblogg > /Library/WebServer/Documents/MEDIA/NOTIZBLOGG/mysql/nb20130910.sql");





//$output = shell_exec("mysqldump " . $mysqldb . " -u " . $mysqluser . " --password=" . $mysqlpasswd . " --single-transaction > " .$path2backup.$filename . "");
//echo "mysqldump " . $mysqldb . " -u " . $mysqluser . " --password=" . $mysqlpasswd . " --single-transaction > " .$path2backup.$filename."";
//echo $output;
//echo "mysqldump " . $mysqldb . " -u " . $mysqluser . " --password=" . $mysqlpasswd . " --single-transaction >" .$path2backup.$filename."";


/*
	echo "<div id='indexTitle'>";
		echo "<div class='left'>";
		echo "<strong>";
			echo "MySql-Dump-Export";
		echo "</strong>";
		echo "</div>";

		echo "<div class='center'>";
			
		echo "</div>";
		echo "<div class='right'>";
			echo "";
		echo "</div>";
	echo "</div>";
*/


?>
