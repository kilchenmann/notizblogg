<script type="text/javascript">
	alert("check.in wurde ausgeführt");

</script>
<?php
// Session starten
require_once 'setting.php';
condb('open');

$sql = "SELECT uid, name FROM user WHERE ".
    "(name like '".$_REQUEST["usr"]."') AND ".
    "(pass = '".md5 ($_REQUEST["key"])."')";
$result = mysql_query($sql);

if (mysql_num_rows($result) > 0)
{
	// Benutzerdaten in ein Array auslesen.
	$data = mysql_fetch_array($result);

	session_start ();

	// Sessionvariablen erstellen und registrieren
	$_SESSION["user_id"] = $data["uid"];
	$_SESSION["user_name"] = $data["name"];
//  $pathLogin = $_POST['path'];
	header ('Location: ' . __SITE_URL__ . '/' . __BASE_FOLDER__ . '/admin.php');
}
else
{
	$_SESSION["user_id"] = '';
	$_SESSION["user_name"] = '';
	header ('Location: ' . __SITE_URL__ . '/' . __BASE_FOLDER__ . '/index.php?access=denied');
}

condb('close');
