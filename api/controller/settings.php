<?php
/*
$pathInfo = pathinfo($_SERVER['SCRIPT_FILENAME']);
$mainFile = $pathInfo['filename'].".".$pathInfo['extension'];
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
*/
require_once ('.conf/db.php');

$settings = file_get_contents($GLOBALS['nb']['indexpath'] . '/' . 'config.json');

$array = json_decode($settings, true);

define ('__SITE_URL__', $array['url']['main']);
define ('__SITE_API__', $array['url']['api']);
define ('__MEDIA_URL__', $array['url']['media']);
define ('__MEDIA_PATH__', $array['path']['media']);

include ('functions.php');
include ('fun4mysql.php');
