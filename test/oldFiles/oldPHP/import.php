<?php

require 'setting.php';


condb('open');
$sql = mysql_query('SELECT bibName FROM bib');
$i = 1400;

while($row = mysql_fetch_object($sql)) {


	echo 'UPDATE `bib` SET `noteID` = ' . $i . ' WHERE `bib`.`bibName` = \'' . htmlentities($row->bibName) . '\';<br>';

	$i++;
}

condb('close');
