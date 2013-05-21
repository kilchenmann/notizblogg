		<?php
		echo "<h3>Category (Source)</h3>";
			$categorySql = mysql_query("SELECT DISTINCT `categoryID` FROM `category`, `source` WHERE category.categoryID = source.sourceCategory ORDER BY category.categoryName");

			while($row = mysql_fetch_object($categorySql)){
				$categoryID = $row->categoryID;
				
					echo linkIndex('source', 'category', $categoryID);
				echo " | ";
			}
		echo "<h3>Project (Source)</h3>";
			if(isset($_GET['categoryID'])){
				$categoryID = $_GET['categoryID'];
				$projectSql = mysql_query("SELECT DISTINCT `projectID` FROM `project`, `source` WHERE project.projectID = source.sourceProject AND source.sourceCategory=".$categoryID." ORDER BY project.projectName");
			} else {
				$projectSql = mysql_query("SELECT DISTINCT `projectID` FROM `project`, `source` WHERE project.projectID = source.sourceProject ORDER BY project.projectName");
			}
			while($row = mysql_fetch_object($projectSql)){
				$projectID = $row->projectID;
					echo linkIndex('source', 'project', $projectID);
				echo " | ";
			}
		?>
