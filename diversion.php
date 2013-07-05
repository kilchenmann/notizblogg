<?php
// Folgende Abfrage / Umleitung resultiert aus der alten Notizblogg-Version 2.0
// Die alten Links im Netz sollten auf die neue Struktur umgeleitet werden
if($_SERVER["QUERY_STRING"]){
	if(isset($_GET["categoryID"])){
		$oldCategoryLink = $_GET["categoryID"];
		header ("Location: index.php?type=note&part=category&id=".$oldCategoryLink."");
		
	}
	if(isset($_GET["projectID"])){
		$oldProjectLink = $_GET["projectID"];
		header ("Location: index.php?type=note&part=project&id=".$oldProjectLink);
	}
	if(isset($_GET["tagID"])){
		$oldTagLink = htmlentities($_GET["tagID"]);

		$newTagSQL = mysql_query("SELECT tagID FROM tag WHERE tagName = '".$oldTagLink."';");
		$checkResult = mysql_num_rows($newTagSQL);
		if($checkResult > 0){
			while($row = mysql_fetch_object($newTagSQL)){
				$newTagLink = $row->tagID;
			}
			header ("Location: index.php?type=note&part=tag&id=".$newTagLink);
		} else {
			header ("Location: index.php");					//evt. error-File
		}
	}
	if(isset($_GET["sourceID"])){
		$oldSourceLink = htmlentities($_GET["sourceID"]);
		$newSourceSQL = mysql_query("SELECT sourceID FROM source WHERE sourceName = '".$oldSourceLink."';");
		$checkResult = mysql_num_rows($newTagSQL);
		if($checkResult > 0){
			while($row = mysql_fetch_object($newSourceSQL)){
				$newSourceLink = $row->sourceID;
			}
			header ("Location: index.php?type=note&part=source&id=".$newSourceLink);
		} else {
			header ("Location: index.php");					//evt. error-File
		}
	}
	if(isset($_GET["searchTerm"])){
		$oldSearchTerm = $_GET["searchTerm"];
		if(isset($_GET["searchItem"]) && $_GET["searchItem"] == "note"){
			header ("Location: index.php?type=note&part=search&id=".$oldSearchTerm);
		} elseif (isset($_GET["searchItem"]) && $_GET["searchItem"] == "source"){
			header ("Location: index.php?type=source&part=search&id=".$oldSearchTerm);
		} else {
			header ("Location: index.php?type=note&part=search&id=".$oldSearchTerm);
		}
	}
}
?>
