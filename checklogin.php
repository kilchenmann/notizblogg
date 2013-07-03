<?php
// Session starten
session_start ();
require_once ("conf/settings.php");
include (SITE_PATH."/common/php/db.php");
connect();
$sql = "SELECT ".
    "uid, name, email ".
  "FROM ".
    "user ".
  "WHERE ".
    "(name like '".$_REQUEST["name"]."') AND ".
    "(pass = '".md5 ($_REQUEST["pwd"])."')";
$result = mysql_query ($sql);

if (mysql_num_rows ($result) > 0)
{
  // Benutzerdaten in ein Array auslesen.
  $data = mysql_fetch_array ($result);

  // Sessionvariablen erstellen und registrieren
  $_SESSION["user_id"] = $data["uid"];
  $_SESSION["user_name"] = $data["name"];


  header ("Location: ".SITE_URL."/".BASE_FOLDER."admin.php");
}
else
{
  header ("Location: ".SITE_URL."/".BASE_FOLDER."admin/login.php?access=denied");
}
disconnect();
?>
