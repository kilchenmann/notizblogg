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

	var setPanel = function(ele, title) {
			var close;
			ele.addClass('panel top')
				.append($('<div>').addClass('left expand_title')
					.append($('<h3>').html(title))
			)
				.append($('<div>').addClass('right collapse_close')
					.append(close = $('<button>').addClass('btn grp_none toggle_collapse'))
			);
			return(close);
		},

		form4note = function(ele, settings) {
			var checkID, publicID, pages, check_ele = {}, media_ele;
			var i = 0;
			var labels = '';
			$.getJSON('get/note/' + settings.noteID, function(data) {
				//console.log(data);
				// get label
	//			console.log((data.label));
				if(data.label.length !== 0) {

					while(i < data.label.length) {
						if (labels === '') {
							labels = data.label[i]['name'];
						} else {
							labels += ', ' + data.label[i]['name'];
						}

					i += 1;
					}
				}
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
							.append(check_ele.label4public = $('<label>').attr({'name': 'check_public'}).addClass('field_obj small').text('Public note?')
								.append(check_ele.span4public = $('<span>').addClass('field_obj small ' + publicID).attr({'id': 'checked'})
									.append(check_ele.input4public = $('<input>').attr({'type': 'checkbox', 'name': 'public', 'checked': publicID }).addClass('field_obj check_public')
										.on('change', function(){
											check_ele.span4public.toggleClass('checked');
										})
								)
									.append(
									//echo "<label class='check' for='delete'></label>";
									$('<label>').addClass('check').attr({'for': 'public'})

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
													.find($('input, textarea, .media, select')).css({'background-color': 'rgba(147, 0, 0, 0.1)'});
												$('input.submit').val('DELETE').css({'background-color': 'rgba(147, 0, 0, 0.8)'});

											} else {
												$('.form')
													.find($('input, textarea, .media, select')).css({'background-color': ''});
												$('input.submit').val('Save').css({'background-color': ''});
											}
										})
								)
							)
						).append($('<p>').html('<br><br><br>'))

					)
						.append($('<div>').addClass('form col_large')
							// line 1: label and check public
							.append($('<p>')
								.append($('<input>').attr({'type': 'text', 'name': 'label', 'placeholder': 'Label', 'value': labels}).addClass('field_obj large'))
									.append($('<input>').attr({'type': 'submit', 'name': 'submit', 'title': 'Save', 'value': 'Save'}).addClass('submit field_obj small'))//.css({float: 'right'}))
						)
							// line 2: source, pages and check delete
							.append($('<p>')
								.append($('<input>').attr({'type': 'text', 'name': 'source', 'placeholder': 'Connect with [source]', 'value': data.source.title}).addClass('field_obj medium'))
								.append($('<input>').attr({'type': 'text', 'name': 'pages', 'placeholder': 'Pages', 'value': pages}).addClass('field_obj small'))
								.append($('<input>').attr({'type': 'reset', 'name': 'reset', 'title': 'Reset', 'value': 'Reset'}).addClass('reset field_obj small'))//.css({float: 'right'}))

						)

					)
				);
				$('input[type=file]').css({'top': '-' + media_ele.height() + 'px'});
			});
		},

		form4source = function(ele, localdata) {

		},

		form4new = function(ele, localdata){
			var selectSource = '<option></option>',
				selectedOne;
			// the last source is always the selected one
			$.getJSON('get/source/last', function(data) {
				var i = 0;
				while (i < data.lastSource.length) {
					selectedOne = data.lastSource[i].id;
					i += 1;
				}
			});
			// all sources to choose from
			$.getJSON('get/source/all', function(data) {
				var i = 0;
				while(i < data.allSources.length) {
						selectSource += '<option value=\'' + data.allSources[i].id + '\'>' + data.allSources[i].name + '</option>';
					i += 1;
				}
				ele
					.append($('<form>').attr({'action': 'core/bin/php/save.data.php', 'method': 'post', 'accept-charset': 'utf-8' })
						.append($('<div>').addClass('form col_medium left')
							.append($('<p>').text('You have to choose a source or add a new one'))
							.append(localdata.form.select_source = $('<select>').attr({'name': 'select_source'}).addClass('field_obj large select').append(selectSource))
							.append(localdata.form.selected_source = $('<div>').addClass('field_obj large fake_area').shownote({
								type: 'source',
								id: selectedOne
							})
						)
					)
						.append($('<div>').addClass('form col_small right'))
				);

				localdata.form.select_source.on('change', function() {
					// show the selected source on the right side
					localdata.form.selected_source.shownote({
						type: 'source',
						id: $(this).val()
					})
				});
			});






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
				localdata.form = {};

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
//								localdata.expand.edit_ele = $('<button>').addClass('btn grp_none toggle_lock');
								//
								if(localdata.settings.noteID === 'new' && localdata.settings.sourceID === 'new' && localdata.settings.data === undefined) {
// new element
									localdata.expand.content = $('<div>');
									localdata.expand.col_ele = setPanel(localdata.expand.content, 'You want to add new notes');

									localdata.expand.form = $('<div>').addClass('form part select_source');

									// get the note data from api/data.php
									form4new(localdata.expand.form, localdata);



								} else {
									if(localdata.settings.sourceID === localdata.settings.noteID) {
// edit the source
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
// edit the note
										localdata.expand.content = $('<div>');
										localdata.expand.col_ele = setPanel(localdata.expand.content, 'You want to edit the existing note ' + localdata.settings.noteID);


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

		newNote: function() {
			return this.each(function(){
				var $this = $(this);
				var localdata = $this.data('localdata');
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
