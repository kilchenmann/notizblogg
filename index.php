<?php
/* -------------------------------------------------------------------------- */
/* ------- This is the index and public start file for the notizblogg ------- */
/* Author & Copyright notizblogg: André Kilchenmann, 2006 - 2013 ------------ */
/* Website: http://notizblogg.ch  ------------------------------------------- */
/* -------------------------------------------------------------------------- */
require_once ("conf/settings.php");
?>

<!DOCTYPE html>
<html>
<head>
<?php

	$count = 33;
	$access = 'public';
	$publicaccess = true;
	$robots = "INDEX,FOLLOW";

	echo "<title>".$siteTitel." by André Kilchenmann</title>\n";
	echo "<meta name='Author' content='André Kilchenmann'>\n";
	//echo "<meta name='Page-topic' content='".$pagetopic."'>\n";
	echo "<meta name='Keywords' content='".$keywords."'>\n";
	echo "<meta name='Description' content='".$description."'>\n";
	echo "<meta name='Robots' content='".$robots."'>\n";
	// mobile settings
	echo "<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'>\n";
	echo "<meta name='apple-mobile-web-app-capable' content='yes'>\n";

	if($siteTitle == "Export bibFile"){
		echo "<meta charset='ISO-8859-15' />\n";
	} else {
		echo "<meta charset='UTF-8' />\n";
	}

	include (SITE_PATH."/common/php/db.php");
	include (SITE_PATH."/common/php/content.php");
	include (SITE_PATH."/admin/edit.php");
	include (SITE_PATH."/common/php/getNote.php");
	include (SITE_PATH."/common/php/getSource.php");
	include (SITE_PATH."/common/php/relations.php");

	echo "<link rel='shortcut icon' href='".SITE_URL."/".BASE_FOLDER."common/images/favicon.ico'>\n";

	echo "<link type='text/css' rel='stylesheet' media='screen' href='".SITE_URL."/".BASE_FOLDER."common/css/".$main_theme.".css' />\n";
	echo "<link type='text/css' rel='stylesheet' media='screen' href='".SITE_URL."/".BASE_FOLDER."common/css/".$mobile_theme.".css' />\n";
	echo "<link type='text/css' rel='stylesheet' media='print, embossed' href='".SITE_URL."/".BASE_FOLDER."common/css/".$print_theme.".css' />\n";

	echo "<script type='text/javascript' src='".SITE_URL."/".BASE_FOLDER."common/jquery/".$jquery_version."'></script>\n";
	echo "<script type='text/javascript' src='".SITE_URL."/".BASE_FOLDER."common/jquery/jquery.jcookie.js'></script>\n";
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
<?php connect();
	// redirect the old Notizblogg version 2.0 - Links
	include (SITE_PATH."/diversion.php");
?>



	<div class="preload">
	<!-- evt. Bilder hier vorladen -->
	</div>

	<header>
		<div class="panel">
		<div class="panelLeft">
		<div class="typeIndex">
			<h2></h2>
		</div>
		<table class="menuBar">
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
		<form accept-charset="utf-8" name="searchForm" class="searchForm" action="<?php echo SITE_URL."/".BASE_FOLDER.MainFile; ?>" method="get">
			<table class="searchBar">
				<tbody>
					<tr class="search">
						<td class="searchTerm">
							<input type="hidden" class="searchType" name="type" value="note" />
							<input type="hidden" class="searchPart" name="part" value="search" />
							<input type="text" class="searchTerm" name="id" placeholder="search in Notes" >
						</td>
						<td class="searchButton">
							<button class="enter">search</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		</div>
		<div class="panelRight">
		<table class="settingBar">
			<tbody>
				<tr class="setting">
					<td>
						<button value="paper" class="changeView"><img src="<?php echo SITE_URL."/".BASE_FOLDER; ?>common/images/viewPaper.png" alt="paper"></button>
					</td>
					<td>
						<button value="home" class="home" title="Back to the main file (home)"><img src="<?php echo SITE_URL."/".BASE_FOLDER; ?>common/images/home.png" alt="home"></button>
					</td>
					<td>
						<button value="settings" class="menuSet"><img src="<?php echo SITE_URL."/".BASE_FOLDER; ?>common/images/settings.png" alt="settings"></button>
					</td>
				</tr>
			</tbody>
		</table>
		</div>
		</div>
	</header>
<!-- *********************************************************************** -->
	<div class="panel">
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
<!-- *********************************************************************** -->
		<div class="titleIndex">
			<h1 class="left"></h1>
			<p class="right"></p>
		</div>
<!-- *********************************************************************** -->
		<div class="contentIndex">
			<div class="newNote">
				<?php //include "admin/newNote.php"; ?>
				<p class="advice">This is the public access to the notizblogg. So, you are not allowed to create new notes!</p>
			</div>
			<div class="editNote">
				<?php //include "admin/editNote.php"; ?>
				<p class="advice">This is the public access to the notizblogg. So, you are not allowed to edit notes!</p>
			</div>
			<div class="newSource">
				<?php //include "admin/newSource.php"; ?>
				<p class="advice">This is the public access to the notizblogg. So, you are not allowed to create new sources!</p>
			</div>
			<div class="editSource">
				<?php //include "admin/editSource.php"; ?>
				<p class="advice">This is the public access to the notizblogg. So, you are not allowed to edit sources!</p>
			</div>
			<div class="partNote">
				<?php include SITE_PATH."/common/php/partNote.php"; ?>
			</div>
			<div class="partSource">
				<?php include SITE_PATH."/common/php/partSource.php"; ?>
			</div>
			<div class="cloudTags">
				<?php include SITE_PATH."/common/php/cloudTags.php"; ?>
			</div>
			<div class="cloudAuthors">
				<?php include SITE_PATH."/common/php/cloudAuthors.php"; ?>
			</div>
			<div class="settings">

			</div>
		</div>
		<div class="contentIndexPlus">

		</div>
		<div class="contentSettings">
			<form class="login" action="<?php echo SITE_URL."/".BASE_FOLDER."checklogin.php"; ?>" method="post">
				<input type="text" name="name" size="20" placeholder="name" autofocus /><br />
				<input type="password" name="pwd" placeholder='losung' size="20" /><br />
				<input class='pathLogin' type='hidden' name='path' placeholder='path' readonly value='' />
				<button class="button" type="submit" value="LOGIN">LOGIN</button>
			</form>
		</div>
	</div>
	</div>
<!-- *********************************************************************** -->



	<div class="lens"></div>
	<div class="viewer">


<?php

	if($_SERVER["QUERY_STRING"]){
		$type = 'note';
		$part = '';
		$partID = '';
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
			include (SITE_PATH."/common/php/".$type.".php");
		echo "</div>";
	} else {
		// Startseite:
?>
		<script type="text/javascript">
			$(".typeIndex h2").html("NOTES");
			$(".partIndex h2").html("Start");
			$(".titleIndex .left").html("<?php echo $siteTitel; ?>");
			//$(".titleIndex .right").html("#notes: <?php echo $count; ?>");
		</script>
<?php
		echo "<div class='desk'>";
			$sql = mysql_query("SELECT noteID FROM note WHERE notePublic = 1 AND noteID <= 3 ORDER BY noteID ASC");
			while($row = mysql_fetch_object($sql)){
				$typeID = $row->noteID;
				showNote($typeID, $access);
			}
		echo "</div>";
		echo "<div class='desk'>";
			$sql = mysql_query("SELECT noteID FROM note WHERE notePublic = 1 AND noteID > 3 ORDER BY date DESC LIMIT ".$count);
			while($row = mysql_fetch_object($sql)){
				$typeID = $row->noteID;
				showNote($typeID, $access);
			}
		echo "</div>";
	}
disconnect();
?>
	</div>
<script type="text/javascript">

	$('body').css({'cursor':'wait'});
	$(document).ready(function() {

		if(jQuery.jCookie('cookietop')){
			// set cookie
			//jQuery.jCookie('cookietop','0');
			var scrollPosition = jQuery.jCookie('cookietop');
			//scrollTop(scrollPosition);
			$("html, body").animate({scrollTop: scrollPosition}, "fast");
			// delete cookie
			if($(".menuNew").text() != "EDIT"){
				jQuery.jCookie('cookietop',null);
			}
		}
		if(jQuery.jCookie('viewertype')){
			var viewerType = jQuery.jCookie('viewertype');
			if(viewerType != 'desk'){
				$('.desk').toggleClass(viewerType);
				$('.desk').toggleClass('desk');
				$("button.changeView").val("desk");
				$("button.changeView").html("<img src='<?php echo SITE_URL."/".BASE_FOLDER;?>common/images/viewDesk.png'>");
			}
		}

		if($("h1.left").text()!=""){
			var siteTitle = $(".titleIndex .left").text()+" (" + $(".partIndex h2").text()+ " in Notizblogg)";
			document.title = siteTitle;
			var activeCategory = $("h1.left a").text();
			$("h3.part").append("\"" + activeCategory + "\"");
		} else {
			document.title = "<?php echo $siteTitle; ?>";
		}
		

		
		
		// mobile trick
		setTimeout(function () {
			// Hide the address bar!
				window.scrollTo(0, 1);
			}, 0);
			$(".lens").bind("contextmenu",function(e){
				return false;
			});

		});

		$('body').css({'cursor':'auto'});

		var loginLocation = window.location.toString();
		var newLocation = loginLocation.split("index.php")[1];
		//var newLocation = getLocation.substr(0,getLocation.length)
		$(".pathLogin").val(newLocation);


		winWidth = $(window).width() - 100;
		winHeight = $(window).height() - 100;

		$(".contentIndex").css({"height":winHeight+"px"});
		$(".contentIndexPlus").css({"height":winHeight+"px"});
		$(".contentSettings").css({"height":winHeight+"px"});
		$(".viewer").css({"min-height":winHeight+"px"});
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
		

	$(window).resize(function(){
		winWidth = $(window).width() - 100;
		winHeight = $(window).height() - 100;

		$(".contentIndex").css({"height":winHeight+"px"});
		$(".contentIndexPlus").css({"height":winHeight+"px"});
		$(".contentSettings").css({"height":winHeight+"px"});
		$(".viewer").css({"min-height":winHeight+"px"});
	});



		$("button.changeView").click(function(){
			if ($("button.changeView").val()=="paper") {
				$("div.desk").addClass("paper");
				$("div.desk").removeClass("desk");
				$("button.changeView").val("desk");
				$("button.changeView").html("<img src='<?php echo SITE_URL."/".BASE_FOLDER; ?>common/images/viewDesk.png'>");
				jQuery.jCookie('viewertype','paper');
			} else {
				$("div.paper").addClass("desk");
				$("div.paper").removeClass("paper");
				$("button.changeView").val("paper");
				$("button.changeView").html("<img src='<?php echo SITE_URL."/".BASE_FOLDER; ?>common/images/viewPaper.png'>");
				jQuery.jCookie('viewertype','desk');
			}
		});
		$("button.home").click(function(){
			window.location = "<?php echo SITE_URL."/".BASE_FOLDER.MainFile; ?>";
		});

		$(".contentIndexPlus li").click(function(){
			var serverPicture = $(this).attr("name");
			$("input.mediaName").val(serverPicture);
			$(".choosenMedia").html("<img>");
			$(".choosenMedia img").attr("src","<?php echo MEDIA_URL; ?>/pictures/"+serverPicture);
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
/*
$("img.staticMedia").mousedown(function(event) {
	switch (event.which) {
		case 1:
			alert('Left mouse button pressed');
			break;
		case 2:
			alert('Middle mouse button pressed');
			break;
		case 3:
			alert('Right mouse button pressed');
			break;
		default:
			alert('You have a strange mouse');
	}
});
*/
		$(".edit").click(function(){
			var cookietop = $(window).scrollTop();
			jQuery.jCookie('cookietop',cookietop);
		});

		$("img.staticMedia").mousedown(function(event) {

			var img = new Image();
			img.src = $(this).attr("src");
			imgRatio = img.width / img.height;
			switch (event.which) {
				case 1:				//left
				case 2:				//middle
				case 3:				//right
				{
					var zoomMedia = $(this).attr("src");			//
					var zoomNote = $(this).attr("title");			// = noteID
					$("body").css("overflow", "hidden");
					$("header").fadeTo("fast", 0);
					$(".partIndex").fadeTo("fast", 0);
					$(".titleIndex").fadeTo("fast", 0);
					$(".viewer").fadeTo("slow", 0);
					$(".lens").fadeTo("slow", 1);

					$(".lens").css({"width":$(window).width()+"px", "height":$(window).height()+"px", "padding":"22px", "cursor":"move"});
					$(".lens").html("<div class='set'><button value='close' class='close'><img src='<?php SITE_URL."/".BASE_FOLDER; ?>common/images/close.png' alt='close'></button></div>");
					$(".lens").append("<img class='zoomMedia' src="+zoomMedia+">");
					if(img.width >= (winWidth/2) || img.height >= (winHeight/2)){
						$(".lens img.zoomMedia").css({"max-width":winWidth+"px", "max-height":winHeight+"px"});
					} else {
						maxWidth = img.width * 2; maxHeight = img.height * 2;
						$(".lens img.zoomMedia").css({"width":maxWidth+"px", "height":maxHeight+"px"});
					}
					imgWidth = $(".zoomMedia").width();
					$(".lens .set").css({"width":imgWidth+"px"});

					//$(this).css({"max-height":"580px"});
					$(".viewer").bind("contextmenu",function(event){
						return false;
					});
					break;
				}
			default:
			{
				alert('You have a strange mouse');
			}}
		});

		$(".lens").mousedown(function(event) {
			switch (event.which) {
				case 1:				//left
				case 2:				//middle
				case 3:				//right
				{
					$(".lens").fadeTo("slow", 0);
					$(".lens").css({"display":"none"});
					$(".lens").html();
					$("body").css("overflow", "auto");

					$("header").fadeTo("slow", 1);
					$(".partIndex").fadeTo("slow", 1);
					$(".titleIndex").fadeTo("slow", 1);
					$(".viewer").fadeTo("slow", 1);
					break;
				}
			default:
			{
				alert('You have a strange mouse');
			}}
		});

		$(".note").mouseenter(function(){
			activeContent = $(this).children(".content");
			textHeight=activeContent.height();
			activeNote = $(this);
			noteHeight = activeNote.height();
			$(this).children(".set").fadeTo("fast", 1);
			$(".set button.mark").click(function(){
				$(this).text("cancel");
				$(this).toggleClass("mark cancel");
				var editContent = activeContent.text();
				var editArea = $("<textarea class='quickEdit' readonly />");
				activeContent.replaceWith(editArea);
				activeNote.css({'height':noteHeight+'px'});
				editArea.css({'height':textHeight+'px'});
				editArea.text(editContent);
				editArea.select();
				$(".set button.cancel").click(function(){
					$('this').text('mark');
					$('this').toggleClass('cancel mark');
					var editArea = $(this).children("textarea");
					var editContent = editArea.text();
					var editedText = $("<p class='content' />");
					editArea.replaceWith(editedText);
					editedText.text(editContent);
					var cookietop = $(window).scrollTop();
					var viewertype = $('.viewer').children().attr('class');
					jQuery.jCookie('cookietop',cookietop);
					jQuery.jCookie('viewertype',viewertype);
					//alert($('.viewer').next()[0].attr('class'));
					location.reload();
				});
			});
		});



		$(".note").mouseleave(function(){
			$(this).children(".set").fadeTo("fast", 0.1);
			if($(this).children("textarea").length){
				var editArea = $(this).children("textarea");
				var editContent = editArea.text();
				var editedText = $("<p class='content' />");
				editArea.replaceWith(editedText);
				editedText.text(editContent);
				var cookietop = $(window).scrollTop();
				var viewertype = $('.viewer').children().attr('class');
				jQuery.jCookie('cookietop',cookietop);
				jQuery.jCookie('viewertype',viewertype);
				location.reload();
			}
		});





	</script>
		<footer>
			<a href="http://notizblogg.ch/">NOTIZBLOGG</a> (<?php echo $nbVersion; ?>) &copy; Andr&eacute; Kilchenmann | 2006-<?php echo date("Y"); ?>
		</footer>
	</body>
</html>
