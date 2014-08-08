<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">

	<?php
	session_start ();
	$access = 'public';
	$user = '--';
	$uid = '';

	require 'core/bin/php/setting.php';

	/*
	 * // the access is regulated in the api get and edit
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
	*/

	?>
	<!--
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
-->
	<title>Notizblogg</title>
	<link rel="shortcut icon" href="<?php echo __SITE_URL__; ?>/core/style/img/favicon.ico">

	<meta name="author" content="André Kilchenmann"/>
	<meta name="description" content="Notizblogg ist der digitale Zettelkasten von André Kilchenmann. Nebst textuellem Inhalt kann der digitale MeMex, auch Bilder, Video- oder Ton-Dokumente aufnehmen."/>
	<meta name="keywords" content="literatur,verwaltung,zettelkasten,luhmann,upcycling,redesign,kilchenmann,andré,milchkannen,eiskunstlauf,fotografie,notizblogg"/>

	<meta name="Resource-type" content="Document"/>

	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/lib/jquery-1.10.2.min.js"></script>

	<!--
	<script src="lib/jqueryui/1.9.1/jquery-ui.min.js"></script>
	-->
	<!--
	<script type="text/javascript" src="core/bin/js/jquery.slimscroll.min.js"></script>
	-->
	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/md5.js"></script>
	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.center.js"></script>
	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.warning.js"></script>
	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.shownote.js"></script>

	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.expand.js"></script>
	<!--
	<script type="text/javascript" src="core/bin/js/examples.js"></script>
	-->


	<link rel="stylesheet" type="text/css" href="<?php echo __SITE_URL__; ?>/core/style/css/nb.css">
<!--
	<link rel="stylesheet" type="text/css" href="<?php //echo __SITE_URL__; ?>/core/style/css/timeline.css">
	<link rel="stylesheet" type="text/css" href="<?php //echo __SITE_URL__; ?>/core/style/css/responsive.css">
-->

	<!--
	<link rel="stylesheet/less" type="text/css" href="core/style/less/setting.less">
	<script type="text/javascript" src="core/lib/less-1.6.3.min.js"></script>
	-->


</head>
<body>

<header>
	<h1>
		<a href='http://notizblogg.ch'>Notizblogg</a> |
		<a href="https://plus.google.com/u/0/102518416171514295136/posts?rel=author">der digitale Zettelkasten von André Kilchenmann</a>
	</h1>
	<div class="left"><span class="project"><a href='<?php echo __SITE_URL__; ?>/'><h2 class="logo">Notizblogg</h2></a></span></div>
	<div class="center"><span class="search"></span></div>
	<div class="right">
		<span class="add"></span>
		<span class="user"></span>
		<span class="drawer"></span>
		<!--
		<span class="menu"></span>
		-->
	</div>
</header>
<div class="float_obj medium warning"></div>
<div class="float_obj large pamphlet"></div>
<footer>
	<p class="small">
		<a href="http://notizblogg.ch">Notizblogg</a>
		is a <a href="http://milchkannen.ch">milchkannen</a> project created by
		<a href="https://plus.google.com/u/0/102518416171514295136/posts?rel=author">André Kilchenmann</a> (content &amp; design) &copy;
		<span class='year'></span>
		<a href="http://milchkannen.ch">
		<img src="<?php echo __SITE_URL__; ?>/core/style/img/akM-logo-small.png" alt="milchkannen | kilchenmann" title="milchkannen | andré kilchenmann"/>
		</a>
	</p>
</footer>

<div id="fullpage">
		<div class="viewer">

			<?php
			// default parameters
			$type = '';
			$query = 'all';
			$viewer = 'wall';
			if($_SERVER['QUERY_STRING']){
				// default values; in case of wrong queries; these variables would be overwritten in the right case
				if(isset($_GET['source'])){
					$type = 'source';
					$query = $_GET['source'];
					$viewer = 'desk';
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
				if(isset($_GET['collection'])){
					$type = 'collection';
					$query = $_GET['collection'];
					$viewer = 'desk';
				}
				if(isset($_GET['q'])){
					$type = 'search';
					$query = $_GET['q'];
				}
			} else {
				?>
				<script type="text/javascript">
					$('#fullpage').css({'background-image': 'url(core/style/img/bg-notizblogg.jpg)'});
				</script>
			<?php
			}

			show($type, $query, $access, $viewer);

			?>

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
		console.log(vars);
		return vars;

	}
	var NB = {};
	NB.url = '<?php echo __SITE_URL__; ?>';
	NB.user = '<?php echo $user; ?>';
	NB.access = '<?php echo $access; ?>';
	NB.uid = '<?php echo $uid; ?>';


//	$(document).ready(function () {


		$.getScript(NB.url + '/core/bin/js/jquery.login.js', function() {
			if(NB.user !== '--' && NB.access !== 'public' && NB.uid !== '') {
				$('.user').login({
					type: 'logout',
					user: NB.uid,
					submit: 'Abmelden',
					action: NB.url + '/core/bin/php/check.out.php'
				});
				$.getScript(NB.url + '/core/bin/js/jquery.finder.js', function() {
					/* integrate the search bar in the header panel */
					$('.search').finder({
						search: 'Suche',
						filter: 'Erweiterte Suche',
						database: ''
					});
				});
					/* integrate the add button */
					$('.add')
						.append($('<button>').addClass('btn grp_none toggle_add'))
						.expand({
							type: 'source',		// source || note
							sourceID: 'new',
							noteID: 'new',
							edit: true,		// true || false
							data: undefined,
							show: 'form'		// booklet || form
						});
				$.getScript(NB.url + '/core/bin/js/jquery.drawer.js', function() {

					$('.drawer').append(
						//		$('<button>').addClass('btn grp_none toggle_drawer')
					);
				});

			} else {
				$('.user').login({
					type: 'login',
					user: 'Benutzername',
					key: 'Passwort',
					submit: 'Anmelden',
					action: NB.url + '/core/bin/php/check.in.php'
				});
			}
		});

		$('.note .tools').each(function() {
			var $tools = $(this),
				$note = $tools.parent($('.note')),
				nID = $note.attr('id'),
				sID = $tools.attr('id'),
				edit_ele,
				tex_ele,
				exp_ele,
				type,
				divs = $note.contents();
				NB.access = '<?php echo $access; ?>';


			var note_obj = {};
			for (var i = 0; i < divs.filter("div").length; i++) {
				var ele;
				switch(i) {
					case 0:
						ele = 'media';
						break;
					case 1:
						ele = 'text';
						break;
					case 2:
						ele = 'latex';
						break;
					case 3:
						ele = 'label';
						break;
					case 4:
						ele = 'tools';
						break;
					default:
						ele = 'empty';
				}
				note_obj[ele] = divs[i].innerHTML;
			}





			if($note.hasClass('topic') && nID === sID) {
				type = 'source';
			} else {
				type = 'note';
			}

			if(NB.access === 'public') {
				edit = false;
				edit_ele = $('<button>').addClass('btn grp_none fake_btn');
			} else {
				edit = true;
				edit_ele = $('<button>').addClass('btn grp_none toggle_edit').expand({
					type: type,
					noteID: nID,
					sourceID: sID,
					edit: edit,
					data: note_obj,
					show: 'form'
				});

			}

			if($note.children('.latex').length > 0) {
				tex_ele = $('<button>').addClass('btn grp_none toggle_cite').click(function() {
					$(this).toggleClass('toggle_comment');
					$note.children('.text').toggle();
					$note.children('.latex').toggle();
				});
				exp_ele = $('<button>').addClass('btn grp_none toggle_expand').expand({
					type: type,
					noteID: nID,
					sourceID: sID,
					edit: edit,
					data: note_obj,
					show: 'booklet'
				});
			} else {
				tex_ele = $('<button>').addClass('btn grp_none fake_btn');
				exp_ele = $('<button>').addClass('btn grp_none fake_btn');
			}

			$tools
				.append(
				$('<div>').addClass('left').append(edit_ele).click(function() {
					if(jQuery.inArray('text', divs)) {
						//console.log(note_obj);

					}
				})
			)
				.append(
				$('<div>').addClass('center').append(tex_ele)
			)
				.append(
				$('<div>').addClass('right').append(exp_ele)
			);

	//		console.log(note_obj);

		});



		var height = $(window).height() - $('header').height() - $('footer').height();
		$('div.viewer').css({'height': height});
		$('.float_obj').center();


		//$.getScript('core/bin/js/jquery.fullpage.min.js', function() {
			/*
			$('#fullpage').fullpage({
				//	anchors: ['info', 'demo', 'tools', 'about', 'login' ],
				anchors: ['start'],
				//	slidesColor: ['#1A1A1A', '#1A1A1A', '#7E8F7C', '#333333'],
				slidesColor: ['#1A1A1A'],
				css3: true
			});
			*/
		//});


/*
		if(getUrlVars()["access"] !== undefined) {
			$('#fullpage').warning({
				type: 'access'
			});
			$('body').on('click', function(){
				window.location.href = window.location.href.split('?')[0];
			})
		}
		*/
		if ($('.note').length === 0) {
			$('#fullpage').warning({
				type: 'noresults',
				lang: 'de'
			});
			$('body').on('click', function(){
				window.location.href = NB.url;
			})
		}
//	});

	$(document).keyup(function(e) {
		if(e.keyCode == 27) {
				if($('.float_obj').is(':visible')) {
					$('.float_obj').hide();
					$('.viewer').css({'opacity': '1'});

					if($('button.toggle_add').hasClass('toggle_delete')) {
						$('button.toggle_add').toggleClass('toggle_delete');
					}

				}
		}
	});

	$(window).load(function() {
		var win_width = $(window).width();
		if($('.wall').length !== 0) {
			var wall = $('.wall');

			var note_width = wall.find('.note').width() + 60;
	//		console.log('note: ' + note_width + ' window: ' + win_width)
			var num_col = Math.floor(win_width / note_width);
			wall.css({
				'-webkit-column-count': num_col,
				'-moz-column-count': num_col,
				'column-count': num_col,
				'width': num_col * note_width
			});
	//		console.log(num_col);
		}
		/*
		if($('.desk').length !== 0) {
			var panel_left = $('.left_side');
			var panel_right = $('.right_side');
			var margin_left = panel_left.position().left + panel_left.width();
			if((margin_left + panel_right.width()) >= win_width ) {
				panel_right.css({left: '44px'});
			} else {
				panel_right.css({left: margin_left});
			}
		}
		*/

	});
	$(window).resize(function() {
		var height = $(window).height() - $('header').height() - $('footer').height();
		$('div.viewer').css({'height': height});
		$('.float_obj').center();
		// set the numbers of wall columns
		if($('.wall').length !== 0) {
			var width = $(window).width();
			var note_width = $('.wall').find('.note').width() + 60;
			var num_col = Math.floor(width / note_width);
			$('.wall').css({
				'-webkit-column-count': num_col,
				'-moz-column-count': num_col,
				'column-count': num_col,
				'width': num_col * note_width
			});
		}

	});

	$.getScript(NB.url + '/core/bin/js/jquery.mousewheel.min.js', function() {
		/*
		$('div.viewer').on('mousewheel', function (e, d) {
			var viewer = $(this);
			if ((this.scrollTop === (viewer[0].scrollHeight - viewer.height()) && d < 0) || (this.scrollTop === 0 && d > 0)) {
				e.preventDefault();
			}
		})
		*/
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


	$('div.desk')
		.mouseenter(function() {
//			$('.note').toggleClass('active');
//			$('.note').children('div').css({'background-color': 'rgba(251, 251, 251, 0.3)'});
		})
		.hover(function() {
//			$('.note').children('div').css({'background-color': 'rgba(251, 251, 251, 0.3)'});
		})
		.mouseleave(function() {
//			$('.note').toggleClass('active');
//			$('.note').children('div').css({'background-color': ''});
	});
	var active = {};
	$('div.note')
		.mouseenter(function () {
			active = activator($(this));
		})
		.on('touchstart', function(){
			active = activator($(this));
		})

		.hover(function() {
/*
			if($(this).hasClass('active')) {

			}
*/
		})

		.mouseleave(function() {
			$(this).toggleClass('active');
			$(this).children('div.media').css({'opacity': '0.8'});
			$(this).children('div.label').css({'opacity': '0.8'});
			$(this).children('div.tools').css({'opacity': '0.1'});
		})
		.on('touchend', function(){

		});
/*
	$('div.tools button').hover(function(){
			// first function is for the mouseover/mouseenter events
			console.log($(this).attr('id'));
		},
		function(){
			// second function is for mouseleave/mouseout events
			$(this).find('button').show();
		});
*/


	var activator = function(element){
		$('div.media').css({'opacity': '0.8'});
		element.toggleClass('active');
		element.children('div.media').css({'opacity': '1'});
		element.children('div.label').css({'opacity': '1'});
		element.children('div.tools').css({'opacity': '1'});
		var type = undefined,
			typeID = undefined;
		if(!element.attr('id')) {
			// title element
			type = 'title';
			typeID = 0;
		} else {
			if(element.hasClass('topic')) {
				type = 'source';
			} else {
				type = 'note';
			}
			typeID = element.attr('id');
		}
		//var activeNote = $('.active .tools button').attr('id');
		var activeNote = {
			type: type,
			id: typeID
		};

		var edit_btn;
/*
		if ($('div.tools').find('button.toggle_edit').length) {
			$('button.toggle_edit').click( function() {
				$('header').expand('form', activeNote.type, activeNote.id, $('#fullpage'));
			});
		} else {
			edit_btn = false;
		}
*/

/*

		$('button.toggle_expand').click( function() {
			var source = $(this).attr('id');
			$('header').expand('booklet', activeNote.type, activeNote.id, source, $('#fullpage'), edit_btn);
		});
*/
		return(activeNote);

	};


	if($('.desk').length !== 0) {
		$.getScript(NB.url + '/core/bin/js/jquery.masonry.min.js', function() {
			$(function(){
				function Arrow_Points()
				{
					var s = $('.right_side').find('.note');
					$.each(s, function(i, obj) {
						var posLeft = $(obj).position().left;		//css("left");
					//	$(obj).addClass('borderclass');
						if(posLeft === 0)
						{
//							html = "<span class='rightCorner'></span>";
							$(obj).prepend($('<span>').addClass('rightCorner'));
						}
						else
						{
//							html = "<span class='leftCorner'></span>";
							$(obj).css({'text-align': 'right'}).prepend($('<span>').addClass('leftCorner'));
						}
					});
				}


	// Divs
				$('.right_side').masonry({itemSelector : '.note'});
				Arrow_Points();




			});


	});
	}


	/* copyright date */
	var curDate = new Date(),
		curYear = curDate.getFullYear();
	$('span.year').text('2006-' + curYear);


</script>



</body>
</html>
