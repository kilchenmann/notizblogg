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

				localdata.create = {};
				localdata.select = {};

				localdata.settings = {
					type: 'new', // or edit / delete
					noteid: '',
					sourceid: ''
				};
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				// set the title of the document and the project specific data
				$this.append(
					localdata.create.button = $('<button>')
						.addClass('btn grp_none toggle_add')
				);

				localdata.create.frame = $('.float_obj.large.pamphlet').empty()
				.append(
					localdata.create.connection = $('<form>')
						.append(
						localdata.select.source = $('<select>')
					)
				);


				// set position of float_obj
				localdata.create.button.on('mouseover', function() {
					//localdata.create.frame.center();
				});

				localdata.create.button.click(function() {
					localdata.create.frame.toggle();

					if(localdata.create.frame.is(':visible')) {
						$('.viewer').css({'opacity': '0.3'});
						if (localdata.settings.type === 'new') {
							localdata.select.source.focus();
							localdata.create.button.toggleClass('toggle_delete');
						}
					} else {
						$('.viewer').css({'opacity': '1'});
						localdata.create.button.toggleClass('toggle_delete');
						localdata.create.frame.empty();
					}
				});



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



	$.fn.create = function(method) {
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
