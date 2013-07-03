<?php
	if(isset($_GET['part'])&&($_GET['part'])=='category'){
		$categoryID = $_GET['id'];
		$projectSql = mysql_query("SELECT DISTINCT `projectID` FROM `project`, `source` WHERE project.projectID = source.sourceProject AND source.sourceCategory=".$categoryID." ORDER BY project.projectName");
		//echo "SELECT DISTINCT `projectID` FROM `project`, `source` WHERE project.projectID = source.sourceProject AND source.sourceCategory=".$categoryID." ORDER BY project.projectName";
	echo "<h3 class='part'>Only projects in this Category: </h3>";
		while($row = mysql_fetch_object($projectSql)){
			$projectID = $row->projectID;
				echo linkIndex('source', 'project', $projectID);
			echo " | ";
		}
		echo "<br>";
		echo "<br>";
		echo "<hr>";
	}


echo "<h3>All Categories (Source)</h3>";
	$categorySql = mysql_query("SELECT DISTINCT `categoryID` FROM `category`, `source` WHERE category.categoryID = source.sourceCategory ORDER BY category.categoryName");

	while($row = mysql_fetch_object($categorySql)){
		$categoryID = $row->categoryID;
		
			echo linkIndex('source', 'category', $categoryID);
		echo " | ";
	}
		echo "<br>";
		echo "<br>";
		echo "<hr>";
echo "<h3>All Projects (Source)</h3>";

		$projectSql = mysql_query("SELECT DISTINCT `projectID` FROM `project`, `source` WHERE project.projectID = source.sourceProject ORDER BY project.projectName");

	while($row = mysql_fetch_object($projectSql)){
		$projectID = $row->projectID;
			echo linkIndex('source', 'project', $projectID);
		echo " | ";
	}
?>
