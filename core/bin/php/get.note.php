<?php
require ('setting.php');

$note = NEW note();
$access = 'enable';

$notes = array();

echo '{"notes": [';

	condb('open');
	// abfrage von ids
	$sql = mysql_query("SELECT noteID FROM note;");
	$count = mysql_num_rows($sql);
	$i = 1;
	while($row = mysql_fetch_object($sql)) {
		$nID = $row->noteID;
		$notes = $note->getNote($nID, $access);
		$i++;
		if($i < $count) {
			echo ',' . PHP_EOL;
		}
	}
	condb('close');

echo ']}';


