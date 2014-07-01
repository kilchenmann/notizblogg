<?php
echo "<h3>Tags in NOTES</h3>";
$sqlTags = mysql_query("SELECT * FROM tag ORDER BY tagName");
	
while($row = mysql_fetch_object($sqlTags)){
	$tagID = $row->tagID;
	$tagName = $row->tagName;
	
	// #Anzahl Notes pro Tag:
	if($access == 'public'){
		$sqlTagName = mysql_query("SELECT * FROM rel_note_tag, note WHERE `rel_note_tag`.`tagID` = ".$tagID." AND `rel_note_tag`.`noteID` = `note`.`noteID` AND `note`.`notePublic` = 1");
	} else {
		$sqlTagName = mysql_query("SELECT * FROM rel_note_tag WHERE `rel_note_tag`.`tagID` = ".$tagID."");
	}
	$countTags = mysql_num_rows($sqlTagName);

		if($countTags>0&&$countTags<=1){
			echo "<a href='?type=note&amp;part=tag&amp;id=".$tagID."' class='tiny' title='".$tagName.": ".$countTags." note'>".$tagName."</a>";
			echo " · ";
		} elseif($countTags>=2&&$countTags<9){
			echo "<a href='?type=note&amp;part=tag&amp;id=".$tagID."' class='small' title='".$tagName.": ".$countTags." note'>".$tagName."</a>";
			echo " · ";
		} elseif($countTags>=9&&$countTags<18){
			echo "<a href='?type=note&amp;part=tag&amp;id=".$tagID."' class='medium' title='".$tagName.": ".$countTags." notes'>".$tagName."</a>";
			echo " · ";
		} elseif($countTags>=18&&$countTags<45){
			echo "<a href='?type=note&amp;part=tag&amp;id=".$tagID."' class='large' title='".$tagName.": ".$countTags." notes'>".$tagName."</a>";
			echo " · ";
		} elseif($countTags>=45){
			echo "<a href='?type=note&amp;part=tag&amp;id=".$tagID."' class='extralarge' title='".$tagName.": ".$countTags." notes'>".$tagName."</a>";
			echo " · ";
		}
	}
	?>
