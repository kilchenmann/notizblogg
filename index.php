<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Notizblogg</title>
	<link rel="shortcut icon" href="core/style/img/favicon.ico">

	<meta name="author" content="André Kilchenmann"/>
	<meta name="description" content="Notizblogg ist der digitale Zettelkasten von André Kilchenmann. Nebst textuellem Inhalt kann der digitale MeMex, auch Bilder, Video- oder Ton-Dokumente aufnehmen."/>
	<meta name="keywords" content="literatur,verwaltung,zettelkasten,luhmann,upcycling,redesign,kilchenmann,andré,milchkannen,eiskunstlauf,fotografie,notizblogg"/>

	<meta name="Resource-type" content="Document"/>

	<script type="text/javascript" src="core/lib/jquery-1.10.2.min.js"></script>

	<!--
	<script src="lib/jqueryui/1.9.1/jquery-ui.min.js"></script>
	-->
	<script type="text/javascript" src="core/bin/js/jquery.slimscroll.min.js"></script>
	<script type="text/javascript" src="core/bin/js/jquery.fullPage.js"></script>
	<script type="text/javascript" src="core/bin/js/jquery.finder.js"></script>
	<script type="text/javascript" src="core/bin/js/jquery.drawer.js"></script>
	<!--
	<script type="text/javascript" src="core/bin/js/examples.js"></script>
	-->
	<link rel="stylesheet/less" type="text/css" href="core/style/less/setting.less">
	<link rel="stylesheet" type="text/css" href="core/style/css/lakto.css"/>
	<link rel="stylesheet" type="text/css" href="core/style/css/fullPage.css"/>

	<script type="text/javascript" src="core/lib/less-1.6.3.min.js"></script>

	<!--[if IE]>
	<script type="text/javascript">
		var console = { log: function () {
		} };
	</script>
	<![endif]-->

</head>
<body>

<header>
	<h1>
		<a href='http://notizblogg.ch'>Notizblogg</a> |
		<a href="https://plus.google.com/u/0/102518416171514295136/posts?rel=author">der digitale Zettelkasten von André Kilchenmann</a>
	</h1>
	<div class="left"><span class="project"><a href='http://notizblogg.ch'><h2 class="logo">Notizblogg</h2></a></span></div>
	<div class="center"><span class="search" id="search"></span></div>
	<div class="right"><span class="login"><button class="btn grp_none toggle_user"></span></div>
</header>
<nav id="drawer">

</nav>
<footer>
	<p>
		<a href="http://notizblogg.ch">Notizblogg</a>
		is a <a href="http://milchkannen.ch">milchkannen</a> project created by
		<a href="https://plus.google.com/u/0/102518416171514295136/posts?rel=author">André Kilchenmann</a> (content &amp; design) &copy;
		<span class='year'></span>
		<a href="http://milchkannen.ch">
		<img src="core/style/img/akM-logo-small.png" alt="milchkannen | kilchenmann" title="milchkannen | andré kilchenmann"/>
		</a>
	</p>
</footer>

<div id="fullpage">
	<div class="section " id="section0">
		<div class="lens"></div>
		<div class="viewer">

			<?php
			require 'core/bin/php/setting.php';
			$access = 'enable';
			$note = NEW note();
			condb('open');

				echo '<div class="desk">';
				$sql = mysql_query('SELECT noteID FROM note WHERE notePublic = 1 AND noteID > 3 ORDER BY date DESC LIMIT 25;');
				while($row = mysql_fetch_object($sql)){
					$nID = $row->noteID;

					$note->getNote($nID, $access);
					//showNote($typeID, $access);
				}
				echo '</div>';

			condb('close');
			?>
		</div>

	</div>
	<div class="section " id="section1">
		<div class="slide" id="slide1">

		</div>

		<div class="slide" id="slide2">

		</div>
	</div>
	<div class="section" id="section2">

	</div>
	<div class="section" id="section3">

	</div>
</div>

<script type="text/javascript">

	$(document).ready(function () {
		$('#fullpage').fullpage({
			anchors: ['start', 'info', 'projects', 'aboutme' ],
			slidesColor: ['#1A1A1A', '#1A1A1A', '#7E8F7C', '#333333'],
			css3: true
		});
		/* integrate the search bar in the header panel */
		$('#search').finder({
			placeholder: 'Suche',
			filter: 'Erweiterte Suche',
			database: ''
		});
		$('#drawer').drawer({
			menu: 'btn-grp'
		});


//		$('.intro').append(
//			$('<button>').html('Inhalt').click(function() {
/*
				var url = "data/example/notes.json";
//				var url = "core/bin/php/get.note.php";

				$.getJSON(url,
					function(data){
						$.each(data.notes, function(i, note){
							$('.intro').append('<div>' + note.title + '<br>' + note.content + '</div><br>')
								.addClass('note');
						});
					});
//			}));
*/
	});

	/* copyright date */
	var curDate = new Date(),
			curYear = curDate.getFullYear();
	$('span.year').text('2006-' + curYear);


</script>
</body>
</html>
