/* ===========================================================================
 *
 * @frame: jQuery plugin template
 *
 * @author André Kilchenmann code@milchkannen.ch
 *
 * @copyright 2014 by André Kilchenmann (milchkannen.ch)
 *
 * @requires
 *  jQuery - min-version 1.10.2
 *
 * ===========================================================================
 * ======================================================================== */

(function( $ ){
    // -----------------------------------------------------------------------
    // define some functions first
    // -----------------------------------------------------------------------
    var deleteUpload = function(ele, settings) {
        var del = {};
        var media_ele = ele.find('span.place4media');
            del.media = del.backup = media_ele.html();       // eg. <img src="..." etc.>
//            media_ele.empty();
        var btn_ele = ele.find('span.button4media');
//            btn_ele.emtpy();
        // set the delete-button
        btn_ele.append(del.btn =
            $('<input>')
            .attr({
                'type': 'button',
                'title': 'Delete',
                'value': 'Delete'
            }).text('Delete')
            .addClass('button small delete')
            .css({'text-align': 'center'})
        );

        del.action = false;

        del.btn.on('click', function() {
            settings.file.val('');
            destroyUpload(ele);
            createUpload(ele, settings);
        });
    },

    createUpload = function(ele, settings) {
        var media_ele = ele.find('span.place4media');
        var btn_ele = ele.find('span.button4media');

        var upl = {};
        // set the browse-button
        btn_ele.append(upl.btn =
            $('<input>')
            .attr({
                'type': 'button',
                'title': 'Browse',
                'value': 'Browse'
            }).text('Browse')
            .addClass('button small browse')
            .css({'text-align': 'center'})
        );
        // create a form around the two span elements
        upl.form = ele.children('span')
            .wrapAll('<form class=\'upload\' method=\'post\' action=\'' + NB.api + '/post.php?media\' enctype=\'multipart/form-data\'>');
        // set the drag'n'drop zone
        media_ele.addClass('drop').empty();
        media_ele.append(upl.inp = $('<input>')
            .attr({
                'type': 'file',
                'name': 'upl'
            })
            .addClass('file_upload')
        );
        // set the action for the browse-button
        upl.btn.on('click', function(){
            upl.inp.click();        // simulate a click on the file_upload-ele.
        });
        // create the upload process with another plugin: fileupload
        upl.form.fileupload({
            // This element will accept file drag/drop uploading
            dropZone: $('.drop'),
            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {
                var ext = data.files[0].name.split('.').pop();
                switch(ext) {
                    case 'jpg':
                    case 'JPG':
                    case 'jpeg':
                    case 'png':
                    case 'PNG':
                    case 'gif':
                    case 'tif':
                    case 'tiff':
                        data.dir = 'picture/';
                        break;
                    case 'pdf':
                        data.dir = 'document/';
                        break;
                    case 'mp4':
                    case 'webm':
                        data.dir = 'movie/';
                        break;
                    case 'mp3':
                    case 'wav':
                        data.dir = 'sound/';
                        break;
                    default:
                        //not supported
                }

                // Append the file name and file size
                settings.file.val(data.dir + data.files[0].name);
                var tpl = {};
                data.context = media_ele.empty()
                        .append(tpl.inp = $('<input>')
                            .attr({
                                'type': 'text',
                                'value': '0',
                                'data-width': '48',
                                'data-height': '48',
                                'data-fgColor': '#0788a5',
                                'data-readOnly': '1',
                                'data-bgColor': '#3e4043'
                            })
                            .addClass('progress')
                        );
                // Initialize the knob plugin
                tpl.inp.knob();
                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },

            progress: function(e, data){
                // Calculate the completion percentage of the upload
                var progress = parseInt(data.loaded / data.total * 100, 10);
                // Update the hidden input field and trigger a change
                // so that the jQuery knob plugin knows to update the dial
                data.context.find('input.progress').val(progress).change();
                if(progress === 100){
                    destroyUpload(ele);
                    deleteUpload(ele, settings);

                    //media_ele.empty();
                    media_ele.html(upl.media =
                        $('<img>')
                            .attr({'src': NB.media + '/' + data.dir + data.files[0].name})
                            .css({'max-width': '120px', 'max-height': '120px'})
                    );
                }
            },

            fail:function(e, data){
                // Something has gone wrong!
                media_ele.toggleClass('error drop').text('There was a probelm with the file upload');
                upl.media = '';
                settings.file.val('');
            }


//			del.file = file.val();


/*
        ele.html(
            upl.form = $('<form>').addClass('upload')
                .attr({
                    'method': 'post',
                    'action': NB.api + '/post.php?media',
                    'enctype': 'multipart/form-data'
                })
                .append(upl.drop = $('<div>')
                    .addClass('field_obj small drop')
                    .append(upl.btn = $('<input>')
                        .attr({
                            'type': 'button',
                            'title': 'Browse',
                            'value': 'Browse'
                        }).text('Browse')
                        .addClass('button small browse')
                        .css({'text-align': 'center'})
                    )
                    .append(upl.inp = $('<input>')
                        .attr({
                            'type': 'file',
                            'name': 'upl'
                        })
                        .addClass('field_obj file_upload')
                    )
                    .append(upl.ele = $('<div>')
                    )
                )
        );*/




/*
                tpl.find('span').append($('<img>').attr({'src': NB.media + '/' + data.files[0].name}).css({'width': '120px'}));
*/
                /*
                .text(data.files[0].name)
                            .append('<span>' + formatFileSize(data.files[0].size) + '</span>');
                */

                // Add the HTML to the UL element
        //		data.context = tpl.appendTo(upl.ele);



                // Listen for clicks on the cancel icon
                /*
                tpl.find('span').click(function(){

                    if(tpl.hasClass('working')){
                        jqXHR.abort();
                    }

                    tpl.fadeOut(function(){
                        tpl.remove();
                    });

                });
                */



        });

        return upl.media;
    },

    saveUpload = function(ele) {
        var media_ele = ele.find('span.place4media').children();
    },

    destroyUpload = function(ele) {
        // clear the two span elements: place4media and button4media
        // and replace the place4media-element with the media, if it's available
        var dest = {};
        dest.media_ele = ele.find('span.place4media').empty();
        dest.btn_ele = ele.find('span.button4media').empty();
        if(dest.media_ele.parent().is('form')) {
            dest.media_ele.unwrap();
//            dest.media_ele.removeClass('drop error');
        }

    };



    // -------------------------------------------------------------------------
    // define the methods here
    // -------------------------------------------------------------------------

    var methods = {
        /*========================================================================*/
        init: function(options) {
            return this.each(function() {
                var $this = $(this), media,
                    localdata = {};
                localdata.settings = {
                    file: undefined,
                    media: undefined,
                    note: undefined
                };
                $.extend(localdata.settings, options);
                $this.data('localdata', localdata);


                if(localdata.settings.file.val().length === 0) {
                    // there's no media yet: create the upload element (browse)
                    createUpload($this, localdata.settings);
                } else {
                    // this note is already connected with a media (delete-func)
                    deleteUpload($this, localdata.settings);
                }

            });											// end "return this.each"
        },												// end "init"

        cancel: function() {
            return this.each(function(){
                var $this = $(this);
                var localdata = $this.data('localdata');
                destroyUpload($this);
            });
        },

        save: function(options) {
            return this.each(function(){
                var $this = $(this);
                var localdata = $this.data('localdata');
                var data = {};
                data.media = $this.find('span.place4media').children('img').html();
                console.log(data.media);
                data.file = localdata.settings.file.val();
                destroyUpload($this);
                return data.media;
            });
        },

        anotherMethod: function() {
            return this.each(function(){
                var $this = $(this);
                var localdata = $this.data('localdata');
            });
        }
        /*========================================================================*/
    };



    $.fn.upload = function(method) {
        // Method calling logic
        if ( methods[method] ) {
            return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            throw 'Method ' + method + ' does not exist on jQuery.upload';
        }
    };
})( jQuery );
