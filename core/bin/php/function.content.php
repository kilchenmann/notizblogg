<?php


function condb($conart) {
	include ('.conf/db.php');
	$connect = mysql_connect($myhost, $myuser, $mypass);
	if (!$connect){
		die('MySQL-Access denied:' . mysql_error());
	} else {
		if ($conart == 'open') {
			mysql_select_db($mydb, $connect) or die ("The database $mydb doesn't exists.");
		} else {
			mysql_close($connect);
		}
	}
}

/* ************************************************************** 
 * Change umlauts like ä to ae: It's important for the author-links & latex
 * ************************************************************** 
 */
function changeUmlaut($string){
  $upas = array("ä"=>"ae", "ö"=>"oe", "ü"=>"ue", "Ä"=>"Ae", "Ö"=>"Oe", "Ü"=>"Ue", " "=>"-", "é"=>"e", "è"=>"e", "à"=>"a", "É"=>"E", "È"=>"E", "À"=>"A", "ñ"=>"n", "ë"=>"e");
  /*foreach($upas as $umlaut=>$replace){
	return (str_replace($umlaut, $replace, $string));
  }
  */
  return strtr($string, $upas);
}

function changeUmlaut4Tex($string){
  $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
  $upas = array("ä"=>"{\\\"a}", "ö"=>"{\\\"o}", "ü"=>"{\\\"u}", "Ä"=>"{\\\"A}", "Ö"=>"{\\\"O}", "Ü"=>"{\\\"U}", "é"=>"{\\'e}", "è"=>"{\\`e}", "à"=>"{\\`a}", "É"=>"{\\'E}", "È"=>"{\\`E}", "À"=>"{\\`A}", "ñ"=>"{\\~n}", "ë"=>"{\\\"e}", "ç"=>"{\\c c}", "ô"=>"{\\^o}", "í"=>"{\\'i}", "ì"=>"{\\`i}", "_"=>"\_", "§"=>"\§", "$"=>"\$", "&"=>"\&", "#"=>"\#", "{"=>"\{", "}"=>"\}", "%"=>"\%", "~"=>"\textasciitilde", "€"=>"\texteuro" );
  /*foreach($upas as $umlaut=>$replace){
	return (str_replace($umlaut, $replace, $string));
  }
  */
  //$htmlString = html_entity_decode($string, ENT_NOQUOTES, 'ISO-8859-15');
  return strtr($string, $upas);
}

function change4Tex($string){
  $upas = array("_"=>"\_", "§"=>"\§", "$"=>"\$", "&"=>"\&", "#"=>"\#", "{"=>"\{", "}"=>"\}", "%"=>"\%", "~"=>"\textasciitilde", "€"=>"\texteuro");
  /*foreach($upas as $umlaut=>$replace){
	return (str_replace($umlaut, $replace, $string));
  }
  */
  return strtr($string, $upas);
}

function save4Tex($string){
  $upas = array(" \""=>" ``", "\" "=>"\'\' ", " '"=>" `", " - "=>" -- " );
  /*foreach($upas as $umlaut=>$replace){
	return (str_replace($umlaut, $replace, $string));
  }
  */
  return strtr($string, $upas);
}

/**
 * This function changes any URLs surrounded by <> into clickable URLs in
 * the message.
 *
 * @author	Peter Bowyer
 * @param	$text, the message from the postcard.
 * @return	returns $text, the message with hyperlinks in it.
 */
function makeurl($text)
{
	$text = preg_replace
		("!&amp;lt;(link:)([^ >\n\t]+)(:)([^ >\n\t]+)&amp;gt;!i", "<a href=\"http://\\2\" target=\"_blank\">\\4</a>", $text);
		
	$text = preg_replace
		("!&amp;lt;(mailto:)([^ >\n\t]+)&amp;gt;!i", "<a href=\"\\1\\2\">\\2</a>", $text);
	$text = preg_replace
		("!&amp;lt;(wiki:)([^ >\n\t]+)&amp;gt;!i", "<a href=\"http://de.wikipedia.org/wiki/\\2\" target=\"_blank\" title=\"Look for <\\2> in wikipedia\">\\2</a>", $text);
	$text = preg_replace
		("!&amp;lt;(nb:)([^ >\n\t]+)&amp;gt;!i", "<a href=\"".MainFile."?type=note&amp;part=search&amp;id=\\2\" title=\"Search here <\\2>\">\\2</a>", $text);
		
	$text = preg_replace
		("!&lt;(link:)([^ >\n\t]+)(:)([^ >\n\t]+)&gt;!i", "<a href=\"http://\\2\" target=\"_blank\">\\4</a>", $text);
		
	$text = preg_replace
		("!&lt;(mailto:)([^ >\n\t]+)&gt;!i", "<a href=\"\\1\\2\">\\2</a>", $text);
	$text = preg_replace
		("!&lt;(wiki:)([^ >\n\t]+)&gt;!i", "<a href=\"http://de.wikipedia.org/wiki/\\2\" target=\"_blank\" title=\"Look for <\\2> in wikipedia\">\\2</a>", $text);
	$text = preg_replace
		("!&lt;(nb:)([^ >\n\t]+)&gt;!i", "<a href=\"".MainFile."?type=note&amp;part=search&amp;id=\\2\" title=\"Search here <\\2>\">\\2</a>", $text);
		
	$text = preg_replace
		("!<(link:)([^ >\n\t]+)(:)([^ >\n\t]+)>!i", "<a href=\"http://\\2\" target=\"_blank\">\\4</a>", $text);
		
	$text = preg_replace
		("!<(mailto:)([^ >\n\t]+)>!i", "<a href=\"\\1\\2\">\\2</a>", $text);
	$text = preg_replace
		("!<(wiki:)([^ >\n\t]+)>!i", "<a href=\"http://de.wikipedia.org/wiki/\\2\" target=\"_blank\" title=\"Look for <\\2> in wikipedia\">\\2</a>", $text);
	$text = preg_replace
		("!<(nb:)([^ >\n\t]+)>!i", "<a href=\"".MainFile."?type=note&amp;part=search&amp;id=\\2\" title=\"Search here <\\2>\">\\2</a>", $text);
	return $text;
}

function linkIndex($type, $part, $id) {
	$tableName = $part."Name";
	if ($id == 0) {
		return '--';
//		return "<a href='".MainFile."?type=".$type."&amp;part=".$part."&amp;id=0' title='no ".$part."' >--</a>";
	} else {
		$sql = mysql_query("SELECT ".$part."Name FROM ".$part." WHERE ".$part."ID=".$id);
			while($row = mysql_fetch_object($sql)){
//				return $row->$tableName;
				return "<a href='?type=".$type."&amp;part=".$part."&amp;id=".$id."' title='".$part."'>".$row->$tableName."</a>";
			}
	}
}


function getIndex($part, $id) {
	$tableName = $part."Name";
	if ($id == 0) {
		return "--";
	} else {
		$sql = mysql_query("SELECT ".$part."Name FROM ".$part." WHERE ".$part."ID=".$id);
			while($row = mysql_fetch_object($sql)){
				return $row->$tableName;
			}
	}
}
	
function linkIndexMN($type, $part, $id){
	$relTable = "rel_".$type."_".$part;
	$partID = $part."ID";
	$mnSql = mysql_query("SELECT ".$part."Name FROM ".$part.", ".$relTable." WHERE ".$part.".".$part."ID = ".$relTable.".".$part."ID AND ".$relTable.".".$type."ID = '".$id."' ORDER BY ".$part."Name");

	$countMN = mysql_num_rows($mnSql);
	if($countMN>0) {
		while($row = mysql_fetch_array($mnSql)) {
			$relIDs[] = $row[$part."Name"];
		}
		asort($relIDs);
			$relData="";
			foreach($relIDs as $relName) {
				$getRelID = mysql_query("SELECT ".$part."ID FROM ".$part." WHERE ".$part."Name = '".$relName."'");
				while($row = mysql_fetch_object($getRelID)){
					$relID = $row->$partID;
					$countSql = mysql_query("SELECT ".$type.".".$type."ID FROM ".$type.", ".$relTable." WHERE ".$part."ID = ".$relID." AND ".$relTable.".".$type."ID = ".$type.".".$type."ID ORDER BY ".$type.".".$type."Title, ".$type.".date DESC");
					$countResult = mysql_num_rows($countSql);
				}
				
				if($relData==""){
					$relData=" || <a href='".MainFile."?type=".$type."&amp;part=".$part."&amp;id=".$relID."' title='#".$type."s: ".$countResult."'>".$relName."</a>";
				} else {
					$relData.= " | <a href='".MainFile."?type=".$type."&amp;part=".$part."&amp;id=".$relID."' title='#".$type."s: ".$countResult."'>".$relName."</a>";
				}
			}
	} else {
		$relData = "";
	}
	echo $relData;
}

function getIndexMN($type, $part, $id){
	$relTable = "rel_".$type."_".$part;
	
	$tagSql = mysql_query("SELECT ".$part."Name FROM ".$part.", ".$relTable." WHERE ".$part.".".$part."ID = ".$relTable.".".$part."ID AND ".$relTable.".".$type."ID = '".$id."' ORDER BY ".$part."Name");
	$countTags = mysql_num_rows($tagSql);

	if($countTags>0) {
		while ($row = mysql_fetch_array($tagSql)) {
			$relNames[] = $row['tagName'];
		}
		return $relNames;
	}

		/*
		asort($relIDs);
			$relData = array();

			foreach($relIDs as $relName) {
				if( empty ( $relData ) ) {
					$relData = array($relID => $relName);
				} else {

				}

				if($relData==""){
					$relData= $relName;
				} else {
					$relData.= ", ".$relName;
				}
			}
	} else {
		$relData = "";
	}
	return array ($relData);
*/
}


function show($type, $part, $partID, $access){
	$count = 200;
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






?>
