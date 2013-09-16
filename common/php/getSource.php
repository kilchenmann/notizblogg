<?php
function showSource($source, $access){
		$authorSql = mysql_query("SELECT authorName, author.authorID FROM author, rel_source_author WHERE author.authorID = rel_source_author.authorID AND rel_source_author.sourceID = '".$source."' ORDER BY authorName");
		$countAuthors = mysql_num_rows($authorSql);
		if($countAuthors>0) {
			while($row = mysql_fetch_array($authorSql)) {
				$authorIDs[] = array('authorID' => $row['authorID'],
										'authorName' => $row['authorName']);   
//				$authorIDs[] = $row['authorID'];   
			}
			asort($authorIDs);
				$authors="";
				foreach($authorIDs as $authorID => $authorName) {
					$authorName = "<a href='".MainFile."?type=source&amp;part=author&amp;id=".$authorName['authorID']."'>".$authorName['authorName']."</a>";

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
				$locationIDs[] = $row['locationName'];   
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
				$sourceTitle = $row->sourceTitle;
				$sourceSubtitle = $row->sourceSubtitle;
				$sourceYear = $row->sourceYear;
				$sourceNote = $row->sourceNote;	
				$sourceEditor = $row->sourceEditor;
				$sourceCategory = $row->sourceCategory;
				$sourceProject = $row->sourceProject;
				$sourceTyp = $row->sourceTyp;

				echo "<div class='note'>";
				echo "<p class='content'>";
				if($sourceTyp!=0){
					$typSql = mysql_query("SELECT bibTypName FROM bibTyp WHERE bibTypID = ".$sourceTyp."");
						while($typ = mysql_fetch_object($typSql)){
							$bibTypName = $typ->bibTypName;
							if($bibTypName == 'collection' || $bibTypName == 'proceedings'){
								echo "@<a href='".MainFile."?type=source&amp;part=collection&amp;id=".$sourceID."' class='text' >".$bibTypName."</a>";
							} else {
								echo "@".$bibTypName;
							}
						}
				}
				//echo "<br>";
				echo "{".$sourceName.",";
				if($sourceEditor==1){
						echo "<br>editor = ";
					} else {
						echo "<br>author = ";
					}
					echo "{".$authors."},";
					echo "<br>title = ";
					echo "{".$sourceTitle."},";
					if($sourceSubtitle!=""){
						echo "<br>subtitle = ";
						echo "{".$sourceSubtitle."},";
					}
					if($locations!=""){
						echo "<br>location = ";
						echo "{".$locations."},";
					}
					if($sourceYear!="0000"){
						echo "<br>year = ";
						echo "{".$sourceYear."},";
					}
					$selectDetail = mysql_query("SELECT * FROM sourceDetail WHERE sourceID = '".$sourceID."'");
					while($row = mysql_fetch_object($selectDetail)){
						$bibFieldID = $row->bibFieldID;
						$sourceDetailName = $row->sourceDetailName;
						
						$selectField = mysql_query("SELECT bibFieldName FROM bibField WHERE bibFieldID = '".$bibFieldID."'");
							while($row = mysql_fetch_object($selectField)){
								$bibFieldName = $row->bibFieldName;
							}
							
							if($bibFieldName=="crossref"){
								$selectSource = mysql_query("SELECT * FROM source WHERE sourceID = '".$sourceDetailName."'");
									while($inrow = mysql_fetch_object($selectSource)) {
										$sourceInID = $inrow->sourceID;
										$sourceInName = $inrow->sourceName;
										$sourceInTitle = $inrow->sourceTitle;
										$sourceInSubtitle = $inrow->sourceSubtitle;
										
										echo "<br><a href='".MainFile."?type=source&amp;part=collection&amp;id=".$sourceInID."' class='text' >crossref</a> = {".$sourceInName."},";
										
										$authorSql = mysql_query("SELECT authorName FROM author, rel_source_author WHERE author.authorID = rel_source_author.authorID AND rel_source_author.sourceID = '".$sourceInID."' ORDER BY authorName");
										$countAuthors = mysql_num_rows($authorSql);
										if($countAuthors>0) {
											while($row = mysql_fetch_array($authorSql)) {
												$inAuthorIDs[] = $row['authorName'];   
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
													echo "<br>bookauthor = {".$inAuthors."},";
												} else {
													echo "<br>editor = {".$inAuthors."},";
												}
											}
									}
									echo "<br>booktitle = {".$sourceInTitle."},";
									echo "<br>booksubtitle = {".$sourceInSubtitle."},";
								
								
							} elseif($bibFieldName=="url") {
								echo "<br><a href='".$sourceDetailName."' class='text' title='extern'>".$bibFieldName."</a> = {".$sourceDetailName."},";
							
							} else {
								echo "<br>".$bibFieldName." = {".$sourceDetailName."},";
							}
					}
					
					echo "<br><a href='".MainFile."?type=note&amp;part=source&amp;id=".$sourceID."' class='text' >note</a> = ";
					if($sourceNote!=""){
						echo "{".$sourceNote."}},";
					} else {echo "{}},";}
			}
			echo "<div class='set'>";
				echo "<button class='mark'>mark</button>";
			echo "</div>";
		echo "<br>";
		echo "<p class='linkText'>";
		echo linkIndex('source', 'category', $sourceCategory);
		echo " &gt; ";
		echo linkIndex('source', 'project', $sourceProject);
			
			if(($_SERVER["QUERY_STRING"]) && (!isset($_GET["editSource"]))){
				$editLink = MainFile."?".$_SERVER['QUERY_STRING']."&amp;editSource=".$sourceID;
			} else {
				$editLink = MainFile."?editSource=".$sourceID;
			}
			if($access != 'public'){
				showEditNoteLink($sourceID, $notePublic, $editLink);
			}
		echo "</p>";
		echo "</div>";
}

function exportSource($source, $handle){

	$authorSql = mysql_query("SELECT authorName, author.authorID FROM author, rel_source_author WHERE author.authorID = rel_source_author.authorID AND rel_source_author.sourceID = '".$source."' ORDER BY authorName");
	$countAuthors = mysql_num_rows($authorSql);
	if($countAuthors>0) {
		while($row = mysql_fetch_array($authorSql)) {
			$authorIDs[] = array('authorID' => $row['authorID'],
									'authorName' => $row['authorName']);   
		}
		asort($authorIDs);
		$authors="";
		foreach($authorIDs as $authorID => $authorName) {
			$authorName = $authorName['authorName'];
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
			$locationIDs[] = $row['locationName'];   
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
		$sourceTitle = $row->sourceTitle;
		$sourceSubtitle = $row->sourceSubtitle;
		$sourceYear = $row->sourceYear;
		$sourceNote = $row->sourceNote;	
		$sourceEditor = $row->sourceEditor;
		$sourceCategory = $row->sourceCategory;
		$sourceProject = $row->sourceProject;
		$sourceTyp = $row->sourceTyp;

		if($sourceTyp!=0){
			$typSql = mysql_query("SELECT bibTypName FROM bibTyp WHERE bibTypID = ".$sourceTyp."");
			while($typ = mysql_fetch_object($typSql)){
				$bibTypName = $typ->bibTypName;
				fwrite($handle, "@" . $bibTypName . "{" . $sourceName . "," . PHP_EOL);
			}
		}
		if($sourceEditor==1){
			fwrite($handle, "editor = {" . $authors . "}," . PHP_EOL);
		} else {
			fwrite($handle, "author = {" . $authors . "}," . PHP_EOL);
		}
		fwrite($handle, "title = {" . $sourceTitle . "}," . PHP_EOL);
		
		if($sourceSubtitle!=""){
			fwrite($handle, "subtitle = {" . $sourceSubtitle . "}," . PHP_EOL);
		}
		if($locations!=""){
			fwrite($handle, "location = {" . $locations . "}," . PHP_EOL);
		}
		if($sourceYear!="0000"){
			fwrite($handle, "year = {" . $sourceYear . "}," . PHP_EOL);
		}
//		fwrite($handle, " = {" . $ . "}," . PHP_EOL);
		$selectDetail = mysql_query("SELECT * FROM sourceDetail WHERE sourceID = '".$sourceID."'");
		while($row = mysql_fetch_object($selectDetail)){
			$bibFieldID = $row->bibFieldID;
			$sourceDetailName = $row->sourceDetailName;
			
			$selectField = mysql_query("SELECT bibFieldName FROM bibField WHERE bibFieldID = '".$bibFieldID."'");
			while($row = mysql_fetch_object($selectField)){
				$bibFieldName = $row->bibFieldName;
			}
				
			if($bibFieldName=="crossref"){
				$selectSource = mysql_query("SELECT * FROM source WHERE sourceID = '".$sourceDetailName."'");
				while($inrow = mysql_fetch_object($selectSource)) {
					$sourceInID = $inrow->sourceID;
					$sourceInName = $inrow->sourceName;
					$sourceInTitle = $inrow->sourceTitle;
					$sourceInSubtitle = $inrow->sourceSubtitle;
					fwrite($handle, "crossref = {" . $sourceInName . "}," . PHP_EOL);
					$authorSql = mysql_query("SELECT authorName FROM author, rel_source_author WHERE author.authorID = rel_source_author.authorID AND rel_source_author.sourceID = '".$sourceInID."' ORDER BY authorName");
					$countAuthors = mysql_num_rows($authorSql);
					if($countAuthors>0) {
						while($row = mysql_fetch_array($authorSql)) {
							$inAuthorIDs[] = $row['authorName'];
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
							fwrite($handle, "bookauthor = {" . $inAuthors . "}," . PHP_EOL);
						} else {
							fwrite($handle, "editor = {" . $inAuthors . "}," . PHP_EOL);
						}
					}
				}
				fwrite($handle, "booktitle = {" . $sourceInTitle . "}," . PHP_EOL);
				fwrite($handle, "booksubtitle = {" . $sourceInSubtitle . "}," . PHP_EOL);
			} else {
				fwrite($handle, $bibFieldName . " = {" . $sourceDetailName . "}," . PHP_EOL);
			}
		}
		
		fwrite($handle, "note = {" . $sourceNote . "}" . PHP_EOL);
		fwrite($handle, "}," . PHP_EOL);
		fwrite($handle, "" . PHP_EOL);
	}
}


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
		echo "<p class='linkText'>--&gt; <a href='?sourceID=".$sourceName."&amp;page=1' title='source'>".$source."</a></p>";
	}
}
?>
