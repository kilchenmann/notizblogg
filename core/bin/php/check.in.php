<?php
// Session starten
require_once 'settings.php';
$mysqli = condb('open');
$sql = $mysqli->query('SELECT userID, name, user, token FROM user WHERE ' .
    '(user like \'' . $_REQUEST['usr'] . '\') AND ' .
    '(pass = \'' . md5($_REQUEST['key']) . '\')');


if (mysqli_num_rows($sql) > 0)
{
	// write the user data in an array...
	$data = mysqli_fetch_array($sql);
	session_start ();
	// ...and register the session
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
