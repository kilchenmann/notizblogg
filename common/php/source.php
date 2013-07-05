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
	case 'author';
	case 'search';
		show($type, $part, $partID, $access);
	break;
	


	
	case 'collection';
		show($type, $part, $partID, $access);	// 1. show collection

		$sql = mysql_query("SELECT bibFieldID FROM bibField WHERE bibFieldName = 'crossref'");
		while($row = mysql_fetch_object($sql)){
			$crossrefID = $row->bibFieldID;
		}
		$sql = mysql_query("SELECT sourceID FROM sourceDetail WHERE bibFieldID = ".$crossrefID." AND sourceDetailName = ".$partID."");
		while($row = mysql_fetch_object($sql)){
			$inSourceID = $row->sourceID;
			showSource($inSourceID);
		}
			// 2. show incollection
		
		
	
	break;
	
	
	case 'edit';
	
	
	break;
	
	case 'complete';
		if($access != 'public'){
			include (SITE_PATH."/admin/completeSource.php");
		}
	break;
	case 'save';
		if($access != 'public'){
			include (SITE_PATH."/admin/saveSource.php");
		}
	break;


}

?>
