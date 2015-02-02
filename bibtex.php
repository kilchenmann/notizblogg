<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Export biblatex from Notizblogg</title>
    <link rel="shortcut icon" href="app/style/img/favicon.ico">

    <meta name="author" content="André Kilchenmann" />
    <meta name="description" content="Notizblogg ist der digitale Zettelkasten von André Kilchenmann. Nebst textuellem Inhalt kann der digitale MeMex, auch Bilder, Video- oder Ton-Dokumente aufnehmen." />
    <meta name="keywords" content="literatur,verwaltung,zettelkasten,luhmann,upcycling,redesign,kilchenmann,andré,milchkannen,eiskunstlauf,fotografie,notizblogg" />
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
        <p class='bibtex_header'>
            % <br>
            % This bib-file was created with <a href='index.php'>Notizblogg</a> (notizblogg.ch): <br>
            % the digital Zettelkasten (filing cabinet system) <br>
            % by <a href='http://milchkannen.ch' target='_blank'>André Kilchenmann</a><br>
            % <br>
        </p>
    </header>
    <div class="float_obj medium warning"></div>
    <!-- <div class="float_obj large pamphlet"></div> -->
    <!-- main view: wrapper -> paper -> wall || desk -->
    <div class="wrapper">
        <div class="paper">

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
        /* show some content */
        $('div.paper').note('biblatex', NB).center('horizontal');

        var height = $(window).height() - $('header').height() - $('footer').height();
        $('div.paper').css({
            'height': height
        });
        $('.float_obj').center();
        // set the numbers of wall columns
    });

    $(window).resize(function() {
        var height = $(window).height() - $('header').height() - $('footer').height();
        $('div.paper').css({
            'height': height
        });

    });

    $body = $("body");

    $(document).on({
        ajaxStart: function() {
            $body.addClass("loading");
            $(document).keyup(function(event) {
                if (event.keyCode == 27) {
                    $body.removeClass("loading");
                }
            });
        },
        ajaxStop: function() {
            $body.removeClass("loading");
        }
    });

    $(window).bind("load", function() {

    });

</script>


</body>

</html>
