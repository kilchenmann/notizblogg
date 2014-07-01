<?php
session_start ();
if (!isset ($_SESSION["user_id"]))
{
  header ("Location: ".SITE_URL."/".BASE_FOLDER."admin/login.php");
}
?>
