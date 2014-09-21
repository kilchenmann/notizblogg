<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<!--
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	-->
	<?php
	session_start ();
	require 'core/bin/php/settings.php';
	$user = array(
		'access' => 1,
		'name' => 'guest',
		'id' => '',
		'avatar' => ''
	);
	if (isset ($_SESSION["token"])) {
		$user = conuser($_SESSION['token']);
	}

	if (isset ($_GET['access'])){
		echo "KEIN ZUGRIFF!!";
	}


	?>

	<title>Notizblogg</title>
	<link rel="shortcut icon" href="<?php echo __SITE_URL__; ?>/core/style/img/favicon.ico">

	<meta name="author" content="André Kilchenmann"/>
	<meta name="description" content="Notizblogg ist der digitale Zettelkasten von André Kilchenmann. Nebst textuellem Inhalt kann der digitale MeMex, auch Bilder, Video- oder Ton-Dokumente aufnehmen."/>
	<meta name="keywords" content="literatur,verwaltung,zettelkasten,luhmann,upcycling,redesign,kilchenmann,andré,milchkannen,eiskunstlauf,fotografie,notizblogg"/>

	<meta name="Resource-type" content="Document"/>

	<meta name="viewport" content="width=480, user-scalable=yes">
	<meta name="viewport" content="initial-scale=0.6, maximum-scale=0.8">

	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/lib/jquery-1.10.2.min.js"></script>

	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/md5.js"></script>
	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.center.js"></script>
	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.warning.js"></script>

	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.note.js"></script>
	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.expand.js"></script>
	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.masonry.min.js"></script>

	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.finder.js"></script>
	<script type="text/javascript" src="<?php echo __SITE_URL__; ?>/core/bin/js/jquery.login.js"></script>



	<link rel="stylesheet" type="text/css" href="<?php echo __SITE_URL__; ?>/core/style/css/nb.css">
<!--
	<link rel="stylesheet" type="text/css" href="<?php echo __SITE_URL__; ?>/core/style/css/responsive.css">
-->

</head>
<body>

<header>
	<h1>
		<a href='http://notizblogg.ch'>Notizblogg</a> |
		<a href="https://plus.google.com/u/0/102518416171514295136/posts?rel=author">der digitale Zettelkasten von André Kilchenmann</a>
	</h1>
	<div class="left">
		<span class="project"></span>
	</div>
	<div class="center">
		<span class="search"></span>
	</div>
	<div class="right">
		<span class="add"></span>
		<span class="user"></span>
	</div>
	<!-- <span class="drawer"></span> -->
	<!-- <span class="menu"></span> -->
</header>
<div class="float_obj medium warning"></div>
<div class="float_obj large pamphlet"></div>
<footer>
	<p class="small">
		<a href="http://notizblogg.ch">Notizblogg</a> | Idea, Concept and Design &copy;
		<a href="https://plus.google.com/u/0/102518416171514295136/posts?rel=author">André Kilchenmann</a> |
		<span class='year'></span>
		<a href="http://milchkannen.ch">
			<img src="<?php echo __SITE_URL__; ?>/core/style/img/akM-logo-small.png" alt="milchkannen | kilchenmann" title="milchkannen | andré kilchenmann"/>
		</a>
	</p>
</footer>
<div class="modal"><!-- Place at bottom of page --></div>
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
		}
		/*
		else {
			?>
			<script type="text/javascript">
				$('#fullpage').css({'background-image': 'url(core/style/img/bg-notizblogg.jpg)'});
			</script>
		<?php
		}

		show($type, $query, $access, $viewer);
*/
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
	return vars;
}


var NB = {};
NB.access = '<?php echo $user['access']; ?>';
NB.user = {
	id:  '<?php echo  $user['id']; ?>',
	name: '<?php echo  $user['name']; ?>',
	avatar: '<?php echo  $user['avatar']; ?>'
};
NB.url = '<?php echo __SITE_URL__; ?>';
NB.uri = NB.url + '/?' + (location.search).substr(1);

if(NB.uri === NB.url + '/?access=denied') {
	$('#fullpage').warning({
		type: 'access',
		lang: 'de'
	});
	$('body').on('click', function () {
		window.location.href = NB.url;
	});
	$(document).keyup(function(event) {
		if(event.keyCode == 27) {
				$('.float_obj').hide();
				$('.viewer').css({'opacity': '1'});
			}
		window.location.href = NB.url;
	});
	NB.uri = NB.url;
}

NB.api = '<?php echo __SITE_API__; ?>';
NB.media = '<?php echo __MEDIA_URL__; ?>';

NB.query = {
	type: '<?php echo $type; ?>',
	id: '<?php echo $query; ?>'
};

$(window).load(function() {
	/* set the correct window size and the dimension of some elements */
	var height = $(window).height() - $('header').height() - $('footer').height();
	$('div.viewer').css({'height': height});
	$('.float_obj').center();

	/* enable the project logo */
	$('.project').append($('<a>').attr({href: NB.url}).append($('<h2>').text('Notizblogg')).addClass('title project logo'));
	/* integrate the search bar in the header panel */
	$('.search').finder({
		search: 'Suche',
		filter: 'Erweiterte Suche',
		database: ''
	});
	/* add button, user / login tools */
	if(NB.user.name !== 'guest' && NB.access !== '1' && NB.user.id !== '') {
		$('.user').login({
			type: 'logout',
			submit: 'Abmelden',
			action: NB.url + '/core/bin/php/check.out.php'
		});
		/* integrate the add button */
		$('.add')
			.append($('<button>').addClass('btn grp_none toggle_add'))
			.expand({
				type: 'source',			// source || note
				sourceID: 'new',
				noteID: 'new',
				edit: true,				// true || false
				data: undefined,
				show: 'form'			// booklet || form
			});

		$('.drawer').append(
		//		$('<button>').addClass('btn grp_none toggle_drawer')
		);

	} else {
		$('.user').login({
			type: 'login',
			user: 'Benutzername',
			key: 'Passwort',
			submit: 'Anmelden',
			action: NB.url + '/core/bin/php/check.in.php'
		});
	}
	/* show some content */
	$('.viewer').note(NB);

});

/* remove the floating elements on clicking the esc-key */
$(document).keyup(function(event) {
	if(event.keyCode == 27) {
		if($('.float_obj').is(':visible')) {
			$(this).hide();
			$('.viewer').css({'opacity': '1'});

			if($('button.toggle_add').hasClass('toggle_delete')) {
				$(this).toggleClass('toggle_delete');
			}

		}
	}
});

$(window).resize(function() {
	var height = $(window).height() - $('header').height() - $('footer').height();
	$('div.viewer').css({'height': height});
	$('.float_obj').center();
	// set the numbers of wall columns
	if ($('.wall').length !== 0) {
		var width = $(window).width();
		var note_width = 320;		// normally 320px
		var num_col = Math.floor(width / note_width);
		var ww = num_col * note_width;
		$('.wall').css({
			'width': ww,

			'-webkit-column-count': num_col,
			'-webkit-column-fill': num_col,

			'-moz-column-count': num_col,
			'-moz-column-fill': num_col,

			'column-count': num_col,
			'column-fill': num_col

		});
	}
});

var isTouchDevice = function() {

	var el = document.createElement('div');
	el.setAttribute('ongesturestart', 'return;');
	/*
	if(typeof el.ongesturestart == "function"){
		return true;
	}else {
		return false
	}
	*/
	return typeof el.ongesturestart == "function";		// true or false
};

var activator = function (element) {
		element.toggleClass('active');
		element.children('div.media').css({'opacity': '1'});
		element.children('div.label').css({'opacity': '1'});
		element.children('div.tools').css({'opacity': '1'});
		var type = undefined,
			typeID = undefined;
		if (!element.attr('id')) {
			// title element
			type = 'title';
			typeID = 0;
		} else {
			if (element.hasClass('topic')) {
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
		return(activeNote);
	};




$body = $("body");

$(document).on({
	ajaxStart: function() {
		$body.addClass("loading");
		$(document).keyup(function(event) {
			if(event.keyCode == 27) {
				$body.removeClass("loading");

				if($('.float_obj').is(':visible')) {
					window.location.href = NB.uri;
/*
					$(this).hide();
					$('.viewer').css({'opacity': '1'});

					if($('button.toggle_add').hasClass('toggle_delete')) {
						$(this).toggleClass('toggle_delete');
					}
*/
				} else {
					window.location.href = NB.url;
				}
			}
		});

	},
	ajaxStop: function() {
		if ($('.desk').length > 0) {
			$(function () {
				function Arrow_Points() {
					var s = $('.right_side').find('.note');
					$.each(s, function (i, obj) {
						var posLeft = $(obj).position().left;		//css("left");
						//	$(obj).addClass('borderclass');
						if (posLeft === 0) {
//							html = "<span class='rightCorner'></span>";
							$(obj).prepend($('<span>').addClass('rightCorner'));
						}
						else {
//							html = "<span class='leftCorner'></span>";
							$(obj).css({'text-align': 'right'}).prepend($('<span>').addClass('leftCorner'));
						}
					});
				}

				// Divs
				$('.right_side')
					.append($('<div>').addClass('timeline_container')
						.append($('<div>').addClass('timeline')
							.append($('<div>').addClass('plus')
						)
					)
				).masonry({itemSelector: '.note'});
				Arrow_Points();
			});
		} else if ($('.wall').length !== 0) {

			var width = $(window).width();
			if(width > screen.availWidth) width = screen.availWidth;
			var note_width = 320;		//$(this).find('.note').width() + 40;		// normally 320px
			var num_col = Math.floor(width / note_width);
			var ww = num_col * note_width;
			$('.wall').css({
				'width': ww,
				'-webkit-column-count': num_col,
				'-webkit-column-fill': num_col,

				'-moz-column-count': num_col,
				'-moz-column-fill': num_col,

				'column-count': num_col,
				'column-fill': num_col

			});
		}

		$body.removeClass("loading");

		//var page = 'ready';

		var active = {};
		$('div.note')
			.mouseenter(function (event) {
				if(!$(this).hasClass('active')) {
					$(this).addClass('active');
				}
			})
			.on('touchstart', function () {
				if(!$(this).hasClass('active')) {
					$(this).addClass('active');
				}
			})

			.mouseleave(function (event) {
				if($(this).hasClass('active')) {
					$(this).removeClass('active');
				}
			})
			.on('touchend', function () {
				if($(this).hasClass('active')) {
					$(this).removeClass('active');
				}
			});
	}
});


$(window).bind("load", function() {
	// code here
	// alert('website is ready!?');
	// alert($('div.note').length);

});







//NB.query = getUrlVars();


//console.log(NB);
//console.log('uri: ' + NB.uri);
//console.log('user: ' + NB.user);
//console.log('uid: ' + NB.uid);
//console.log('access: ' + NB.access);
//console.log(NB.query);

//console.log((location.search).substr(1));

//	$(document).ready(function () {








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

//	});



//$.getScript(NB.url + '/core/bin/js/jquery.mousewheel.min.js', function() {
/*
 $('div.viewer').on('mousewheel', function (e, d) {
 var viewer = $(this);
 if ((this.scrollTop === (viewer[0].scrollHeight - viewer.height()) && d < 0) || (this.scrollTop === 0 && d > 0)) {
 e.preventDefault();
 }
 })
 */
//});



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








/* copyright date */
var curDate = new Date(),
	curYear = curDate.getFullYear();
$('span.year').text('2006-' + curYear);


</script>



</body>
</html>
