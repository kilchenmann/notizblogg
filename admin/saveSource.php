<?php
if($_POST['sTagTitle'] != "" && $_POST['sTyp'] != ""){
	//0. collect Post-Data
	$bibTypName = htmlentities($_POST['sTyp'],ENT_QUOTES,'UTF-8');
	$bibTypSql = mysql_query("SELECT bibTypID FROM bibTyp WHERE bibTypName = '".$bibTypName."'");
	while($row = mysql_fetch_object($bibTypSql)){
		$bibTypID = $row->bibTypID;
	}
	$sourceTitle = htmlentities($_POST['sTitle'],ENT_QUOTES,'UTF-8');
	$sourceTagTitle = htmlentities($_POST['sTagTitle'],ENT_QUOTES,'UTF-8');
	$sourceSubtitle = htmlentities($_POST['sSubTitle'],ENT_QUOTES,'UTF-8');
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
	$sourceNote = htmlentities($_POST['sNote'],ENT_QUOTES,'UTF-8');
	$sourceYear = $_POST['sYear'];
	if($sourceYear == ""){
		$sourceYear = "NULL";
	}
	
	$categoryName = htmlentities($_POST['sCategory'],ENT_QUOTES,'UTF-8');
		if ($_POST['sCatNew']) {
			$newCategoryName = htmlentities($_POST['sCatNew'],ENT_QUOTES,'UTF-8');
			$newCatSql = "INSERT INTO category (categoryName) VALUES ('".$newCategoryName."');";
			if (!mysql_query($newCatSql)){
				die('Error: ' . mysql_error());
			}
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
		if (!mysql_query($newCatSql)){
			die('Error: ' . mysql_error());
		}
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




	// Auf existierenden Datensatz überprüfen
	$checkSource = mysql_query("SELECT sourceID FROM source WHERE sourceName = '".$sourceName."'") or die(mysql_error());
	$countResults = mysql_num_rows($checkSource);
	
	if($countResults==1){
		while($row = mysql_fetch_object($checkSource)){
			$sourceID = $row->sourceID;
		}
		//echo "<div class='note'>";
		echo "<p class='advice'>The source <strong><a href='?type=note&part=source&id= " . $sourceID . "' >'" . $sourceName . "'</a></strong> already exists.</p>";
		showSource($sourceID);

	} else {
	
		$sql="INSERT INTO source (sourceName, sourceTitle, sourceSubtitle, sourceYear, sourceTyp, sourceEditor, sourceNote, sourceCategory, sourceProject) VALUES
		(\"".$sourceName."\", \"".$sourceTitle."\", \"".$sourceSubtitle."\", ".$sourceYear.", \"".$bibTypID."\", \"".$sourceEditor."\", \"".$sourceNote."\", \"".$categoryID."\", \"".$projectID."\");";
		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}
		$query = mysql_query("SELECT sourceID FROM `source` WHERE `sourceName` = '". $sourceName ."' ORDER BY `sourceID` DESC LIMIT 1") or die(mysql_error());
		$countResults = mysql_num_rows($query);
		if($countResults==1){
			while($row = mysql_fetch_object($query)){
				$sourceID = $row->sourceID;
			}
		} 
		
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
				$bibField2 = htmlentities($_POST['miscField2'],ENT_QUOTES,'UTF-8');
				$bibFieldVal2 = htmlentities($_POST['miscFieldValue2'],ENT_QUOTES,'UTF-8');
				break;
		} // Ende switch


/*
			$authorSql = mysql_query("SELECT authorName FROM author, rel_source_author WHERE author.authorID = rel_source_author.authorID AND rel_source_author.sourceID = '".$sourceID."' ORDER BY authorName");

			$countTags = mysql_num_rows($authorSql);
			if($countTags>0) {
				while($row = mysql_fetch_array($authorSql)) {
					$authorIDs[] = $row['authorName'];   
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
*/
		}


	}

