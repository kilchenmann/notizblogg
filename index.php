<!DOCTYPE html>
<html>
<head>
<?php
	global $count;
	$count = 200;
	
	$root = pathinfo($_SERVER['SCRIPT_FILENAME']);
	define ('BASE_FOLDER',	basename($root['dirname']));	// = notizblogg
	define ('SITE_ROOT',	$root['dirname']);				// = /var/www/notizblogg
	define ('SITE_URL',		'http://'.$_SERVER['HTTP_HOST'].'/'.BASE_FOLDER); // = http://chip.iml.unibas.ch/notizblogg
	
	


	include (SITE_ROOT."/common/config.php");
	include (SITE_ROOT."/common/php/db.php");
	include (SITE_ROOT."/common/php/content.php");
	include (SITE_ROOT."/common/php/getNote.php");
	
//	include ("fn/content.php");
//	include ("fn/getNote.php");
//	include ("fn/getSource.php");
	//include ("checkuser.php");
	//include ("fn_show.php");
	//include ("fn_edit.php");
	//$sitePath = $_SERVER["REQUEST_URI"];
	//connect();
		$siteTitle = "Notizblogg";
	//disconnect();
	$folder = "";

	date_default_timezone_set("UTC");
?>
	<title><?php echo $siteTitle ?></title>
	<meta name="Author" content="André Kilchenmann">
	<meta name="Page-topic" content="Sammelsurium von Ideen, Zitaten &amp; Präsentation von Projekten">
	<meta name="Keywords" content="">
	<meta name="Description" content="Interner Bereich von Notizblogg">
	<meta name="Robots" content="noindex,nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />

<?php
	if($siteTitle == "Export bibFile"){
		echo "<meta charset='ISO-8859-15' />";
	} else {
		echo "<meta charset='UTF-8' />";
	}
	
	echo "<link rel='shortcut icon' href='".SITE_URL."/common/images/favicon.ico'>";
	
	echo "<link type='text/css' rel='stylesheet' media='screen' href='".SITE_URL."/common/css/screen.css' />";
	echo "<link type='text/css' rel='stylesheet' media='screen' href='".SITE_URL."/common/css/screenMobile.css' />";
	echo "<link type='text/css' rel='stylesheet' media='print, embossed' href='".SITE_URL."/common/css/print.css' />";
	
	echo "<script type='text/javascript' src='".SITE_URL."/common/jquery/jquery-1.7.2.min.js'></script>";
?>



	<script type="text/javascript">
	
	function changeMenu(type){
		if(type=="NOTES"){
			$(".typeIndex h2").html("SOURCES");
			$(".typeIndex h2").css({"cursor":"n-resize"});
			if($(".titleIndex").css("display")=="none"){
				var active = $(".menu button.active").val();
				$("."+active).slideToggle("slow");
			}

			$("input.searchTerm").attr({"placeholder":"search in Sources"});
			$("input.searchType").val("source");
			$(".menuNew").val("newSource");
			$(".menuPart").val("partSource");
			$(".menuCloud").val("cloudAuthors");
			$(".menuCloud").html("Authors");

			if($(".titleIndex").css("display")=="none"){
				var active = $(".menu button.active").val();
				$("."+active).slideToggle("slow");
				if ($(".contentIndexPlus").css("display")!="none") {
					$(".contentIndexPlus").slideToggle("slow");
				}
			}


		} else if(type=="SOURCES"){
			$(".typeIndex h2").html("NOTES");
			$(".typeIndex h2").css({"cursor":"s-resize"});
			//$(this).css({"background-position":"73px -66px"});
			if($(".titleIndex").css("display")=="none"){
				var active = $(".menu button.active").val();
				$("."+active).fadeOut("slow");
				if ($(".contentIndexPlus").css("display")!="none") {
					$(".contentIndexPlus").slideToggle("slow");
				}
			}
			$("input.searchTerm").attr({"placeholder":"search in Notes"});
			$("input.searchType").val("note");
			$(".menuNew").val("newNote");
			$(".menuPart").val("partNote");
			$(".menuCloud").val("cloudTags");
			$(".menuCloud").html("Tags");

			if($(".titleIndex").css("display")=="none"){
				var active = $(".menu button.active").val();
				$("."+active).fadeIn("slow");
			}
		}	
		
	}
	
	
	</script>
</head>
<body>
<?php connect(); ?>

	<div class="preload">
	<!-- evt. Bilder hier vorladen -->
	</div>

	<header>
		<div class="typeIndex">
			<h2></h2>
		</div>
		<table class="menuBar" cellspacing="0" cellpadding="0">
			<tbody>
				<tr class="menu">
					<td>
						<button value="newNote" class="menuNew">NEW</button>
					</td>
					<td>
						<button value="partNote" class="menuPart">Part</button>
					</td>
					<td>
						<button value="cloudTags" class="menuCloud">Tags</button>
					</td>
				</tr>
			</tbody>
		</table>
		<table class="searchBar" cellspacing="0" cellpadding="0">
			<tbody>
				<tr class="search">
					<form accept-charset="utf-8" name="searchForm" class="searchForm" action="index.php" method="get">
					<td class="searchTerm" colspan="3">
						<input type="hidden" class="searchType" name="type" value="note" size="16" />
						<input type="hidden" class="searchPart" name="part" value="search" size="16" />
						<input type="text" class="searchTerm" name="id" placeholder="search in Notes" >
					</td>
					<td class="searchButton">
						<button class="enter">search</button>
					</td>
					</form>
				</tr>
			</tbody>
		</table>
		<table class="settingBar" cellspacing="0" cellpadding="0">
			<tbody>
				<tr class="setting">
					<td>
						<button value="paper" class="changeView"><img src="<?php echo SITE_URL; ?>/common/images/viewPaper.png" alt="paper"></button>
					</td>
					<td>
						<a href="index.php" title="Back to the index (home)"><button value="home" class="home"><img src="<?php echo SITE_URL; ?>/common/images/home.png" alt="home"></button></a>
					</td>
					<td>
						<button value="settings" class="menuSet"><img src="<?php echo SITE_URL; ?>/common/images/settings.png" alt="settings"></button>
					</td>
				</tr>
			</tbody>
		</table>
	</header>
<!-- ------------------------------------------------------------- -->
	<div class="navigationIndex">
		<div class="partIndex">
			<h2></h2>
		</div>
		<script type="text/javascript">
			$(".typeIndex h2").click(function() {
				if($("button.menuNew").text() == "NEW"){
					changeMenu($(this).text());
				} else {
					alert("This function is disabled by the edit mode");
				}
			});
		</script>
		
		<!-- ------------------------------------------- -->
		<div class="titleIndex">
			<h1 class="left"></h1>
			<p class="right"></p>
		</div>
		
		
		
		<!-- ------------------------------------------- -->
		<div class="contentIndex">
			<div class="newNote">
				<p>Hier JS-Function für new note!</p>
				<?php include "newNote.php"; ?>
			</div>
			<div class="editNote">
				<?php include "editNote.php"; ?>
			</div>
			<div class="newSource">
				<?php include "newSource.php"; ?>
			</div>
			<div class="editSource">
				<?php //include "editSource.php"; ?>
			</div>
			<div class="partNote">
				<?php include "partNote.php"; ?>
			</div>
			<div class="partSource">
				<?php include "partSource.php"; ?>
			</div>
			<div class="cloudTags">
				<?php include "cloudTags.php"; ?>
			</div>
			<div class="cloudAuthors">
				<?php include "cloudAuthors.php"; ?>
			</div>
			<div class="settings">

			</div>
		</div>
		<div class="contentIndexPlus">
<?php
		$allFiles = scandir('media'); //Ordner "media" auslesen
		foreach ($allFiles as $file) { // Ausgabeschleife
			if($file != "." && $file != ".." && !is_dir($file)){
				echo "<li name='".$file."'><img src='media/".$file."' > ".$file."</li>"; //Ausgabe Einzeldatei
			}
		}
?>
		</div>
		<div class="contentSettings">
			<li><a href="user.php" >Profile</a></li>
			<li><a href="showMysqlDumb.php" >Backup</a></li>
			<li><a href="logout.php" >Logout</a></li>
		</div>
	</div>
<!-- ------------------------------------------------------------- -->
	
	<div class="viewer">
	
	
	
<?php

	if($_SERVER["QUERY_STRING"]){
		if(isset($_GET["type"])){
			$type = $_GET["type"];
		}
		if(isset($_GET["part"])){
			$part = $_GET["part"];
		}
		if(isset($_GET["id"])){
			$partID = $_GET["id"];
		}
		echo "<div class='desk'>";
			include (SITE_ROOT."/common/".$type.".php");
		echo "</div>";
	} else {
		// Startseite:
?>
		<script type="text/javascript">
			$(".typeIndex h2").html("NOTES");
			$(".partIndex h2").html("Index");
			$(".titleIndex .left").html("Notizblogg");
			$(".titleIndex .right").html("#notes: <?php echo $count; ?>");
		</script>
<?php
		echo "<div class='desk'>";
			$sql = mysql_query("SELECT noteID FROM note ORDER BY date DESC LIMIT ".$count);
			while($row = mysql_fetch_object($sql)){
				$typeID = $row->noteID;
				showNote($typeID);
			}
		echo "</div>";
	}

disconnect();
?>
	</div>
<script type="text/javascript">

	$(document).ready(function() {
		if($("h1.left").text()!=""){
			var siteTitle = $(".titleIndex .left").text()+" ("+$(".partIndex h2").text()+" in Notizblogg)";
			document.title = siteTitle;
		} else {
			document.title="Notizblogg | intern";
		}
		// mobile trick
		setTimeout(function () {
			// Hide the address bar!
				window.scrollTo(0, 1);
			}, 0);
		});
		
		var editLocation = window.location.toString();
		var getLocation = editLocation.split("edit=")[0];
		var newLocation = getLocation.substr(0,getLocation.length-1)
		$(".path").val(newLocation);
		
		
		/*
		$("header").click(function(){
			$(".slidePart").slideToggle("slow");
			$(".indexTitle .content").slideToggle("slow");
		});
		*/
		/*
		$(document).click(function(event) {
			//alert(e);
			if (!$(event.target).is("button")) {
		//Hide the menus if visible
				if($(".titleIndex").css("display")=="none"){
					var openIndex = $("tr.menu button.active").val();

					if(openIndex == "newNote" || openIndex == "newSource" || openIndex == "cloudTags" || openIndex == "cloudAuthors"){
						$("div."+openIndex).animate({
							width: "344px",
							height: "480px",
						},"slow");
					}
					$("div."+openIndex).slideToggle("slow", function(){
						$(".wall").fadeTo("slow", 1);
						$(".titleIndex").slideToggle("slow");

					});
					$("button.active").toggleClass("active");

				}
			}
		});
		*/

		$(".menu button").click(function(){
			var openIndex = $(this).val();
			if($(".titleIndex").css("display")!="none"){
				/* titleIndex ist offen und somit kein Fenster; dieses öffnen */
				$(this).toggleClass("active");				// button wird aktiviert
				$(".partIndex").fadeTo("fast", 0.1);
				$(".titleIndex").slideToggle("fast", function(){
					$("div.contentIndex").slideToggle("fast");
					$("div."+openIndex).slideToggle("fast");
					$(".viewer").fadeTo("slow", 0.1);
				});
				if($(this).hasClass("menuNew")){
					$("div.contentIndex").animate({
						width: "720px"
					},"fast");
				} 

			} else if((!$(this).hasClass("active"))&&($(".titleIndex").css("display")=="none")){
				/* es ist bereits ein fenster geöffnet */
				if ($(".contentIndexPlus").css("display")!="none") {
					$(".contentIndexPlus").slideToggle("slow");
				}
				$("button.active").toggleClass("active");
				$(this).toggleClass("active");
				
				if(!$(this).hasClass("menuNew")){
					$(".contentIndex").animate({
						width: "460px"
					},"slow");
				} else {
					$(".contentIndex").animate({
						width: "720px"
					},"slow");
				}
				
				$(".contentIndex").children("div").fadeOut("fast");
				$("div."+openIndex).fadeIn("fast");

			} else if($(this).hasClass("active")){
				/* die aktive fensterauswahl wurde angeklickt, das fenster wird geschlossen */
				//alert("die aktive fensterauswahl wurde angeklickt, das fenster wird geschlossen");
				if ($(".contentIndexPlus").css("display")!="none") {
					$(".contentIndexPlus").slideToggle("slow");
				}
				if($(this).hasClass("menuNew")){
					$(".contentIndex").animate({
						width: "460px"
					},"fast");
				} 
				$("div."+openIndex).slideToggle("fast");
				$("div.contentIndex").slideToggle("fast", function(){

					$(".titleIndex").slideToggle("fast");
					$(".viewer").fadeTo("slow", 1);
					$(".partIndex").fadeTo("fast", 1);
				});
				
				if($("button.menuNew").text()=="EDIT"){
					$("button.menuNew").html("NEW");
					$("button.menuNew").val("newNote");

					//var newLocation = editLocation.text().split("edit=");
					window.location.replace($(".path").val());
//					location.reload();
				}

				$(this).toggleClass("active");
			}
		});

		$("button.changeView").click(function(){
			if ($("button.changeView").val()=="paper") {
				$("div.desk").addClass("paper");
				$("div.desk").removeClass("desk");
				$("button.changeView").val("desk");
				$("button.changeView").html("<img src='<?php echo SITE_URL;?>/common/images/viewDesk.png'>");
			} else {
				$("div.paper").addClass("desk");
				$("div.paper").removeClass("paper");
				$("button.changeView").val("paper");
				$("button.changeView").html("<img src='<?php echo SITE_URL;?>/common/images/viewPaper.png'>");
			}
		});

		$(".contentIndexPlus li").click(function(){
			var serverPicture = $(this).attr("name");
			$("input.mediaName").val(serverPicture);
			$(".choosenMedia").html("<img>");
			$(".choosenMedia img").attr("src","media/"+serverPicture);
		});
		
		/*
		$("a.nonpublic").click(function(){
			var note = $(this).closest(".note").children("div.cache").text();
			var noteID = note.split("·")[0];
			var noteTitle = note.split("·")[1];
			var noteContent = note.split("·")[2];
			var noteCategory = note.split("·")[3];
			var noteProject = note.split("·")[4];
			var noteSourceExtern = note.split("·")[5];
			var noteSource = note.split("·")[6];
			var pageStart = note.split("·")[7];
			var pageEnd = note.split("·")[8];
			var noteMedia = note.split("·")[9];
			var notePublic = note.split("·")[10];
			$(".newNote input[name='noteID']").val(noteID);
			$(".newNote input[name='nTitle']").val(noteTitle);
			$(".newNote textarea[name='nContent']").html(noteContent);
			
			$(".newNote input[name='mediaName']").val(noteMedia);
			
			
			$("button.menuNew").html("EDIT");
			$("button.menuNew").toggleClass("active");
			$(".partIndex").fadeTo("fast", 0.1);
			$(".titleIndex").slideToggle("fast", function(){
				$("div.contentIndex").slideToggle("fast");
				$("div.newNote").slideToggle("fast");
				$(".viewer").fadeTo("slow", 0.1);
			});
			$("div.contentIndex").animate({
				width: "720px"
			},"fast");
		});
		*/
		$("input.mediaName").change(function(){
			var viewPicture = $(this).val();
			if(viewPicture != ""){
				$(".choosenMedia").html("<img>");
				$(".choosenMedia img").attr("src","media/"+viewPicture);
			} else {
				$(".choosenMedia").html("");
			}
		});

		$("button.menuSet").click(function(){
			if ($(".contentSettings").css("display")=="none") {
				$(".contentSettings").slideToggle("slow");
			} else {
				$(".contentSettings").slideToggle("slow");
			}
		});
		
		$("button.server").click(function(){
			if ($(".contentIndexPlus").css("display")=="none") {
				$(".contentIndexPlus").slideToggle("slow");
			} else {
				$(".contentIndexPlus").slideToggle("slow");
			}
		});


		$(".note a.zoom").click(function() {
		alert ($(this).attr("title"));
		
		
		//alert($(this).text());
		
		$(".viewer").fadeOut("fast");
		//$(this).parent("div").fadeIn("fast");
		
		$(this).parent("div").animate({
				width: "800px",
				
			},"fast");
		$(this).text("-")
//		$(".contentIndex").children("div").fadeOut("fast");
		//$(this).toggleClass('mediaZoom');
		/*
		$(this).animate({
			width: "500px",
			height: "500px"
			},"fast");
		*/
		});

		$("img.staticMedia").click(function(){
			$("div.desk").addClass("lens");
			$("div.desk").removeClass("desk");
		});


	</script>
		<footer>
			NOTIZBLOGG (2.2) &copy; Andr&eacute; Kilchenmann | 2006-<?php echo date("Y"); ?>
		</footer>
	</body>
</html>
