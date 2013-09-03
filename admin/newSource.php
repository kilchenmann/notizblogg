<?php
	$checkID = md5(microtime());
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
				<input type='hidden' name='sCheckID' placeholder='checkID' readonly value='<?php echo $checkID; ?>' />
				<input type='hidden' name='sourceID' placeholder='ID' readonly value='<?php echo $sourceID; ?>' />
				<p>@bibTyp
					<select name='sTyp' required='required' class='bibTyp' >
						<?php formSelect("bibTyp"); ?>
					</select>
				</p>
				<p>
					<input type='text' name='sTitle' placeholder='Title' value='<?php echo $sourceTitle; ?>' />
				</p>
				<p>
					<input type='text' name='sSubTitle' placeholder='Subtitle'/>
				</p>
				<p>
					<input type='text' class='medium' name='sTagTitle' placeholder='Title_Tag' required='required' />
					<input type='text' class='xsmall' name='sYear' placeholder='Year'/>
				</p>
				<div class="author">
					<p>Choose an Author</p>
					<select name="selectAuthor" class="choice selectAuthor" size='11'>
						<?php formSelect('author'); ?>
					</select> 
					<p class='choosenVal'>
						<a class='delAuthor1'>x</a>
						<input type="text" name="sAuthor1" class="newAuthor1" placeholder='1. Author' required='required' />
					</p>
					<p class='choosenVal'>
						<a class='delAuthor2'>x</a>
						<input type="text" name="sAuthor2" class="newAuthor2" placeholder='2. Author' />
					</p>
					<p class='choosenVal'>
						<a class='delAuthor3'>x</a>
						<input type="text" name="sAuthor3" class="newAuthor3" placeholder='3. Author' />
					</p>
					<p class='choosenVal'>
						<a class='delAuthor4'>x</a>
						<input type="text" name="sAuthor4" class="newAuthor4" placeholder='4. Author' />
					</p>
					<p class='choosenVal'>Editors? 
						<input type="radio" name="sEditor" checked="checked" value="0">no
						<input type="radio" name="sEditor" value="1">yes
					</p>
				</div>
				
				<textarea name='sNote' placeholder='Comment' rows='50' cols='50' style='height: 85px;'></textarea>
				<input class='path' type='hidden' name='path' placeholder='path' readonly value='' />
			</td>
			<td class="right completeSource">
				<div class="location" style="display:none;" >
					<p>Choose a location</p>
					<select name="selectLocation" class="choice selectLocation" size='11'>
						<?php formSelect('location'); ?>
					</select> 
					<p class='choosenVal'>
						<a class='delLocation1'>x</a>
						<input type="text" name="sLocation1" class="newLocation1" placeholder='1. Location' required='required' />
					</p>
					<p class='choosenVal'>
						<a class='delLocation2'>x</a>
						<input type="text" name="sLocation2" class="newLocation2" placeholder='2. Location' />
					</p>
					<p class='choosenVal'>
						<a class='delLocation3'>x</a>
						<input type="text" name="sLocation3" class="newLocation3" placeholder='3. Location' />
					</p>
					<p class='choosenVal'>
						<a class='delLocation4'>x</a>
						<input type="text" name="sLocation4" class="newLocation4" placeholder='4. Location' />
					</p>
				</div>
				<br>
				<div class="specificFields">
				
				
				</div>
				<br>
				<div class="defaultFields">
				
				
				</div>
			</td>
		</tr>

		<tr>
			<td class="left_bottom">
				<p>
					<select name="sCategory" class='small'>
						<?php formSelect("category"); ?>
					</select>
					<input type="text" name="sCatNew" class='small' placeholder='new Category'/>
				</p>
				<p>
					<select name="sProject" class='small'>
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
					<button class="button" type="submit" value="SAVE">SAVE</button>
					<button class="button" type="reset" value="Clear">Clear</button>
				</p>
			</td>
		</tr>
	</table>
</form>


<script type="text/javascript" charset="utf-8">
	
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
	if($('input.newAuthor1').val().length == 0){
		$('input.newAuthor1').val($(this).val());
	} else {
		if($('input.newAuthor2').val().length == 0){
			$('input.newAuthor2').val($(this).val());
		} else {
			if($('input.newAuthor3').val().length == 0){
				$('input.newAuthor3').val($(this).val());
			} else {
				if($('input.newAuthor4').val().length == 0){
					$('input.newAuthor4').val($(this).val());
				}
			}
		}
	}
});

$('a.delAuthor1').click(function() {
	$('input.newAuthor1').val('');
});
$('a.delAuthor2').click(function() {
	$('input.newAuthor2').val('');
});
$('a.delAuthor3').click(function() {
	$('input.newAuthor3').val('');
});
$('a.delAuthor4').click(function() {
	$('input.newAuthor4').val('');
});

$('select.selectLocation option').click(function() {
	if($('input.newLocation1').val().length == 0){
		$('input.newLocation1').val($(this).val());
	} else {
		if($('input.newLocation2').val().length == 0){
			$('input.newLocation2').val($(this).val());
		} else {
			if($('input.newLocation3').val().length == 0){
				$('input.newLocation3').val($(this).val());
			} else {
				if($('input.newLocation4').val().length == 0){
					$('input.newLocation4').val($(this).val());
				}
			}
		}
	}
});

$('a.delLocation1').click(function() {
	$('input.newLocation1').val('');
});
$('a.delLocation2').click(function() {
	$('input.newLocation2').val('');
});
$('a.delLocation3').click(function() {
	$('input.newLocation3').val('');
});
$('a.delLocation4').click(function() {
	$('input.newLocation4').val('');
});



</script>


