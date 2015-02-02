<?php
// Session starten
require_once 'settings.php';
$mysqli = condb('open');
$sql = $mysqli->query('SELECT userID, name, user, token FROM user WHERE ' .
    '(user LIKE \'' . $_POST['usr'] . '\') AND ' .
    '(pass = \'' . md5($_POST['key']) . '\')');
condb('close');

if (mysqli_num_rows($sql) > 0)
{
	header ('Location: ' . $_POST['uri'] . '');
	// write the user data in an array...
	$data = mysqli_fetch_array($sql);
	session_start ();
	// ...and register the session
	$_SESSION["token"] = $data["token"] . '/' . $data["userID"];
	$_SESSION["user"] = $data["user"];
//  $pathLogin = $_POST['path'];
}
else
{
	$_SESSION["token"] = '';
	$_SESSION["user"] = '';
	header ('Location: ' . __SITE_URL__ . '/?access=denied');
}
