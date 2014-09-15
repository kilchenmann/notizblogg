<?php

/* path, file, url --------------------------------------------------------- */
$pathInfo = pathinfo($_SERVER['SCRIPT_FILENAME']);
$mainFile = $pathInfo['filename'].".".$pathInfo['extension'];
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

require_once('.conf/db.php');

//print_r($db);

//define ('__MAIN_FILE__', $pathInfo['filename'].".".$pathInfo['extension']);
define ('__MAIN_FILE__', '');
// index.php
// echo "MAIN_FILE: ".__MAIN_FILE__."<br>";

define ('__BASE_FOLDER__', $GLOBALS['nb']['path']);
// /nb
// echo "BASE_FOLDER: ".__BASE_FOLDER__."<br>";

define ('__SITE_PATH__', $pathInfo['dirname']);
// /Library/WebServer/Documents/nb
// echo "SITE_PATH: ".__SITE_PATH__."<br>";

define ('__SITE_URL__', $protocol.$_SERVER['HTTP_HOST'] . __BASE_FOLDER__);
// http://notizblogg.ch
// echo "SITE_URL: ".__SITE_URL__."<br>";

define ("__MEDIA_URL__", $protocol.$_SERVER['HTTP_HOST'] . "/data/media");
// http://somewhere.com/MEDIA/NOTIZBLOGG
// echo "MEDIA_URL: ".__MEDIA_URL__."<br>";

define ('__DOWNLOAD_URL__', $protocol.$_SERVER['HTTP_HOST'] . "/data/export");
// echo "DOWNLOAD_URL: ".__DOWNLOAD_URL__."<br>";

define ('__SITE_API__', $protocol.$_SERVER['HTTP_HOST'] . "/nb/api");


/* include all my classes and other php files ------------------------------ */

include ('functions.php');

//include ('class.note.php');
//include ('class.source.php');

//include ('class.show.php');
//include ('show.data.php');
//include ('get.JSON.php');

