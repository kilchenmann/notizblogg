<?php
session_start ();
$access = 'public';
$user = '--';
$uid = '';

require 'core/bin/php/setting.php';

if (!isset ($_SESSION["token"])) {
	$access = 'public';
	$user = '--';
	$uid = '';
} else {
	condb('open');
	$token = (explode("-",$_SESSION["token"]));
	$sql = mysql_query("SELECT username FROM user WHERE uid = " . $token[1] . " AND token = '" . $token[0] . "';");

	while($row = mysql_fetch_object($sql)){
		$user = $row->username;
	}
	condb('close');

	if($user != '') {
		$access = 'private';
		$uid = $token[1];
	} else {
		$user = '--';
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
<!--
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
-->
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
	<!--
	<script type="text/javascript" src="core/bin/js/jquery.slimscroll.min.js"></script>
	-->
	<script type="text/javascript" src="core/bin/js/jquery.center.js"></script>
	<script type="text/javascript" src="core/bin/js/jquery.warning.js"></script>
	<!--
	<script type="text/javascript" src="core/bin/js/examples.js"></script>
	-->

	<link rel="stylesheet" type="text/css" href="core/style/css/fullPage.css">

	<link rel="stylesheet" type="text/css" href="core/style/css/nb.css">

	<link rel="stylesheet" type="text/css" href="core/style/css/responsive.css">

	<!--
	<link rel="stylesheet/less" type="text/css" href="core/style/less/setting.less">
	<script type="text/javascript" src="core/lib/less-1.6.3.min.js"></script>
	-->

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

<div id="fullpage">
	<div id="section0" class="section">
		<div class="viewer">
			<div class="desk">

			<?php
			if($_SERVER['QUERY_STRING']){
				// default values; in case of wrong queries; these variables would be overwritten in the right case
				$type = '';
				$query = 'all';
				if(isset($_GET['source'])){
					$type = 'source';
					$query = $_GET['source'];
				}
				if(isset($_GET['note'])){
					$type = 'note';
					$query = $_GET['note'];
				}
				if(isset($_GET['label'])){
					$type = 'label';
					$query = $_GET['label'];
				}
				if(isset($_GET['author'])){
					$type = 'author';
					$query = $_GET['author'];
				}
				if(isset($_GET['search'])){
					$type = 'search';
					$query = $_GET['search'];
				}
				?>
				<script type="text/javascript">
					$('#section0').css({'background-image': 'url(core/style/img/bg-empty.jpg)'})
				</script>
				<?php
			} else {
				// Startseite:
				$type = '';
				$query = 'all';
			}

			show($type, $query, $access);

			?>


			</div>
		</div>

	</div>
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

		$.getScript('core/bin/js/jquery.fullpage.min.js', function() {
			$('#fullpage').fullpage({
				//	anchors: ['info', 'demo', 'tools', 'about', 'login' ],
				anchors: ['start'],
				//	slidesColor: ['#1A1A1A', '#1A1A1A', '#7E8F7C', '#333333'],
				slidesColor: ['#1A1A1A'],
				css3: true
			});
			var height = $(window).height() - $('header').height() - $('footer').height();
			$('div.viewer').css({'max-height': height});
		});


		/*
		$.getScript('core/bin/js/jquery.lookFor.js', function() {
			$('.note').lookFor(
				//		$('<button>').addClass('btn grp_none toggle_drawer')
			);
		});
*/

		var user = '<?php echo $user; ?>',
			access = '<?php echo $access; ?>',
			uid = '<?php echo $uid; ?>';



		$.getScript('core/bin/js/jquery.login.js', function() {
			if(user !== '--' && access !== 'public' && uid !== '') {
				$('.user').login({
					type: 'logout',
					user: uid,
					submit: 'Abmelden',
					action: 'core/bin/php/check.out.php'
				});
				$.getScript('core/bin/js/jquery.finder.js', function() {
					/* integrate the search bar in the header panel */
					$('.search').finder({
						search: 'Suche',
						filter: 'Erweiterte Suche',
						database: ''
					});
				});

			} else {
				$('.user').login({
					type: 'login',
					user: 'Benutzername',
					key: 'Passwort',
					submit: 'Anmelden',
					action: 'core/bin/php/check.in.php'
				});
			}
		});


		$.getScript('core/bin/js/jquery.drawer.js', function() {
			$('.drawer').append(
		//		$('<button>').addClass('btn grp_none toggle_drawer')
			);
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

		if(getUrlVars()["access"] !== undefined) {
			$('body').warning({
				type: 'access'
			});
			$(this).on('click', function(){
				window.location.href = window.location.href.split('?')[0];
			})
		}
		if ($('.note').length === 0) {
			$('body').warning({
				type: 'noresults',
				lang: 'de'
			});
			$(this).on('click', function(){
				window.location.href = window.location.href.split('?')[0];
			})
		}

	});

	$(window).resize(function() {
		var height = $(window).height() - $('header').height() - $('footer').height();
		$('div.viewer').css({'max-height': height, overflow: 'scroll'});
	});

	/*
	var url="http://localhost/nb/core/bin/php/get.note.php?id=3";
	$.getJSON(url,function(json){
// loop through the members here
		$.each(json.notes,function(i,note){
			$(".note")//.html($('<div>').addClass('note')
				.append($('<h3>').html(note.title))
				.append($('<p>').html(note.content))
				.append($('<p>')
					.append($('<a>').attr({href: '?type=note&part=category&id=' + note.category.id }).html(note.category.name))
					.append($('<span>').html(' | '))
					.append($('<a>').attr({href: '?type=note&part=project&id=' + note.project.id }).html(note.project.name))
				);
			//);
		});
	});
	*/

	/* copyright date */
	var curDate = new Date(),
		curYear = curDate.getFullYear();
	$('span.year').text('2006-' + curYear);


</script>



</body>
</html>
