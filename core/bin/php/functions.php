<?php

function condb($conart) {
	$mysqli = new mysqli($GLOBALS['nb']['host'], $GLOBALS['nb']['user'], $GLOBALS['nb']['pass'], $GLOBALS['nb']['db']);
	if ($mysqli->connect_errno) {
		die('Connect Error: ' . $mysqli->connect_errno);
	}
	if($conart == 'close') {
		mysqli_close($mysqli);
		$mysqli = null;
	}
	return $mysqli;
}

function conuser($token) {
	$user = array(
		'access' => 1,
		'name' => 'guest',
		'id' => '',
		'avatar' => ''
	);
	// check if the access is true and correct
	$token = (explode('-', $token . '-'));
	$mysqli = condb('open');
	$sql = $mysqli->query("SELECT user, userID, email FROM user WHERE userID = " . $token[1] . " AND token = '" . $token[0] . "';");
	condb('close');
	$num_results = mysqli_num_rows($sql);
	if ($num_results > 0) {
		while ($row = mysqli_fetch_object($sql)) {
			$avatar = __SITE_URL__ . '/data/user/' . $row->userID;
			if (@fopen($avatar, "r") == false) {
				$avatar = 'http://www.gravatar.com/avatar/' . md5($row->email);
				if (@fopen($avatar, "r") == false) {
					$avatar = __MEDIA_URL__ . '/user/' . $row->userID;
				}
			}
			$user = array(
				'access' => 0,
				'name' => $row->user,
				'id' => $row->userID,
				'avatar' => $avatar
			);
		}
	}
	return ($user);
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

function change4Tex($text){
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


	$upas = array(" &quot;"=>" ``", "&quot; "=>"'' ", "&quot;, "=>"'', ", "&quot;. "=>"''. ", " &#039;"=>" `", "&#039; "=>"' ", "&#039;, "=>"', ", "&#039;. "=>"'. ", " - "=>" -- ", " — "=>" -- ", "_"=>"\_", "§"=>"\§", "$"=>"\$", " & "=>" \& ", "#"=>"\#", "{"=>"\{", "}"=>"\}", "%"=>"\%", "~"=>"\textasciitilde", "€"=>"\texteuro");
  /*foreach($upas as $umlaut=>$replace){
	return (str_replace($umlaut, $replace, $string));
  }
  */
  return '``' . strtr($text, $upas) . '\'\'';
}

function getLastChar($string){
	$lastChar = substr($string, -1);

	if(($lastChar != '?') && ($lastChar != '!')) {
		$string .=  '.';
	}
	return $string;
}

function save4Tex($string){

//  $upas = array(" \""=>" ``", "\" "=>"'' ", " '"=>" `", " - "=>" -- " );
  /*foreach($upas as $umlaut=>$replace){
	return (str_replace($umlaut, $replace, $string));
  }
  */
//  return strtr($string, $upas);
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
		("!&amp;lt;(nb:)([^ >\n\t]+)&amp;gt;!i", "<a href='".__MAIN_FILE__."?type=note&amp;part=search&amp;id=\\2' title='Search here <\\2>'>\\2</a>", $text);
		
	$text = preg_replace
		("!&lt;(link:)([^ >\n\t]+)(:)([^ >\n\t]+)&gt;!i", "<a href='http://\\2' target='_blank'>\\4</a>", $text);
	$text = preg_replace
		("!&lt;(mailto:)([^ >\n\t]+)&gt;!i", "<a href='\\1\\2'>\\2</a>", $text);
	$text = preg_replace
		("!&lt;(wiki:)([^ >\n\t]+)&gt;!i", "<a href='http://de.wikipedia.org/wiki/\\2' target='_blank' title='Look for <\\2> in wikipedia'>\\2</a>", $text);
	$text = preg_replace
		("!&lt;(nb:)([^ >\n\t]+)&gt;!i", "<a href='".__MAIN_FILE__."?type=note&amp;part=search&amp;id=\\2' title='Search here <\\2>'>\\2</a>", $text);
		
	$text = preg_replace
		("!<(link:)([^ >\n\t]+)(:)([^ >\n\t]+)>!i", "<a href='http://\\2' target='_blank'>\\4</a>", $text);
	$text = preg_replace
		("!<(mailto:)([^ >\n\t]+)>!i", "<a href='\\1\\2'>\\2</a>", $text);
	$text = preg_replace
		("!<(wiki:)([^ >\n\t]+)>!i", "<a href='http://de.wikipedia.org/wiki/\\2' target='_blank' title='Look for <\\2> in wikipedia'>\\2</a>", $text);
	$text = preg_replace
		("!<(nb:)([^ >\n\t]+)>!i", "<a href='".__MAIN_FILE__."?type=note&amp;part=search&amp;id=\\2' title='Search here <\\2>'>\\2</a>", $text);
	return $text;
}

function getIndex($part, $id)
{
	$array = array(
		'id' => '0',
		'name' => ''
	);
	if ($id > 0) {
		$partID = $part . 'ID';
		$mysqli = condb('open');
		$sql = $mysqli->query('SELECT ' . $part . ' FROM ' . $part . ' WHERE `' . $partID . '` = \'' . $id . '\';');
		condb('close');
		// echo 'SELECT ' . $part . ' FROM ' . $part . ' WHERE `' . $partID . '` = \'' . $id . '\';';
		$num_results = mysqli_num_rows($sql);
		if ($num_results > 0) {
			while ($row = mysqli_fetch_object($sql)) {
				$array = array(
					'id' => $id,
					'name' => $row->$part
				);
			}
		}
	}
	return $array;
};

function getIndexMN($type, $part, $id)
{
	$array = array();
	$relTable = "rel_" . $type . "_" . $part;
	$partID = $part . "ID";
	$mysqli = condb('open');
	$sql = $mysqli->query('SELECT ' . $part . '.' . $partID . ', ' . $part . ' FROM ' . $part . ', ' . $relTable . ' WHERE ' . $part . '.' . $partID . ' = ' . $relTable . '.' . $partID . ' AND ' . $relTable . '.' . $type . 'ID = \'' . $id . '\' ORDER BY ' . $part);
	//echo '<br>getIndexMN: SELECT ' . $part . '.' . $partID . ', ' . $part . ' FROM ' . $part . ', ' . $relTable . ' WHERE ' . $part . '.' . $partID . ' = ' . $relTable . '.' . $partID . ' AND ' . $relTable . '.' . $type . 'ID = \'' . $id . '\' ORDER BY ' . $part . '<br>';

	$num_labels = mysqli_num_rows($sql);
	if ($num_labels > 0) {
		while ($row = mysqli_fetch_object($sql)) {
			// get number of notes with this value
			$num_results = mysqli_num_rows($mysqli->query('SELECT * FROM ' . $relTable . ' WHERE ' . $partID . ' = \'' . $row->$partID . '\';'));
			array_push($array, array('id' => $row->$partID, 'name' => $row->$part, 'num' => $num_results));
		}
	}
	condb('close');
	return $array;
};


function getNote2Author($id) {
	$mysqli = condb('open');
	$sql = $mysqli->query('SELECT bib.noteID, note.notePublic FROM rel_bib_author, note, bib WHERE rel_bib_author.authorID = ' . $id . ' AND rel_bib_author.bibID = bib.bibID AND bib.noteID = note.noteID;');
	condb('close');
	$num_results = mysqli_num_rows($sql);
	$notes = array();
	if($num_results > 0) {
		$i=0;



		while($row = mysqli_fetch_object($sql)) {
//			$mysqli = condb('open');
//			$sql = $mysqli->query('SELECT noteID FROM bib WHERE bibID = ' . $brow->bibID . ';');
//			condb('close');
//			while($row = mysqli_fetch_object($sql)){
//				array_push($notes, $row->noteID);
//			}

			$notes[$i]['id'] = $row->noteID;
			$notes[$i]['ac'] = $row->notePublic;
			$i++;
		}
	}
	return $notes;
}


function getNote2Label($id) {
	$mysqli = condb('open');
	$sql = $mysqli->query('SELECT note.noteID, note.notePublic FROM rel_note_label, note WHERE rel_note_label.labelID = ' . $id . ' AND rel_note_label.noteID = note.noteID;');
	condb('close');
	$num_results = mysqli_num_rows($sql);
	$notes = array();
	if($num_results > 0) {
		$i=0;
		while($row = mysqli_fetch_object($sql)) {
			$notes[$i]['id'] = $row->noteID;
			$notes[$i]['ac'] = $row->notePublic;
			$i++;
		}
	}
	return $notes;
}

function getNoteID($id) {

}

function getMedia($media) {
	$media = explode('.', $media.'.');
	$name = $media[0];
	$ext = $media[1];

	$media_tag = '<span class=\'warning invisible\'>[The media file is missing OR \'' . $ext . '\' is not supported]</span>';

	switch($ext){
		case "jpg";
		case "png";
		case "gif";
		case "jpeg";
		case "tif";
		{
			$media_path = __MEDIA_URL__."/pictures/".$name;

			if (@fopen($media_path,"r")==true){

				//if (file_exists($media)){
				//$size = ceil(filesize($media	)/1024);
				//$name = $media_path['filename'];
				$size = getimagesize($media_path);
				// ergibt mit $infoSize[0] für breite und $infoSize[1] für höhe
				$media_tag = "<img class='staticMedia' src='".$media_path."' alt='".$name."' title='".$name."' />";
			}
			break;
		}

		case "pdf";
		{
			$media_path = __MEDIA_URL__."/documents/".$media;
			if (@fopen($media_path,"r")==true){
				$media_tag = "<p class='download'>".$media." (".$size."kb) <a href='".$media_path."' title='Download ".$media." (".$size."kb)' >Open</a></p><br>";
			}
			break;
		}

		case "mp4";
		case "webm";
		{
			$media_path = __MEDIA_URL__."/movies/".$name;
			if (@fopen($media_path,"r")==true){

				$media_tag = "<video>not yet implemented</video>";
				/*
				echo "<video class='dynamicMedia' controls preload='auto' poster='".$media_path."png'>";
				echo "<source src='".__MEDIA_URL__."/movies/".$fileName.".mp4' >";
				//type='video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"'
				echo "<source src='".__MEDIA_URL__."/movies/".$fileName.".webm' >";
				//type='video/webm; codecs=\"vp8, vorbis\"'
				echo "</video>";
				*/
			}
			break;
		}

		case "mp3";
		case "wav";
		{
			$media_path = __MEDIA_URL__."/sound/".$media;
			if (@fopen($media_path,"r")==true){

				$media_tag = "<audio>not yet implemented</audio>";
				/*
				echo "<video class='dynamicMedia' controls preload='auto' poster='".$media_path."png'>";
				echo "<source src='".__MEDIA_URL__."/movies/".$fileName.".mp4' >";
				//type='video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"'
				echo "<source src='".__MEDIA_URL__."/movies/".$fileName.".webm' >";
				//type='video/webm; codecs=\"vp8, vorbis\"'
				echo "</video>";
				*/
				/*
				echo "<audio class='dynamicMedia' controls preload='auto'>";
				echo "<source src='".__MEDIA_URL__."/sound/".$fileName.".mp3' type='audio/mpeg; codecs=mp3'>";
				echo "<source src='".__MEDIA_URL__."/sound/".$fileName.".wav' type='audio/wav; codecs=1'>";
				echo "</audio>";
				*/
			}
			break;
		}

		default; {
		$media_tag = "";
					 // <p class='warning invisible'>[".$ext."] is not supported in notizblogg!?</p>
		}

	}

	return $media_tag;
}










/*

	die();



//	$arrayMN = array();

	$mnSql = mysql_query("SELECT " . $partVal . " FROM " . $part . ", " . $relTable . " WHERE " . $part . "." . $partID . " = " . $relTable . "." . $partID . " AND " . $relTable . "." . $type . "ID = '" . $id . "' ORDER BY " . $partVal);
	//echo ("mnSql (" . $type . " " . $part . "): SELECT " . $partVal . " FROM " . $part . ", " . $relTable . " WHERE " . $part . "." . $partID . " = " . $relTable . "." . $partID . " AND " . $relTable . "." . $type . "ID = '" . $id . "' ORDER BY " . $partVal . "<br>");

	$countMN = mysql_num_rows($mnSql);
	if ($countMN > 0) {
		while ($row = mysql_fetch_object($mnSql)) {
			$relIDs[] = $row[$partVal];
		}
		asort($relIDs);
		$relData = "";
		foreach ($relIDs as $relName) {
			$getRelID = mysql_query("SELECT " . $partID . " FROM " . $part . " WHERE " . $partVal . " = '" . $relName . "'");
			while ($row = mysql_fetch_object($getRelID)) {
				$relID = $row->$partID;
				$countSql = mysql_query("SELECT " . $type . "." . $type . "ID FROM " . $type . ", " . $relTable . " WHERE " . $part . "ID = " . $relID . " AND " . $relTable . "." . $type . "ID = " . $type . "." . $type . "ID");

				//echo "countSql (" . $type . " " . $part . "): SELECT " . $type . "." . $type . "ID FROM " . $type . ", " . $relTable . " WHERE " . $part . "ID = " . $relID . " AND " . $relTable . "." . $type . "ID = " . $type . "." . $type . "ID ORDER BY " . $type . ".dateCreated DESC<br>";

				$countResult = mysql_num_rows($countSql);

				array_push($arrayMN, array('id' => $relID, 'name' => $relName, 'number' => $countResult));
			}


		}
	}
	return $arrayMN;

};

*/

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



		/*
						if(empty ($arrayMN)) {
							$arrayMN = array(
								0 => array(
									'id' => $relID,
									'name' => $relName
								)
							);
						} else {

						}

		*/
				/*
				$i = 0;

				while($i < $countResult){

					$arrayMN = array(
						$i => array(
							'id' => $relID,
							'name' => $relName
						)
					);
//					$arrayMN[$i]['id'] = $relID;
//					$arrayMN[$i]['name'] = $relName;
//					$arrayMN = array("id" => $relID, "name" => $relName);
					$i++;
				}

				/*
				for($i = 0; $countResult > $i; i++) {

					$arrayMN[$i] = $relID;
					$arrayMN[$i] = $relName;
				}
				*/



//				array_push($arrayMN, $relID, $relName);
/*

				if($link == 'link'){
					if($relData == ""){
						$relData="<a href='".__MAIN_FILE__."?".$part."=".$relID."' title='#".$type."s: ".$countResult."'>".$relName."</a>";
					} else {
						$relData.= $delimiter . " <a href='".__MAIN_FILE__."?".$part."=".$relID."' title='#".$type."s: ".$countResult."'>".$relName."</a>";
					}

				} else {
					if($relData == ""){
						$relData= $relName;
					} else {
						$relData.= $delimiter . "" . $relName . "</a>";
					}

				}
				*/
//			}
//	}
//print_r($arrayMN);
//}

function oldgetIndexMN($type, $part, $id){
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



function showMediaOld($id, $media, $title) {
	$mediaFile = explode(".", $media);
	$fileName = $mediaFile[0];
	$extension = $mediaFile[1];
	switch($extension){
		case "jpg";
		case "png";
		case "gif";
		case "jpeg";
		case "tif";
		{
			$mediaInfo = __MEDIA_URL__."/pictures/".$media;

			if (@fopen($mediaInfo,"r")==true){

//			if (file_exists($mediaInfo)){
				//$size = ceil(filesize($mediaInfo)/1024);
				$fileName = $mediaInfo['filename'];
				$infoSize = getimagesize($mediaInfo);
				// ergibt mit $infoSize[0] für breite und $infoSize[1] für höhe
				echo "<img class='staticMedia' src='".__MEDIA_URL__."/pictures/".$media."' alt='".$title."' title='".$id."' />";
			} else {
				echo "<p class='warning' title='".__MEDIA_URL__."/pictures/".$media."'>[The picture file is missing!]</p>";
			}
			break;
		}

		case "pdf";
		{
			$mediaInfo = __MEDIA_URL__."/documents/".$media;
			if (@fopen($mediaInfo,"r")==true){
				echo "<p class='download'>".$media." (".$size."kb) <a href='".__MEDIA_URL__."/documents/".$media."' title='Download ".$media." (".$size."kb)' >Open</a></p><br>";
			} else {
				echo "<p class='warning'>[The pdf document is missing!]</p>";
			}
			break;
		}

		case "mp4";
		case "webm";
		{
			$mediaInfo = __MEDIA_URL__."/movies/".$media;
			if (@fopen($mediaInfo,"r")==true){
				echo "<video class='dynamicMedia' controls preload='auto' poster='".__MEDIA_URL__."/movies/".$fileName.".png'>";
				echo "<source src='".__MEDIA_URL__."/movies/".$fileName.".mp4' >";
				//type='video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"'
				echo "<source src='".__MEDIA_URL__."/movies/".$fileName.".webm' >";
				//type='video/webm; codecs=\"vp8, vorbis\"'
				echo "</video>";
			} else {
				echo "<p class='warning'>[The movie file is missing!]</p>";
			}
			break;
		}

		case "mp3";
		case "wav";
		{
			$mediaInfo = __MEDIA_URL__."/sound/".$media;
			if (file_exists($mediaInfo)){
				echo "<audio class='dynamicMedia' controls preload='auto'>";
				echo "<source src='".__MEDIA_URL__."/sound/".$fileName.".mp3' type='audio/mpeg; codecs=mp3'>";
				echo "<source src='".__MEDIA_URL__."/sound/".$fileName.".wav' type='audio/wav; codecs=1'>";
				echo "</audio>";
			} else {
				echo "<p class='warning'>[The audio file is missing!]</p>";
			}
			break;
		}

		default; {
		echo "<p class='warning'>[".$extension."] is not supported in notizblogg!?</p>";
		}

	}
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

function insertMN($table,$relTable,$data,$linkID,$linkTable){
	$data=trim($data);
	$tableID = $table."ID";
	$tableName = $table."Name";
	$linkTableID = $linkTable."ID";
	// Check Table, if data already exists
	if($data!=""){
		$relSql = mysql_query("SELECT ".$tableID." FROM ".$table." WHERE ".$tableName." = '".$data."'");
		if(mysql_num_rows($relSql)==1) {
			while($row = mysql_fetch_object($relSql)){
				$relIDs[] = $row->$tableID;
			}
		} else {
			// New Data
			mysql_query("INSERT INTO ".$table." (".$tableName.") VALUES ('".$data."')");
			$relIDs[] = mysql_insert_id();
		}
		// Save data in relational Table
		foreach($relIDs as $relID) {
			mysql_query("INSERT INTO ".$relTable." (".$tableID.", ".$linkTableID.") VALUES ('".$relID."', '".$linkID."')");
		}
	}
}


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

