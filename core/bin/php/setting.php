<?php
/*
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 25/04/14
 * Time: 13:30
 */


//global $access;
//$access = 'denied';			// default: denied

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


/*
function showError($query, $line){
	if($access != 'public') {
		if (!mysql_query($query)){
			echo '<p class=\'advice\'>ERROR No.: ' .  $line . '</p>';
			die ('Your Query: ' . $query . '<br>Error: (' . mysql_errno() . ') ' . mysql_error());
		}
	}
}
*/
/* path, file, url ---------------------------------------------------------- */
global $pathInfo;
$pathInfo = pathinfo($_SERVER['SCRIPT_FILENAME']);

global $mainFile;
$mainFile = $pathInfo['filename'].".".$pathInfo['extension'];
define ('MainFile', $pathInfo['filename'].".".$pathInfo['extension']);

include ('function.content.php');
include ('class.note.php');
include ('class.source.php');

