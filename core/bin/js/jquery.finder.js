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


				localdata.search = {};

				localdata.settings = {
					placeholder: 'search',
					filter: 'search filter',
					database: ''
				};
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				// set the title of the document and the project specific data
				$this
					.append(localdata.search.filter = $('<button>')
						.attr({
							'type': 'button',
							'title': localdata.settings.filter
						})
						.addClass('btn grp_left search_filter')
					)
					.append(localdata.search.field = $('<input>')
						.attr({
							'type': 'text',
							'title': localdata.settings.placeholder,
							'placeholder': localdata.settings.placeholder
						})
						.addClass('input grp_middle search_field')
					)
					.append(localdata.search.button = $('<button>')
						.attr({
							'type': 'button',
							'title': localdata.settings.placeholder
						})
						.addClass('btn grp_right search_btn')
					);

				$this.append(
					localdata.search.extended = $('<div>')
						.addClass('float_obj small search_extended')
						.append(
							localdata.search.extendedform = $('<form>')
								.append($('<input>')
									.attr({
										'type': 'search',
										'title': localdata.settings.placeholder,
										'placeholder': localdata.settings.placeholder
									})
								)
						)
				);

				localdata.search.filter.on('click', function(event) {
					localdata.search.extended.toggle();
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



	$.fn.finder = function(method) {
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
