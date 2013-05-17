<?php
/* ************************************************************** 
 * Connect to MySQL-Database and disconnect it
 * ************************************************************** 
 */
global $con;
 
 
function connect(){
	include (SITE_ROOT."/common/.privat/conf-pw.php");
	$con = mysql_connect($mysqlhost, $mysqluser, $mysqlpasswd);
		if (!$con){
			die('MySQL-Access denied:' . mysql_error());
		} else {
			mysql_select_db($mysqldb, $con) or die ("The database '".$mysqldb."' doesn't exists.");
		}
}

function disconnect(){
	mysql_close($con);
}

?>
