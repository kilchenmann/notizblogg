<script type="text/javascript">


/*
+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!

Dieser erste Teil komplett überarbeiten! Keine document.ready function,
da dieses Dokument mit dem Hauptdokument (hier admin.php) geladen wird.
Andere Lösung finden

+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!+!
*/
	$(document).ready(function() {
		changeMenu("NOTES");
		$("button.menuNew").val("completeSource");
		$("button.menuNew").html("Complete");
		$("button.menuNew").toggleClass("active");
		
		$(".viewer").fadeTo("fast", 0.1);
		$(".partIndex").fadeTo("fast", 0.1);
		
		$(".titleIndex").slideToggle("fast");
		$(".contentIndex").slideToggle("fast");
		$("div.completeSource").slideToggle("fast");
		$(".contentIndex").animate({
				width: "720px"
		},"fast");
	});

</script>
<?php
if($_POST['sTagTitle'] != "" && $_POST['sTyp'] != ""){
?>
	<script type="text/javascript">
		alert("speichern!?");
	</script>
	<?php
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
		?>
		<script type="text/javascript">
			var sourceID = "<?php echo $sourceID; ?>";
			var sourceName = "<?php echo $sourceName; ?>";
			$(".completeSource").html("<p class='advice'>The source <strong><a href='?type=note&part=source&id= " + sourceID + "' >'" + sourceName + "'</a></strong> already exists.</p>");
			var showSource = "<?php showSource($sourceID); ?>";
			$(".completeSource").append(showSource);
		</script>

		<?php
	} else {
		$sql="INSERT INTO source (sourceName, sourceTitle, sourceSubtitle, sourceYear, sourceTyp, sourceEditor, sourceNote, sourceCategory, sourceProject) VALUES
		(\"".$sourceName."\", \"".$sourceTitle."\", \"".$sourceSubtitle."\", ".$sourceYear.", \"".$bibTypID."\", \"".$sourceEditor."\", \"".$sourceNote."\", \"".$categoryID."\", \"".$projectID."\");";
		if (!mysql_query($sql)){
			die('Error: ' . mysql_error());
		}
		
		$query = mysql_query("SELECT * FROM `source` ORDER BY `sourceID` DESC LIMIT 1") or die(mysql_error());
		while($row = mysql_fetch_object($query)){
			$sourceTitle = $row->sourceTitle;
			$sourceSubtitle = $row->sourceSubtitle;
			$sourceYear = $row->sourceYear;
			$sourceNote = $row->sourceNote;
			$sourceEditor = $row->sourceEditor;
			$sourceName = $row->sourceName;
			$sourceID = $row->sourceID;
			$bibTypID = $row->bibTypID;
			
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
		}
		?>
		<script type="text/javascript">
			var sourceID = "<?php echo $sourceID; ?>";
			var showSource = "<?php showSource($sourceID); ?>";
			var actionPath = "<?php echo SITE_URL."/".BASE_FOLDER.MainFile."?type=source&part=save&id=".$sourceID; ?>";
			
			$(".completeSource").html("");
			$(".completeSource").append(
				$this.append($('<form>').attr({'accept-charset':'utf-8', 'name':'noteSource', 'action':actionPath, 'method':'post', 'enctype':'multipart/form-data'}).addClass('sourceForm')
					.append($('table').addClass('form')
						.append($('tr')
							.append($('td').addClass('left')
								.append($('h3').text('Created SOURCE (part 1)'))
								.append(showSource)
							
							)
							.append($('td').addClass('right')
								.append($('h3').text('Complete SOURCE (part 2)'))
							
							
							)
						)
					)
				)
			
			
			
			
			
			
			
			
			
			
			
			);
					
/*
					.append("<tr>")
						.append("<td class='left'>")
							.append("<h3>Created SOURCE (part 1)</h3>")
							.append(showSource)
						.append("</td>")
						.append("<td class='right'>")
							.append("<h3>Complete SOURCE (part 2)</h3>")
							.append("<button class='button' type='submit' value='SAVE'>SAVE</button>")
							.append("<button class='button' type='reset' value='Clear'>Clear</button>")
						.append("</td>")
					.append("</tr>")
				.append("</table>")
				.append("</form>")

*/
		</script>

		<?php


// *A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*A*


echo "";
?>
	<table class='form'>
		<tr>
			<td class="left">
				<h3>Created SOURCE (part 1)</h3>
				<?php showSource($sourceID); ?>
			</td>
			<td class="right">
				<h3>Complete SOURCE (part 2)</h3>
				<?php
				$completeSourceWithType = $_POST['sTyp'];
				switch($completeSourceWithType){
					case "article";
						echo "<input type='text' name='journaltitle' placeholder='journalTitle' required='required' size='28' /><br />";
					break;
					case "book";
					case "booklet";
					case "collection";
						echo "<select name='selectLocation1' id='selectLocation1'>";
							formSelect('location');
						echo "</select> ";
						echo "<input type='text' name='sLocation1' id='newLocation1' placeholder='1. Location' size='28' required='required' /><br />";
						$i=2;
						while ($i<5) {
							echo "<div id='location".$i."' style='display:none'>";
								echo "<select name='selectLocation".$i."' id='selectLocation".$i."'>";
									formSelect('location');
								echo "</select> ";
								echo "<input type='text' name='sLocation".$i."' id='newLocation".$i."' placeholder='".$i.". Location' size='28'/><br />";
							echo "</div>";
							$i++;
						}
					break;
					case "online";
						echo "<input type='text' name='url' placeholder='url' size='35' required='required' /><br />";
						$today = date("Y-m-d");
						echo "<input type='date' name='urldate' placeholder='YYYY-MM-DD' size='35' required='required' value='".$today."'/><br />";
					break;
					case "proceedings";
						echo "<input type='text' name='eventtitle' placeholder='eventtitle' required='required' size='28' /><br />";
						echo "<input type='text' name='venue' placeholder='venue' size='28' /><br />";
						echo "<select name='selectLocation1' id='selectLocation1'>";
							formSelect('location');
						echo "</select> ";
						echo "<input type='text' name='sLocation1' id='newLocation1' placeholder='1. Location' size='28' required='required' /><br />";
							
						$i=2;
						
						while ($i<5) {
							echo "<div id='location".$i."' style='display:none'>";
								echo "<select name='selectLocation".$i."' id='selectLocation".$i."'>";
									formSelect('location');
								echo "</select> ";
								echo "<input type='text' name='sLocation".$i."' id='newLocation".$i."' placeholder='".$i.". Location' size='28'/><br />";
							echo "</div>";
						$i++;
						}
					break;
					case "report";
					case "thesis";
						echo "<input type='text' name='type' placeholder='type' size='28' /><br />";
						echo "<input type='text' name='institution' placeholder='institution' size='28' /> ";
					break;
					case "inbook";
						echo "<select name='inbook' id='selectSource'>";
							formSelectTyp('source', 'book');
						echo "</select>";
						echo "<br />";
						echo "<input type='text' name='pageStart' placeholder='from page' size='16' />";
						echo "<input type='text' name='pageEnd' placeholder='to page' size='16'  />";
					break;
					case "incollection";
						echo "<select name='incollection' id='selectSource'>";
							formSelectTyp('source', 'collection');
						echo "</select>";
						echo "<br />";
						echo "<input type='text' name='pageStart' placeholder='from page' size='16' />";
						echo "<input type='text' name='pageEnd' placeholder='to page' size='16'  />";
					break;
					case "inproceedings";
						echo "<select name='inproceedings' id='selectSource'>";
							formSelectTyp('source', 'proceedings');
						echo "</select>";
						echo "<br />";
						echo "<input type='text' name='pageStart' placeholder='from page' size='16' />";
						echo "<input type='text' name='pageEnd' placeholder='to page' size='16'  />";
					break;
					case "manual";
					case "misc";				
					case "periodical";				
					case "unpublished";
						echo "<select name='miscField1'>";
							formSelect('bibField');
						echo "</select>";
						echo "<input type='text' name='miscFieldValue1' size='28' />";
						echo "<select name='miscField2'>";
							formSelect('bibField');
						echo "</select>";
						echo "<input type='text' name='miscFieldValue2' size='28' />";
					break;
				} // Ende switch
				?>

				<button class="button" type="submit" value="NEXT">NEXT</button>
				<!-- Hier wird das soeben konstruierte Formular mittels JavaScript / jQuery ausgeblendet und durch das zweite Formular ersetzt. -->
				<button class="button" type="reset" value="Clear">Clear</button>
			</td>
		</tr>


<?php

// *V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*V*




	
	}

	
}
