<?php
// Session starten
require_once 'setting.php';
condb('open');

$sql = "SELECT userID, name, user, token FROM user WHERE ".
    "(user like '".$_REQUEST["usr"]."') AND ".
    "(pass = '".md5 ($_REQUEST["key"])."')";
$result = mysql_query($sql);

if (mysql_num_rows($result) > 0)
{
	// Benutzerdaten in ein Array auslesen.
	$data = mysql_fetch_array($result);

	session_start ();

	// Sessionvariablen erstellen und registrieren
	$_SESSION["token"] = $data["token"] . '-' . $data["userID"];
	$_SESSION["user"] = $data["user"];
//  $pathLogin = $_POST['path'];
	header ('Location: ' . __SITE_URL__ . '/');
}
else
{
	$_SESSION["token"] = '';
	$_SESSION["user"] = '';
	header ('Location: ' . __SITE_URL__ . '/?access=denied');
}

condb('close');
