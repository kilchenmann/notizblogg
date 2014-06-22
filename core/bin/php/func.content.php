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
		("!&amp;lt;(nb:)([^ >\n\t]+)&amp;gt;!i", "<a href=\"".__MAIN_FILE__."?type=note&amp;part=search&amp;id=\\2\" title=\"Search here <\\2>\">\\2</a>", $text);
		
	$text = preg_replace
		("!&lt;(link:)([^ >\n\t]+)(:)([^ >\n\t]+)&gt;!i", "<a href=\"http://\\2\" target=\"_blank\">\\4</a>", $text);
	$text = preg_replace
		("!&lt;(mailto:)([^ >\n\t]+)&gt;!i", "<a href=\"\\1\\2\">\\2</a>", $text);
	$text = preg_replace
		("!&lt;(wiki:)([^ >\n\t]+)&gt;!i", "<a href=\"http://de.wikipedia.org/wiki/\\2\" target=\"_blank\" title=\"Look for <\\2> in wikipedia\">\\2</a>", $text);
	$text = preg_replace
		("!&lt;(nb:)([^ >\n\t]+)&gt;!i", "<a href=\"".__MAIN_FILE__."?type=note&amp;part=search&amp;id=\\2\" title=\"Search here <\\2>\">\\2</a>", $text);
		
	$text = preg_replace
		("!<(link:)([^ >\n\t]+)(:)([^ >\n\t]+)>!i", "<a href=\"http://\\2\" target=\"_blank\">\\4</a>", $text);
	$text = preg_replace
		("!<(mailto:)([^ >\n\t]+)>!i", "<a href=\"\\1\\2\">\\2</a>", $text);
	$text = preg_replace
		("!<(wiki:)([^ >\n\t]+)>!i", "<a href=\"http://de.wikipedia.org/wiki/\\2\" target=\"_blank\" title=\"Look for <\\2> in wikipedia\">\\2</a>", $text);
	$text = preg_replace
		("!<(nb:)([^ >\n\t]+)>!i", "<a href=\"".__MAIN_FILE__."?type=note&amp;part=search&amp;id=\\2\" title=\"Search here <\\2>\">\\2</a>", $text);
	return $text;
}

function linkIndex($type, $part, $id) {
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
					$relData=" <a href='".__MAIN_FILE__."?type=".$type."&amp;part=".$part."&amp;id=".$relID."' title='#".$type."s: ".$countResult."'>".$relName."</a>";
				} else {
					$relData.= ", <a href='".__MAIN_FILE__."?type=".$type."&amp;part=".$part."&amp;id=".$relID."' title='#".$type."s: ".$countResult."'>".$relName."</a>";
				}
			}
	} else {
		$relData = "";
	}
	return $relData;
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



function showMedia($id, $media, $title) {
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