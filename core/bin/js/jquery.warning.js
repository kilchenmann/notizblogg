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


				localdata.warning = {};

				localdata.settings = {
					type: 'access',
					lang: 'en'
				};
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);
				localdata.warning.object = $('.warning');

				// set some text
				// change the if clause to a switch element
				if(localdata.settings.lang === 'de') {
					localdata.warning.object
						.append($('<h3>').html('Hoppela'))
						.append(localdata.warning.line_de = $('<p>').html('Da ging etwas schief!'))
				} else {
					localdata.warning.object
						.append($('<h3>').html('Warning'))
						.append(localdata.warning.line_en = $('<p>').html('Something went wrong!'))
				}

				switch (localdata.settings.type) {
					case 'access':
						if(!localdata.warning.line_de) {
							localdata.warning.line_en.append($('<p>').html('Do you have a Loginname and a Password?'));
						} else {
							localdata.warning.line_de.append($('<p>').html('Hast du einen Loginname und ein Passwort?'));
						}
						break;

					case 'permission':
						if(!localdata.warning.line_de) {
							localdata.warning.line_en.append($('<p>').html('You don\'t have the permission to do that!'));
						} else {
							localdata.warning.line_de.append($('<p>').html('Du hast keine Berechtigung für diesen Vorgang!'));
						}
						break;

					case 'noresults':
						if(!localdata.warning.line_de) {
							localdata.warning.line_en.append($('<p>').html('Either you are not allowed to see the note or there are no notes with this item.'));
						} else {
							localdata.warning.line_de.append($('<p>').html('Entweder hast du keine Berechtigung für diese Anfrage oder die Notiz existiert nicht.'));
						}
						break;

					default:
						localdata.warning.line_en.append($('<p>').html('not allowed'));
				}


				$this.children(':not(.warning)').fadeTo('slow', 0.25);
				localdata.warning.object.fadeTo('slow', 0.95).center();

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



	$.fn.warning = function(method) {
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
