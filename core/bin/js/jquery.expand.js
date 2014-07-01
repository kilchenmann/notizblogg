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

(function( $ ){
	// -----------------------------------------------------------------------
	// define some functions
	// -----------------------------------------------------------------------
	//
	// -------------------------------------------------------------------------
	// define the methods
	// -------------------------------------------------------------------------

	var methods = {
		/*========================================================================*/
		init: function(options) {
			return this.each(function() {
				var $this = $(this),
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

										// get the source data from get.JSON.php
										console.log('core/bin/php/get.JSON.php?source=' + localdata.settings.sourceID);
										$.getJSON('api/data.php?source=' + localdata.settings.sourceID, function(data) {
											console.log(data.author);
										});


										// the note is a source ;)
										var checkID = check_id();
										localdata.expand.content = $('<div>').addClass('form entire edit_source')
											.append($('<h3>').html('You want to edit the existing source ' + localdata.settings.sourceID))
											.append($('<form>').attr({'action': 'save.php', 'method': 'post', 'accept-charset': 'utf-8' })
												.append($('<input>').attr({'type': 'hidden', 'placeholder': 'checkID', 'readonly': 'readonly', 'name': 'checkID', 'value': checkID }))
												.append($('<input>').attr({'type': 'hidden', 'placeholder': 'noteID', 'readonly': 'readonly', 'name': 'noteID', 'value': localdata.settings.noteID }))
												.append($('<input>').attr({'type': 'text', 'name': 'title', 'placeholder': 'Title', 'value': ''}))
												.append($('<textarea>').attr({'type': 'text', 'name': 'content', 'placeholder': 'Content', 'required': 'required', 'rows': '10', 'cols': '50', 'value': ''}))
										)

									} else {
										// split the text to get the title and the content
										var title = localdata.settings.data['text'].split('</h3><p>')[0];
										var text = localdata.settings.data['text'].split('</h3><p>')[1];

										console.log(text);
										var checkID = check_id();
										localdata.expand.content = $('<div>').addClass('form entire edit_note')
											.append($('<h3>').html('You want to edit the existing note ' + localdata.settings.noteID))
											.append($('<form>').attr({'action': 'save.php', 'method': 'post', 'accept-charset': 'utf-8' })
												.append($('<input>').attr({'type': 'hidden', 'placeholder': 'checkID', 'readonly': 'readonly', 'name': 'checkID', 'value': checkID }))
												.append($('<input>').attr({'type': 'hidden', 'placeholder': 'noteID', 'readonly': 'readonly', 'name': 'noteID', 'value': localdata.settings.noteID }))
												.append($('<input>').attr({'type': 'text', 'name': 'title', 'placeholder': 'Title', 'value': title}))
												.append($('<textarea>').attr({'type': 'text', 'name': 'content', 'placeholder': 'Content', 'required': 'required', 'rows': '10', 'cols': '50'}).html(text))
										)
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

							localdata.expand.tex_ele = $('<button>').addClass('btn grp_none toggle_cite');
							localdata.expand.col_ele= $('<button>').addClass('btn grp_none toggle_collapse');




						// create the floating element
							localdata.expand.frame
								.append(localdata.expand.content)
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
