<?php
//$indexTitle = "note";
	if($access == 'public'){
		$gpa = "AND notePublic = 1";
	} else {
		$gpa = "";
	}
switch($part){
	case "category";
	case "project";
			show($type, $part, $partID, $access);
	break;

	case "tag";
		$selectTag = mysql_query("SELECT tagName FROM tag WHERE tagID = '".$partID."'");
		while($row = mysql_fetch_object($selectTag)){
			$tagName = $row->tagName;
		}
		$tagSql = mysql_query("SELECT note.noteID FROM note, rel_note_tag WHERE tagID = ".$partID." AND rel_note_tag.noteID = note.noteID ".$gpa." ORDER BY note.noteTitle, note.date DESC");
		$countResult = mysql_num_rows($tagSql);
?>
		<script type="text/javascript">
			$('.partIndex h2').html("<?php echo $part; ?>");
			$('.titleIndex .left').html("<?php echo $tagName; ?>");
			$('.titleIndex .right').html("<?php echo "#".$type."s: ".$countResult; ?>");
		</script>
<?php
				while($row = mysql_fetch_object($tagSql)){
					showNote($row->noteID, $access);
				}
	break;
		
	case "search";
		$searchSql = mysql_query("SELECT noteID FROM note WHERE `noteTitle` LIKE '%".$partID."%' OR `noteContent` LIKE '%".$partID."%' OR noteSourceExtern LIKE '%".$partID."%' ".$gpa." ORDER BY date DESC");
		$countResult = mysql_num_rows($searchSql);
?>
		<script type="text/javascript">
			$('.partIndex h2').html("<?php echo $part."ed"; ?>");
			$('.titleIndex .left').html("<?php echo '\''.$partID.'\''; ?>");
			$('.titleIndex .right').html("<?php echo '#'.$type.'s: '.$countResult; ?>");
		</script>
<?php
			while($row = mysql_fetch_object($searchSql)){
				showNote($row->noteID, $access);
			}
	break;
		
	case "save";
		if($access != 'public'){
			include (SITE_PATH."/admin/saveNote.php");
		}
	break;
		
	case "zoom";
			showNote($partID, $access);
?>
		<script type="text/javascript">
			$('.partIndex h2').html("note");
			$('.titleIndex .left').html("<?php echo 'N° '.$partID; ?>");
			$('.titleIndex .right').html("");
			$(".table").removeClass("table");
			$('.viewer').children().addClass("lens");
			$('.zoom').html('&nbsp;-&nbsp;');
		</script>
<?php

	break;

	case "source";
		showSource($partID);
		show('note', 'source', $partID, $access);
	break;
}
?>

<script type="text/javascript">
	window.onload=function(){
		$('.typeIndex h2').html("NOTES");
		$('.typeIndex h2').css({'cursor':'s-resize'});
		
		if($('.titleIndex').css('display')=='none'){
			var active = $('.menu button.active').val();
			//$('.'+active).fadeOut('slow');
		}
		$('input.searchTerm').attr({'placeholder':'search in Notes'});
		$('input.searchType').val('note');
		if($('.menuNew').text()=="NEW"){
			$('.menuNew').val('newNote');
		} else {
			$('.menuNew').val('editNote');
		}

		$('.menuPart').val('partNote');
		$('.menuCloud').val('cloudTags');
		$('.menuCloud').html('Tags');
		
		if($('.titleIndex').css('display')=='none'){
			var active = $('.menu button.active').val();
			//$('.'+active).fadeIn('slow');
		}
	}
</script>
