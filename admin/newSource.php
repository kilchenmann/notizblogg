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
	

echo "<form accept-charset='utf-8' name='noteSource' class='sourceForm' action='".SITE_URL."/".BASE_FOLDER.MainFile."?type=source&part=save&id=".$sourceID."' method='post' enctype='multipart/form-data' >";
?>
	<table class='form'>
		<tr>
			<td class="left">
				<h3>Create new SOURCE</h3>
				<input type='hidden' name='sourceID' placeholder='ID' readonly value='<?php echo $sourceID; ?>' />
				<!--<p>Title</p>-->
				<input type='text' name='sTitle' placeholder='Title' autofocus='autofocus' value='<?php echo $sourceTitle; ?>' />
				<input type='text' name='sSubTitle' placeholder='Subtitle'/>
				<input type='text' name='sTagTitle' placeholder='Title_Tag' required='required' />
				<textarea name='sNote' placeholder='Comment' rows='10' cols='50' ></textarea>
			</td>
			<td class="right">
				<!--<p>Author / Editor</p>-->
				<select name="selectAuthor1" id="selectAuthor1">
						<?php formSelect('author'); ?>
				</select> 
				<input type="text" name="sAuthor1" id="newAuthor1" class='small'  placeholder='1. Author' required='required' />
				<?php
					$i=2;
					
					while ($i<5) {
						echo "<div id='author".$i."' style='display:none'>";
							echo "<select name='selectAuthor".$i."' id='selectAuthor".$i."'>";
								formSelect('author');
							echo "</select> ";
							echo "<input type='text' name='sAuthor".$i."' id='newAuthor".$i."' class='small' placeholder='".$i.". Author'/><br />";
						echo "</div>";
					$i++;
					}
				?>
				Editors? 
				<input type="radio" name="sEditor" checked="checked" value="0">no
				<input type="radio" name="sEditor" value="1">yes
			</td>
		</tr>

		<tr>
			<td class="left">
				<select name="sCategory">
					<?php formSelect("category"); ?>
				</select>
				<input type="text" name="sCatNew" class='small' placeholder='new Category'/>
				
				<select name="sProject">
					<?php formSelect("project"); ?>
				</select>
				<input type="text" name="sProNew" class='small' placeholder='new Project'/>
			</td>
			<td class="right">
				<input type='text' name='sYear' placeholder='Year'/>
					<select name='sourceTyp'>
						<?php formSelect("bibTyp"); ?>
					</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bottom">
		<?php
			if($noteID != 0){
				echo "<input type='radio' name='delete' value='NO' checked /> edit or <i class='warning'>delete</i> ";
				echo "<input type='radio' name='delete' value='YES' /> ";
			} else {
				echo "<input type='hidden' name='delete' />";
			}
		?>
			<input class='path' type='hidden' name='path' placeholder='path' readonly value='' />				
			<button class="button" type="submit" value="NEXT">NEXT</button>
			<!-- Hier wird das soeben konstruierte Formular mittels JavaScript / jQuery ausgeblendet und durch das zweite Formular ersetzt. -->
			<button class="button" type="reset" value="Clear">Clear</button>
			</td>
		</tr>
	
	
	</table>
</form>


<script type="text/javascript" charset="utf-8">
	
// Autor 1
$('select#selectAuthor1').change(function() {
	if($(this).val() == 'author'){
		$("input#newAuthor1").val("");
		$("#author2").css({"display":"none"});
		$("#author3").css({"display":"none"});
		$("#author4").css({"display":"none"});
	} else {
		$('input#newAuthor1').val($(this).val());
		$("#author2").css({"display":"block"});				
	}
});
$('input#newAuthor1').change(function() {
	if($(this).val() != ""){
		$("#author2").css({"display":"block"});
	} else {
		$("#author2").css({"display":"none"});
		$("#author3").css({"display":"none"});
		$("#author4").css({"display":"none"});			
	}
});
    
 // Autor 2
    $(function() {
        $('select#selectAuthor2').change(function() {
            if($(this).val() =='author'){
				$('input#newAuthor2').val("");
				$("#author3").css({"display":"none"});
				$("#author4").css({"display":"none"});
			} else {
				$('input#newAuthor2').val($(this).val() );
				$("#author3").css({"display":"block"});
			}
        });
    });
   $(function() {
        $('input#newAuthor2').change(function() {
			if($(this).val() !=''){
				$("#author3").css({"display":"block"});
			} else {
				$("#author3").css({"display":"none"});
				$("#author4").css({"display":"none"});			
			}
		
		});
	});    
    
// Autor 3    
    $(function() {
        $('select#selectAuthor3').change(function() {
            if($(this).val() =='author'){
				$('input#newAuthor3').val("");
				$("#author4").css({"display":"none"});
			} else {
				$('input#newAuthor3').val($(this).val() );
				$("#author4").css({"display":"block"});
			}
        });
    });
   $(function() {
        $('input#newAuthor3').change(function() {
			if($(this).val() !=''){
				$("#author4").css({"display":"block"});
			} else {
				$("#author4").css({"display":"none"});			
			}
		
		});
	}); 
	
	    
// Autor 4
    $(function() {
        $('select#selectAuthor4').change(function() {
            if($(this).val() =='author'){
				$('input#newAuthor4').val("");
			} else {
				$('input#newAuthor4').val($(this).val() );
			}
        });
    });
</script>
