<?php
// Session starten
require_once 'setting.php';
condb('open');

$sql = "SELECT uid, name, token FROM user WHERE ".
    "(name like '".$_REQUEST["usr"]."') AND ".
    "(pass = '".md5 ($_REQUEST["key"])."')";
$result = mysql_query($sql);

if (mysql_num_rows($result) > 0)
{
	// Benutzerdaten in ein Array auslesen.
	$data = mysql_fetch_array($result);

	session_start ();

	// Sessionvariablen erstellen und registrieren
	$_SESSION["token"] = $data["token"] . '-' . $data["uid"];
	$_SESSION["user"] = $data["name"];
//  $pathLogin = $_POST['path'];
	header ('Location: ' . __SITE_URL__ . '/index.php');
}
else
{
	$_SESSION["token"] = '';
	$_SESSION["user"] = '';
	header ('Location: ' . __SITE_URL__ . '/index.php?access=denied');
}

condb('close');
