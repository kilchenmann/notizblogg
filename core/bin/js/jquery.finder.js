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
					search: 'search',
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
							'type': 'search',
							'title': localdata.settings.search,
							'placeholder': localdata.settings.search
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
					localdata.search.frame = $('<div>')
						.addClass('float_obj medium search_frame')
						.append(
						localdata.search.extended = $('<form>')
							.attr({
								'action': 'core/bin/php/search.php',
								'method': 'post'
							})

							.append($('<p>')
								.append($('<label>')
									.text('Name')
								)
								.append(localdata.search.first = $('<input>')
									.attr({
										'type': 'text'
									})
									.addClass('field_obj medium')
							)
						)
							.append($('<p>')
								.append($('<input>')
									.attr({
										'type': 'text'
									})
									.addClass('field_obj medium')
							)
						)
							.append($('<p>')
								.append($('<button>')
									.attr({
										'type': 'submit',
										'title': localdata.settings.search,
										'value': localdata.settings.search
									}).text(localdata.settings.search)
									.addClass('button small submit')
							)
						)
					)
				);





				// set position of float_obj
				localdata.search.filter.on('mouseover', function() {
					var filpos = $this.position();
					localdata.search.frame.css({
						top: $('header').position().top + 44 +'px',
						left: $(this).position().left - 256 + 'px'
					})

				});

				localdata.search.filter.on('click', function() {
					localdata.search.frame.toggle();
					if(localdata.search.frame.is(':visible')) {
						localdata.search.first.focus();
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
