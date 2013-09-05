<?php
if($_POST['nContent'] != ""){
	//0. collect Post-Data
	$checkID = $_POST['nCheckID'];
	$noteTitle = htmlentities(save4Tex($_POST['nTitle']),ENT_NOQUOTES,'UTF-8');
	$noteContent = htmlentities(save4Tex($_POST['nContent']),ENT_QUOTES,'UTF-8');
	$noteTags = htmlentities($_POST['nTag'],ENT_NOQUOTES,'UTF-8');
	$tags = explode("/", $noteTags);
	$categoryName = htmlentities($_POST['nCategory'],ENT_QUOTES,'UTF-8');
		if ($_POST['nCatNew']) {
			$newCategoryName = htmlentities($_POST['nCatNew'],ENT_QUOTES,'UTF-8');
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
	$projectName = htmlentities($_POST['nProject'],ENT_QUOTES,'UTF-8');
		if ($_POST['nProNew']) {
			$newProjectName = htmlentities($_POST['nProNew'],ENT_QUOTES,'UTF-8');
			$newCatSql = "INSERT INTO project (projectName) VALUES ('".$newProjectName."');";
			if (!mysql_query($newCatSql)){
				//die('Error: ' . mysql_error());
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
			
	$mediaFile = $_POST['mediaName'];
		$allowedExts = array("jpg", "jpeg", "gif", "png", "tif", "pdf", "ogv", "mp4", "webm", "ogg", "mp3", "wav");
					
		$fileName = $_FILES['uploadFile']['name'];
		$extensionPosition = strpos($fileName,".");
		$extension = substr($fileName,($extensionPosition+1));
			//$extension = end(explode(".", $fileName));
			//echo "File: ".$_FILES['mediaFile']."<br />";
		if($_FILES['uploadFile']['name']!=""){
			echo "<div class='note'>";
			if (($_FILES['uploadFile']['size'] < 80000000) && in_array($extension, $allowedExts)){
				if ($_FILES['uploadFile']['error'] > 0) {
					echo "Return Code: " . $_FILES['uploadFile']['error'] . "<br />";
				} else {
					echo "Upload: " . $_FILES['uploadFile']['name'] . "<br />";
					echo "Type: " . $_FILES['uploadFile']['type'] . "<br />";
					echo "Size: " . ($_FILES['uploadFile']['size'] / 1024) . " Kb<br />";
					echo "Temp file: " . $_FILES['uploadFile']['tmp_name'] . "<br />";
					if (file_exists("media/".$_FILES['uploadFile']['name'])){
						echo $_FILES['uploadFile']['name'] . " already exists. ";
					} else {
						move_uploaded_file($_FILES['uploadFile']['tmp_name'],
						"media/" . $_FILES['uploadFile']['name']);
						echo "Stored in: " . "media/" . $_FILES['uploadFile']['name'];
					}
					$mediaFile = $fileName;
				}
			} else {
				echo "Invalid file";
			}
			echo "</div>";
		}
	$noteSourceExtern = htmlentities($_POST['sourceLink'],ENT_QUOTES,'UTF-8');
	$noteSourceName = htmlentities($_POST['sourceLiterature'],ENT_QUOTES,'UTF-8');
		if($noteSourceName!="" || $noteSourceName!="source"){
			$sourceSql = mysql_query("SELECT sourceID FROM source WHERE sourceName = '".$noteSourceName."'");
			while($rowSource = mysql_fetch_object($sourceSql)){
				$noteSource = $rowSource->sourceID;
			}
		}
		
		if(isset($_POST['sourcePageStart']) && $_POST['sourcePageStart'] != ""){
			$pageStart = $_POST['sourcePageStart'];
			$pageEnd = $_POST['sourcePageEnd'];
			if(($pageEnd-$pageStart)<=0){
				$pageEnd = 0;
			}
		} else {
			$pageStart = 0;
			$pageEnd = 0;
		}

		if(isset($_POST['notePublic'])){
			$notePublic = $_POST['notePublic'];
		} else {
			$notePublic = 0;
		}			
	// End of point 0: data collection //
	$back2path = $_POST['path'];
	//echo $back2path;

		//1. new note? true when delete not exist
		if (!$_POST['delete'] && $_POST['nCheckID'] != "") {
			if(preg_match('/^[a-f0-9]{32}$/',$_POST['nCheckID'])){
				$sqlCheck = mysql_query("SELECT noteID FROM note WHERE checkID = '".$_POST['nCheckID']."'");
				if(@mysql_num_rows($sqlCheck) == 1){
					echo "<div class='note'><p class='advice'>This note <strong>" . $noteID . "</strong> already exists.</p></div>";
					showNote($noteID, $access);
					echo "<a href='" . $back2path . "' class='goback'>Go Back</a>";
				} else {
					$sql="INSERT INTO note (noteTitle, noteContent, noteCategory, noteProject, noteSourceExtern, noteSource, pageStart, pageEnd, noteMedia, notePublic, checkID) VALUES (\"".$noteTitle."\", \"".$noteContent."\", \"".$categoryID."\", \"".$projectID."\", \"".$noteSourceExtern."\", \"".$noteSource."\", \"".$pageStart."\", \"".$pageEnd."\", \"".$mediaFile."\", \"".$notePublic."\", \"".$checkID."\");";
					if (!mysql_query($sql)){
						die('Error: ' . mysql_error());
					}
					$query = mysql_query("SELECT noteID FROM `note` ORDER BY `noteID` DESC LIMIT 1") or die(mysql_error());
					while($row = mysql_fetch_object($query)){
						$noteID = $row->noteID;
						// Zettel-Register-Verbindung neu speichern
						foreach($tags as $tag) {
							insertMN('tag','rel_note_tag',$tag,$noteID,'note');
						}
						// Medien-Verbindung speichern
						if($mediaFile!=''){
							$sql="INSERT INTO media (noteID, mediaFile) VALUES (\"".$noteID."\", \"".$mediaFile."\");";
							if (!mysql_query($sql)){
								die('Error: ' . mysql_error());
							}
						}
						// alles wurde in die DB geschoben und kann nun gezeigt werden //
						echo "<div class='note'><p class='advice'>The new note has been created</p></div>";
						showNote($noteID, $access);
						echo "<a href='" . $back2path . "' class='goback'>Go Back</a>";

					}
				}
			} else {
				echo "<p class='warning'>Checksum is wrong or manipulated!</p>";
			}
		} else {
			//2. if delete exist: is it yes or no? 
			// NO = edit
			if($_POST['delete'] == 'NO'){
				$noteID = $_POST['noteID'];
				$update = "UPDATE note SET noteTitle='".$noteTitle."', noteContent='".$noteContent."', noteCategory='".$categoryID."', noteProject='".$projectID."', noteSourceExtern='".$noteSourceExtern."', noteSource='".$noteSource."', pageStart='".$pageStart."', pageEnd='".$pageEnd."', noteMedia='".$mediaFile."', notePublic='".$notePublic."' WHERE noteID = '".$noteID."'";
				if (!mysql_query($update)){
					die('Error: ' . mysql_error());
				}

				// Zettel-Register-Verbindung erst löschen, danach neu speichern
				$queryTag = "DELETE FROM rel_note_tag WHERE noteID=".$noteID.";";
				$dropTag = mysql_query ($queryTag) or die (mysql_error());

				foreach($tags as $tag) {
					insertMN('tag','rel_note_tag',$tag,$noteID,'note');
				}

				// Medien-Verbindung, falls vorhanden, erst löschen, danach neu speichern
				$queryMedia = "DELETE FROM media WHERE noteID=".$noteID.";";
				$dropMedia = mysql_query ($queryMedia) or die (mysql_error());
				if($mediaFile!=''){
					$sql="INSERT INTO media (noteID, mediaFile) VALUES (\"".$noteID."\", \"".$mediaFile."\");";
					if (!mysql_query($sql)){
						die('Error: ' . mysql_error());
					}
				}
				// alles wurde in die DB geschoben und kann nun gezeigt werden //
					echo "<div class='note'><p class='advice'>The old note has been updated</p></div>";
					showNote($noteID, $access);
					echo "<a href='" . $back2path . "' class='goback'>Go Back</a>";

			// YES = delete
			} else {
				$noteID = $_POST['noteID'];
				$query = "DELETE FROM note WHERE noteID=".$noteID.";";
				$queryTag = "DELETE FROM rel_note_tag WHERE noteID=".$noteID.";";
				$queryMedia = "DELETE FROM media WHERE noteID=".$noteID.";";
				$drop = mysql_query ($query) or die (mysql_error());
				$dropTag = mysql_query ($queryTag) or die (mysql_error());
				$dropMedia = mysql_query ($queryMedia) or die (mysql_error());

				echo "<div class='note'><p class='warning'>The following note was deleted!</p></div>";
				echo "<div class='note'>";
					echo "<h3>".$noteTitle."</h3>"; //
					echo "<p class='content'>";
						if ($noteSource!=0){
							echo "``".makeurl(nl2br($noteContent))."''";
						} else {
							echo makeurl(nl2br($noteContent));
						}
						echo "<br>";
						showSourceCite($noteSource,$notePageStart,$notePageEnd);
					echo "</p>";
					if($noteSourceExtern!=""){
						echo "<p class='linkText'>--&gt; <a href='".$noteSourceExtern."' title='extern'>".$noteSourceExtern."</a></p>";
					}
					echo "<p class='linkText'>";
						echo linkIndex('note', 'category', $noteCategory);
						echo " &gt; ";
						echo linkIndex('note', 'project', $noteProject);
						linkIndexMN('note','tag', $noteID);
					echo "</p>";
				echo "</div>";
				echo "<a href='" . $back2path . "' class='goback'>Go Back</a>";
			}
		}
	}
		?>
