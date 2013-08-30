<?php
	$sourceID = "";
	$sourceTitle = "";
	$noteContent = "";
	$noteCategory = 0;
	$noteProject = 0;
	$noteSourceExtern = "";
	$noteSource = 0;
	$pageStart = "";
	$pageEnd = "";
	$noteMedia = "";
	$notePublic = 0;

echo "<h3>Create new SOURCE</h3>";
echo "<form accept-charset='utf-8' name='noteSource' class='sourceForm' action='".SITE_URL."/".BASE_FOLDER.MainFile."?type=source&part=save&id=".$sourceID."' method='post' enctype='multipart/form-data' >";
?>
	<table class='form'>
		<tr>
			<td class="left">
				<input type='hidden' name='sourceID' placeholder='ID' readonly value='<?php echo $sourceID; ?>' />
				<p>@bibTyp
					<select name='sTyp' required='required' class='bibTyp' >
						<?php formSelect("bibTyp"); ?>
					</select>
					<input type='text' class='xsmall' name='sYear' placeholder='Year'/>
				</p>
				<p>
					<input type='text' name='sTitle' placeholder='Title' value='<?php echo $sourceTitle; ?>' />
				</p>
				<p>
					<input type='text' name='sSubTitle' placeholder='Subtitle'/>
				</p>
				<p>
					<input type='text' name='sTagTitle' placeholder='Title_Tag' required='required' />
				</p>
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
					?>
				<p>
					Editors? 
					<input type="radio" name="sEditor" checked="checked" value="0">no
					<input type="radio" name="sEditor" value="1">yes
				</p>
				
				<textarea name='sNote' placeholder='Comment' rows='50' cols='50' style='height: 85px;'></textarea>
				<input class='path' type='hidden' name='path' placeholder='path' readonly value='' />
			</td>
			<td class="right completeSource">
<!--
				<p class='location1' style='display:none'>
				<select name='selectLocation1' class='selectLocation1'>
					<?php // formSelect('location'); ?>
				</select>
				<input type='text' name='sLocation1' class='newLocation1 small' placeholder='1. Location' size='28' required='required' />
				</p>
-->

				<?php
/*
				$i=2;
				while ($i<5) {
					echo "<p class='location".$i."' style='display:none'>";
						echo "<select name='selectLocation".$i."' class='selectLocation".$i."'>";
							formSelect('location');
						echo "</select> ";
						echo "<input type='text' name='sLocation".$i."' class='newLocation".$i." small' placeholder='".$i.". Location' size='28'/><br />";
					echo "</p>";
					$i++;
				}
*/
				?>



			</td>
		</tr>

		<tr>
			<td class="left_bottom">
				<p>
					<select name="sCategory">
						<?php formSelect("category"); ?>
					</select>
					<input type="text" name="sCatNew" class='small' placeholder='new Category'/>
				</p>
				<p>
					<select name="sProject">
						<?php formSelect("project"); ?>
					</select>
					<input type="text" name="sProNew" class='small' placeholder='new Project'/>
				</p>
			</td>
			<td class="right_bottom">
				<p>
				<?php
					if($noteID != 0){
						echo "<input type='radio' name='delete' value='NO' checked /> edit or <i class='warning'>delete</i> ";
						echo "<input type='radio' name='delete' value='YES' /> ";
					} else {
						echo "<input type='hidden' name='delete' />";
					}
				?>
				</p>
				<p>
					<button class="button" type="submit" value="NEXT">NEXT</button>
					<button class="button" type="reset" value="Clear">Clear</button>
				</p>
			</td>
		</tr>
	</table>
</form>


<script type="text/javascript" charset="utf-8">
	
// adding a form on the right side for the chosen bibTyp
$('select.bibTyp').change(function() {
	var completeSourceWithType = this.value;
	switch(completeSourceWithType) 
	{
	case 'article':
		$('td.completeSource').html("<p><input type='text' name='journaltitle' placeholder='journalTitle' required='required' size='28' /></p>");
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
    
// Location 1
$('select.selectLocation1').change(function() {
	if($(this).val() == 'location'){
		$("input.newLocation1").val("");
		$(".location2").css({"display":"none"});
		$(".location3").css({"display":"none"});
		$(".location4").css({"display":"none"});
	} else {
		$('input.newLocation1').val($(this).val());
		$(".location2").css({"display":"block"});				
	}
});
$('input.newLocation1').change(function() {
	if($(this).val() != ""){
		$(".location2").css({"display":"block"});
	} else {
		$(".location2").css({"display":"none"});
		$(".location3").css({"display":"none"});
		$(".location4").css({"display":"none"});			
	}
});
    
 // Location 2
    $(function() {
        $('select.selectLocation2').change(function() {
            if($(this).val() =='location'){
				$('input.newLocation2').val("");
				$(".location3").css({"display":"none"});
				$(".location4").css({"display":"none"});
			} else {
				$('input.newLocation2').val($(this).val() );
				$(".location3").css({"display":"block"});
			}
        });
    });
   $(function() {
        $('input.newLocation2').change(function() {
			if($(this).val() !=''){
				$(".location3").css({"display":"block"});
			} else {
				$(".location3").css({"display":"none"});
				$(".location4").css({"display":"none"});			
			}
		
		});
	});    
    
// Location 3    
    $(function() {
        $('select.selectLocation3').change(function() {
            if($(this).val() =='location'){
				$('input.newLocation3').val("");
				$(".location4").css({"display":"none"});
			} else {
				$('input.newLocation3').val($(this).val() );
				$(".location4").css({"display":"block"});
			}
        });
    });
   $(function() {
        $('input.newLocation3').change(function() {
			if($(this).val() !=''){
				$(".location4").css({"display":"block"});
			} else {
				$(".location4").css({"display":"none"});			
			}
		
		});
	}); 
	
	    
// Location 4
    $(function() {
        $('select.selectLocation4').change(function() {
            if($(this).val() =='location'){
				$('input.newLocation4').val("");
			} else {
				$('input.newLocation4').val($(this).val() );
			}
        });
    });    

</script>


