<?php

if($_POST['sTagTitle'] != "" && $_POST['sTyp'] != ""){
	//0. collect Post-Data
	$bibTypName = htmlentities($_POST['sTyp'],ENT_QUOTES,'UTF-8');
	$bibTypSql = mysql_query("SELECT bibTypID FROM bibTyp WHERE bibTypName = '".$bibTypName."'");
	while($row = mysql_fetch_object($bibTypSql)){
		$bibTypID = $row->bibTypID;
	}
	$sourceTitle = htmlentities(save4Tex($_POST['sTitle']),ENT_QUOTES,'UTF-8');
	$sourceTagTitle = htmlentities($_POST['sTagTitle'],ENT_QUOTES,'UTF-8');
	$sourceSubtitle = htmlentities(save4Tex($_POST['sSubTitle']),ENT_QUOTES,'UTF-8');
	$author1 = "";
	$author2 = "";
	$author3 = "";
	$author4 = "";
	if($_POST['sAuthor1']!=""){
		$author1 = htmlentities($_POST['sAuthor1'],ENT_QUOTES,'UTF-8');
		if($_POST['sAuthor2']!=""){
			$author2 = htmlentities($_POST['sAuthor2'],ENT_QUOTES,'UTF-8');
			if($_POST['sAuthor3']!=""){
				$author3 = htmlentities($_POST['sAuthor3'],ENT_QUOTES,'UTF-8');
				if($_POST['sAuthor4']!=""){
					$author4 = htmlentities($_POST['sAuthor4'],ENT_QUOTES,'UTF-8');
				}
			}
		}
	}
	
	$sourceEditor = $_POST['sEditor'];
	$sourceNote = htmlentities(save4Tex($_POST['sNote']),ENT_QUOTES,'UTF-8');
	$sourceYear = $_POST['sYear'];

	$categoryName = htmlentities($_POST['sCategory'],ENT_QUOTES,'UTF-8');
		if ($_POST['sCatNew']) {
			$newCategoryName = htmlentities($_POST['sCatNew'],ENT_QUOTES,'UTF-8');
			$newCatSql = "INSERT INTO category (categoryName) VALUES ('".$newCategoryName."');";
			showError($newCatSql, __LINE__);
			$categoryName = $newCategoryName;
		}
		if ($categoryName != "category") {
		$catSql = mysql_query("SELECT categoryID FROM category WHERE categoryName = '".$categoryName."'");
			while($row = mysql_fetch_object($catSql)){
				$categoryID = $row->categoryID;
			}
		} else {
			$categoryID = 0;
			$categoryName = "--";
		}
	
	$projectName = htmlentities($_POST['sProject'],ENT_QUOTES,'UTF-8');
	if ($_POST['sProNew']) {
		$newProjectName = htmlentities($_POST['sProNew'],ENT_QUOTES,'UTF-8');
		$newCatSql = "INSERT INTO project (projectName) VALUES ('".$newProjectName."');";
		showError($newCatSql, __LINE__);
		$projectName = $newProjectName;
	}
	if ($projectName != "project") {
		$catSql = mysql_query("SELECT projectID FROM project WHERE projectName = '".$projectName."'");
		while($row = mysql_fetch_object($catSql)){
			$projectID = $row->projectID;
		}
	} else {
		$projectID = 0;
		$projectName = "--";
	}

	// Referenz für BibLaTex erstellen
	$author1NamePost = $_POST['sAuthor1'];
	$tagTitlePost = $_POST['sTagTitle'];
	if(strpos($author1NamePost,",")!==false){
		$author1Name = substr($author1NamePost,0,strpos($author1NamePost,","));
	} else {
		$author1Name = $author1NamePost;
	}
	$author1Name = substr($author1NamePost,0,strpos($author1NamePost,","));
	$authorNameTex = changeUmlaut($author1Name);
	$tagTitleTex = changeUmlaut($tagTitlePost);
	$sourceName = $authorNameTex.":".$tagTitleTex;
	
	// End of point 0: data collection //
	$back2path = $_POST['path'];



	//1. new note? true when delete not exist
//	if ($_POST['delete'] && $_POST['sCheckID'] != "") {
	if ($_POST['sourceID'] == "") {
	// Auf existierenden Datensatz überprüfen
		if(preg_match('/^[a-f0-9]{32}$/',$_POST['sCheckID'])){
			$sqlCheck = mysql_query("SELECT sourceID FROM source WHERE checkID = '".$_POST['sCheckID']."'");
			if(@mysql_num_rows($sqlCheck) == 1){
				echo "<div class='note'><p class='advice'>This source <strong>" . $sourceID . "</strong> already exists.</p></div>";
				showSource($sourceID, $access);
				echo "<a href='" . $back2path . "' class='goback'>Go Back</a>";

			} else {
				$sql="INSERT INTO source (sourceName, sourceTitle, sourceSubtitle, sourceYear, sourceTyp, sourceEditor, sourceNote, sourceCategory, sourceProject) VALUES
				(\"".$sourceName."\", \"".$sourceTitle."\", \"".$sourceSubtitle."\", ".$sourceYear.", \"".$bibTypID."\", \"".$sourceEditor."\", \"".$sourceNote."\", \"".$categoryID."\", \"".$projectID."\");";
				showError($sql, __LINE__);
				$query = mysql_query("SELECT sourceID FROM `source` WHERE `sourceName` = '". $sourceName ."' ORDER BY `sourceID` DESC LIMIT 1");
				showError($query, __LINE__);
				$countResults = mysql_num_rows($query);
				if($countResults==1){
					while($row = mysql_fetch_object($query)){
						$sourceID = $row->sourceID;
					}
				} 
			$saveDetails = true; 
			}
		} else {
			echo "<p class='warning'>Checksum is wrong or manipulated!</p>";
		}

	} else {
		
		// if delete exist, we should have a sourceID!?
		$sourceID = $_POST['sourceID'];
		$deleteIt = $_POST['delete'];
		//2. if delete exist: is it yes or no? 
		// NO = edit
		if($deleteIt == 'NO'){
			$update="UPDATE source SET sourceName='".$sourceName."', sourceTitle='".$sourceTitle."', sourceSubtitle='".$sourceSubtitle."', sourceYear='".$sourceYear."', sourceTyp='".$bibTypID."', sourceEditor='".$sourceEditor."', sourceNote='".$sourceNote."', sourceCategory='".$categoryID."', sourceProject='".$projectID."' WHERE sourceID = '".$sourceID."'";
			showError($update, __LINE__);

			$saveDetails = true;
			// YES = delete
		} else if($deleteIt == 'YES'){
			$deleteNow;
			//1. check if there are any connections to notes!?
			$sql = mysql_query("SELECT noteID FROM note WHERE noteSource = " . $sourceID . ";");
			//showError($sql, __LINE__);
			$countNotes = mysql_num_rows($sql);
			if($countNotes > 0) {
				//alert with notes:
				echo "<div class='note'><p class='advice'>You can't delete this source (" . $sourceID . ")! There are some notes connected.</p></div>";
				echo "<a href='" . $back2path . "' class='goback'>Go Back</a>";
				showSource($sourceID, $access);
				while($row = mysql_fetch_object($sql)){
					$link2noteID = $row->noteID;
					showNote($link2noteID, $access);
				}
				$deleteNow = false;

			}
			// 2. check in case of 'book', 'collection' or 'proceedings': 
			//     is there an inbook, incollection or inproceeding?
			$crossrefSql = mysql_query("SELECT bibFieldID FROM bibField WHERE bibFieldName = 'crossref'");
			//showError($crossrefSql, __LINE__);
				while($row = mysql_fetch_object($crossrefSql)){
					$crossrefID = $row->bibFieldID;
				}
				$sql = mysql_query("SELECT sourceID FROM sourceDetail WHERE bibFieldID = " . $crossrefID . " AND sourceDetailName = '" . $sourceID . "'");
				//showError($sql, __LINE__);
				$countNotes = mysql_num_rows($sql);
				if($countNotes > 0) {
					//alert with crossrefs:
					echo "<div class='note'><p class='advice'>You can't delete this source (" . $sourceID . ")! It's a " . $bibTypName . " with other sources in it</p></div>";
					echo "<a href='" . $back2path . "' class='goback'>Go Back</a>";
					showSource($sourceID, $access);
					while($row = mysql_fetch_object($sql)){
						$link2sourceID = $row->noteID;
						showSource($link2sourceID, $access);
					}
					$deleteNow = false;
					
				}
				if(!isset($deleteNow)){
					$querySource = "DELETE FROM source WHERE sourceID=".$sourceID.";";
					$dropSource = mysql_query ($querySource);
					showError($querySource, __LINE__);
					echo "<div class='note'>";
					echo "<p class='warning'>";
						echo "The source (" . $sourceID . ") has been deleted<br><br>";
						echo "bibRef: ". $sourceName ."<br>";
						echo "title: ". $sourceTitle ."<br>";
						echo "subtitle: ". $sourceSubtitle ."<br>";
						echo "note: ". $sourceNote ."<br>";
					echo "</p></div>";
					echo "<a href='" . $back2path . "' class='goback'>Go Back</a>";
				}
		}
		// then we have to delete all sourceDetails
		// in the case of delete == yes, then it is o.k.
		// in the case of delete == no, then we're inserting the new details
		// Zettel-Register-Verbindungen erst löschen, danach neu speichern
		
		$delRelAuthor = mysql_query("DELETE FROM `rel_source_author` WHERE `sourceID` = " . $sourceID . ";");
		//showError($delRelAuthor, __LINE__);
		
		$delRelLocation = mysql_query("DELETE FROM `rel_source_location` WHERE `sourceID` = " . $sourceID . ";");
		//showError($delRelLocation, __LINE__);

		$delRelDetail = mysql_query("DELETE FROM `sourceDetail` WHERE `sourceID` = " . $sourceID . ";");
		//showError($delRelDetail, __LINE__);
		

	}
	if(isset($saveDetails)){
				// Source-Author-Verbindung neu speichern
				if($author1!=""){
					insertMN('author','rel_source_author',$author1,$sourceID,'source');
				}
				if($author2!=""){
					insertMN('author','rel_source_author',$author2,$sourceID,'source');
				}
				if($author3!=""){
					insertMN('author','rel_source_author',$author3,$sourceID,'source');
				}
				if($author4!=""){
					insertMN('author','rel_source_author',$author4,$sourceID,'source');
				}
				
				switch($bibTypName){
					case "article";
						$journaltitle = htmlentities($_POST['journaltitle'],ENT_QUOTES,'UTF-8');
						insertField('article', $journaltitle, $sourceID);
						break;
					case "book";
					case "booklet";
					case "collection";
						$location1 = "";
						$location2 = "";
						$location3 = "";
						$location4 = "";
						if($_POST['sLocation1']!=""){
							$location1 = htmlentities($_POST['sLocation1'],ENT_QUOTES,'UTF-8');
							if($_POST['sLocation2']!=""){
								$location2 = htmlentities($_POST['sLocation2'],ENT_QUOTES,'UTF-8');
								if($_POST['sLocation3']!=""){
									$location3 = htmlentities($_POST['sLocation3'],ENT_QUOTES,'UTF-8');
									if($_POST['sLocation4']!=""){
										$location4 = htmlentities($_POST['sLocation4'],ENT_QUOTES,'UTF-8');
									}
								}
							}
						}
						// Source-Location-Verbindung neu speichern
						if($location1!=""){
							insertMN('location','rel_source_location',$location1,$sourceID,'source');
						}
						if($location2!=""){
							insertMN('location','rel_source_location',$location2,$sourceID,'source');
						}
						if($location3!=""){
							insertMN('location','rel_source_location',$location3,$sourceID,'source');
						}
						if($location4!=""){
							insertMN('location','rel_source_location',$location4,$sourceID,'source');
						}
						break;
					case "online";
						$url = $_POST['url'];
						insertField('url', $url, $sourceID);
						$urldate = $_POST['urldate'];
						insertField('urldate', $urldate, $sourceID);
						break;
					case "proceedings";
						$eventtitle = htmlentities($_POST['eventtitle'],ENT_QUOTES,'UTF-8');
						insertField('eventtitle', $eventtitle, $sourceID);
						$venu = htmlentities($_POST['venue'],ENT_QUOTES,'UTF-8');
						insertField('venu', $venu, $sourceID);
						
						$location1 = "";
						$location2 = "";
						$location3 = "";
						$location4 = "";
						if($_POST['sLocation1']!=""){
							$location1 = htmlentities($_POST['sLocation1'],ENT_QUOTES,'UTF-8');
							if($_POST['sLocation2']!=""){
								$location2 = htmlentities($_POST['sLocation2'],ENT_QUOTES,'UTF-8');
								if($_POST['sLocation3']!=""){
									$location3 = htmlentities($_POST['sLocation3'],ENT_QUOTES,'UTF-8');
									if($_POST['sLocation4']!=""){
										$location4 = htmlentities($_POST['sLocation4'],ENT_QUOTES,'UTF-8');
									}
								}
							}
						}
						break;
					case "report";
					case "thesis";
						$type = htmlentities($_POST['type'],ENT_QUOTES,'UTF-8');
						insertField('type', $type, $sourceID);
						$institution = htmlentities($_POST['institution'],ENT_QUOTES,'UTF-8');
						insertField('institution', $institution, $sourceID);
						break;
					case "inbook";
						$insource = htmlentities($_POST['inbook'],ENT_QUOTES, 'UTF-8');
						insertField('crossref', $insource, $sourceID);
						$pageStart = htmlentities($_POST['pageStart'],ENT_QUOTES,'UTF-8');
						$pageEnd = htmlentities($_POST['pageEnd'],ENT_QUOTES,'UTF-8');
						if(($pageEnd - $pageStart) <= 0) {
							$pages = $pageStart;
						} else {
							$pages = $pageStart . '--' . $pageEnd;
						}
						insertField('pages', $pages, $sourceID);
						break;
					case "incollection";
						$insource = htmlentities($_POST['incollection'],ENT_QUOTES, 'UTF-8');
						insertField('crossref', $insource, $sourceID);
						$pageStart = htmlentities($_POST['pageStart'],ENT_QUOTES,'UTF-8');
						$pageEnd = htmlentities($_POST['pageEnd'],ENT_QUOTES,'UTF-8');
						if(($pageEnd - $pageStart) <= 0) {
							$pages = $pageStart;
						} else {
							$pages = $pageStart . '--' . $pageEnd;
						}
						insertField('pages', $pages, $sourceID);
						break;
					case "inproceedings";
						$insource = htmlentities($_POST['inproceedings'],ENT_QUOTES, 'UTF-8');
						insertField('crossref', $insource, $sourceID);
						$pageStart = htmlentities($_POST['pageStart'],ENT_QUOTES,'UTF-8');
						$pageEnd = htmlentities($_POST['pageEnd'],ENT_QUOTES,'UTF-8');
						if(($pageEnd - $pageStart) <= 0) {
							$pages = $pageStart;
						} else {
							$pages = $pageStart . '--' . $pageEnd;
						}
						insertField('pages', $pages, $sourceID);
						break;
					case "manual";
					case "misc";				
					case "periodical";				
					case "unpublished";
						$bibField1 = htmlentities($_POST['miscField1'],ENT_QUOTES,'UTF-8');
						$bibFieldVal1 = htmlentities($_POST['miscFieldValue1'],ENT_QUOTES,'UTF-8');
						insertField($bibField1, $bibFieldVal1, $sourceID);
						$bibField2 = htmlentities($_POST['miscField2'],ENT_QUOTES,'UTF-8');
						$bibFieldVal2 = htmlentities($_POST['miscFieldValue2'],ENT_QUOTES,'UTF-8');
						insertField($bibField2, $bibFieldVal2, $sourceID);
						break;
				} // Ende switch
				
				$selectDetail1 = htmlentities($_POST['selectDetail1'],ENT_QUOTES,'UTF-8');
				$valDetail1 = htmlentities($_POST['valDetail1'],ENT_QUOTES,'UTF-8');
				if($selectDetail1 != "" && $valDetail1 != ""){
					insertField($selectDetail1, $valDetail1, $sourceID);
				}
				$selectDetail2 = htmlentities($_POST['selectDetail2'],ENT_QUOTES,'UTF-8');
				$valDetail2 = htmlentities($_POST['valDetail2'],ENT_QUOTES,'UTF-8');
				if($selectDetail2 != "" && $valDetail2 != ""){
					insertField($selectDetail2, $valDetail2, $sourceID);
				}
			
			//all datas should be saved in the database; have a look about
			echo "<div class='note'><p class='advice'>The source (" . $sourceID . ") has been saved</p></div>";
			showSource($sourceID, $access);
			echo "<a href='" . $back2path . "' class='goback'>Go Back</a>";

	}
}


