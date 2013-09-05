<?php
	$edit = $_GET["editSource"];
	$sql = mysql_query("SELECT * FROM source WHERE sourceID=".$edit);
	while($row = mysql_fetch_object($sql)){
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
	}
	if($sourceYear == 0){
		$sourceYear = "";
	}
	$explodeSourceName = explode(":", $sourceName);
	$sourceTagTitle = $explodeSourceName[1];


	$authorSql = mysql_query("SELECT authorName, author.authorID FROM author, rel_source_author WHERE author.authorID = rel_source_author.authorID AND rel_source_author.sourceID = '".$sourceID."' ORDER BY authorName");
	$countAuthors = mysql_num_rows($authorSql);
	if($countAuthors > 0) {
		while($row = mysql_fetch_array($authorSql)) {
			$authorIDs[] = array('authorID' => $row['authorID'],
								'authorName' => $row['authorName']);
		}
		asort($authorIDs);
		$i = 1;
		foreach($authorIDs as $authorID => $authorName) {
			$author[$i] = $authorName['authorName'];
		$i++;
		}
	}
	?>
	<script type="text/javascript">
		$(document).ready(function() {
			changeMenu("NOTES");
			$("button.menuNew").val("editSource");
			$("button.menuNew").html("EDIT");
			$("button.menuNew").toggleClass("active");
			
			$(".viewer").fadeTo("fast", 0.1);
			$(".partIndex").fadeTo("fast", 0.1);
			
			$(".titleIndex").slideToggle("fast");
			$(".contentIndex").slideToggle("fast");
			$("div.editSource").slideToggle("fast");
			$(".contentIndex").animate({
					width: "720px"
			},"fast");
		});
	</script>
	
	<?php
echo "<h3>Edit SOURCE N° " . $sourceID . "</h3>";
echo "<form accept-charset='utf-8' name='noteSource' class='sourceForm' action='".SITE_URL."/".BASE_FOLDER.MainFile."?type=source&part=save&id=".$sourceID."' method='post' enctype='multipart/form-data' >";
?>
	<table class='form'>
		<tr>
			<td class="left">
				<input type='hidden' name='sourceID' placeholder='ID' readonly value='<?php echo $sourceID; ?>' />
				<p>@bibTyp
					<select name='sTyp' required='required' class='bibTyp' >
						<?php formSelected("bibTyp", $sourceTyp); ?>
					</select>
				</p>
				<p>
					<input type='text' name='sTitle' placeholder='Title' value='<?php echo $sourceTitle; ?>' />
				</p>
				<p>
					<input type='text' name='sSubTitle' placeholder='Subtitle' value='<?php echo $sourceSubtitle; ?>'/>
				</p>
				<p>
					<input type='text' class='medium' name='sTagTitle' placeholder='Title_Tag' required='required' value='<?php echo $sourceTagTitle; ?>' />
					<input type='text' class='xsmall' name='sYear' placeholder='Year' value='<?php echo $sourceYear; ?>'/>
				</p>
				<div class="author">
					<p>Choose an Author</p>
					<select name="selectAuthor" class="choice selectAuthor" size='11'>
						<?php formSelect('author'); ?>
					</select> 
					<p class='choosenVal'>
						<a class='delAuthor1'>x</a>
						<input type="text" name="sAuthor1" class="editAuthor1" placeholder='1. Author' required='required' value='<?php echo $author[1]; ?>'/>
					</p>
					<p class='choosenVal'>
						<a class='delAuthor2'>x</a>
						<input type="text" name="sAuthor2" class="editAuthor2" placeholder='2. Author' value='<?php echo $author[2]; ?>'/>
					</p>
					<p class='choosenVal'>
						<a class='delAuthor3'>x</a>
						<input type="text" name="sAuthor3" class="editAuthor3" placeholder='3. Author' value='<?php echo $author[3]; ?>'/>
					</p>
					<p class='choosenVal'>
						<a class='delAuthor4'>x</a>
						<input type="text" name="sAuthor4" class="editAuthor4" placeholder='4. Author' value='<?php echo $author[4]; ?>'/>
					</p>
					<p class='choosenVal'> 
					<?php
						echo "Editors?";
						if($sourceEditor == 1){
							echo "<input type='radio' name='sEditor' value='0'>no";
							echo "<input type='radio' name='sEditor' checked=checked' value='1'>yes";
						} else {
							echo "<input type='radio' name='sEditor' checked=checked' value='0'>no";
							echo "<input type='radio' name='sEditor' value='1'>yes";
						}
						
					?>
					</p>
				</div>
			</td>

			<td class="right completeSource">
				<div class="location" style="display:none;" >
					<p>Choose a location</p>
					<select name="selectLocation" class="choice selectLocation" size='11'>
						<?php formSelect('location'); ?>
					</select> 
					<p class='choosenVal'>
						<a class='delLocation1'>x</a>
						<input type="text" name="sLocation1" class="editLocation1" placeholder='1. Location' />
					</p>
					<p class='choosenVal'>
						<a class='delLocation2'>x</a>
						<input type="text" name="sLocation2" class="editLocation2" placeholder='2. Location' />
					</p>
					<p class='choosenVal'>
						<a class='delLocation3'>x</a>
						<input type="text" name="sLocation3" class="editLocation3" placeholder='3. Location' />
					</p>
					<p class='choosenVal'>
						<a class='delLocation4'>x</a>
						<input type="text" name="sLocation4" class="editLocation4" placeholder='4. Location' />
					</p>
				</div>
			<?php
				if($sourceTyp!=0){
					$typSql = mysql_query("SELECT bibTypName FROM bibTyp WHERE bibTypID = ".$sourceTyp."");
					while($typ = mysql_fetch_object($typSql)){
						$completeSourceWithType = $typ->bibTypName;
					}
					switch($completeSourceWithType) 
					{

					case "book":
					case "booklet":
					case "collection":
					case "proceedings":
					?>
					<script type="text/javascript">
						$('div.location').css({'display':'block'});
					</script>
					<?php
						$locationSql = mysql_query("SELECT locationName, location.locationID FROM location, rel_source_location WHERE location.locationID = rel_source_location.locationID AND rel_source_location.sourceID = '".$sourceID."' ORDER BY locationName");
						$countLocations = mysql_num_rows($locationSql);
						if($countLocations > 0) {
							while($row = mysql_fetch_array($locationSql)) {
								$locationIDs[] = array('locationID' => $row['locationID'],
													'locationName' => $row['locationName']);
							}
							asort($locationIDs);
							$i = 1;
							foreach($locationIDs as $locationID => $locationName) {
								$location[$i] = $locationName['locationName'];
							?>
							<script type="text/javascript">
								var i = <?php echo $i; ?>;
								var editLocation = '<?php echo $location[$i]; ?>';
//								alert(i + " = " + editLocation);
								$('.editLocation' + i).attr({'value':editLocation});
							</script>
							<?php
							$i++;
							}
						}
						break;
					case "inbook":
					case "incollection":
					case "inproceedings":
					echo "<div class='specificFields'>";
						$selectDetail = mysql_query("SELECT * FROM sourceDetail WHERE sourceID = '".$sourceID."'");
						while($row = mysql_fetch_object($selectDetail)){
							$bibFieldID = $row->bibFieldID;
							$sourceDetailName = $row->sourceDetailName;
							
							$selectField = mysql_query("SELECT bibFieldName FROM bibField WHERE bibFieldID = '".$bibFieldID."' ORDER BY bibFieldName");
							while($row = mysql_fetch_object($selectField)){
								$bibFieldName = $row->bibFieldName;
								switch($bibFieldName)
								{
								case "crossref":
									$crossrefSql = mysql_query("SELECT sourceName FROM source WHERE sourceID = '".$sourceDetailName."'");
									while($inrow = mysql_fetch_object($crossrefSql)) {
										$sourceInName = $inrow->sourceName;
									}
									$inTyp = substr($completeSourceWithType, 2);
									echo "in <select name='" . $completeSourceWithType . "' class='selectSource'>";
										formSelectedTyp('source', $inTyp, $sourceInName);
									echo "</select>";
								break;
								
								case "pages":
									$pages = split("--", $sourceDetailName);
									$pageStart = $pages[0];
									$pageEnd = $pages[1];
									echo "<p class='pages'>";
										echo "page from <input type='text' name='pageStart' class='xsmall' placeholder='from page' value='".$pageStart."' size='16' />";
										echo " to page <input type='text' name='pageEnd' class='xsmall' placeholder='to page' value='".$pageEnd."' size='16' />";
									echo "</p>";
								break;
								}
							}
						}
						break;
					}
				}
			
			echo "</div>";
			echo "<div class='specificFields'>";

	$selectDetail = mysql_query("SELECT * FROM sourceDetail WHERE sourceID = '".$sourceID."'");
	while($row = mysql_fetch_object($selectDetail)){
		$bibFieldID = $row->bibFieldID;
		$sourceDetailName = $row->sourceDetailName;
		
		$selectField = mysql_query("SELECT bibFieldName FROM bibField WHERE bibFieldID = '".$bibFieldID."'");
			while($row = mysql_fetch_object($selectField)){
				$bibFieldName = $row->bibFieldName;
			}
			
			if($bibFieldName != "crossref" && $bibFieldName != "pages"){
				echo "<br>" . $bibFieldName . "<br><input type='text' value='" . $sourceDetailName . "' name='" . $bibFieldName . "' />";
			}
	}

			echo "</div>";
			echo "<br>";
			echo "<div class='defaultFields'>";
				echo "<p class='plusDetail1'>";
					echo "<select name='selectDetail1'>";
						echo formSelect('bibField');
					echo "</select>";
					echo "<input type='text' name='valueDetail1' class='small' size='28' />";
				echo "</p>";
				echo "<p class='plusDetail2'>";
					echo "<select name='selectDetail2'>";
						echo formSelect('bibField');
					echo "</select>";
					echo "<input type='text' name='valueDetail2' class='small' size='28' />";
				echo "</p>";
			echo "</div>";
/*

	
	// ---------------------------------------------------------------------- //
	// hier einige default zusatz-felder. funktion erstellen, die nur ein 
	// weiteres feld hinzu fügt, wenn das vorherige ausgefüllt wurde! 
	// ---------------------------------------------------------------------- //
		var selectbibField = "<?php formSelect('bibField'); ?>";
		$('td.completeSource').append("<p class='plusDetail1'>");
			$('p.plusDetail1').append("<select name='selectDetail1'>" + selectbibField + "</select>");
			$('p.plusDetail1').append("<input type='text' name='valueDetail1' class='small' size='28' />");
		$('td.completeSource').append("<p class='plusDetail2'>");
			$('p.plusDetail2').append("<select name='selectDetail2'>" + selectbibField + "</select>");
			$('p.plusDetail2').append("<input type='text' name='valueDetail2' class='small' size='28' />");
		$('td.completeSource').append("<p class='plusDetail3'>");
			$('p.plusDetail3').append("<select name='selectDetail3'>" + selectbibField + "</select>");
			$('p.plusDetail3').append("<input type='text' name='valueDetail3' class='small' size='28' />");
		$('td.completeSource').append("<p class='plusDetail4'>");
			$('p.plusDetail4').append("<select name='selectDetail4'>" + selectbibField + "</select>");
			$('p.plusDetail4').append("<input type='text' name='valueDetail4' class='small' size='28' />");
	
});


*/
?>

			</td>
		</tr>
		<tr>
			<td class="left_bottom">
				<p>
					<select name="sCategory" class='small'>
						<?php formSelected("category", $sourceCategory); ?>
					</select>
					<input type="text" name="sCatNew" class='small' placeholder='new Category'/>
				</p>
				<p>
					<select name="sProject" class='small'>
						<?php formSelect("project", $sourceProject); ?>
					</select>
					<input type="text" name="sProNew" class='small' placeholder='new Project'/>
				</p>
			</td>
			<td class="right_bottom">
				<textarea name='sNote' placeholder='Comment' rows='50' cols='50' style='height: 85px;'><?php echo $sourceNote; ?></textarea>
				<input class='path' type='hidden' name='path' placeholder='path' readonly value='' />
				<br>
				<p>
				<?php
					if($sourceID != 0){
						echo "<input type='radio' name='delete' value='NO' checked /> edit or <i class='delete'>delete</i> ";
						echo "<input type='radio' name='delete' value='YES' /> ";
					} else {
						echo "<input type='hidden' name='delete' />";
					}
				?>
				</p><br>
				<p>
					<button class="button" type="submit" value="SAVE">SAVE</button>
					<button class="button" type="reset" value="Clear">Clear</button>
				</p>
			</td>
		</tr>
	
	
	</table>
</form>


<script type="text/javascript">

// adding a form on the right side for the chosen bibTyp
$('select.bibTyp').change(function() {
	$('div.location').css({'display':'none'});
	var completeSourceWithType = this.value;
	switch(completeSourceWithType) 
	{
	case 'article':
		$('div.specificFields').html("<p><input type='text' name='journaltitle' placeholder='journalTitle' required='required' size='28' /></p>");
		break;
	case "book":
	case "booklet":
	case "collection":
		$('div.location').css({'display':'block'});
		$('div.specificFields').html("");
		break;
	case 'online':
		$('div.specificFields').html("<p><input type='text' name='url' placeholder='url' size='35' required='required' /></p>");
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10){dd='0'+dd}
		if(mm<10){mm='0'+mm} 
		today = yyyy + '-' + mm + '-' + dd;
		$('div.specificFields').append("<p><input type='date' name='urldate' class='small' placeholder='YYYY-MM-DD' size='35' required='required' value='" + today + "'/> (Date of request)</p>");
		break;
	case 'proceedings':
		$('div.location').css({'display':'block'});
		$('div.specificFields').html("<p><input type='text' name='eventtitle' placeholder='eventtitle' required='required' size='28' /></p>");
		$('div.specificFields').append("<p><input type='text' name='venue' placeholder='venue' size='28' /></p>");
		break;
	case 'report':
	case 'thesis':
		$('div.specificFields').html("<p><input type='text' name='type' placeholder='type' size='28' /></p>");
		$('div.specificFields').append("<p><input type='text' name='institution' placeholder='institution' size='28' /></p>");
		break;
	case 'inbook':
		var selectBook = "<?php formSelectTyp('source', 'book'); ?>";
		$('div.specificFields').html("<p class='inbook'>");
			$('p.inbook').append("<select name='inbook' class='selectSource'>" + selectBook + "</select>");
		$('div.specificFields').append("<p class='pages'>");
			$('p.pages').append("<input type='text' name='pageStart' class='small' placeholder='from page' size='16' />");
			$('p.pages').append("<input type='text' name='pageEnd' class='small' placeholder='to page' size='16' />");
		break;
	case 'incollection':
		var selectCollection = "<?php formSelectTyp('source', 'collection'); ?>";
		$('div.specificFields').html("<p class='incollection'>");
			$('p.incollection').append("<select name='incollection' class='selectSource'>" + selectCollection + "</select>");
		$('div.specificFields').append("<p class='pages'>");
			$('p.pages').append("<input type='text' name='pageStart' class='small' placeholder='from page' size='16' />");
			$('p.pages').append("<input type='text' name='pageEnd' class='small' placeholder='to page' size='16' />");
		break;
	case 'inproceedings':
		var selectProceedings = "<?php formSelectTyp('source', 'proceedings'); ?>";
		$('div.specificFields').html("<p class='inproceedings'>");
			$('p.inproceedings').append("<select name='inproceedings' class='selectSource'>" + selectProceedings + "</select>");
		$('div.specificFields').append("<p class='pages'>");
			$('p.pages').append("<input type='text' name='pageStart' class='small' placeholder='from page' size='16' />");
			$('p.pages').append("<input type='text' name='pageEnd' class='small' placeholder='to page' size='16' />");
		break;
	case "manual":
	case "misc":
	case "periodical":
	case "unpublished":
		var selectbibField = "<?php formSelect('bibField'); ?>";
		$('div.specificFields').html("<p class='miscField1'>");
			$('p.miscField1').append("<select name='miscField1'>" + selectbibField + "</select>");
			$('p.miscField1').append("<input type='text' name='miscFieldValue1' class='small' size='28' />");
		$('div.specificFields').append("<p class='miscField2'>");
			$('p.miscField2').append("<select name='miscField2'>" + selectbibField + "</select>");
			$('p.miscField2').append("<input type='text' name='miscFieldValue2' class='small' size='28' />");
		break;
	}
	
	// ---------------------------------------------------------------------- //
	// hier einige default zusatz-felder. funktion erstellen, die nur ein 
	// weiteres feld hinzu fügt, wenn das vorherige ausgefüllt wurde! 
	// ---------------------------------------------------------------------- //
		var selectbibField = "<?php formSelect('bibField'); ?>";
		$('div.defaultFields').html("");
		$('div.defaultFields').append("<p class='plusDetail1'>");
			$('p.plusDetail1').append("<select name='selectDetail1' class='small'>" + selectbibField + "</select>");
			$('p.plusDetail1').append("<input type='text' name='valueDetail1' class='small' size='28' />");
		$('div.defaultFields').append("<p class='plusDetail2'>");
			$('p.plusDetail2').append("<select name='selectDetail2' class='small'>" + selectbibField + "</select>");
			$('p.plusDetail2').append("<input type='text' name='valueDetail2' class='small' size='28' />");
});
$('select.selectAuthor option').click(function() {
	if($('input.editAuthor1').val().length == 0){
		$('input.editAuthor1').val($(this).val());
	} else {
		if($('input.editAuthor2').val().length == 0){
			$('input.editAuthor2').val($(this).val());
		} else {
			if($('input.editAuthor3').val().length == 0){
				$('input.editAuthor3').val($(this).val());
			} else {
				if($('input.editAuthor4').val().length == 0){
					$('input.editAuthor4').val($(this).val());
				}
			}
		}
	}
});

$('a.delAuthor1').click(function() {
	$('input.editAuthor1').val('');
});
$('a.delAuthor2').click(function() {
	$('input.editAuthor2').val('');
});
$('a.delAuthor3').click(function() {
	$('input.editAuthor3').val('');
});
$('a.delAuthor4').click(function() {
	$('input.editAuthor4').val('');
});

$('select.selectLocation option').click(function() {
	if($('input.editLocation1').val().length == 0){
		$('input.editLocation1').val($(this).val());
	} else {
		if($('input.editLocation2').val().length == 0){
			$('input.editLocation2').val($(this).val());
		} else {
			if($('input.editLocation3').val().length == 0){
				$('input.editLocation3').val($(this).val());
			} else {
				if($('input.editLocation4').val().length == 0){
					$('input.editLocation4').val($(this).val());
				}
			}
		}
	}
});

$('a.delLocation1').click(function() {
	$('input.editLocation1').val('');
});
$('a.delLocation2').click(function() {
	$('input.editLocation2').val('');
});
$('a.delLocation3').click(function() {
	$('input.editLocation3').val('');
});
$('a.delLocation4').click(function() {
	$('input.editLocation4').val('');
});

</script>
