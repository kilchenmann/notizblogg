<?php
?>
		<script type="text/javascript">
			changeMenu("NOTES");
			$(".partIndex h2").html("Excerpt");
			$(".titleIndex .left").html("");
			$(".titleIndex .right").html("#sources:");
		</script>
<?php

switch($part){
	case "category";
	case "project";
		show($type, $part, $partID, $access);
	break;
	
	case 'author';
//		$authorID = $partID;
	//	linkIndexMN($type, $part, $partID);
	$authorID = $partID;
	// type = source
	// part = author
	$relTable = "rel_".$type."_".$part;
	$authorSql = mysql_query("SELECT ".$part."Name, ".$type."ID FROM ".$part.", ".$relTable." WHERE ".$part.".".$part."ID = ".$relTable.".".$part."ID AND ".$relTable.".".$part."ID = '".$partID."' ORDER BY ".$part."Name");
	$countAuthors = mysql_num_rows($authorSql);
	while($row = mysql_fetch_object($authorSql)){
		$authorName = $row->authorName;
		$sourceID = $row->sourceID;
		showSource($sourceID);
	}
?>
		<script type="text/javascript">
			changeMenu("NOTES");
			$(".partIndex h2").html("Author");
			$(".titleIndex .left").html("<?php echo $authorName; ?>");
			$(".titleIndex .right").html("#sources: <?php echo $countAuthors; ?>");
		</script>
<?php

	break;
	
	case 'collection';
	
	
	break;
	
	case 'search';
	
	
	break;
	
	case 'edit';
	
	
	break;
	
	case 'save';
	
	
	break;


}

?>
