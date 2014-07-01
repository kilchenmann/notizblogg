<?php
	if($access == 'public'){
		$gpa = "AND notePublic = 1";
	} else {
		$gpa = "";
	}
	
	if(isset($_GET['part'])&&($_GET['part'])=='category'){
		$categoryID = $_GET['id'];
		$projectSql = mysql_query("SELECT DISTINCT `projectID` FROM `project`, `note` WHERE project.projectID = note.noteProject AND note.noteCategory=".$categoryID." ".$gpa." ORDER BY project.projectName");
		
	echo "<h3 class='part'>Only projects in this Category: </h3>";
		while($row = mysql_fetch_object($projectSql)){
			$projectID = $row->projectID;
			//echo "<li>";
				echo linkIndex('note', 'project', $projectID);
			echo " | ";
			//echo "</li>";
		}
		echo "<br>";
		echo "<br>";
		echo "<hr>";

	}
	echo "<h3>All Categories (Note)</h3>";
	$categorySql = mysql_query("SELECT DISTINCT `categoryID` FROM `category`, `note` WHERE category.categoryID = note.noteCategory  ".$gpa." ORDER BY category.categoryName");
	while($row = mysql_fetch_object($categorySql)){
	//$categoryID = $row->categoryID;
		//echo "<li>";
			echo linkIndex('note', 'category', $row->categoryID);
		echo " | ";
		//echo "</li>";
	}
	echo "<br>";
	echo "<br>";
	echo "<hr>";
	echo "<h3>All Projects (Note)</h3>";
	$projectSql = mysql_query("SELECT DISTINCT `projectID` FROM `project`, `note` WHERE project.projectID = note.noteProject ".$gpa." ORDER BY project.projectName");
	while($row = mysql_fetch_object($projectSql)){
		$projectID = $row->projectID;
		//echo "<li>";
			echo linkIndex('note', 'project', $projectID);
		echo " | ";
		//echo "</li>";
	}
?>
