<?php
echo "<h3>Authors in SOURCES</h3>";
	$sqlAuthor = mysql_query("SELECT * FROM author ORDER BY authorName");
		
	while($row = mysql_fetch_object($sqlAuthor)){
		$sqlAuthorName = mysql_query("SELECT * FROM rel_source_author WHERE `rel_source_author`.`authorID` = ".$row->authorID."");
		$countAuthors = mysql_num_rows($sqlAuthorName);
			$authorID = $row->authorID;
			$authorName = $row->authorName;
		if($countAuthors>0&&$countAuthors<=1){
			echo "<a href='?type=source&amp;part=author&amp;id=".$authorID."' class='tiny' title='".$authorName.": ".$countAuthors." source'>".$authorName."</a>";
			echo " · ";
		} elseif($countAuthors>=2&&$countAuthors<9){
			echo "<a href='?type=source&amp;part=author&amp;id=".$authorID."' class='small' title='".$authorName.": ".$countAuthors." sources'>".$authorName."</a>";
			echo " · ";
		} elseif($countAuthors>=9&&$countAuthors<18){
			echo "<a href='?type=source&amp;part=author&amp;id=".$authorID."' class='medium' title='".$authorName.": ".$countAuthors." sources'>".$authorName."</a>";
			echo " · ";
		} elseif($countAuthors>=18&&$countAuthors<45){
			echo "<a href='?type=source&amp;part=author&amp;id=".$authorID."' class='large' title='".$authorName.": ".$countAuthors." sources'>".$authorName."</a>";
			echo " · ";
		} elseif($countAuthors>=45){
			echo "<a href='?type=source&amp;part=author&amp;id=".$authorID."' class='extralarge' title='".$authorName.": ".$countAuthors." sources'>".$authorName."</a>";
			echo " · ";
		}
	}

?>
