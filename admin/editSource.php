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
	$explodeSourceName = explode(":", $sourceName);
	$sourceTagTitle = $explodeSourceName[1];

	if($sourceTyp!=0){
		$typSql = mysql_query("SELECT bibTypName FROM bibTyp WHERE bibTypID = ".$sourceTyp."");
		while($typ = mysql_fetch_object($typSql)){
			$completeSourceWithType = $typ->bibTypName;
		}
	}

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
			$author[$i] = $authorName['authorID'];
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
echo "<h3>SOURCE N° " . $sourceID . "</h3>";
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
					<input type='text' class='xsmall' name='sYear' placeholder='Year' value='<?php echo $sourceYear; ?>'/>
				</p>
				<p>
					<input type='text' name='sTitle' placeholder='Title' value='<?php echo $sourceTitle; ?>' />
				</p>
				<p>
					<input type='text' name='sSubTitle' placeholder='Subtitle' value='<?php echo $sourceSubtitle; ?>'/>
				</p>
				<p>
					<input type='text' name='sTagTitle' placeholder='Title_Tag' required='required' value='<?php echo $sourceTagTitle; ?>' />
				</p>
				<?php
				if($countAuthors > 0) {
					$i = 1;
					while($i <= $countAuthors){
						echo "<p class='author".$i."'>";
							echo "<select name='selectAuthor".$i."' class='selectAuthor".$i."'>";
								formSelected('author', $author[$i]);
							echo "</select> ";
							echo "<input type='text' name='sAuthor".$i."' class='newAuthor".$i."  small' placeholder='".$i.". Author' value='".$author[$i]."'/><br />";
						echo "</p>";
						$i++;
					}

					if($i < 4) {
						echo "<p class='author".$i."'>";
						echo "<select name='selectAuthor".$i."' class='selectAuthor".$i."'>";
							formSelect('author');
						echo "</select>";
						echo "<input type='text' name='sAuthor".$i."' class='newAuthor".$i." small' placeholder='".$i.". Author' />";
						echo "</p>";
						$i++;
						while ($i<5) {
							echo "<p class='author".$i."' style='display:none'>";
								echo "<select name='selectAuthor".$i."' class='selectAuthor".$i."'>";
									formSelect('author');
								echo "</select> ";
								echo "<input type='text' name='sAuthor".$i."' class='newAuthor".$i."  small' placeholder='".$i.". Author'/><br />";
							echo "</p>";
						$i++;
						}
					}
					
				} else {
				?>
				<p>
					<select name="selectAuthor1" class="selectAuthor1">
						<?php formSelect('author'); ?>
					</select> 
					<input type="text" name="sAuthor1" class="newAuthor1 small" placeholder='1. Author' required='required' />
				</p>
					<?php
						$i=2;
						
						while ($i<5) {
							echo "<p class='author".$i."' style='display:none'>";
								echo "<select name='selectAuthor".$i."' class='selectAuthor".$i."'>";
									formSelect('author');
								echo "</select> ";
								echo "<input type='text' name='sAuthor".$i."' class='newAuthor".$i."  small' placeholder='".$i.". Author'/><br />";
							echo "</p>";
						$i++;
						}
				}
				echo "<p>";
				echo "Editors?";
				if($sourceEditor == 1){
					echo "<input type='radio' name='sEditor' value='0'>no";
					echo "<input type='radio' name='sEditor' checked=checked' value='1'>yes";
				} else {
					echo "<input type='radio' name='sEditor' checked=checked' value='0'>no";
					echo "<input type='radio' name='sEditor' value='1'>yes";					
				}
				
				echo "</p>";
				?>
				
				<textarea name='sNote' placeholder='Comment' rows='50' cols='50' style='height: 85px;'><?php echo $sourceNote; ?></textarea>
				<input class='path' type='hidden' name='path' placeholder='path' readonly value='' />
			</td>

			<td class="right completeSource">
<?php

/*
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



	switch($completeSourceWithType) 
	{
	case 'article':
		echo "<p><input type='text' name='journaltitle' placeholder='journalTitle' required='required' value='".."' size='28' /></p>");
		break;
	case "book":
	case "booklet":
	case "collection":
		var selectLocation = "<?php formSelect('location'); ?>";
		$('td.completeSource').html("<p class='location1'>");
			$('p.location1').append("<select name='selectLocation1' class='selectLocation1'>" + selectLocation + "</select>");
			$('p.location1').append("<input type='text' name='sLocation1' class='newLocation1 small' placeholder='1. Location' size='28' required='required' />");
		var i = 2;
		while (i < 5) {
			$('td.completeSource').append("<p class='location" + i +"' style='display:none'>");
				$('p.location' + i).append("<select name='selectLocation" + i + "' class='selectLocation" + i + "'>" + selectLocation + "</select>");
				$('p.location' + i).append("<input type='text' name='sLocation" + i + "' class='newLocation" + i + " small' placeholder='" + i + ". Location' size='28' />");
			i++;
		}
		
		break;
	case 'online':
		$('td.completeSource').html("<p><input type='text' name='url' placeholder='url' size='35' required='required' /></p>");
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();
		if(dd<10){dd='0'+dd}
		if(mm<10){mm='0'+mm} 
		today = yyyy + '-' + mm + '-' + dd;
		$('td.completeSource').append("<p><input type='date' name='urldate' class='small' placeholder='YYYY-MM-DD' size='35' required='required' value='" + today + "'/> (Date of request)</p>");
		break;
	case 'proceedings':
		$('td.completeSource').html("<p><input type='text' name='eventtitle' placeholder='eventtitle' required='required' size='28' /></p>");
		$('td.completeSource').append("<p><input type='text' name='venue' placeholder='venue' size='28' /></p>");

		var selectLocation = "<?php formSelect('location'); ?>";
		$('td.completeSource').append("<p class='location1'>");
			$('p.location1').append("<select name='selectLocation1' class='selectLocation1'>" + selectLocation + "</select>");
			$('p.location1').append("<input type='text' name='sLocation1' class='newLocation1 small' placeholder='1. Location' size='28' required='required' />");
		var i = 2;
		while (i < 5) {
			$('td.completeSource').append("<p class='location" + i +"' style='display:none'>");
				$('p.location' + i).append("<select name='selectLocation" + i + "' class='selectLocation" + i + "'>" + selectLocation + "</select>");
				$('p.location' + i).append("<input type='text' name='sLocation" + i + "' class='newLocation" + i + " small' placeholder='" + i + ". Location' size='28' />");
			i++;
		}

		break;
	case 'report':
	case 'thesis':
		$('td.completeSource').html("<p><input type='text' name='type' placeholder='type' size='28' /></p>");
		$('td.completeSource').append("<p><input type='text' name='institution' placeholder='institution' size='28' /></p>");
		break;
	case 'inbook':
		var selectBook = "<?php formSelectTyp('source', 'book'); ?>";
		$('td.completeSource').html("<p class='inbook'>");
			$('p.inbook').append("<select name='inbook' class='selectSource'>" + selectBook + "</select>");
		$('td.completeSource').append("<p class='pages'>");
			$('p.pages').append("<input type='text' name='pageStart' class='small' placeholder='from page' size='16' />");
			$('p.pages').append("<input type='text' name='pageEnd' class='small' placeholder='to page' size='16' />");
		break;
	case 'incollection':
		var selectCollection = "<?php formSelectTyp('source', 'collection'); ?>";
		$('td.completeSource').html("<p class='incollection'>");
			$('p.incollection').append("<select name='incollection' class='selectSource'>" + selectCollection + "</select>");
		$('td.completeSource').append("<p class='pages'>");
			$('p.pages').append("<input type='text' name='pageStart' class='small' placeholder='from page' size='16' />");
			$('p.pages').append("<input type='text' name='pageEnd' class='small' placeholder='to page' size='16' />");
		break;
	case 'inproceedings':
		var selectProceedings = "<?php formSelectTyp('source', 'proceedings'); ?>";
		$('td.completeSource').html("<p class='inproceedings'>");
			$('p.inproceedings').append("<select name='inproceedings' class='selectSource'>" + selectCollection + "</select>");
		$('td.completeSource').append("<p class='pages'>");
			$('p.pages').append("<input type='text' name='pageStart' class='small' placeholder='from page' size='16' />");
			$('p.pages').append("<input type='text' name='pageEnd' class='small' placeholder='to page' size='16' />");
		break;
	case "manual":
	case "misc":
	case "periodical":
	case "unpublished":
		var selectbibField = "<?php formSelect('bibField'); ?>";
		$('td.completeSource').html("<p class='miscField1'>");
			$('p.miscField1').append("<select name='miscField1'>" + selectbibField + "</select>");
			$('p.miscField1').append("<input type='text' name='miscFieldValue1' class='small' size='28' />");
		$('td.completeSource').append("<p class='miscField2'>");
			$('p.miscField2').append("<select name='miscField2'>" + selectbibField + "</select>");
			$('p.miscField2').append("<input type='text' name='miscFieldValue2' class='small' size='28' />");
		break;
	}
	
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
					<select name="nCategory">
						<?php formSelected("category", $noteCategory); ?>
					</select>
					<input type="text" name="nCatNew" class='small' placeholder='new Category' />
					<select name="nProject">
						<?php formSelected("project", $noteProject); ?>
					</select>
					<input type="text" class='small' name="nProNew" placeholder='new Project' />
			</td>
			<td class="right_bottom">
				<p>
				<?php
					if($sourceID != 0){
						echo "<input type='radio' name='delete' value='NO' checked /> edit or <i class='warning'>delete</i> ";
						echo "<input type='radio' name='delete' value='YES' /> ";
					} else {
						echo "<input type='hidden' name='delete' />";
					}
				?>
					<input class='path' type='hidden' name='path' placeholder='path' readonly value='' />
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
// Autor 1
$('select.selectAuthor1').change(function() {
	if($(this).val() == 'author'){
		$("input.newAuthor1").val("");
		$(".author2").css({"display":"none"});
		$(".author3").css({"display":"none"});
		$(".author4").css({"display":"none"});
	} else {
		$('input.newAuthor1').val($(this).val());
		$(".author2").css({"display":"block"});				
	}
});
$('input.newAuthor1').change(function() {
	if($(this).val() != ""){
		$(".author2").css({"display":"block"});
	} else {
		$(".author2").css({"display":"none"});
		$(".author3").css({"display":"none"});
		$(".author4").css({"display":"none"});			
	}
});
    
 // Autor 2
    $(function() {
        $('select.selectAuthor2').change(function() {
            if($(this).val() =='author'){
				$('input.newAuthor2').val("");
				$(".author3").css({"display":"none"});
				$(".author4").css({"display":"none"});
			} else {
				$('input.newAuthor2').val($(this).val() );
				$(".author3").css({"display":"block"});
			}
        });
    });
   $(function() {
        $('input.newAuthor2').change(function() {
			if($(this).val() !=''){
				$(".author3").css({"display":"block"});
			} else {
				$(".author3").css({"display":"none"});
				$(".author4").css({"display":"none"});			
			}
		
		});
	});    
    
// Autor 3    
    $(function() {
        $('select.selectAuthor3').change(function() {
            if($(this).val() =='author'){
				$('input.newAuthor3').val("");
				$(".author4").css({"display":"none"});
			} else {
				$('input.newAuthor3').val($(this).val() );
				$(".author4").css({"display":"block"});
			}
        });
    });
   $(function() {
        $('input.newAuthor3').change(function() {
			if($(this).val() !=''){
				$(".author4").css({"display":"block"});
			} else {
				$(".author4").css({"display":"none"});			
			}
		
		});
	}); 
	
	    
// Autor 4
    $(function() {
        $('select.selectAuthor4').change(function() {
            if($(this).val() =='author'){
				$('input.newAuthor4').val("");
			} else {
				$('input.newAuthor4').val($(this).val() );
			}
        });
    });

</script>
