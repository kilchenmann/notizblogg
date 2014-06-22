<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 18/06/14
 * Time: 15:43
 */

//$indexTitle = "note";


if($access == 'public'){
	$gpa = "AND notePublic = 1";
} else {
	$gpa = "";
}


function show($type, $query, $access)
{
	switch ($type) {
		case 'source';
			$source = NEW source();
				$source->showSource($query, $access);
			break;

		case 'note';
			$note = NEW note();
			$note->showNote($query, $access);
			break;

		case 'label';
			$source = NEW source();
			condb('open');
			$sql = mysql_query("SELECT sourceID FROM source WHERE sourceCategory=" . $query);
			condb('close');
			$num_results = mysql_num_rows($sql);
			if($num_results > 0) {
				while ($row = mysql_fetch_object($sql)) {
					$source->showSource($row->noteID, $access);
				}
			}
			break;

		case 'author';
			condb('open');
			$sql = mysql_query("SELECT sourceID FROM rel_source_author WHERE authorID=".$query.";");
			condb('close');
			$num_results = mysql_num_rows($sql);
			if($num_results > 0) {
				while ($row = mysql_fetch_object($sql)) {
					$source = NEW source();
					$source->showSource($row->sourceID, $access);
				}
			}
			break;

		case 'collection';
			echo $type . ": " . $query . PHP_EOL;
			break;

		case 'search';
			echo $type . ": " . $query . PHP_EOL;
			break;

		default:
			if($access === 'public') {
				$query = 'WHERE sourcePublic = 1';
			} else { // ($access !== 'public' && $id === 'all')
				$query = '';
			}
			condb('open');
			$sql = mysql_query("SELECT sourceID FROM source " . $query . ";");
			condb('close');
			$num_results = mysql_num_rows($sql);
			if($num_results > 0) {
				while ($row = mysql_fetch_object($sql)) {
					$source = NEW source();
					$source->showSource($row->sourceID, $access);
				}
			}
	}
}







function show_oldversion($type, $part, $partID, $access){
	$count = 200;
	$sql = '';
	if(!isset($_GET['page'])){
		$_GET['page'] = 1;
	}
	$offset = ($_GET['page'] - 1) * $count;
	if($type == 'note'){
		$orderBy = "pageStart, ".$type."Title, date DESC LIMIT ".$offset;
	} else {
		$orderBy = $type."Typ, " . $type."Title, date DESC LIMIT ".$offset;
	}
	if($access == 'public' && $type == 'note'){
		$gpa = "AND notePublic = 1";
	} else {
		$gpa = "";
	}

	switch($part){
		// case 1:n
		case "category";
		case "project";
		case "source";
			$sql = mysql_query("SELECT ".$type."ID FROM ".$type." WHERE ".$type.$part." = ".$partID." ".$gpa." ORDER BY ".$orderBy.", ".$count."");
			$partIndex = $part;
			$titleIndexLeft = linkIndex($type, $part, $partID);
			break;

		// case m:n
		case "tag";
		case "author";
			$relTable = "rel_".$type."_".$part;
			$sql = mysql_query("SELECT ".$part."Name, ".$type."ID FROM ".$part.", ".$relTable." WHERE ".$part.".".$part."ID = ".$relTable.".".$part."ID AND ".$relTable.".".$part."ID = '".$partID."' ORDER BY ".$part."Name");
			$partIndex = $part;
			$titleIndexLeft = linkIndex($type, $part, $partID);
			break;

		case "excerpt";
		case "collection";
			$sql = mysql_query("SELECT ".$type."ID FROM ".$type." WHERE ".$type."ID = ".$partID." ".$gpa.";");
			$partIndex = $part;
			$titleIndexLeft = linkIndex('note', 'source', $partID);
			break;

		case "search";
			$partID = htmlentities($partID,ENT_QUOTES,'UTF-8');
			if($type == 'note'){
				$sql = mysql_query("SELECT noteID FROM note WHERE (`noteTitle` LIKE '%".$partID."%' OR `noteContent` LIKE '%".$partID."%' OR noteSourceExtern LIKE '%".$partID."%') ".$gpa." ORDER BY date DESC");

			} else {
				$sql = mysql_query("SELECT sourceID FROM source WHERE (`sourceTitle` LIKE '%".$partID."%' OR `sourceSubtitle` LIKE '%".$partID."%' OR sourceNote LIKE '%".$partID."%' OR sourceName LIKE '%".$partID."%') ".$gpa." ORDER BY date DESC");
			}
			$partIndex = $part."ed";
			$titleIndexLeft = "'".$partID."'";
			break;

		case "export";
			$sql = mysql_query("SELECT ".$type."ID FROM ".$type." WHERE ".$type."Typ != 0 ORDER BY sourceTyp DESC");
			$partIndex = $part;
			$partName = getIndex($type, $part, $partID);
			$year = date("Y");
			$date = date("Ymd");
			$filename = $partName . "_" . $date . ".bib";
			//$tmpPath = split('/notizblogg', SITE_URL);
			$backuppath = "export/bibtex/" . $filename;
			$downloadurl = DOWNLOAD_URL . "/bibtex/" . $filename;
			$titleIndexLeft = "<a href='".$downloadurl."'>Download bibTex file</a>";
			if(!file_exists($backuppath)){
				$copyRight = html_entity_decode("%% %% %% %% %% %% %% %% %% %% %% %% %% %% %%\n%% This bibFile was created with\n%% Notizblogg &copy; by\n%% Andr&eacute; Kilchenmann | 2006-". $year ." \n%%\n%% -&gt; ak@notizblogg.ch\n%% -&gt; http://notizblogg.ch\n%% %% %% %% %% %% %% %% %% %% %% %% %% %% %%\n\n",ENT_NOQUOTES,'ISO-8859-15');
				fopen($backuppath, 'w+');
				if (!$handle = fopen($backuppath, 'w')) {
					echo "Cannot open file (".$backuppath.")";
					exit;
				}
				if (fwrite($handle, $copyRight) === FALSE) {
					echo "Cannot write to file (".$backuppath.")";
					exit;
				}
			}
			break;
		//default:

	}

	// hier noch if $sql existiert
	$countResult = mysql_num_rows($sql);
	if($countResult != 0){
		?>
		<script type="text/javascript">
			$('.partIndex h2').html("<?php echo $partIndex; ?>");
			$('.titleIndex .left').html("<?php echo $titleIndexLeft; ?>");
			$('.titleIndex .right').html("<?php echo "#".$type."s: ".$countResult; ?>");
		</script>
		<?php

		$tableID = $type."ID";
		for($i=1; $i<=$countResult; $i++){
			$row = mysql_fetch_object($sql);
			$typeID = $row->$tableID;
			if($type=="note"){
				showNote($typeID, $access);
			} else {
				showSource($typeID, $access);
				if($_GET['part']=='export'){
					//$file = escapeshellarg($backuppath); // for the security concious (should be everyone!)
					//$line = `tail -n 1 $backuppath;

					$file_arr = file($backuppath);
					$last_row = $file_arr[count($file_arr) - 1];
					$last_data = explode("%", $last_row);

					if($last_data[1] != ($countResult)){
						//$fp = fopen($backuppath, "r");
						//$data = fgets($fp, 12);
						//echo ftell($fp);
						/*
						$cursor = -1;
						$tmp = " ";
						while ($tmp != "%".$i) {
							fseek($read, $cursor, SEEK_END);
							$tmp = fgetc($read);
							$pos = $pos - 1;
						}
						$tmp = fgets($read);
						fclose($read);
						echo $tmp;
						*/

						$handle = fopen($backuppath, 'a');
						exportSource($typeID, $handle);
						if($i == ($countResult)){
							fwrite($handle, "%".$i);
							fclose($backuppath);
						}
					}
				}
			}

		}


		/*
		while($row = mysql_fetch_object($sql)){
			$typeID = $row->$tableID;
			if($type=="note"){
				showNote($typeID, $access);
			} else {
				showSource($typeID, $access);
				if($_GET['part']=='all'){
					$handle = fopen($backuppath, 'a');
						exportSource($typeID, $handle);
					fclose($backuppath);
				}
			}
		}
		* */
	} else {
		$partIndex = $part;
		if($part == 'source'){
			$titleIndexLeft = linkIndex($type, $part, $partID);
		} elseif($part == 'search') {
			$partIndex = $part."ed";
			$titleIndexLeft = "'".$partID."'";
		} else {
			$titleIndexLeft = "found nothing";
		}
		?>
		<script type="text/javascript">
			$('.partIndex h2').html("<?php echo $partIndex; ?>");
			$('.titleIndex .left').html("<?php echo $titleIndexLeft; ?>");
			$('.titleIndex .right').html("<?php echo "#".$type."s: ".$countResult; ?>");
		</script>
		<?php
		$zeroResults = "Either you are not allowed to see the note or there are no notes with this item.";
		echo "<div class='note'><p class='advice'>".$zeroResults."</p></div>";
	}
}