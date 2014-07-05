/* ===========================================================================
 *
 * @frame: jQuery plugin for lakto — flat one page and responsive webdesign template
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

(function($){
	// -----------------------------------------------------------------------
	// define some functions
	// -----------------------------------------------------------------------
	//

	var form4note = function(ele, settings) {
			var checkID, publicID, pages, check_ele = {}, media_ele;
			$.getJSON('api/data.php?note=' + settings.noteID, function(data) {

				if (data.setting.checkID === null) {
					checkID = check_id();
				} else {
					checkID = data.setting.checkID;
				}
				if(data.setting.public === '1') {
					publicID = 'checked';
				}
				if(data.page.start !== '0') {
					pages = data.page.start;
					if(data.page.end !== pages && data.page.end !== '0') {
						console.log(data.page.end);
						pages += '-' + data.page.end;
					}
				}
				if(data.media !== '') {
					media_ele = $('<img>').attr({'src': '../media/pictures/' + data.media, 'alt': data.media}).addClass('media');

				} else {
					media_ele = $('<img>').attr({'src': '', 'alt': 'upload a media (picture, video, etc.)'}).addClass('media').css({'width':'160px', 'height': '160px'});
				}
				ele
					.append($('<form>').attr({'action': 'core/bin/php/save.data.php', 'method': 'post', 'accept-charset': 'utf-8' })
						.append($('<div>').addClass('form col_medium left')
							.append($('<input>').attr({'type': 'text', 'name': 'title', 'placeholder': 'Title', 'value': data.title}).addClass('field_obj large'))
							.append($('<textarea>').attr({'type': 'text', 'name': 'content', 'placeholder': 'Content', 'required': 'required'}).addClass('field_obj large').html(data.content))
					)
						.append($('<div>').addClass('form col_small right')
							.append($('<input>').attr({'type': 'hidden', 'placeholder': 'checkID', 'readonly': 'readonly', 'name': 'checkID', 'value': checkID }))
							.append($('<input>').attr({'type': 'hidden', 'placeholder': 'noteID', 'readonly': 'readonly', 'name': 'noteID', 'value': data.id }))
							.append($('<input>').attr({'type': 'text', 'name': 'media', 'placeholder': 'Filename', 'value': data.media}).addClass('field_obj small'))
							.append($('<div>').addClass('media field_obj small')
								.append(media_ele)
								.append($('<input>').attr({'type': 'file', 'name': 'upload', 'placeholder': 'Upload new file'}).addClass('upload field_obj small'))
						).append($('<p>').html('<br><br><br>'))
							.append(check_ele.label4public = $('<label>').attr({'name': 'check_public'}).addClass('field_obj small')
								.append(check_ele.span4public = $('<span>').text('Public note?').addClass('field_obj small ' + publicID)
									.append(check_ele.input4public = $('<input>').attr({'type': 'checkbox', 'name': 'public', 'checked': publicID }).addClass('field_obj check_public')
										.on('change', function(){
											check_ele.span4public.toggleClass('checked');
										})
								)
							)
						).append($('<p>').html('<br><br>'))
							.append(check_ele.label4delete = $('<label>').attr({'name': 'check_delete'}).addClass('field_obj small')
								.append(check_ele.span4delete = $('<span>').text('Delete note?').addClass('field_obj warning small')
									.append(check_ele.input4delete = $('<input>').attr({'type': 'checkbox', 'name': 'delete'}).addClass('field_obj check_delete')
										.on('change', function(){
											check_ele.span4delete.toggleClass('checked');
											if(check_ele.span4delete.hasClass('checked')) {
												$('.form')
													.find($('input, textarea, .media, select')).css({'background-color': 'rgba(147, 0, 0, 0.2)'});
											} else {
												$('.form')
													.find($('input, textarea, .media, select')).css({'background-color': ''});
											}
										})
								)
							)
						).append($('<p>').html('<br><br><br>'))

					)
						.append($('<div>').addClass('form col_large')
							// line 1: label and check public
							.append($('<p>')
								.append($('<input>').attr({'type': 'text', 'name': 'label', 'placeholder': 'Label', 'value': data.label.name}).addClass('field_obj large'))
									.append($('<input>').attr({'type': 'submit', 'name': 'submit', 'title': 'Save', 'value': 'Save'}).addClass('submit field_obj small'))//.css({float: 'right'}))
						)
							// line 2: source, pages and check delete
							.append($('<p>')
								.append($('<input>').attr({'type': 'text', 'name': 'source', 'placeholder': 'Connect with [source]', 'value': data.source.title}).addClass('field_obj medium'))
								.append($('<input>').attr({'type': 'text', 'name': 'pages', 'placeholder': 'Pages', 'value': pages}).addClass('field_obj small'))
								.append($('<input>').attr({'type': 'reset', 'name': 'reset', 'title': 'Reset', 'value': 'Reset'}).addClass('submit field_obj small'))//.css({float: 'right'}))

						)

					)
				);
				$('input[type=file]').css({'top': '-' + media_ele.height() + 'px'});
			});
		},

		form4source = function(ele, localdata) {

		},

		form4new = function(){

		};
	// -------------------------------------------------------------------------
	// define the methods
	// -------------------------------------------------------------------------

	var methods = {
		/*========================================================================*/
		init: function(options) {
			return this.each(function() {
				var $this = $(this),
					checkID,
					localdata = {};

				localdata.expand = {};

				localdata.settings = {
					type: 'source',		// source || note
					noteID: undefined,
					sourceID: undefined,
					edit: false,		// true || false
					data: undefined,
					show: 'booklet'		// booklet || form
				};

				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);


				$this.click(function() {
					localdata.expand.frame = $('.float_obj.large.pamphlet').empty();
					localdata.expand.content = '';
					if(localdata.settings.noteID === undefined && localdata.settings.sourceID === undefined && localdata.settings.data === undefined) {
						// nothing is defined! There is no object to show
						// abort or alert
						$('#fullpage').warning({
							type: 'noresults',
							lang: 'de'
						});
					} else {
						// set the various content of the floating element
						if(localdata.settings.edit === false) {
							localdata.expand.edit_ele = $('<button>').addClass('btn grp_none fake_btn');
						} else {
							if(localdata.settings.show === 'form') {
								localdata.expand.edit_ele = $('<button>').addClass('btn grp_none toggle_lock');
								if(localdata.settings.noteID === 'new' && localdata.settings.sourceID === 'new' && localdata.settings.data === undefined) {
									// in this case we have to show the edit form for new notes
									localdata.expand.content = $('<div>').addClass('form part select_source')
										.append($('<h3>').html('You want to add a NEW note'))
										.append($('<h4>').html('First you have to choose a source or create a new one'))
								} else {
									if(localdata.settings.sourceID === localdata.settings.noteID) {

										// get the source data from api/data.php
										$.getJSON('api/data.php?source=' + localdata.settings.sourceID, function(data) {

										});


										// the note is a source ;)
										var checkID = check_id();
										localdata.expand.content = $('<div>').addClass('form entire edit_source')
											.append($('<h3>').html('You want to edit the existing source ' + localdata.settings.sourceID))
											.append($('<form>').attr({'action': 'save.php', 'method': 'post', 'accept-charset': 'utf-8' })
												.append($('<input>').attr({'type': 'hidden', 'placeholder': 'checkID', 'readonly': 'readonly', 'name': 'checkID', 'value': check_id() }))
												.append($('<input>').attr({'type': 'hidden', 'placeholder': 'noteID', 'readonly': 'readonly', 'name': 'noteID', 'value': localdata.settings.sourceID }))
												.append($('<input>').attr({'type': 'text', 'name': 'title', 'placeholder': 'Title', 'value': ''}))
												.append($('<textarea>').attr({'type': 'text', 'name': 'content', 'placeholder': 'Content', 'required': 'required', 'rows': '10', 'cols': '50', 'value': ''}))
										)

									} else {
										localdata.expand.content = $('<div>').addClass('panel top')
											.append($('<div>').addClass('left expand_title')
												.append($('<h3>').html('You want to edit the existing note ' + localdata.settings.noteID))
										)
											.append($('<div>').addClass('right collapse_close')
												.append(localdata.expand.col_ele = $('<button>').addClass('btn grp_none toggle_collapse'))
										);

										localdata.expand.form = $('<div>').addClass('form entire edit_note');

										// get the note data from api/data.php

											form4note(localdata.expand.form, localdata.settings);








									}
								}
								localdata.expand.edit_ele = $('<button>').addClass('btn grp_none toggle_lock');
							} else {
								localdata.expand.content = $('<div>').addClass('booklet')
									.append($('<h3>').html('You want to see the booklet of source ' + localdata.settings.sourceID))
									.append($('<h4>').html(''));
								localdata.expand.edit_ele = $('<button>').addClass('btn grp_none toggle_edit');
							}
						}

						//	localdata.expand.tex_ele = $('<button>').addClass('btn grp_none toggle_cite');
						//	localdata.expand.col_ele= $('<button>').addClass('btn grp_none toggle_collapse');




						// create the floating element
							localdata.expand.frame
								.append(localdata.expand.content)
								.append(localdata.expand.form);

								/*
								.append(
								$('<div>').addClass('tools').css({opacity: '1'})
									.append(
									$('<div>').addClass('left').append(localdata.expand.edit_ele)
								)
									.append(
									$('<div>').addClass('center').append(localdata.expand.tex_ele)
								)
									.append(
									$('<div>').addClass('right').append(localdata.expand.col_ele)
								)
							);
							*/



						// the real action by clicking (from above)
						if($this.hasClass('toggle_delete') || localdata.expand.frame.is(':visible')) {
							// close the element, show the viewer and remove the delete class from add button
							localdata.expand.frame.toggle().empty();
							$('.viewer').fadeTo('slow', '1');
							$('button.toggle_delete').toggleClass('toggle_delete');
						} else {
							// hide the viewer, show the floating element and change the add button to a close button
							$('.viewer').fadeTo('slow', '0.2');
							localdata.expand.frame.toggle();
							$('button.toggle_add').toggleClass('toggle_delete');
							localdata.expand.col_ele.click(function() {
								// by clicking on the collapse button:
								// close the element, show the viewer and remove the delete class from add-button
								localdata.expand.frame.toggle().empty();
								$('.viewer').fadeTo('slow', '1');
								$('button.toggle_delete').toggleClass('toggle_delete');
							})
						}


					}		// end of if(data exists)
				});		// end of $this.click function





				/*







				// set the title of the document and the project specific data

				$this.click(function() {









				});
*/





			});											// end "return this.each"
		},												// end "init"

		anotherMethod: function() {
			return this.each(function(){
				var $this = $(this);
				var localdata = $this.data('localdata');
			});
		}
		/*========================================================================*/
	};



	$.fn.expand = function(method) {
		// Method calling logic
		if ( methods[method] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			throw 'Method ' + method + ' does not exist on jQuery.tooltip';
		}
	};
})( jQuery );
