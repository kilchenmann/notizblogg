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
  $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
 //???????? $upas = array("ä"=>"{\\\"a}", "ö"=>"{\\\"o}", "ü"=>"{\\\"u}", "Ä"=>"{\\\"A}", "Ö"=>"{\\\"O}", "Ü"=>"{\\\"U}", "é"=>"{\\'e}", "è"=>"{\\`e}", "à"=>"{\\`a}", "É"=>"{\\'E}", "È"=>"{\\`E}", "À"=>"{\\`A}", "ñ"=>"{\\~n}", "ë"=>"{\\\"e}", "ç"=>"{\\c c}", "ô"=>"{\\^o}", "í"=>"{\\'i}", "ì"=>"{\\`i}", "_"=>"\_", "§"=>"\§", "$"=>"\$", "&"=>"\&", "#"=>"\#", "{"=>"\{", "}"=>"\}", "%"=>"\%", "~"=>"\textasciitilde", "€"=>"\texteuro" );
  /*
	foreach($upas as $umlaut=>$replace){
	return (str_replace($umlaut, $replace, $string));
  }
  */
  //$htmlString = html_entity_decode($string, ENT_NOQUOTES, 'ISO-8859-15');
  return strtr($string, $upas);
}

function html2tex($text, $cite = ''){
	$text = preg_replace
	("!&amp;lt;(link:)([^ >\n\t]+)(:)([^ >\n\t]+)&amp;gt;!i", "\\4", $text);
	$text = preg_replace
	("!&amp;lt;(mailto:)([^ >\n\t]+)&amp;gt;!i", "\\2", $text);
	$text = preg_replace
	("!&amp;lt;(wiki:)([^ >\n\t]+)&amp;gt;!i", "\\2", $text);
	$text = preg_replace
	("!&amp;lt;(nb:)([^ >\n\t]+)&amp;gt;!i", "\\2", $text);

	$text = preg_replace
	("!&lt;(link:)([^ >\n\t]+)(:)([^ >\n\t]+)&gt;!i", "\\4", $text);
	$text = preg_replace
	("!&lt;(mailto:)([^ >\n\t]+)&gt;!i", "\\2", $text);
	$text = preg_replace
	("!&lt;(wiki:)([^ >\n\t]+)&gt;!i", "\\2", $text);
	$text = preg_replace
	("!&lt;(nb:)([^ >\n\t]+)&gt;!i", "\\2", $text);

	$text = preg_replace
	("!<(link:)([^ >\n\t]+)(:)([^ >\n\t]+)>!i", "\\4", $text);
	$text = preg_replace
	("!<(mailto:)([^ >\n\t]+)>!i", "\\2", $text);
	$text = preg_replace
	("!<(wiki:)([^ >\n\t]+)>!i", "\\2", $text);
	$text = preg_replace
	("!<(nb:)([^ >\n\t]+)>!i", "\\2", $text);


	//$upas = array(' &quot;'=>' ``', '&quot; '=>'\'\' ', '&quot;, '=>'\'\', ', '&quot;. '=>'\'\'. ', ' &#039;'=>' `', '&#039; '=>'\' ', '&#039;, '=>'\', ', '&#039;. '=>'\'. ', ' - '=>' -- ', ' — '=>' -- ', ' &ndash; '=>' -- ', ' &mdash; '=>' -- ', '_'=>'\\_', '§'=>'\\§', '$'=>'\\$', '& '=>'\\& ', ' #'=>' \\#', '{'=>'\\{', '}'=>'\\}', '%'=>'\\%', '~'=>'\\textasciitilde', '€'=>'\\texteuro');
  /*foreach($upas as $umlaut=>$replace){
	return (str_replace($umlaut, $replace, $string));
  }
  */
  if($cite != 'cite') {
      return $text;
  } else {
      return '``' . $text . '\'\'';
  }
}

function getLastChar($string){
	$lastChar = substr($string, -1);
	if(($lastChar != '?') && ($lastChar != '!') && ($lastChar != ':') && ($lastChar != '.')) {
		$string .=  '.';
	}
	return $string;
}
function writeTex($string) {
	$upas = array(' "'=>' ``', '" '=>'\'\' ', ' \''=>' `', ' - '=>' -- ', ' — '=>' -- ', ' -, '=>' --, ', ' —, '=>' --, ', '$'=>'\$' );
	foreach($upas as $umlaut=>$replace){
		return (str_replace($umlaut, $replace, $string));
	}
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
		("!&amp;lt;(link:)([^ >\n\t]+)(:)([^ >\n\t]+)&amp;gt;!i", "<a href='http://\\2' target='_blank'>\\4</a>", $text);
	$text = preg_replace
		("!&amp;lt;(mailto:)([^ >\n\t]+)&amp;gt;!i", "<a href='\\1\\2'>\\2</a>", $text);
	$text = preg_replace
		("!&amp;lt;(wiki:)([^ >\n\t]+)&amp;gt;!i", "<a href='http://de.wikipedia.org/wiki/\\2' target='_blank' title='Look for <\\2> in wikipedia'>\\2</a>", $text);
	$text = preg_replace
		("!&amp;lt;(nb:)([^ >\n\t]+)&amp;gt;!i", "<a href='?q=\\2' title='Search here <\\2>'>\\2</a>", $text);

	$text = preg_replace
		("!&lt;(link:)([^ >\n\t]+)(:)([^ >\n\t]+)&gt;!i", "<a href='http://\\2' target='_blank'>\\4</a>", $text);
	$text = preg_replace
		("!&lt;(mailto:)([^ >\n\t]+)&gt;!i", "<a href='\\1\\2'>\\2</a>", $text);
	$text = preg_replace
		("!&lt;(wiki:)([^ >\n\t]+)&gt;!i", "<a href='http://de.wikipedia.org/wiki/\\2' target='_blank' title='Look for <\\2> in wikipedia'>\\2</a>", $text);
	$text = preg_replace
		("!&lt;(nb:)([^ >\n\t]+)&gt;!i", "<a href='?q=\\2' title='Search here <\\2>'>\\2</a>", $text);

	$text = preg_replace
		("!<(link:)([^ >\n\t]+)(:)([^ >\n\t]+)>!i", "<a href='http://\\2' target='_blank'>\\4</a>", $text);
	$text = preg_replace
		("!<(mailto:)([^ >\n\t]+)>!i", "<a href='\\1\\2'>\\2</a>", $text);
	$text = preg_replace
		("!<(wiki:)([^ >\n\t]+)>!i", "<a href='http://de.wikipedia.org/wiki/\\2' target='_blank' title='Look for <\\2> in wikipedia'>\\2</a>", $text);
	$text = preg_replace
		("!<(nb:)([^ >\n\t]+)>!i", "<a href='?q=\\2' title='Search here <\\2>'>\\2</a>", $text);
	return $text;
}


function getNoteID($id) {

}


function linkIndex($type, $part, $id) {
	return $type . ' + ' . $part . ' + ' . $id;
//	return "<a href='?type=".$type."&amp;part=".$part."&amp;id=".$id."' title='".$part."'>".$row->$tableName."</a>";

/*
	$tableName = $part."Name";
	if ($id == 0) {
		return '--';
//		return "<a href='".__MAIN_FILE__."?type=".$type."&amp;part=".$part."&amp;id=0' title='no ".$part."' >--</a>";
	} else {
		$sql = mysql_query("SELECT ".$part."Name FROM ".$part." WHERE ".$part."ID=".$id);
			while($row = mysql_fetch_object($sql)){
//				return $row->$tableName;
				return "<a href='?type=".$type."&amp;part=".$part."&amp;id=".$id."' title='".$part."'>".$row->$tableName."</a>";
			}
	}
*/
}


function showError($lineNumber, $fileNumber) {
	echo '<div class=\'note warning\'>';
	echo '<h3>';
	echo 'FATAL ERROR';
	echo '</h3>';
	echo '<p>Error N° ' . $lineNumber . '</p>';
	echo '<p>' . $fileNumber . '</p>';
	echo '</div>';
}

function showZeroResults() {
	echo '<div class=\'note warning\'>';
	echo '<h3>';
	echo '#Results: 0';
	echo '</h3>';
	echo '<p>Either you are not allowed to see the note or there are no notes with this item!</p>';
	echo '</div>';
}


// from edit.php

/* **************************************************************
 * Insert different relations between the tables
 * **************************************************************
 */



	function showEditNoteLink($note, $notePublic, $editLink){
		if($notePublic == 1){
			//echo "<a href='?type=note&part=edit&id=".$note."' class='public' title='edit'>e</a>";
			echo "<a href='".$editLink."' class='edit public' title='edit' name=".$note.">e</a>";
		} else {
			//echo "<a href='?type=note&part=edit&id=".$note."' class='nonpublic' title='edit'>e</a>";
			echo "<a href='".$editLink."' class='edit nonpublic' title='edit' name=".$note.">e</a>";
		}
	}

	function editSource($source){
		$sourceTyp = "";
		$sql = mysql_query("SELECT sourceTyp, bibTypName FROM source, bibTyp WHERE sourceID = ".$source." AND source.sourceTyp = bibTyp.bibTypID");
		while ($row = mysql_fetch_object($sql)){
			$sourceTyp = $row->bibTypName;

		}
		linkEdit('source', $source, $sourceTyp);
	}

/* **************************************************************
 * All functions for formulars
 * **************************************************************
 */

function formSelect($table) {
	$tableName = $table."Name";
	if($table == 'category' || $table == 'project'){
		echo "<option selected>".$table."</option>";
	} else {
		echo "<option></option>";
	}
	$select = mysql_query("SELECT ".$tableName." FROM ".$table." ORDER BY ".$table."Name");
	while($row = mysql_fetch_object($select)){
		$option = $row->$tableName;
		echo "<option>".$option."</option>";
	}
}

function formSelectTyp($table,$typ) {
	$tableName = $table."Name";
	$tableID = $table."ID";
	$typSQL = mysql_query("SELECT bibTypID FROM bibTyp WHERE bibTypName = '".$typ."'");
	while($row = mysql_fetch_object($typSQL)){
		$bibTypID = $row->bibTypID;
	}
	echo "<option selected>".$table."</option>";
	$select = mysql_query("SELECT ".$tableName." FROM ".$table." WHERE sourceTyp = ".$bibTypID." ORDER BY ".$table."Name");
	while($row = mysql_fetch_object($select)){
		$option = $row->$tableName;
		echo "<option>".$option."</option>";
	}
}
function formSelectedTyp($table, $typ, $inName) {
	$tableName = $table."Name";
	$tableID = $table."ID";
	$typSQL = mysql_query("SELECT bibTypID FROM bibTyp WHERE bibTypName = '".$typ."'");
	while($row = mysql_fetch_object($typSQL)){
		$bibTypID = $row->bibTypID;
	}
	echo "<option selected>".$inName."</option>";
	$select = mysql_query("SELECT ".$tableName." FROM ".$table." WHERE sourceTyp = ".$bibTypID." ORDER BY ".$table."Name");
	while($row = mysql_fetch_object($select)){
		$option = $row->$tableName;
		echo "<option>".$option."</option>";
	}
}

function formSelected($table, $selectedID) {
	$tableName = $table."Name";
	$tableID = $table."ID";
	if ($selectedID != "0") {
		$selected = mysql_query("SELECT ".$tableName." FROM ".$table." WHERE ".$tableID." = ".$selectedID);
		while($row = mysql_fetch_object($selected)){
			$selectedName = $row->$tableName;
		}
		$sql = mysql_query("SELECT ".$tableName." FROM ".$table." ORDER BY ".$table."Name");
		while($row = mysql_fetch_object($sql)){
			if ($row->$tableName==$selectedName){
				echo "<option selected>".$selectedName."</option>";
			} else {
				echo "<option>".$row->$tableName."</option>";
			}
		}
		echo "<option>".$table."</option>";
	} else {
		formSelect($table);
	}
}

 function addButton($id, $name, $table, $typ) {
	 $addTyp = "add".$typ;
	 $editID = $name."ID";

	 echo "<form name='".$addTyp."' action='".$addTyp.".php' method='get'>";
	 echo "<input type='hidden' name='".$editID."' value='".$id."' />";
	 echo "<input class='button' type='submit' value='+ ".$typ."' />";
	 echo "</form>";
	 echo "<br />";
 }

function insertField($field, $fieldValue, $sourceID){
	$selectField = mysql_query("SELECT bibFieldID FROM bibField WHERE bibFieldName = '".$field."'");
	while($row = mysql_fetch_object($selectField)){
		$bibFieldID = $row->bibFieldID;
	}
	if($field == 'crossref') {
		$selectSource = mysql_query("SELECT sourceID FROM source WHERE sourceName = '".$fieldValue."'");
		while($row = mysql_fetch_object($selectSource)){
			$fieldValue = $row->sourceID;
		}
	}

	$insertTyp = "INSERT INTO sourceDetail (sourceID, bibFieldID, sourceDetailName)	VALUES (\"".$sourceID."\", \"".$bibFieldID."\", \"".$fieldValue."\");";
	if (!mysql_query($insertTyp)){
		die('Error: ' . mysql_error());
	}
}

function prep4js($table) {
	$tableName = $table."Name";
	$tableID = $table."ID";
	$select = mysql_query("SELECT ".$tableName." FROM ".$table." ORDER BY ".$table."Name");
	echo "<textarea class='prep4js'>";
	while($row = mysql_fetch_object($select)){
		$option = $row->$tableName;

		echo $option . "//";
	}
	echo "</textarea>";
}
