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
					lang: 'en'
				};
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);
				localdata.pamphlet.object = $('.pamphlet').empty();
				localdata.pamphlet.object
					.append($('<div>').addClass('panel top')
						.append($('<div>').addClass('left')
							.append($('<h3>').html(localdata.settings.view + ': ' + localdata.settings.type + ' = ' + localdata.settings.id))
						)
						.append($('<div>').addClass('center'))
						.append($('<div>').addClass('right')
							.append($('<button>').addClass('btn grp_none toggle_collapse'))
						)
					);


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



				if(localdata.pamphlet.object.hasClass('visible')) {
					$('button.toggle_collapse').click(function(){
						alert('cklick?');
						$('header').fadeTo('slow', 1);
						$this.children(':not(.pamphlet)').fadeTo('slow', 1);
						localdata.pamphlet.object.fadeTo('fast', 0).empty();
						$('footer').fadeTo('slow', 1);
					});
				} else {
					$('header').fadeTo('slow', 0.25);
					$this.children(':not(.pamphlet)').fadeTo('slow', 0.25);
					localdata.pamphlet.object.fadeTo('fast', 1.00).center().toggleClass('visible');
					$('footer').fadeTo('slow', 0.25);

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
