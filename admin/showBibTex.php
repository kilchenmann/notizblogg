<!DOCTYPE html>
<html>
<head>
	<link type='text/css' rel='stylesheet' media='screen' href='../common/css/screen.css' />
	<script type='text/javascript' src='../common/jquery/jquery-1.7.2.min.js'></script>
</head>
<body>	

<?php
$siteTitle = "Export bibTex file from notizblogg";
//include 'header.php';
require_once ("../conf/settings.php");
require_once ("../conf/.privat/conf-pw.php");
require_once ("../common/php/db.php");
$date = date("Ymd");
$filename = "nb" . $date . ".bib";

	$tmpPath = split('/notizblogg', SITE_URL);
	$backuppath = "bibtex/" . $filename;
	$downloadurl = SITE_URL . "/notizblogg/admin/bibtex/" . $filename;
	
if(!is_dir("bibtex")){
	echo "no directory";
	$mkDir = shell_exec("mkdir bibtex  2>&1");
}
connect();
	//$sourceSql = mysql_query("SELECT sourceID FROM source WHERE `sourceTyp` != 0 ORDER BY sourceTyp, sourceName");
	$sourceSql = "SELECT sourceID FROM source WHERE `sourceTyp` != 0 ORDER BY sourceTyp, sourceName";
	showError($sourceSql, __LINE__);

	fopen($backuppath, 'w+');
	// Let's make sure the file exists and is writable first.
	if (is_writable($backuppath)) {

		// In our example we're opening $filename in append mode.
		// The file pointer is at the bottom of the file hence
		// that's where $somecontent will go when we fwrite() it.
		if (!$handle = fopen($backuppath, 'w')) {
			 echo "Cannot open file (".$backuppath.")";
			 exit;
		}
		$copyRight = html_entity_decode("%% %% %% %% %% %% %% %% %% %% %% %% %% %% %%\n%% This bibFile was created with\n%% Notizblogg (". $nbVersion .") &copy; by\n%% Andr&eacute; Kilchenmann | 2013 \n%%\n%% -&gt; ak@notizblogg.ch\n%% -&gt; http://notizblogg.ch\n%% %% %% %% %% %% %% %% %% %% %% %% %% %% %%\n\n",ENT_NOQUOTES,'ISO-8859-15');
		// Write $somecontent to our opened file.
		if (fwrite($handle, $copyRight) === FALSE) {
			echo "Cannot write to file (".$backuppath.")";
			exit;
		}
		echo "here is an output";
		while($row = mysql_fetch_object($sourceSql)){
			$source = $row->sourceID;
			showSource($source, 'public');
		}
			/*
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
									fwrite($handle, "@".$bibTypName);
						}
				}
				//echo "<br>";
				fwrite($handle, "{".$sourceName.",");
				if($sourceEditor==1){
						fwrite($handle, "editor = ");
					} else {
						fwrite($handle, "author = ");
					}
					fwrite($handle, "{".$authors."},");
					fwrite($handle, "title = {".$sourceTitle."},");
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
		
		
		
		
		
		
		
		









































					showSource($sourceID, $access);
					
					$somecontent = $showSource->showBibEntry($sourceID);
					if(isset($GLOBALS['crossrefID'])&&isset($_GET['id'])){
						$sourceInID = $GLOBALS['crossrefID'];
						$showInSource = new Source();
						$someInContent = $showInSource->showBibEntry($sourceInID);
						if (fwrite($handle, changeUmlaut4Tex($someInContent)) === FALSE) {
							echo "Cannot write to file (".$filename.")";
							exit;
						}
						echo changeUmlaut4Tex($someInContent);
						echo "<br />";
						echo "<p class='linkText'>";
						$showInSource->link2Source($sourceInID);
						$showInSource->editSource($sourceInID);
						echo "</p><br /><br />";
					}
				if (fwrite($handle, changeUmlaut4Tex($somecontent)) === FALSE) {
					echo "Cannot write to file (".$filename.")";
					exit;
				}
				echo changeUmlaut4Tex($somecontent);
				echo "<br />";
				echo "<p class='linkText'>";
				$showSource->link2Source($sourceID);
				$showSource->editSource($sourceID);
				echo "</p>";
			}
			* */
			fclose($handle);
		}


?>

<div class="lens">
	<div class='download'>
		<form>
			<p>There is a new bibTex file (<?php echo $filename; ?>) of your sources from notizblogg. Here you can download it.</p><br>
			<a href='<?php echo $downloadurl; ?>' class='button'>DOWNLOAD</a>
			<br>
			<br>
			<hr>
			<p>...or go <a href="../admin.php">back</a></p>
		</form>
	</div>

</div>
<script type="text/javascript">
	$("body").css("overflow", "hidden");
	$(".lens").fadeTo("slow", 1);
	$(".lens").css({"width":$(window).width()+"px", "height":$(window).height()+"px", "padding":"22px", "cursor":"move"});
	$(".lens .set").css({"width":"300px"});
</script>
</body>
</html>
