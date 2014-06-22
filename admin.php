<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
<?php
$username = 'gast';
$userid = '';
session_start ();
if (!isset ($_SESSION["user_id"]))
{
	header ("Location: index.php");
} else {
	require 'core/bin/php/setting.php';
	condb('open');
	$sql = mysql_query("SELECT username FROM user WHERE uid = " . $_SESSION["user_id"] . ";");
	while($row = mysql_fetch_object($sql)){
		$username = $row->username;
	}
	condb('close');
	$userid = $_SESSION["user_id"];
}
?>


	<!--
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	-->
	<title>Notizblogg &ndash; <?php echo $username; ?></title>
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
	<script type="text/javascript" src="core/bin/js/jquery.center.js"></script>
	<script type="text/javascript" src="core/bin/js/jquery.warning.js"></script>
	<!--
	<script type="text/javascript" src="core/bin/js/examples.js"></script>
	-->
	<link rel="stylesheet/less" type="text/css" href="core/style/less/setting.less">

	<link rel="stylesheet" type="text/css" href="core/style/css/fullPage.css">

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
	<div class="center"><span class="search"></span></div>
	<div class="right">
		<span class="user"></span>
		<span class="drawer"></span>
		<!--
		<span class="menu"></span>
		-->

	</div>
</header>
<div class="float_obj medium warning"></div>
<footer>
	<p class="small">
		<a href="http://notizblogg.ch">Notizblogg</a>
		is a <a href="http://milchkannen.ch">milchkannen</a> project created by
		<a href="https://plus.google.com/u/0/102518416171514295136/posts?rel=author">André Kilchenmann</a> (content &amp; design) &copy;
		<span class='year'></span>
		<a href="http://milchkannen.ch">
			<img src="core/style/img/akM-logo-small.png" alt="milchkannen | kilchenmann" title="milchkannen | andré kilchenmann"/>
		</a>
	</p>
</footer>


<div id="main">
	<div class="section0">
		<div class="note"></div>
		<?php
/*
		$access = '';
		$info = NEW note();
		echo $info->getNote(1, $access);
		echo '<br>';
		echo $info->getNote(2, $access);
		echo '<br>';
		echo $info->getNote(3, $access);
*/
		?>
	</div>

			<?php
			/*
			$note = NEW note();
			condb('open');

			$sql = mysql_query('SELECT noteID FROM note WHERE notePublic = 1 AND noteID > 3 AND noteID < 150 ORDER BY date DESC LIMIT 4;');
			while($row = mysql_fetch_object($sql)){
				$nID = $row->noteID;
				$note->getNote($nID, $access);
				//showNote($typeID, $access);
			}
			condb('close');
			*/
			?>

			<?php
			/*
			$note2 = NEW note();
			condb('open');
			$sql = mysql_query('SELECT noteID FROM note WHERE notePublic = 1 AND noteID > 130 ORDER BY date DESC LIMIT 4;');
			while($row = mysql_fetch_object($sql)){
				$nID = $row->noteID;
				$note2->getNote($nID, $access);
				//showNote($typeID, $access);
			}
			condb('close');
			*/
			?>
		</div>


<script type="text/javascript">
	// Read a page's GET URL variables and return them as an associative array.
	function getUrlVars()
	{
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}



	$(document).ready(function () {
/*
		$.getScript('core/bin/js/jquery.loader.js', function(){
			$('body').loader({
				plugins: ['login', 'finder', 'drawer']
			})
		});
*/
/*
		$.getScript('core/bin/js/jquery.fullpage.min.js', function() {
			$('#fullpage').fullpage({
				//	anchors: ['info', 'demo', 'tools', 'about', 'login' ],
				anchors: ['info'],
				//	slidesColor: ['#1A1A1A', '#1A1A1A', '#7E8F7C', '#333333'],
				slidesColor: ['#1A1A1A'],
				css3: true
			});
		});
		*/
		$.getScript('core/bin/js/jquery.login.js', function() {
			$('.user').login({
				type: 'logout',
				user: <?php echo $userid; ?>,
				submit: 'Abmelden',
				action: 'core/bin/php/check.out.php'
			});
		});
		$.getScript('core/bin/js/jquery.finder.js', function() {
		/* integrate the search bar in the header panel */
			$('.search').finder({
				search: 'Suche',
				filter: 'Erweiterte Suche',
				database: ''
			});
		});
		$.getScript('core/bin/js/jquery.drawer.js', function() {
			$('.drawer').append(
				$('<button>').addClass('btn grp_none toggle_drawer')
			);
		});


		/*
		$.getJSON('core/bin/php/get.note.php', function(data) {
			var items = [];
			$.each( data, function( key, val ) {
				items.push( "<li id='" + key + "'>" + val + "</li>" );
			});
			$( "<ul/>", {
				"class": "my-new-list",
				html: items.join( "" )
			}).appendTo( "body" );
		});
		*/

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

		if(getUrlVars()["access"] !== undefined) {
			$('body').warning({
				type: 'access'
			});
			$(this).on('click', function(){
				window.location.href = window.location.href.split('?')[0];
			})
		}
	});

	var request = getUrlVars(),
		type = 'note',
		part = '',
		qid = '';
	if(request.type !== undefined){
		type = request.type;
		if(request.part !== undefined){
			part = '?part='+request.part;
			if(request.id !== undefined){
				qid = '&id='+request.id;
			}
		}
	}

//	for( var i = 1; i < 4; i++ ){
		var url='http://localhost/nb/core/bin/php/get.' + type + '.php' + part + qid; // + type + part + qid;
		$.getJSON(url,function(json){
// loop through the members here
			var note = {};
			$.each(json.notes,function(i,note){
				$(".section0").append($('<div>').addClass('note')
					.append($('<h3>').html(note.title))
					.append($('<p>').html(note.content))
					.append($('<p>')
						.append($('<a>').attr({href: '?type=note&part=category&id=' + note.category.id }).html(note.category.name))
						.append($('<span>').html(' | '))
						.append($('<a>').attr({href: '?type=note&part=project&id=' + note.project.id }).html(note.project.name))
					)
				);
			});
		});
//	}
	/* copyright date */
	var curDate = new Date(),
		curYear = curDate.getFullYear();
	$('span.year').text('2006-' + curYear);

</script>


</body>
