<?php
/* ************************************************************** 
 * Search the media and show it
 * ************************************************************** 
 */
 
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

			echo "<a href='?sourceID=".$sourceName."&amp;page=1' class='edit' >note</a>";

		
	}
	
?>
