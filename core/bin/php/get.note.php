<?php
require ('setting.php');
if(isset($_GET["id"])){
	$partID = $_GET["id"];
}

$access = '';
$info = NEW note();
//echo $info->getNote(1, $access);
echo $info->getNote($partID, $access);
//echo $info->getNote('3', $access);


/*
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


/*
			$note = NEW note();
			$access = 'enable';
//			echo '<div class=\'desk\'>';
			$sql = mysql_query("SELECT noteID FROM note WHERE notePublic = 1 AND noteID <= 3 ORDER BY noteID ASC");
			while($row = mysql_fetch_object($sql)){
				$typeID = $row->noteID;
				//showNote($typeID, $access);
				$note->getNote($typeID, $access);
			}
//			echo '</div>';
*/