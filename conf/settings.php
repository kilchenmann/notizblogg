<?php
/* -------------------------------------------------------------------------- */
/* ----- This is the configuration and settings file for the notizblogg ----- */
/* Author & Copyright notizblogg: André Kilchenmann, 2006 - 2013 ------------ */
/* Website: http://notizblogg.ch  ------------------------------------------- */
/* -------------------------------------------------------------------------- */

/* header, main topics ------------------------------------------------------ */
global $siteTitle;
	$siteTitel = "Notizblogg = Zettelkasten + Memex in digitaler Form";
global $nbVersion;
	$nbVersion = 3.9;
global $pagetopic;
	$pagetopic = "Sammelsurium von Ideen, Zitaten &amp; Präsentation von eigenen Projekten";
global $keywords;
	$keywords = "literatur,verwaltung,zettelkasten,luhmann,upcycling,"."redesign,kilchenmann,andré,milchkannen,eiskunstlauf,fotografie,notizblogg";
global $description;
	$description = "Notizblogg ist der digitale Zettelkasten ".
	"von André Kilchenmann. Nebst textuellem Inhalt kann der digitale MeMex, ".
	"auch Bilder, Video- oder Ton-Digitalisate aufnehmen.";
/* path, file, url ---------------------------------------------------------- */
global $pathInfo;
	$pathInfo = pathinfo($_SERVER['SCRIPT_FILENAME']);
global $mainFile;
	$mainFile = $pathInfo['filename'].".".$pathInfo['extension'];
define ('MainFile', $pathInfo['filename'].".".$pathInfo['extension']);
global $protocol;
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define ('SITE_URL', $protocol.$_SERVER['HTTP_HOST']);
	// http://iml-pluto.iml.unibas.ch
	//echo "SITE_URL: ".SITE_URL."<br>";	
define ('BASE_FOLDER', basename($pathInfo['dirname'])."/");	// nicht immer nötig
	// notizblogg/
	//echo "BASE_FOLDER: ".BASE_FOLDER."<br>";	
define ("MEDIA_FOLDER", "/MEDIA/NOTIZBLOGG");
define ("MEDIA_URL", SITE_URL . MEDIA_FOLDER);
	// http://iml-pluto.iml.unibas.ch/MEDIA/NOTIZBLOGG
	//echo "MEDIA_URL: ".MEDIA_URL."<br>";	
define ('SITE_PATH', $pathInfo['dirname']);
	// /Library/WebServer/Documents/notizblogg
	//echo "SITE_PATH: ".SITE_PATH."<br>";

global $count;
	$count = "25";				// default: 25
global $access;
	$access = "denied";			// default: denied
global $robots;
	$robots = "noindex,nofollow";	// default: noindex,nofollow
global $zeroResults;
	$zeroResults = "Either you are not allowed to see the note or there are no notes with this item.";
/* design, css, theme ------------------------------------------------------- */
global $main_theme;
	$main_theme = "screen";		// default: screen
global $mobile_theme;
	$mobile_theme = "mobile";	// default: mobile
global $print_theme;
	$print_theme = "print";		// default: print
/* js, jquery, plugins ------------------------------------------------------ */
global $jquery_version;
	$jquery_version = "jquery-1.7.2.min.js";

	date_default_timezone_set("UTC");

?>
