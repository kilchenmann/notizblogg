/* ===========================================================================
 *
 * @frame: jQuery plugin 'panel' for notizblogg: logo, searchbar & login
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


(function ($) {
	'use strict';
	// -----------------------------------------------------------------------
	// -----------------------------------------------------------------------
	// define some functions first
	// -----------------------------------------------------------------------


	// -------------------------------------------------------------------------
	// -------------------------------------------------------------------------
	// define the methods here
	// -------------------------------------------------------------------------
	var methods = {
		/*========================================================================*/
		init: function () {
			return this.each(function () {
				var $this = $(this),
					localdata = {};

				localdata.settings = {
					project: 'Notizblogg', // default: Notizblogg
					logo: 'nb-logo.png', // default: nb-logo.png
					user: undefined // undefined = guest
				};

				localdata.search = {};

				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				console.log('text aus panel');


			}); // end "return this.each"
		}, // end "init"


		project: function () {
			return this.each(function () {
				var $this = $(this),
					localdata = $this.data('localdata');

			});
		},

		search: function () {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				var search = {};
				$this
					.append(search.simple = $('<form>')
						.attr({
							'accept-charset': 'utf-8',
							'name': 'simpleSearch',
							'action': NB.url + '/core/bin/php/search.data.php',
							'method': 'get'
						})
						.append(search.filter = $('<button>')
							.attr({
								'type': 'button',
								'title': 'filter'
							})
							.addClass('btn grp_left search_filter')
						)
						.append(search.field = $('<input>')
							.attr({
								'type': 'search',
								'title': 'SEARCH',
								'placeholder': 'search',
								'name': 'q'
							})
							.addClass('input grp_middle search_field')
							.focus(function () {
								search.field
									.attr({
										'placeholder': ''
									})
									.css({
										'background-color': '#ffffe0'
									});
							})
							.focusout(function () {
								search.field
									.attr({
										'placeholder': 'search'
									})
									.css({
										'background-color': '#ffffff'
									});
							})
						)
						.append(search.button = $('<button>')
							.attr({
								'type': 'submit',
								'title': 'GO!'
							})
							.addClass('btn grp_right search_btn')
						)
					);

			});
		},

		login: function () {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
			});
		},

		anotherMethod: function () {
				return this.each(function () {
					var $this = $(this);
					var localdata = $this.data('localdata');
				});
			}
			/*========================================================================*/
	};



	$.fn.panel = function (method) {
		// Method calling logic
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			throw 'Method ' + method + ' does not exist on jQuery.tooltip';
		}
	};
})(jQuery);
