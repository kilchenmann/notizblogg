<?php

/* ************************************************************** 
 * Insert different relations between the tables
 * ************************************************************** 
 */
function insert1N(){
	
}

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

/* ************************************************************** 
 * Search different relations between the tables
 * ************************************************************** 
 */



function linkIndexMNlist($id,$table){
	$tableID = $table."ID";
	$tableName = $table."Name";
	
	$tagSql = mysql_query("SELECT tagName FROM tag, rel_note_tag WHERE tag.tagID = rel_note_tag.tagID AND rel_note_tag.noteID = '".$id."' ORDER BY tagName");
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
					$relData="<li>- <a href='index.php?".$tableID."=".$relName."&amp;page=1'>".$relName."</a></li>";
				} else {
					$relData.= "<li>- <a href='index.php?".$tableID."=".$relName."&amp;page=1'>".$relName."</a></li>";
				}
			}
	} else {
		$relData = "";
	}
	echo $relData;
}




/* ************************************************************** 
 * Search the media to the note and show it
 * ************************************************************** 
 */


function editMedia($noteMedia){
		$mediaInfo = pathinfo($GLOBALS['folder']."media/".$noteMedia);
		if($noteMedia!="choose"||$noteMedia!=""){
			if (file_exists($GLOBALS['folder']."media/".$noteMedia)){
				$size = ceil(filesize($GLOBALS['folder']."media/".$noteMedia)/1024);
				$fileName = $mediaInfo['filename'];
				switch($mediaInfo['extension']){
					case "jpg";
					case "png";
					case "gif";
					case "jpeg";
					case "tif";
					echo "<img src='".$GLOBALS['folder']."media/".$noteMedia."' alt='".$noteMedia."' class='editMedia' /></a>";
					break;
				
					case "pdf";
					echo "<a href='".$GLOBALS['folder']."media/".$noteMedia."' title='Download ".$noteMedia." (".$size."kb)' ><img src='css/images/pdf.png' class='editMedia' alt='".$noteMedia."' class='img_big' title='Download ".$noteMedia." (".$size."kb)' /></a>";
					
					break;
					
					case "ogv";
					case "mp4";
					case "webm";
					
						echo "<video class='editMedia' controls preload='auto' width='320' poster='".$GLOBALS['folder']."/".$fileName.".png'>";
							echo "<source src='".$GLOBALS['folder']."media/".$fileName.".mp4' type='video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"'>";
							echo "<source src='".$GLOBALS['folder']."media/".$fileName.".webm' type='video/webm; codecs=\"vp8, vorbis\"'>";
							echo "<source src='".$GLOBALS['folder']."media/".$fileName.".ogv' type='video/ogg; codecs=\"theora, vorbis\"'>";
						echo "</video>";
					break;
					case "ogg";
					case "mp3";
					case "wav";
						echo "<audio class='editMedia' controls preload='auto'>";
							echo "<source src='".$GLOBALS['folder']."media/".$fileName.".mp3' type='audio/mpeg; codecs=mp3'>";
							echo "<source src='".$GLOBALS['folder']."media/".$fileName.".ogg' type='audio/ogg; codecs=vorbis'>";
							echo "<source src='".$GLOBALS['folder']."media/".$fileName.".wav' type='audio/wav; codecs=1'>";
						echo "</audio><br />";
					break;
				}
			} else {
				echo $noteMedia;
				echo " [Media file is missing!]<br /><br />";
			}
		}
	}


function showMediaList($id,$noteTitle,$noteContent,$noteCategory,$noteProject,$noteSourceExtern,$noteSource,$notePageStart,$notePageEnd){
	$mediaSql = mysql_query("SELECT * FROM media WHERE noteID = '".$id."'");
		if (mysql_num_rows($mediaSql)!=0) {
			while($rowMedia = mysql_fetch_object($mediaSql)){
				$mediaID = $rowMedia->mediaID;
				//$GLOBALS['folder'] = "intern/media";
				$mediaFile = $rowMedia->mediaFile;
				$mediaInfo = pathinfo($GLOBALS['folder']."media/".$mediaFile);
				
			if (file_exists($GLOBALS['folder']."/".$noteMedia)){
				$size = ceil(filesize($GLOBALS['folder']."media/".$mediaFile)/1024);
				switch($mediaInfo['extension']){
					case "jpg";
					case "png";
					case "gif";
					case "jpeg";
					case "tif";
					echo "<a class='media_zoom' rel='#".$mediaID."'><img src='".$GLOBALS['folder']."media/".$rowMedia->mediaFile."' alt='".$noteTitle.": ".$noteContent."' /></a><br />";
				/*echo "<object rel='#".$mediaID."' data='media/".$rowMedia->mediaFile."' type='application/pdf' width='320' height='auto' class='media_zoom' ></object>";*/
					echo "<div class='img_overlay' id='".$mediaID."' >";
						echo "<strong>".$noteTitle."</strong><br />";
						echo "<img src='".$GLOBALS['folder']."media/".$mediaFile."' alt='[media not found!]' class='img_big' />";
						showNoteBig($noteID,$noteTitle,$noteContent,$noteCategory,$noteProject,$noteSourceExtern,$noteSource,$notePageStart,$notePageEnd,$notePublic);
					echo "</div>";
					break;
				
					case "pdf";
					echo "<a href='".$GLOBALS['folder']."media/".$mediaFile."' title='Download ".$mediaFile." (".$size."kb)' >".$mediaFile."<img src='css/images/pdf.png' class='media_zoom'  alt='".$mediaFile."' class='img_big' title='Download ".$mediaFile." (".$size."kb)' /></a><br />";
					
					break;
					
					case "ogv";
					case "mp4";
					case "webm";
						echo "<video src='".$GLOBALS['folder']."media/".$mediaFile."' class='media_zoom' controls preload='auto'></video><br />";
					break;
					case "ogg";
					case "mp3";
					case "wav";
						echo "<audio src='".$GLOBALS['folder']."media/".$mediaFile."' class='media_zoom' controls preload='auto'></audio><br />";
					break;
				}
			}
			}

		}
	
	
}

/* ************************************************************** 
 * Search the source and show it
 * ************************************************************** 
 */
function showSourceLink($sourceID,$notePageStart,$notePageEnd){
	if($sourceID!=0){
		$sourceSql = mysql_query("SELECT sourceName FROM source WHERE sourceID='".$sourceID."'");
			while($row = mysql_fetch_object($sourceSql)){
				$sourceName = $row->sourceName;
			}
		if($notePageStart!=0){
			$pages=$notePageEnd-$notePageStart;
			if($pages<=0){
				$source = $sourceName." (".$notePageStart.")";
			} elseif($pages==1){
				$source = $sourceName." (".$notePageStart."f.)";
			} else {
				$source = $sourceName." (".$notePageStart."ff.)";
			}
		} else {
			$source = $sourceName;
		}
		echo "<p class='linkText'>--&gt; <a href='index.php?sourceID=".$sourceName."&amp;page=1' title='source'>".$source."</a></p>";
	}
}




	function showBibEntry($source){
		$authorSql = mysql_query("SELECT authorName FROM author, rel_source_author WHERE author.authorID = rel_source_author.authorID AND rel_source_author.sourceID = '".$source."' ORDER BY authorName");
		$countAuthors = mysql_num_rows($authorSql);
		if($countAuthors>0) {
			while($row = mysql_fetch_array($authorSql)) {
				$authorIDs[] = html_entity_decode($row['authorName'],ENT_NOQUOTES,'UTF-8');   //ISO-8859-15
			}

			asort($authorIDs);
				$authors="";
				foreach($authorIDs as $authorName) {
					if($authors==""){
						$authors=$authorName;
					} else {
						$authors.= " and ".$authorName;
					}
				}
				
		} else {
			$authors = "";
		}

		
		$locationSql = mysql_query("SELECT locationName FROM location, rel_source_location WHERE location.locationID = rel_source_location.locationID AND rel_source_location.sourceID = '".$source."' ORDER BY locationName");
		$countLocations = mysql_num_rows($locationSql);
		if($countLocations>0) {
			while($row = mysql_fetch_array($locationSql)) {
				$locationIDs[] = html_entity_decode($row['locationName'],ENT_NOQUOTES,'UTF-8');   
			}

			asort($locationIDs);
				$locations="";
				foreach($locationIDs as $locationName) {
					if($locations==""){
						$locations=$locationName;
					} else {
						$locations.= " and ".$locationName;
					}
				}
				
		} else {
			$locations = "";
		}

		
		
		$sourceSql = mysql_query("SELECT * FROM source WHERE sourceID='".$source."'");
			while($row = mysql_fetch_object($sourceSql)){
								
				$sourceID = $row->sourceID;
				$sourceName = $row->sourceName;
				$sourceTitle = html_entity_decode($row->sourceTitle,ENT_NOQUOTES,'UTF-8');
				$sourceSubtitle = html_entity_decode($row->sourceSubtitle,ENT_NOQUOTES,'UTF-8');
				$sourceYear = $row->sourceYear;
				$sourceNote = change4Tex(html_entity_decode($row->sourceNote,ENT_NOQUOTES,'UTF-8'));
				$sourceEditor = $row->sourceEditor;

				$sourceTyp = $row->sourceTyp;
				if($sourceTyp!=0){
					$typSql = mysql_query("SELECT bibTypName FROM bibTyp WHERE bibTypID = ".$sourceTyp."");
						while($typ = mysql_fetch_object($typSql)){
							$bibTypName = $typ->bibTypName;
								if($sourceEditor==1){
									$writer = "editor = {".change4Tex($authors)."}";
								} else {
									$writer = "author = {".change4Tex($authors)."}";
								}
									$sourceTitle = "title = {".change4Tex($sourceTitle)."}";
								if($sourceSubtitle!=""){
									$sourceSubtitle = "subtitle = {".change4Tex($sourceSubtitle)."},\n\t";
								}
								if($locations!=""){
									$locations = "location = {".change4Tex($locations)."},\n\t";
								}
								if($sourceYear!="0000"){
									$year = "year = {".$sourceYear."},\n\t";
								}
								// es folgen spezifische Felder
								$selectDetail = mysql_query("SELECT * FROM sourceDetail WHERE sourceID = '".$sourceID."'");
								$countDetail = mysql_num_rows($selectDetail);
								$details="";
								$crossRef="";
								if($countDetail>0){
								$i=1; 
									
								while($row = mysql_fetch_object($selectDetail)){
									$bibFieldID = $row->bibFieldID;
									$sourceDetailName = html_entity_decode($row->sourceDetailName,ENT_NOQUOTES,'UTF-8');
									
									
									$selectField = mysql_query("SELECT bibFieldName FROM bibField WHERE bibFieldID = '".$bibFieldID."'");
										while($row = mysql_fetch_object($selectField)){
											$bibFieldName = $row->bibFieldName;
										}
										

										if($bibFieldName=="crossref"){
											$selectSource = mysql_query("SELECT * FROM source WHERE sourceID = '".$sourceDetailName."'");
												while($inrow = mysql_fetch_object($selectSource)) {
													$sourceInID = $inrow->sourceID;
													global $crossrefID;
													$crossrefID = $sourceInID;
													
													$sourceInName = html_entity_decode($inrow->sourceName,ENT_NOQUOTES,'UTF-8');
													$sourceInTitle = html_entity_decode($inrow->sourceTitle,ENT_NOQUOTES,'UTF-8');
													$sourceInSubtitle = html_entity_decode($inrow->sourceSubtitle,ENT_NOQUOTES,'UTF-8');
													
													$crossRef = "crossref = {".$sourceInName."},\n\t";
													$inWriter = "";
													$authorSql = mysql_query("SELECT authorName FROM author, rel_source_author WHERE author.authorID = rel_source_author.authorID AND rel_source_author.sourceID = '".$sourceInID."' ORDER BY authorName");
													$countAuthors = mysql_num_rows($authorSql);
													if($countAuthors>0) {
														while($row = mysql_fetch_array($authorSql)) {
															$inAuthorIDs[] = html_entity_decode($row['authorName'],ENT_NOQUOTES,'UTF-8');   
														}

														asort($inAuthorIDs);
															$inAuthors="";
															foreach($inAuthorIDs as $inAuthorName) {
																if($inAuthors==""){
																	$inAuthors=$inAuthorName;
																} else {
																	$inAuthors.= " and ".$inAuthorName;
																}
															}
															
													} else {
														$inAuthors = "";
													}
													$editorSql = mysql_query("SELECT sourceEditor FROM source WHERE sourceID = ".$sourceInID."");
													while($row = mysql_fetch_object($editorSql)){
														if($row->sourceEditor==0){
															$inWriter = "bookauthor = {".change4Tex($inAuthors)."},\n\t";
														} else {
															$inWriter = "editor = {".change4Tex($inAuthors)."},\n\t";
														}
													}
													
												}
												$bookTitle = "booktitle = {".change4Tex($sourceInTitle)."},\n\t";
												$bookSubTitle = "booksubtitle = {".change4Tex($sourceInSubtitle)."},\n\t";
											
											$crossRef = $crossRef."".$inWriter."".$bookTitle."".$bookSubTitle;
										} else {
											$detailFields[$i]= $bibFieldName." = {".change4Tex($sourceDetailName)."},\n\t";
										}
										$i++;
								}
								
								foreach ($detailFields as $detailField){
									if($details==""){
										$details=$detailField;
									} else {
										$details.="".$detailField."";
									}
								}
							}
							echo "<br /><br />";
							//Ausgabe für das bib-File
							//return "\n@".$bibTypName."{".$sourceName.",\n\t".$writer.",\n\t".$sourceTitle.",\n\t".$sourceSubtitle."".$locations."".$year."".$details."".$crossRef."note = {".$sourceNote."}\n},\n";
							return "\n@".$bibTypName."{".$sourceName.",\n\t".$writer.",\n\t".$sourceTitle.",\n\t".$sourceSubtitle."".$locations."".$year."".$details."".$crossRef."note = {}\n},\n";
						}

				}


			}

	}

	function link2Source($source){
		$selectName = mysql_query("SELECT sourceName FROM source WHERE sourceID = ".$source."");
			while($row = mysql_fetch_object($selectName)){
				$sourceName = $row->sourceName;
			}

			echo "<a href='index.php?sourceID=".$sourceName."&amp;page=1' class='edit' >note</a>";

		
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
	echo "<option selected>".$table."</option>";
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
			
		$insertTyp = "INSERT INTO sourceDetail (sourceID, bibFieldID, sourceDetailName)	VALUES
		(\"".$sourceID."\", \"".$bibFieldID."\", \"".$fieldValue."\");";
		if (!mysql_query($insertTyp)){
			die('Error: ' . mysql_error());
		}
}

?>
