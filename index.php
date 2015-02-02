<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Notizblogg</title>
	<link rel="shortcut icon" href="app/style/img/favicon.ico">

	<meta name="author" content="André Kilchenmann" />
	<meta name="description" content="Notizblogg ist der digitale Zettelkasten von André Kilchenmann. Nebst textuellem Inhalt kann der digitale MeMex, auch Bilder, Video- oder Ton-Dokumente aufnehmen." />
	<meta name="keywords" content="literatur,verwaltung,zettelkasten,luhmann,upcycling,redesign,kilchenmann,andré,milchkannen,eiskunstlauf,fotografie,notizblogg" />
	<!-- Google Analytics and Webmaster Tools -->
	<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	ga('create', 'UA-36145850-1', 'auto');
	ga('send', 'pageview');

	</script>

<!--
	<meta name="Resource-type" content="Document" />
-->
	<!-- mobile devices / responsive stuff -->
	<meta name="viewport" content="width=480, user-scalable=yes">
	<meta name="viewport" content="initial-scale=0.6, maximum-scale=0.8">
	<!-- jQUERY LIBrary -->
	<script type="text/javascript" src="app/lib/jquery.min.js"></script>
	<script type="text/javascript" src="app/lib/jquery-ui.min.js"></script>
	<script type="text/javascript" src="app/js/functions.js"></script>

	<!-- some VENDOR stuff -->
	<script type="text/javascript" src="app/js/vendor/md5.js"></script>
	<script type="text/javascript" src="app/js/vendor/jquery.masonry.min.js"></script>
	<script type="text/javascript" src="app/js/vendor/jquery.vague.js"></script>
	<script type="text/javascript" src="app/js/vendor/jquery.knob.js"></script>
	<script type="text/javascript" src="app/js/vendor/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="app/js/vendor/jquery.iframe-transport.js"></script>
	<script type="text/javascript" src="app/js/vendor/jquery.fileupload.js"></script>

	<!-- notizblogg specific tools -->
	<!-- some functional and styling stuff -->
	<script type="text/javascript" src="app/js/jquery.center.js"></script>
	<script type="text/javascript" src="app/js/jquery.warning.js"></script>
	<script type="text/javascript" src="app/js/jquery.upload.js"></script>

	<script type="text/javascript">
	NB = {
		url: window.location.protocol + '//' + window.location.hostname + window.location.pathname,
		uri: window.location.href,
		query: getUrlVars(),
		access: '1'
	};

	var settings = (function () {
		var settings = null;
		$.ajax({
			'async': false,
			'global': false,
			'url': 'config.json',
			'dataType': 'json',
			'success': function (data) {
				settings = data;
				NB.api = data.url.api;
				NB.media = data.url.media;
				var user = (function () {
					var user = null;
					$.ajax({
						'async': false,
						'global': false,
						'url': settings.url.api + '/get.php?user',
						'dataType': 'json',
						'success': function (usr) {
							NB.user = {
								id: usr.id,
								name: usr.name,
								avatar: usr.avatar,
								access: usr.access
							}
						}
					});
				})();
			}
		});
	})();

	if(NB.user.id !== undefined && NB.user.name != 'guest') {
		NB.access = NB.user.access;
	}

	if (NB.uri === NB.url + '?access=denied') {
		$('.wrapper').warning({
			type: 'access',
			lang: 'de'
		});
		$('body').on('click', function() {
			window.location.href = NB.url;
		});
		$(document).keyup(function(event) {
			if (event.keyCode == 27) {
				$('.float_obj.warning').hide();
				$('.viewer').css({
					'opacity': '1'
				});
			}
			window.location.href = NB.url;
		});
		NB.uri = NB.url;
	}
	</script>

	<!-- project, searchbar and login module for the PANEL -->
	<script type="text/javascript" src="app/js/jquery.panel.js"></script>
	<!-- show, add, edit and expand NOTE -->
	<script type="text/javascript" src="app/js/jquery.note.js"></script>
	<script type="text/javascript" src="app/js/jquery.form.js"></script>

	<!-- test test test test test -->
	<!-- add the expand function to the note plugin as a new method!! -->
	<script type="text/javascript" src="app/js/jquery.expand.js"></script>
	<!--
	<script type="text/javascript" src="app/js/jquery.finder.js"></script>
	<script type="text/javascript" src="app/js/jquery.login.js"></script>
-->

	<!-- style / design / responsive specs. -->
	<link rel="stylesheet" type="text/css" href="app/style/css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="app/style/css/nb.css">
	<link rel="stylesheet" type="text/css" href="app/style/css/responsive.css">

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
	<!-- <div class="float_obj large pamphlet"></div> -->
	<!-- main view: wrapper -> viewer -> wall || desk -->
	<div class="wrapper">
		<div class="viewer">

		</div>
	</div>
	<footer>

	</footer>
	<div class="modal">
		<!-- Place at bottom of page -->
	</div>

	<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
	<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->

	<!-- and my SETTINGS and FUNCTIONS library -->
	<script type="text/javascript">

	//
	// when window is LOADing
	//
	$(window).load(function() {
		// initial the panel
		$('header').panel();

		$('header .project').panel('project', 'Notizblogg', 'notizblogg.png');
		$('header .search').panel('search', NB.api + '/controller/search.data.php');
		if (NB.user.id !== '' && NB.access !== '1') {
			$('header .user').panel('log', NB.api + '/controller/check.out.php');
			$('header .add').panel('add');
		} else {
			$('header .user').panel('login', NB.api + '/controller/check.in.php');
		}
		$('footer').panel('foot', 'v15.01');

		/* show some content */
		$('.viewer').note(NB);

		var height = $(window).height() - $('header').height() - $('footer').height();
		$('div.viewer').css({
			'height': height
		});
		$('.float_obj').center();
		// set the numbers of wall columns
	});

	$(window).resize(function() {
		var height = $(window).height() - $('header').height() - $('footer').height();
		$('div.viewer').css({
			'height': height
		});
		$('.float_obj.warning').center();
		// set the numbers of wall columns

		$('.float_obj.form_frame').center('horizontal');
		$('.float_obj.logout_frame').center('horizontal');
		$('.float_obj.logout_frame').center('bound2object', $('button.user'));

		$('.left_side').center('horizontal').css({'position': 'relative'});
		$('.right_side').center('horizontal').css({'position': 'relative'});


		if ($('.wall').length !== 0) {
			var width = $(window).width();
			var note_width = $('.note').width();
			//var note_width = 320;		// normally 320px
			var num_col = Math.floor(width / note_width);
			var ww = num_col * note_width;
/*
			$('.wall').css({
				'width': ww

				'-webkit-column-count': num_col,
				'-webkit-column-fill': num_col,

				'-moz-column-count': num_col,
				'-moz-column-fill': num_col,

				'-o-column-count': num_col,
				'-o-column-fill': num_col,

				'column-count': num_col,
				'column-fill': num_col

			});
*/
		}
	});


	/* remove the floating elements on clicking the esc-key */
	$(document).keyup(function(event) {
		if (event.keyCode == 27) {
			if ($('.float_obj.warning').is(':visible')) {
				$('.float_obj.warning').hide();
				$('.viewer').css({
					'opacity': '1'
				});

				if ($('button.toggle_add').hasClass('toggle_delete')) {
					$(this).toggleClass('toggle_delete');
				}

			}
		}
	});

	$body = $("body");

	$(document).on({
		ajaxStart: function() {
			$body.addClass("loading");
			$(document).keyup(function(event) {
				if (event.keyCode == 27) {
					$body.removeClass("loading");
					/*
					if ($('.float_obj').is(':visible')) {
						window.location.href = NB.uri;
					} else {
						window.location.href = NB.url;
					}
					*/
				}
			});
		},
		ajaxStop: function() {
			if ($('.desk').length > 0) {
				$(function() {
					function Arrow_Points() {
						var s = $('.container').find('.note');
						$.each(s, function(i, obj) {
							var posLeft = $(obj).position().left; //css("left");
							//	$(obj).addClass('borderclass');
							if (posLeft === 0) {
								//							html = "<span class='rightCorner'></span>";
								$(obj).prepend($('<span>').addClass('rightCorner'));
							} else {
								//							html = "<span class='leftCorner'></span>";
								$(obj).css({
									'text-align': 'right',
									'left': posLeft + 10 + 'px'
								}).prepend($('<span>').addClass('leftCorner'));
							}
						});
					}

					// Divs
					setTimeout(function(){
						$('.container')
						.append($('<div>').addClass('timeline_container')
							.append($('<div>').addClass('timeline')
								.append($('<div>').addClass('plus'))
							)
						).masonry({
							itemSelector: '.note'
						});
						Arrow_Points();
					}, 300);


				});
			} else if ($('.wall').length !== 0) {
/*
				var width = $(window).width();
				if (width > screen.availWidth) width = screen.availWidth;
				var note_width = $(this).find('.note').width();
				//			var note_width = 320;		//$(this).find('.note').width() + 40;		// normally 320px
				var num_col = Math.floor(width / note_width);
				var ww = num_col * note_width;
				$('.wall').css({
					'width': ww,
		//			'-webkit-column-count': num_col,
		//			'-webkit-column-fill': num_col,

					'-moz-column-count': num_col,
					'-moz-column-fill': num_col,

					'column-count': num_col,
					'column-fill': num_col

				});
*/
			}

			$body.removeClass("loading");

			//var page = 'ready';

			var active = {};
			$('div.note')
				.mouseenter(function(event) {
					if (!$(this).hasClass('active')) {
						$(this).addClass('active');
					}
				})
				.on('touchstart', function() {
					if (!$(this).hasClass('active')) {
						$(this).addClass('active');
					}
				})

			.mouseleave(function(event) {
				if ($(this).hasClass('active')) {
					$(this).removeClass('active');
				}
			})
				.on('touchend', function() {
					if ($(this).hasClass('active')) {
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

</script>


</body>

</html>
