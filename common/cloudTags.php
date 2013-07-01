<?php
echo "<h3>Tags in NOTES</h3>";
$sqlTags = mysql_query("SELECT * FROM tag ORDER BY tagName");
	
while($row = mysql_fetch_object($sqlTags)){
	$tagID = $row->tagID;
	$tagName = $row->tagName;
	
	// #Anzahl Notes pro Tag:
	$sqlTagName = mysql_query("SELECT * FROM rel_note_tag WHERE `rel_note_tag`.`tagID` = ".$tagID."");
	$countTags = mysql_num_rows($sqlTagName);

		if($countTags>0&&$countTags<=1){
			echo "<a href='index.php?type=note&part=tag&id=".$tagID."' class='tiny' title='".$tagName.": ".$countTags." note'>".$tagName."</a>";
			echo " · ";
		} elseif($countTags>=2&&$countTags<9){
			echo "<a href='index.php?type=note&part=tag&id=".$tagID."' class='small' title='".$tagName.": ".$countTags." note'>".$tagName."</a>";
			echo " · ";
		} elseif($countTags>=9&&$countTags<18){
			echo "<a href='index.php?type=note&part=tag&id=".$tagID."' class='medium' title='".$tagName.": ".$countTags." notes'>".$tagName."</a>";
			echo " · ";
		} elseif($countTags>=18&&$countTags<45){
			echo "<a href='index.php?type=note&part=tag&id=".$tagID."' class='large' title='".$tagName.": ".$countTags." notes'>".$tagName."</a>";
			echo " · ";
		} elseif($countTags>=45){
			echo "<a href='index.php?type=note&part=tag&id=".$tagID."' class='extralarge' title='".$tagName.": ".$countTags." notes'>".$tagName."</a>";
			echo " · ";
		}
	}
	?>
