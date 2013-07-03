<?php
/* ************************************************************** 
 * Search different relations between the tables
 * ************************************************************** 
 */



function linkIndexMNlist($id,$table){
	$tableID = $table."ID";
	$tableName = $table."Name";
	
	$tagSql = mysql_query("SELECT tagName FROM tag, rel_note_tag WHERE tag.tagID = rel_note_tag.tagID AND rel_note_tag.noteID = '".$id."' ORDER BY tagName");
	// $tagIDs = array();
	$countTags = mysql_num_rows($tagSql);
	if($countTags>0) {
		while($row = mysql_fetch_array($tagSql)) {
			$relIDs[] = $row['tagName'];
		}
		asort($relIDs);
			$relData="";
			foreach($relIDs as $relName) {
				if($relData==""){
					$relData="<li>- <a href='".MainFile."?".$tableID."=".$relName."&amp;page=1'>".$relName."</a></li>";
				} else {
					$relData.= "<li>- <a href='".MainFile."?".$tableID."=".$relName."&amp;page=1'>".$relName."</a></li>";
				}
			}
	} else {
		$relData = "";
	}
	echo $relData;
}

?>
