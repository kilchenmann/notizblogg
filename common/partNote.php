		<?php
			echo "<h3>Category (Note)</h3>";
			$categorySql = mysql_query("SELECT DISTINCT `categoryID` FROM `category`, `note` WHERE category.categoryID = note.noteCategory ORDER BY category.categoryName");
			while($row = mysql_fetch_object($categorySql)){
			//$categoryID = $row->categoryID;
				//echo "<li>";
					echo linkIndex('note', 'category', $row->categoryID);
				echo " | ";
				//echo "</li>";
			}
			echo "<h3>Project (Note)</h3>";
			if(isset($_GET['part'])&&($_GET['part'])=='category'){
				$categoryID = $_GET['id'];
				$projectSql = mysql_query("SELECT DISTINCT `projectID` FROM `project`, `note` WHERE project.projectID = note.noteProject AND note.noteCategory=".$categoryID." ORDER BY project.projectName");
			} else {
				$projectSql = mysql_query("SELECT DISTINCT `projectID` FROM `project`, `note` WHERE project.projectID = note.noteProject ORDER BY project.projectName");
			}
				while($row = mysql_fetch_object($projectSql)){
					$projectID = $row->projectID;
					//echo "<li>";
						echo linkIndex('note', 'project', $projectID);
					echo " | ";
					//echo "</li>";
				}
		?>
