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
	if($table == 'category' || $table == 'project'){
		echo "<option selected>".$table."</option>";
	} else {
		echo "<option></option>";
	}
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
function formSelectedTyp($table, $typ, $inName) {
	$tableName = $table."Name";
	$tableID = $table."ID";
	$typSQL = mysql_query("SELECT bibTypID FROM bibTyp WHERE bibTypName = '".$typ."'");
		while($row = mysql_fetch_object($typSQL)){
			$bibTypID = $row->bibTypID;
		}
	echo "<option selected>".$inName."</option>";
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
	if($field == 'crossref') {
		$selectSource = mysql_query("SELECT sourceID FROM source WHERE sourceName = '".$fieldValue."'");
		while($row = mysql_fetch_object($selectSource)){
			$fieldValue = $row->sourceID;
		}
	}
			
	$insertTyp = "INSERT INTO sourceDetail (sourceID, bibFieldID, sourceDetailName)	VALUES (\"".$sourceID."\", \"".$bibFieldID."\", \"".$fieldValue."\");";
	if (!mysql_query($insertTyp)){
		die('Error: ' . mysql_error());
	}
}

function prep4js($table) {
	$tableName = $table."Name";
	$tableID = $table."ID";
	$select = mysql_query("SELECT ".$tableName." FROM ".$table." ORDER BY ".$table."Name");
		echo "<textarea class='prep4js'>";
		while($row = mysql_fetch_object($select)){
			$option = $row->$tableName;
			
			echo $option . "//";
		}
		echo "</textarea>";
}

function formSelectMN($table) {
	$tableName = $table."Name";
	$i = 1;
	$selectName = "select" . $table . $i;
	$inputName = "input" . $table . $i;

// :+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+:+
	echo "<p>";
		echo "<select name='" . $selectName . "' class='" . $selectName . " smalldown' >";
			formSelect($table);
		echo "</select>";
		echo "<input type='text' name='" . $inputName . "' class='" . $inputName . " newselect' placeholder='" . $i . ". " . $table . "' required='required' />";
	echo "</p>";
?>
<script type="text/javascript">
// Autor 1
$('<?php echo $selectName; ?>').change(function() {
	if($(this).val() == 'author'){
		$("input.inputauthor1").val("");
		$(".author2").css({"display":"none"});
		$(".author3").css({"display":"none"});
		$(".author4").css({"display":"none"});
	} else {
		$('input.inputauthor1').val($(this).val());
		$(".author2").css({"display":"block"});				
	}
});
$('input.inputauthor1').change(function() {
	if($(this).val() != ""){
		$(".author2").css({"display":"block"});
	} else {
		$(".author2").css({"display":"none"});
		$(".author3").css({"display":"none"});
		$(".author4").css({"display":"none"});			
	}
});

<?php
	$i++;
	while ($i <= 4) {
	$selectName = "select" . $table . $i;
	$inputName = "input" . $table . $i;
		echo "<p class='" . $table . $i . "' style='display:none'>";
			echo "<select name='" . $selectName . "' class='" . $selectName . "' >";
				formSelect($table);
			echo "</select>";
			echo "<input type='text' name='" . $inputName . "' class='" . $inputName . " small' placeholder='" . $i . ". " . $table . "' required='required' />";
		echo "</p>";
		$i++;
	}

?>

    <script type="text/javascript">
 // Autor 2
    $(function() {
        $('select.selectauthor2').change(function() {
            if($(this).val() =='author'){
				$('input.inputauthor2').val("");
				$(".author3").css({"display":"none"});
				$(".author4").css({"display":"none"});
			} else {
				$('input.inputauthor2').val($(this).val() );
				$(".author3").css({"display":"block"});
			}
        });
    });
   $(function() {
        $('input.inputauthor2').change(function() {
			if($(this).val() !=''){
				$(".author3").css({"display":"block"});
			} else {
				$(".author3").css({"display":"none"});
				$(".author4").css({"display":"none"});			
			}
		
		});
	});    
    
// Autor 3    
    $(function() {
        $('select.selectauthor3').change(function() {
            if($(this).val() =='author'){
				$('input.inputauthor3').val("");
				$(".author4").css({"display":"none"});
			} else {
				$('input.inputauthor3').val($(this).val() );
				$(".author4").css({"display":"block"});
			}
        });
    });
   $(function() {
        $('input.inputauthor3').change(function() {
			if($(this).val() !=''){
				$(".author4").css({"display":"block"});
			} else {
				$(".author4").css({"display":"none"});			
			}
		
		});
	}); 
	
	    
// Autor 4
    $(function() {
        $('select.selectauthor4').change(function() {
            if($(this).val() =='author'){
				$('input.inputauthor4').val("");
			} else {
				$('input.inputauthor4').val($(this).val() );
			}
        });
    });



</script>
<?php




}


?>
