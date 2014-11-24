<?php
// Wird ausgeführt um mit der Ausgabe des Headers zu warten.
ob_start ();

session_start ();
session_unset ();
session_destroy ();

require_once 'settings.php';
header ('Location: ' . __SITE_URL__ . '/');

ob_end_flush ();
