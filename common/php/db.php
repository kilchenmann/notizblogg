<?php
/* ************************************************************** 
 * Connect to MySQL-Database and disconnect it
 * ************************************************************** 
 */
 
function connect(){
	include (SITE_PATH."/conf/.privat/conf-pw.php");
	$con = mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
		if (!$con){
			die('MySQL-Access denied:' . mysql_error());
		} else {
			mysql_select_db($mysqldb, $con) or die ("The database '".$mysqldb."' doesn't exists.");
		}
}

function disconnect(){
	include (SITE_PATH."/conf/.privat/conf-pw.php");
	$con = mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
	mysql_close($con);
}

?>
