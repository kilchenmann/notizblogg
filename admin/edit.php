<?php

/* ************************************************************** 
 * Insert different relations between the tables
 * ************************************************************** 
 */

function insertMN($table,$relTable,$data,$linkID,$linkTable){
	$data=trim($data);
	$tableID = $table."ID";
	$tableName = $table."Name";
	$linkTableID = $linkTable."ID";
	// Check Table, if data already exists
		if($data!=""){
		$relSql = mysql_query("SELECT ".$tableID." FROM ".$table." WHERE ".$tableName." = '".$data."'");
			if(mysql_num_rows($relSql)==1) {
				while($row = mysql_fetch_object($relSql)){
					$relIDs[] = $row->$tableID;
				}
			} else {
			// New Data
				mysql_query("INSERT INTO ".$table." (".$tableName.") VALUES ('".$data."')");
					$relIDs[] = mysql_insert_id();
			}
			// Save data in relational Table
			foreach($relIDs as $relID) {
				mysql_query("INSERT INTO ".$relTable." (".$tableID.", ".$linkTableID.") VALUES ('".$relID."', '".$linkID."')");
			}
		}					
}
		
		
	function showEditNoteLink($note, $notePublic, $editLink){
		if($notePublic == 1){
			//echo "<a href='?type=note&part=edit&id=".$note."' class='public' title='edit'>e</a>";
			echo "<a href='".$editLink."' class='edit public' title='edit' name=".$note.">e</a>";
		} else {
			//echo "<a href='?type=note&part=edit&id=".$note."' class='nonpublic' title='edit'>e</a>";
			echo "<a href='".$editLink."' class='edit nonpublic' title='edit' name=".$note.">e</a>";
		}
	}
		
	function editSource($source){
		$sourceTyp = "";
		$sql = mysql_query("SELECT sourceTyp, bibTypName FROM source, bibTyp WHERE sourceID = ".$source." AND source.sourceTyp = bibTyp.bibTypID");
			while ($row = mysql_fetch_object($sql)){
				$sourceTyp = $row->bibTypName;
			
			}
			linkEdit('source', $source, $sourceTyp);
	}

/* ************************************************************** 
 * All functions for formulars
 * ************************************************************** 
 */

function formSelect($table) {
	$tableName = $table."Name";
	echo "<option selected>".$table."</option>";
	$select = mysql_query("SELECT ".$tableName." FROM ".$table." ORDER BY ".$table."Name");
		while($row = mysql_fetch_object($select)){
			$option = $row->$tableName;
			echo "<option>".$option."</option>";
		}
}

function formSelectTyp($table,$typ) {
	$tableName = $table."Name";
	$tableID = $table."ID";
	$typSQL = mysql_query("SELECT bibTypID FROM bibTyp WHERE bibTypName = '".$typ."'");
		while($row = mysql_fetch_object($typSQL)){
			$bibTypID = $row->bibTypID;
		}
	echo "<option selected>".$table."</option>";
	$select = mysql_query("SELECT ".$tableName." FROM ".$table." WHERE sourceTyp = ".$bibTypID." ORDER BY ".$table."Name");
		while($row = mysql_fetch_object($select)){
			$option = $row->$tableName;
			echo "<option>".$option."</option>";
		}
}

function formSelected($table, $selectedID) {
	$tableName = $table."Name";
	$tableID = $table."ID";
	if ($selectedID != "0") {
		$selected = mysql_query("SELECT ".$tableName." FROM ".$table." WHERE ".$tableID." = ".$selectedID);
			while($row = mysql_fetch_object($selected)){
				$selectedName = $row->$tableName;
			}
		$sql = mysql_query("SELECT ".$tableName." FROM ".$table." ORDER BY ".$table."Name");
			while($row = mysql_fetch_object($sql)){
				if ($row->$tableName==$selectedName){
					echo "<option selected>".$selectedName."</option>";
				} else {
					echo "<option>".$row->$tableName."</option>";
				}
			}
				echo "<option>".$table."</option>";	
	} else {
		formSelect($table);
	}
}
 
 function addButton($id, $name, $table, $typ) {
	$addTyp = "add".$typ;
	$editID = $name."ID";
	
	echo "<form name='".$addTyp."' action='".$addTyp.".php' method='get'>";
		echo "<input type='hidden' name='".$editID."' value='".$id."' />";
		echo "<input class='button' type='submit' value='+ ".$typ."' />";
	echo "</form>";
	echo "<br />";
}

function insertField($field, $fieldValue, $sourceID){
	$selectField = mysql_query("SELECT bibFieldID FROM bibField WHERE bibFieldName = '".$field."'");
		while($row = mysql_fetch_object($selectField)){
			$bibFieldID = $row->bibFieldID;
		}
			
		$insertTyp = "INSERT INTO sourceDetail (sourceID, bibFieldID, sourceDetailName)	VALUES
		(\"".$sourceID."\", \"".$bibFieldID."\", \"".$fieldValue."\");";
		if (!mysql_query($insertTyp)){
			die('Error: ' . mysql_error());
		}
}

?>
