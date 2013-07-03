<?php
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
  $upas = array("ä"=>"{\\\"a}", "ö"=>"{\\\"o}", "ü"=>"{\\\"u}", "Ä"=>"{\\\"A}", "Ö"=>"{\\\"O}", "Ü"=>"{\\\"U}", "é"=>"{\\'e}", "è"=>"{\\`e}", "à"=>"{\\`a}", "É"=>"{\\'E}", "È"=>"{\\`E}", "À"=>"{\\`A}", "ñ"=>"{\\~n}", "ë"=>"{\\\"e}", "ç"=>"{\\c c}", "ô"=>"{\\^o}", "í"=>"{\\'i}", "ì"=>"{\\`i}" );
  /*foreach($upas as $umlaut=>$replace){
	return (str_replace($umlaut, $replace, $string));
  }
  */
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
		return "<a href='".MainFile."?type=".$type."&amp;part=".$part."&amp;id=0' title='no ".$part."' >--</a>";
	} else {
		$sql = mysql_query("SELECT ".$part."Name FROM ".$part." WHERE ".$part."ID=".$id);
			while($row = mysql_fetch_object($sql)){
				return "<a href='".MainFile."?type=".$type."&amp;part=".$part."&amp;id=".$id."' title='".$part."'>".$row->$tableName."</a>";
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
function indexMN($type, $part, $id){
	$tableID = $part."ID";
	$tableName = $part."Name";
	$relTable = "rel_".$type."_".$part;
	
	$tagSql = mysql_query("SELECT ".$part."Name FROM ".$part.", ".$relTable." WHERE ".$part.".".$part."ID = ".$relTable.".".$part."ID AND ".$relTable.".".$type."ID = '".$id."' ORDER BY ".$part."Name");
	// $tagIDs = array();
	$countTags = mysql_num_rows($tagSql);
	if($countTags>0) {
		while($row = mysql_fetch_array($tagSql)) {
			$relIDs[] = $row['tagName'];
		}
		asort($relIDs);
			$relData="";
			foreach($relIDs as $relName) {
				if($relData==""){
					$relData= $relName;
				} else {
					$relData.= " / ".$relName;
				}
			}
	} else {
		$relData = "";
	}
	return $relData;
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
		$orderBy = $type."Title, date DESC LIMIT ".$offset;
	}
	if($access == 'public' && $type == 'note'){
		$gpa = "AND notePublic = 1";
	} else {
		$gpa = "";
	}
	$sql = mysql_query("SELECT ".$type."ID FROM ".$type." WHERE ".$type.$part." = ".$partID." ".$gpa." ORDER BY ".$orderBy.", ".$count."");

	//$sql = mysql_query("SELECT noteID FROM note WHERE noteProject = 5 ORDER BY noteTitle");
	$countResult = mysql_num_rows($sql);

?>
	<script type="text/javascript">
		$('.partIndex h2').html("<?php echo $part; ?>");
		$('.titleIndex .left').html("<?php echo linkIndex($type, $part, $partID); ?>");
		$('.titleIndex .right').html("<?php echo "#".$type."s: ".$countResult; ?>");
	</script>
<?php
		
	$tableID = $type."ID";
	
	while($row = mysql_fetch_object($sql)){
		$typeID = $row->$tableID;
		if($type=="note"){
			showNote($typeID, $access);
		} else {
			showSource($typeID);
		}
	}
}





?>
