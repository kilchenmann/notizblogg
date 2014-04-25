<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 25/04/14
 * Time: 13:30
 */


global $access;
$access = 'denied';			// default: denied

/* path, file, url ---------------------------------------------------------- */



/*
global $pathInfo, $mainFile, $protocol, $access;

$pathInfo = pathinfo($_SERVER['SCRIPT_FILENAME']);
$mainFile = $pathInfo['filename'].'.'.$pathInfo['extension'];
define ('MainFile', $pathInfo['filename'].'.'.$pathInfo['extension']);
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
define ('SITE_URL', $protocol.$_SERVER['HTTP_HOST']);
// e.g. SITE_URL = http://milchkannen.ch.ch
// echo "SITE_URL: ".SITE_URL."<br>";
define ('BASE_FOLDER', '');	// nicht immer nötig
// e.g. BASE_FOLDER = notizblogg/
// echo "BASE_FOLDER: ".BASE_FOLDER."<br>";

define ('MEDIA_URL', SITE_URL.'/media');
// http://iml-pluto.iml.unibas.ch/MEDIA/NOTIZBLOGG
//echo "MEDIA_FOLDER: ".MEDIA_FOLDER."<br>";
define ('SITE_PATH', $pathInfo['dirname']);
// /Library/WebServer/Documents/notizblogg
//echo "SITE_PATH: ".SITE_PATH."<br>";
define ('DOWNLOAD_URL', SITE_URL . '/export');
*/
function db($open_or_close) {
	require ('.conf/db.php');
	$con = mysql_connect($myhost, $myuser, $mypass);
	if (!$con){
		die('MySQL-Access denied:' . mysql_error());
	} else {
		if ($open_or_close === 1) {
			mysql_select_db($mydb, $con) or die ('The database \''.$mydb.'\' doesn\'t exists.');
		} else {
			mysql_close($con);
		}
	}
}

function showError($query, $line){
	if($access != 'public') {
		if (!mysql_query($query)){
			echo '<p class=\'advice\'>ERROR No.: ' .  $line . '</p>';
			die ('Your Query: ' . $query . '<br>Error: (' . mysql_errno() . ') ' . mysql_error());
		}
	}
}

include ('content.php');
include ('note.php');
include ('source.php');

