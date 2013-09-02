<?php
	$edit = $_GET["editNote"];
	$sql = mysql_query("SELECT * FROM note WHERE noteID=".$edit);
	while($row = mysql_fetch_object($sql)){
		$noteID = $row->noteID;
		$noteTitle = $row->noteTitle;
		$noteContent = $row->noteContent;
		$noteCategory = $row->noteCategory;
		$noteProject = $row->noteProject;
		$noteSourceExtern = $row->noteSourceExtern;
		$noteSource = $row->noteSource;
		$pageStart = $row->pageStart;
		$pageEnd = $row->pageEnd;
		$noteMedia = $row->noteMedia;
		$notePublic = $row->notePublic;
	}
	?>
	<script type="text/javascript">
		$(document).ready(function() {
			changeMenu("SOURCES");
			$("button.menuNew").val("editNote");
			$("button.menuNew").html("EDIT");
			$("button.menuNew").toggleClass("active");
			
			$(".viewer").fadeTo("fast", 0.1);
			$(".partIndex").fadeTo("fast", 0.1);
			
			$(".titleIndex").slideToggle("fast");
			$(".contentIndex").slideToggle("fast");
			$("div.editNote").slideToggle("fast");
			$(".contentIndex").animate({
					width: "720px"
			},"fast");
		});
	</script>
	
	<?php


echo "<form accept-charset='utf-8' name='noteEdit' class='noteForm' action='".SITE_URL."/".BASE_FOLDER.MainFile."?type=note&part=save&id=".$noteID."' method='post' enctype='multipart/form-data' >";
?>
	<table class='form'>
		<tr>
			<td class="left">
				<h3>NOTE N° <?php echo $noteID; ?></h3>
				<input type='hidden' name='noteID' placeholder='ID' readonly value='<?php echo $noteID; ?>' />
				<!--<p>Title</p>-->
				<input type='text' name='nTitle' placeholder='Title' autofocus='autofocus' value='<?php echo $noteTitle; ?>' />
				<!--<p>Note</p>-->
				<textarea name='nContent' placeholder='Content' rows='10' cols='50' required='required' ><?php echo $noteContent; ?></textarea>
			</td>
			<td class="right">

			<?php
/*

*/
			?>
				
				<!-- search a file in the upload directory -->
				
				<!--
					<img rel='#showFolder' src='css/images/search.png' alt='(show folder)' class='note_zoom' width='33' title='show folder' />
				</p>
					<div id='showFolder' class='img_overlay'>
					<?php
						//showMediaFolder();
					?>
					</div>
				-->

				<!--<select name='readFolder' class='readFolder'>-->

				<div class="dropFile">
					<input type='file' name='uploadFile' class='uploadFile' placeholder='upload file' />
					<input type="text" class="client" value="Upload (Drag'n'Drop)" />
					<button type="button" class="client">CLIENT</button>
					<!--<p>from CLIENT</button>-->
				</div>
				<button type="button" class="server">SERVER</button>
				<div class="choosenMedia">
				<?php
				if(($noteMedia != "") && ($noteMedia != "")){
					echo "<p>Change Media</p>";
					showMedia($noteID, $noteMedia, $noteTitle);
				} else {
					echo "<p>+ Media</p>";
					$noteMedia='';
				}
				?>
				</div>
				<input type='text' name='mediaName' class='mediaName' placeholder='File name' value='<?php echo $noteMedia; ?>' />
			</td>
		</tr>
		<tr>
			<td class="left">
					<!--<p>tag1 / tag2 / etc</p>-->
					<input type="text" name="nTag" placeholder='tag1 / tag2 / etc' value="<?php echo indexMN('note','tag', $noteID); ?>" />
					<select name="nCategory">
						<?php formSelected("category", $noteCategory); ?>
					</select>
					<input type="text" name="nCatNew" class='small' placeholder='new Category' />
					<select name="nProject">
						<?php formSelected("project", $noteProject); ?>
					</select>
					<input type="text" class='small' name="nProNew" placeholder='new Project' />
			</td>
			<td class="right">
				<p>+ Source</p>
				<!--<p>direct Hyperlink</p>-->
				<input type='text' name='sourceLink' placeholder='direct Hyperlink' value='<?php echo $noteSourceExtern; ?>' />

				
				<!--<p>and/or select literature</p>-->

				<p><select name='sourceLiterature' class='selectSource'>
					<?php
					if($noteSource != "") {
						formSelected("source", $noteSource);
					} else {
						formSelect("source");
					}
					?>
				</select>
				<?php
				if($noteID){
					echo "<a href='newSource.php?noteID=".$noteID."' target='_blank'> Create new source</a></p>";
				} else {
					echo "not listed? <a title='1st save this note. 2nd editing the note, so you can creating a new source and add it to the note'>info</a>";
				}
			
			if($pageStart==0){$pageStart="";}
			if($pageEnd==0){$pageEnd="";}
			?>
				<input type='text' class='small' name='sourcePageStart' placeholder='from page' value='<?php echo $pageStart; ?>' />
				<input type='text' class='small' name='sourcePageEnd' placeholder='to page' value='<?php echo $pageEnd; ?>' />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom">
				<?php
			if($notePublic==1){
				echo "<input type='checkbox' name='notePublic' value='1' checked /> public? ";
			} else {
				echo "<input type='checkbox' name='notePublic' value='1' /> public? ";
			}
			if($noteID != 0){
				echo "<input type='radio' name='delete' value='NO' checked /> edit or <i class='warning'>delete</i> ";
				echo "<input type='radio' name='delete' value='YES' /> ";
			} else {
				echo "<input type='hidden' name='delete' />";
			}
				?>
			<input class='path' type='hidden' name='path' placeholder='path' readonly value='' />
			
			<button class="button" type="submit" value="SAVE">SAVE</button>
			<button class="button" type="reset" value="Clear">Clear</button>
			</td>
		</tr>
	
	
	</table>
</form>

