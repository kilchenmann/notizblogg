<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <?php
		session_start ();
		require 'core/bin/php/settings.php';
		$user = array (
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
    <link rel="shortcut icon" href="core/style/img/favicon.ico">

    <meta name="author" content="André Kilchenmann" />
    <meta name="description" content="Notizblogg ist der digitale Zettelkasten von André Kilchenmann. Nebst textuellem Inhalt kann der digitale MeMex, auch Bilder, Video- oder Ton-Dokumente aufnehmen." />
    <meta name="keywords" content="literatur,verwaltung,zettelkasten,luhmann,upcycling,redesign,kilchenmann,andré,milchkannen,eiskunstlauf,fotografie,notizblogg" />

    <meta name="Resource-type" content="Document" />

    <meta name="viewport" content="width=480, user-scalable=yes">
    <meta name="viewport" content="initial-scale=0.6, maximum-scale=0.8">

    <!-- jQUERY LIBrary -->
    <script type="text/javascript" src="core/lib/jquery.min.js"></script>
    <script type="text/javascript" src="core/lib/jquery-ui.min.js"></script>
    <!-- and my SETTINGS and FUNCTIONS library -->
    <script type="text/javascript" src="settings.js"></script>
    <script type="text/javascript" src="core/bin/js/functions.js"></script>


    <!-- some VENDOR stuff -->
    <script type="text/javascript" src="core/bin/js/vendor/md5.js"></script>
    <script type="text/javascript" src="core/bin/js/vendor/jquery.masonry.min.js"></script>
    <script type="text/javascript" src="core/bin/js/vendor/jquery.vague.js"></script>
    <script type="text/javascript" src="core/bin/js/vendor/jquery.knob.js"></script>
    <script type="text/javascript" src="core/bin/js/vendor/jquery.ui.widget.js"></script>
    <script type="text/javascript" src="core/bin/js/vendor/jquery.iframe-transport.js"></script>
    <script type="text/javascript" src="core/bin/js/vendor/jquery.fileupload.js"></script>

    <!-- notizblogg specific tools -->
    <!-- some functional and styling stuff -->
    <script type="text/javascript" src="core/bin/js/jquery.center.js"></script>
    <script type="text/javascript" src="core/bin/js/jquery.warning.js"></script>
    <script type="text/javascript" src="core/bin/js/jquery.upload.js"></script>
    <!-- project, searchbar and login module for the PANEL -->
    <script type="text/javascript" src="core/bin/js/jquery.panel.js"></script>
    <!-- show, add, edit and expand NOTE -->
    <script type="text/javascript" src="core/bin/js/jquery.note.js"></script>
    <script type="text/javascript" src="core/bin/js/jquery.form.js"></script>

    <!-- test test test test test -->
    <!-- add the expand function to the note plugin as a new method!! -->
    <script type="text/javascript" src="core/bin/js/test/jquery.expand.js"></script>
    <!--
	<script type="text/javascript" src="core/bin/js/jquery.finder.js"></script>
	<script type="text/javascript" src="core/bin/js/jquery.login.js"></script>
-->

    <!-- style / design / responsive specs. -->

    <link rel="stylesheet" type="text/css" href="core/style/css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="core/style/css/nb.css">
    <link rel="stylesheet" type="text/css" href="core/style/css/responsive.css">

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






<script type="text/javascript">
    NB.access = '<?php echo $user['access']; ?>';
    NB.user = {
        id: '<?php echo  $user['id']; ?>',
        name: '<?php echo  $user['name']; ?>',
        avatar: '<?php echo  $user['avatar']; ?>'
    };

    //NB.url = '<?php echo __SITE_URL__; ?>';
    NB.query = getUrlVars();
    NB.uri = NB.url + (location.search);

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

/*

    NB.api = '<?php echo __SITE_API__; ?>';
    NB.media = '<?php echo __MEDIA_URL__; ?>';

*/

//console.log(NB);
    //
    // when window is LOADing
    //
    $(window).load(function() {
        // initial the panel
        $('header').panel();

        $('header .project').panel('project', 'Notizblogg', 'notizblogg.png');
        $('header .search').panel('search', NB.url + '/core/bin/php/search.data.php');
        if (NB.user.id !== '' && NB.access !== '1') {
            $('header .user').panel('log', NB.url + '/core/bin/php/check.out.php');
            $('header .add').panel('add');
        } else {
            $('header .user').panel('login', NB.url + '/core/bin/php/check.in.php');
        }
        $('footer').panel('foot');

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
                        var s = $('.right_side').find('.note');
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
                    $('.right_side')
                        .append($('<div>').addClass('timeline_container')
                            .append($('<div>').addClass('timeline')
                                .append($('<div>').addClass('plus'))
                            )
                    ).masonry({
                        itemSelector: '.note'
                    });
                    Arrow_Points();
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
        //            '-webkit-column-count': num_col,
        //            '-webkit-column-fill': num_col,

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
