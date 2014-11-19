<?php

class post {
	var $id;	// id number
	var $access;	// do you have the rights to see notes and sources?
	var $type;
	var $data;
	var $user;

	var $part;
	var $query;
	var $json;


	function post() {


	}

	function checkNote() {
		$source = false;
		$mysqli = condb('open');
		$sql = $mysqli->query('SELECT bibID FROM bib WHERE noteID = ' . $this->id .';');
		if(mysqli_num_rows($sql) > 0) {
			// note is a source
			while($row = mysqli_fetch_object($sql)) {
				$source = $row->bibID;
			}
		}
		return $source;
	}

	function updateNote() {
		if(!array_key_exists('notePublic', $this->data)) $this->data['notePublic'] = 0;

		$this->data['pageStart'] = '0';
		$this->data['pageEnd'] = '0';
		$tmp_pages = explode('-', $this->data['pages']);
		if(strpos($this->data['pages'], '-') !== false) {
			$this->data['pageStart'] = $tmp_pages[0];
			$this->data['pageEnd'] = $tmp_pages[1];
			if($this->data['pageEnd'] != '') {
				if($this->data['pageEnd'] <= $this->data['pageStart']) $this->data['pageEnd'] = '0';
			}
		} else {
			$page_start = $this->data['pages'];
		}

		if(!empty($this->data['noteComment']) && !empty($this->data['bibID']))
		{
			// some checks and request first
			$mysqli = condb('open');
			//$bibsql = $mysqli->query('SELECT bibID ')
			if($this->data['checkID'] == '') {

			}
			updateMN('label', 'note', $this->data['noteLabel'], $this->data['noteID']);

			// update the data
			$sql = $mysqli->query('UPDATE note SET ' .
									'noteTitle=\'' . $this->data['noteTitle'] . '\', ' .
									'noteSubtitle=\'' . $this->data['noteSubtitle'] . '\', ' .
									'noteComment=\'' . $this->data['noteComment'] . '\', ' .
									'noteLink=\'' . $this->data['noteLink'] . '\', ' .
									'noteMedia=\'' . $this->data['noteMedia'] . '\', ' .
									'bibID=\'' . $this->data['bibID'] . '\', ' .
									'pageStart=\'' . $this->data['pageStart']. '\', ' .
									'pageEnd=\'' . $this->data['pageEnd']. '\', ' .
									'userID=\'' . $this->user. '\', ' .
									'notePublic=\'' . $this->data['notePublic'] . '\' ' .
									'WHERE noteID = ' . $this->data['noteID'] . ';');
			condb('close');

			return json_encode(array(
				'error' => false,
			));
			exit;
		}else{
			return json_encode(array(
				'error' => true,
				'msg'   => "Something went totally wrong!"
			));
			exit;
		}
	}



	function updateSource() {
		if(!array_key_exists('notePublic', $this->data)) $this->data['notePublic'] = 0;
		if(!array_key_exists('bibEditor', $this->data)) $this->data['bibEditor'] = 0;
/*
		$this->data['pageStart'] = '0';
		$this->data['pageEnd'] = '0';
		$tmp_pages = explode('-', $this->data['pages']);
		if(strpos($this->data['pages'], '-') !== false) {
			$this->data['pageStart'] = $tmp_pages[0];
			$this->data['pageEnd'] = $tmp_pages[1];
			if($this->data['pageEnd'] != '') {
				if($this->data['pageEnd'] <= $this->data['pageStart']) $this->data['pageEnd'] = '0';
			}
		} else {
			$page_start = $this->data['pages'];
		}
*/

		if(!empty($this->data['bibName']) && !empty($this->data['bibTyp']))
		{
			if($this->data['noteID'] == 0) {
				//new source: insert
				if($this->data['noteComment'] == '') $this->data['noteComment'] = $this->data['noteTitle'];
				$mysqli = condb('open');
				$notesql = $mysqli->query('INSERT INTO note ' .
										'(`noteTitle`, `userID`, `checkID`, `noteComment`) ' .
										'VALUES' .
										'(\'' . $this->data['noteTitle'] .
										 '\', \'' . $this->user .
										 '\', \'' . $this->data['checkID'] .
										 '\', \'' . $this->data['noteComment'] . '\');');
				$noteID = $mysqli->insert_id;
				$this->data['noteID'] = $noteID;
				echo $this->data['noteID'];
				$sql = $mysqli->query('INSERT INTO bib ' .
										'(\'bib\', \'bibEditor\', \'bibTyp\', \'noteID\') ' .
										'VALUES' .
										'(\'' . $this->data['bibName'] .
										 '\', \'' . $this->data['bibEditor'] .
										 '\', \'' . $this->data['bibTyp'] .
										 '\', \'' . $this->data['noteID'] .
										 '\');');
				$this->data['bibID'] = mysqli_insert_id($sql);
				$mysqli = condb('close');
			}
			//old source: update
			// some checks and request first
			$mysqli = condb('open');
			//$bibsql = $mysqli->query('SELECT bibID ')
			if($this->data['checkID'] == '') {

			}
			updateMN('author', 'bib', $this->data['noteAuthor'], $this->data['bibID']);
			updateMN('label', 'note', $this->data['noteLabel'], $this->data['noteID']);
			$detail = array();
			switch($this->data['bibTypName']) {
				case 'article';
					$detail[] = 'journaltitle';
					$detail[] = 'number';
					$detail[] = 'year';
					$detail[] = 'pages';
					break;

				case 'book';
				case 'booklet';
				case 'collection';
				case 'project';
					updateMN('location', 'bib', $this->data['noteLocation'], $this->data['bibID']);
					break;

				case 'online';
					$detail[] = 'url';
					$detail[] = 'urldate';
					break;
				case 'proceedings';
					$detail[] = 'eventtitle';
					$detail[] = 'venue';
					updateMN('location', 'bib', $this->data['noteLocation'], $this->data['bibID']);
					break;

				case 'report';
				case 'thesis';
					$detail[] = 'type';
					$detail[] = 'institution';
					break;

				case 'inbook';
				case 'incollection';
				case 'inproceedings';
					$detail[] = 'crossref';
					$detail[] = 'pages';
					break;


				case 'manual';
				case 'misc';
				case 'periodical';
				case 'unpublished';

					break;
			}

			if( !empty($detail) ) {
				updateDetail($v, $this->data[$v], $this->data['bibID']);
				foreach($detail as $v) {
					insertDetail($v, $this->data[$v], $this->data['bibID']);
				}
			}

			$bibsql = $mysqli->query('UPDATE bib SET ' .
									'bib=\'' . $this->data['bibName'] . '\', ' .
									'bibEditor=\'' . $this->data['bibEditor'] . '\', ' .
									'bibTyp=\'' . $this->data['bibTyp'] . '\' ' .
									'WHERE bibID = ' . $this->data['bibID'] . ';');
			// update the data
			$sql = $mysqli->query('UPDATE note SET ' .
									'noteTitle=\'' . $this->data['noteTitle'] . '\', ' .
									'noteSubtitle=\'' . $this->data['noteSubtitle'] . '\', ' .
									'noteComment=\'' . $this->data['noteComment'] . '\', ' .
									'noteLink=\'' . $this->data['noteLink'] . '\', ' .
									'noteMedia=\'' . $this->data['noteMedia'] . '\', ' .
									'pageStart=\'' . $this->data['pageStart']. '\', ' .
									'pageEnd=\'' . $this->data['pageEnd']. '\', ' .
									'userID=\'' . $this->user. '\', ' .
									'notePublic=\'' . $this->data['notePublic'] . '\' ' .
									'WHERE noteID = ' . $this->data['noteID'] . ';');
			condb('close');

			return json_encode(array(
				'error' => false,
			));
			exit;

		}else{
			return json_encode(array(
				'error' => true,
				'msg'   => "Something went totally wrong!"
			));
			exit;
		}
	}

	function deleteNote() {


	}

	function uploadMedia() {
		// A list of permitted file extensions
		$allowed = array('jpg', 'JPG', 'png', 'PNG', 'gif', 'jpeg', 'tif', 'pdf', 'mp4', 'webm', 'mp3', 'wav');

		if(isset($this->data['upl']) && $this->data['upl']['error'] == 0){

			$ext = pathinfo($this->data['upl']['name'], PATHINFO_EXTENSION);

			if(!in_array(strtolower($ext), $allowed)){
				return json_encode(array(
					'error' => true,
					'msg'   => "Something went totally wrong!"
				));
			}


			switch($ext) {
				case "jpg";
				case "JPG";
				case "png";
				case "PNG";
				case "gif";
				case "jpeg";
				case "tif";
					$dir = '/picture/';
					break;

				case "pdf";
					$dir = '/document/';
					break;

				case "mp4";
				case "webm";
					$dir = '/movie/';
					break;

				case "mp3";
				case "wav";
					$dir = '/sound/';
					break;
			}

			if(move_uploaded_file($this->data['upl']['tmp_name'], __MEDIA_PATH__ . $dir .$this->data['upl']['name'])){
				return json_encode(array(
					'error' => false,
				));
			}
		} else {
			return json_encode(array(
				'error' => true,
				'msg'   => "Something went totally wrong!"
			));
		}
	}



}

/*

require_once 'settings.php';
condb('open');
if($_POST['content'] != ""){
	print_r($_POST['content']);
	//0. collect Post-Data
	$checkID = $_POST['checkID'];
	$noteTitle = htmlentities($_POST['title'], ENT_NOQUOTES, 'UTF-8');
	$noteContent = htmlentities($_POST['content'], ENT_QUOTES, 'UTF-8');
	$noteLabels = htmlentities($_POST['label'], ENT_NOQUOTES, 'UTF-8');
	$labels = explode(",", $noteLabels);
	$categoryID = 0;
	$projectID = 0;
	$noteSource = 0;
	/*
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
*/
/* // media upload
	$mediaFile = $_POST['media'];
	$allowedExts = array("jpg", "jpeg", "gif", "png", "tif", "pdf", "ogv", "mp4", "webm", "ogg", "mp3", "wav");
	$fileName = $_FILES['upload']['name'];
	$extensionPosition = strpos($fileName,".");
	$extension = substr($fileName,($extensionPosition+1));
	//$extension = end(explode(".", $fileName));
	//echo "File: ".$_FILES['mediaFile']."<br />";
	if($_FILES['upload']['name'] != ""){
		echo "<div class='note'>";
		if (($_FILES['uploadFile']['size'] < 80000000) && in_array($extension, $allowedExts)){
			if ($_FILES['uploadFile']['error'] > 0) {
				echo "Return Code: " . $_FILES['uploadFile']['error'] . "<br />";
			} else {
				echo "Upload: " . $_FILES['uploadFile']['name'] . "<br />";
				echo "Type: " . $_FILES['uploadFile']['type'] . "<br />";
				echo "Size: " . ($_FILES['uploadFile']['size'] / 1024) . " Kb<br />";
				echo "Temp file: " . $_FILES['uploadFile']['tmp_name'] . "<br />";
				echo "<hr>";
				echo 'Here is some more debugging info:<br>';
				print_r($_FILES);
				$mediaType = split("/", $_FILES['uploadFile']['type']);
				switch($mediaType[0]){
					case 'image';
						$mediaTypeFolder = __MEDIA_URL__ . '/pictures';
						break;

					case 'video';
						$mediaTypeFolder = __MEDIA_URL__ . '/movies';
						break;

					case 'application'; // in case of pdf?!
						$mediaTypeFolder = __MEDIA_URL__ . '/documents';
						break;

				}
				echo "Folder: " . $mediaTypeFolder . "<br>";
				if (file_exists($mediaTypeFolder . "/" . $_FILES['uploadFile']['name'])){
					echo $_FILES['uploadFile']['name'] . " already exists. ";
				} else {
					move_uploaded_file($_FILES['uploadFile']['name'], $mediaTypeFolder . "/" . $_FILES['uploadFile']['name']);

					echo "Stored in: " . $mediaTypeFolder .  "/" . $_FILES['uploadFile']['name'];
				}
				$mediaFile = $fileName;
			}
		} else {
			echo "Invalid file";
			echo "Upload: " . $_FILES['uploadFile']['name'] . "<br />";
			echo "Type: " . $_FILES['uploadFile']['type'] . "<br />";
			echo "Size: " . ($_FILES['uploadFile']['size'] / 1024) . " Kb<br />";
			echo "Folder: " . $mediaTypeFolder . "<br>";
		}
		echo "</div>";
	}
	*/



	/*
	$noteSourceExtern = '';//htmlentities($_POST['link'],ENT_QUOTES,'UTF-8');
	$noteSourceName = htmlentities($_POST['source'],ENT_QUOTES,'UTF-8');
	if($noteSourceName!="" || $noteSourceName!="source"){
		$sourceSql = mysql_query("SELECT sourceID FROM source WHERE sourceName = '".$noteSourceName."'");
		while($rowSource = mysql_fetch_object($sourceSql)){
			$noteSource = $rowSource->sourceID;
		}
	}

	if(isset($_POST['pages']) && $_POST['pages'] != ""){
		$pages = explode("-", $_POST['pages']);
		$pageStart = $pages[0];
		$pageEnd = $pages[1];
		if(($pageEnd-$pageStart)<=0){
			$pageEnd = 0;
		}
	} else {
		$pageStart = 0;
		$pageEnd = 0;
	}

	if(isset($_POST['public'])){
		$notePublic = 1;
	} else {
		$notePublic = 0;
	}
	// End of point 0: data collection //
//	$back2path = $_POST['path'];
	//echo $back2path;

	//1. new note? true when delete not exist
	if (!isset($_POST['delete']) && $_POST['checkID'] != "") {
		if(preg_match('/^[a-f0-9]{32}$/',$_POST['checkID'])){
			$sqlCheck = mysql_query("SELECT noteID FROM note WHERE checkID = '".$_POST['checkID']."'");
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
					foreach($labels as $label) {
						insertMN('label','rel_note_label',$label,$noteID,'note');
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
		if(!isset($_POST['delete'])){
			$noteID = $_POST['noteID'];
			$update = "UPDATE note SET noteTitle='".$noteTitle."', noteContent='".$noteContent."', noteCategory='".$categoryID."', noteProject='".$projectID."', noteSourceExtern='".$noteSourceExtern."', noteSource='".$noteSource."', pageStart='".$pageStart."', pageEnd='".$pageEnd."', noteMedia='".$mediaFile."', notePublic='".$notePublic."' WHERE noteID = '".$noteID."'";
			if (!mysql_query($update)){
				die('Error: ' . mysql_error());
			}

			// Zettel-Register-Verbindung erst löschen, danach neu speichern
			$queryLabel = "DELETE FROM rel_note_label WHERE noteID=".$noteID.";";
			$dropLabel = mysql_query ($queryLabel) or die (mysql_error());

			foreach($labels as $label) {
				insertMN('label','rel_note_label',$label,$noteID,'note');
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
condb('close');
*/
