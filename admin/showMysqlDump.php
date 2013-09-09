<?php
$siteTitle = "Export MySql-Dump";
//include 'header.php';
//include '.privat/conf-pw.php';
$date=date("Ymd");
$filename = "nb".$date.".sql";
$output = shell_exec("mysqldump --opt --user=".$mysqluser." --pass=".$mysqlpasswd." ".$mysqldb." --single-transaction > mysql/".$filename."");

	echo "<div id='indexTitle'>";
		echo "<div class='left'>";
		echo "<strong>";
			echo "MySql-Dump-Export";
		echo "</strong>";
		echo "</div>";

		echo "<div class='center'>";
			echo "<a href='mysql/".$filename."'>DOWNLOAD</a>";
		echo "</div>";
		echo "<div class='right'>";
			echo "";
		echo "</div>";
	echo "</div>";








include 'footer.php'; 
?>
