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


				localdata.pamphlet = {};

				localdata.settings = {
					view: 'booklet',	// booklet, edit
					type: 'source',		// source, note
					id: '',				// id of the source or the note
					lang: 'en',
					mainele: $('<body>')
				};
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				localdata.pamphlet.object = $('.pamphlet').empty();
				localdata.pamphlet.object
					.append($('<div>').addClass('pamphlet book'))
					.append($('<div>').addClass('pamphlet text')
						.append($('<h3>').addClass('title').html(localdata.settings.view + ': ' + localdata.settings.type + ' = ' + localdata.settings.id))
					)
					.append($('<div>').addClass('pamphlet label'))
					.append($('<div>').addClass('pamphlet tools')
						.append($('<div>').addClass('left')
							.append($('<button>').addClass('btn grp_none toggle_edit'))
						)
						.append($('<div>').addClass('center')
							.append($('<button>').addClass('btn grp_none toggle_cite'))
						)
						.append($('<div>').addClass('right')
							.append(localdata.pamphlet.collapse = $('<button>').addClass('btn grp_none toggle_collapse').click(function(){
								localdata.pamphlet.object.toggleClass('visible').hide().empty();
								localdata.settings.mainele.fadeTo('fast', 1);
							}))
						)
					);

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



				if(!localdata.pamphlet.object.hasClass('visible')) {
//					$('header').fadeTo('slow', 0.25);
					localdata.settings.mainele.fadeTo('fast', 0.25);
					localdata.pamphlet.object.center().toggleClass('visible').fadeTo('fast', 1.00);
				}




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
