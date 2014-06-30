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
	var appendBook = function (localdata) {
			console.log('appendBook');
		localdata.pamphlet.content
			.append($('<div>').addClass('pamphlet book'))
			.append($('<div>').addClass('pamphlet text')
				.append($('<h3>').addClass('title').html(localdata.settings.view + ': ' + localdata.settings.type + ' = ' + localdata.settings.id))
		)
			.append($('<div>').addClass('pamphlet label'))


		},

		appendForm = function(localdata) {
			console.log('appendForm');
			localdata.pamphlet.content
				.append($('<div>').addClass('pamphlet form')
					.html('formular für die editierung von ' + localdata.settings.type)

				)


		};





	// -------------------------------------------------------------------------
	// define the methods
	// -------------------------------------------------------------------------

	var methods = {
		/*========================================================================*/
		init: function(options) {
			return this.each(function() {
				var $this = $(this),
					loaded = false,
					edit_btn = '';

				localdata = {};


				localdata.pamphlet = {};

				localdata.settings = {
					view: 'booklet',	// booklet, edit
					type: 'source',		// source, note
					id: '',				// id of the source or the note
					lang: 'en',
					mainele: $('<body>'),
					edit_btn: 'fake'
				};

				if(localdata.settings.edit_btn === false) {
					edit_btn = 'fake';
				} else {
					edit_btn = 'toggle_edit';
				}
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				localdata.pamphlet.object = $('.pamphlet').empty();
				/* from php: class.show.php

				 $show_tools_left = '<div class=\'left\'>';
				 if($this->access != 'public' && isset($_SESSION['token'])) {
				 $show_tools_left .= '<button class=\'btn grp_none toggle_edit\' id=\'' . $this->data['type'] . '\'></button>';
				 } else {
				 $show_tools_left .= '<button class=\'btn grp_none fake_btn\'></button>';
				 }
				 $show_tools_left .= $this->close;

				 $show_tools_center = '<div class=\'center\'>';

				 if(!empty($this->data['source'])) {
				 // note with source
				 if($this->data['source']['id'] != 0 && $this->data['source']['bibTyp']['name'] != 'projcet') {
				 $show_tools_center .= '<button class=\'btn grp_none toggle_cite\' id=\'cite_note_' . $this->id . '\'></button>';
				 }
				 } else if ((isset($this->data['bibTyp']) && $this->data['bibTyp']['name'] != 'projcet')) {
				 $show_tools_center .= '<button class=\'btn grp_none toggle_cite\' id=\'cite_note_' . $this->id . '\'></button>';
				 } else {
				 $show_tools_center .= '<button class=\'btn grp_none toggle_cite\' id=\'cite_note_' . $this->id . '\'></button>';
				 }
				 $show_tools_center .= $this->close;

				 $show_tools_right = '<div class=\'right\'>';
				 if($this->type == 'source') {
				 $show_tools_right .= '<button class=\'btn grp_none toggle_expand\' id=\'' . $this->id . '\'></button>';
				 } else {

				 if(!empty($this->data['source'])) {
				 $show_tools_right .= '<button class=\'btn grp_none toggle_expand\' id=\'' . $this->data['source']['id'] . '\'></button>';
				 } else {
				 $show_tools_right .= '<button class=\'btn grp_none fake_btn\'></button>';
				 }


				 }
				 $show_tools_right .= $this->close;
				 */
				localdata.pamphlet.object
					.append(localdata.pamphlet.content = $('<div>').addClass('pamphlet content'))
					.append(localdata.pamphlet.tools = $('<div>').addClass('pamphlet tools'))
						.append($('<div>').addClass('left')
							.append(localdata.pamphlet.edit = $('<button>').addClass('btn grp_none ' + edit_btn))
					)
						.append($('<div>').addClass('center')
							.append($('<button>').addClass('btn grp_none toggle_cite'))
					)
						.append($('<div>').addClass('right')
							.append(localdata.pamphlet.collapse = $('<button>').addClass('btn grp_none toggle_collapse')
								.click(function(){
									localdata.pamphlet.object.toggleClass('visible').hide().empty();
									localdata.settings.mainele.fadeTo('fast', 1);
									loaded = false;
								})
						)
					);



				switch(localdata.settings.view) {
					case 'form':
						console.log(localdata.settings.view);
						localdata.pamphlet.edit.toggleClass('toggle_edit toggle_lock');
					//	appendForm(localdata);
						break;

					default:	// booklet
						console.log(localdata.settings.view);
//						localdata.pamphlet.edit.addClass('toggle_edit');
					//	appendBook(localdata);
				}

				if(!localdata.pamphlet.object.hasClass('visible')) {
//					$('header').fadeTo('slow', 0.25);
					localdata.settings.mainele.fadeTo('fast', 0.25);
					localdata.pamphlet.object.center().toggleClass('visible').fadeTo('fast', 1.00);
					loaded = true;
				}


/*
					localdata.pamphlet.collapse.click(function(){
					console.log('collapse!?');
					localdata.pamphlet.object.toggleClass('visible').hide().empty();
					localdata.settings.mainele.fadeTo('fast', 1);
				});
*/
				/*
				switch (localdata.settings.type) {
					case 'access':
						if(!localdata.pamphlet.line_de) {
							localdata.pamphlet.line_en.append($('<p>').html('Do you have a Loginname and a Password?'));
						} else {
							localdata.pamphlet.line_de.append($('<p>').html('Hast du einen Loginname und ein Passwort?'));
						}
						break;

					case 'permission':
						if(!localdata.pamphlet.line_de) {
							localdata.pamphlet.line_en.append($('<p>').html('You don\'t have the permission to do that!'));
						} else {
							localdata.pamphlet.line_de.append($('<p>').html('Du hast keine Berechtigung für diesen Vorgang!'));
						}
						break;

					case 'noresults':
						if(!localdata.pamphlet.line_de) {
							localdata.pamphlet.line_en.append($('<p>').html('Either you are not allowed to see the note or there are no notes with this item.'));
						} else {
							localdata.pamphlet.line_de.append($('<p>').html('Entweder hast du keine Berechtigung für diese Anfrage oder die Notiz existiert nicht.'));
						}
						break;

					default:
						localdata.pamphlet.line_en.append($('<p>').html('not allowed'));
				}
				*/








			});											// end "return this.each"
		},												// end "init"

		form: function(type, id, mainele){
			mainele.pamphlet({
				view: 'form',
				type: type,
				id: id,
				edit_btn: true
			});

			localdata.pamphlet.content
				.append($('<div>').addClass('pamphlet form')
					.html('formular für die editierung von ' + localdata.settings.type)

			);

			console.log('You want to edit the ' + type + ' with the id ' + id);

		},

		booklet: function(type, id, src, mainele, edit_btn){
			console.log(edit_btn);
			mainele.pamphlet({
				type: type,
				id: src,
				edit_btn: edit_btn
			});
			localdata.pamphlet.content
				.append($('<div>').addClass('pamphlet booklet')
					.html('boklet für die ' + localdata.settings.type)

			);
			console.log('You want to see the ' + type + ' with the id ' + id + ' and the sourceID ' + src);

		},

		anotherMethod: function() {
			return this.each(function(){
				var $this = $(this);
				var localdata = $this.data('localdata');
			});
		}
		/*========================================================================*/
	};



	$.fn.pamphlet = function(method) {
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
