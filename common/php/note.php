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
	case "tag";
	case "search";
		show($type, $part, $partID, $access);
	break;
	
	case "source";
		show($part, 'excerpt', $partID, $access);	// 1. show Source
		show($type, $part, $partID, $access);		// 2. show Notes
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
