<?php
// Wird ausgeführt um mit der Ausgabe des Headers zu warten. 
ob_start ();

session_start ();
session_unset ();
session_destroy ();

require_once 'setting.php';
header ('Location: ' . __SITE_URL__ . '/' . __BASE_FOLDER__ . '/index.php');

ob_end_flush ();