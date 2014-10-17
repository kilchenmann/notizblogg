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
		init: function (options) {
			return this.each(function () {
				var $this = $(this),
					localdata = {};

				localdata.settings = {
					project: 'Notizblogg',	// default: Notizblogg
					logo: 'nb-logo.png',	// default: nb-logo.png
					user: undefined,		// undefined = guest
					action: undefined
				};
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

			}); // end "return this.each"
		}, // end "init"


		project: function () {
			return this.each(function () {
				var $this = $(this),
					localdata = $this.data('localdata');

			});
		},

		search: function (action) {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				var search = {};
				$this
					.append(search.simple = $('<form>')
						.attr({
							'accept-charset': 'utf-8',
							'name': 'simpleSearch',
							'action': action,
							'method': 'get'
						})
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
									$(this).select();
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

		login: function (action) {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				var login = {};

				$this.append(
					login.button = $('<button>')
					.addClass('btn grp_none toggle_user')
				);

				$this.append(
					login.frame = $('<div>')
						.addClass('float_obj medium login_frame')
						.append(
							login.form = $('<form>')
							.attr({
								'action': action,
								'method': 'post'
							})
						)
				);
				login.form.append($('<p>')
					.append(login.name = $('<input>')
						.attr({
							'type': 'text',
							'name': 'usr',
							'title': 'Username',
							'placeholder': 'Username'
						})
					.addClass('field_obj small')
					)
				)

				.append($('<p>')
					.append($('<input>')
						.attr({
							'type': 'password',
							'name': 'key',
							'title': 'Password',
							'placeholder': 'Password'
						})
						.addClass('field_obj small')
					)
				)
				.append($('<p>')
					.append($('<input>')
						.attr({
							'type': 'hidden',
							'name': 'uri',
							'value': NB.uri
						})
						.addClass('field_obj small')
					)
				)
				.append($('<p>')
					.append($('<input>')
						.attr({
							'type': 'submit',
							'title': 'Login',
							'value': 'Login'
						}).text('Login')
						.addClass('button small submit')
					)
				);
				// set position of float_obj
				login.button
					.on('mouseover', function() {
						login.frame.css({
							position: 'absolute',
							top: $('header').position().top + 44 +'px',
							left: $(this).position().left - login.frame.width() + 'px'
						});
					})
					.on('click', function() {
					if(login.frame.is(':visible')) {
						login.frame.slideUp();
					} else {
						login.frame.slideDown();

					}
					if(login.frame.is(':visible')) {
						login.name.focus();
					}
				});
			});
		},
		log: function (action) {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				var log = {};

				$this.append(
					log.add = $('<button>')
						.attr({
							'type': 'button',
							'title': 'add new'
						})
						.addClass('btn grp_left toggle_add')
					).append(
						log.user = $('<button>')
						.addClass('btn grp_right toggle_user')
						.css({
							'background-image': 'url("' + NB.user.avatar + '")',
							'background-repeat': 'no-repeat',
							'background-position': 'center',
							'background-size': '42px 42px',
							'border': 'none'
						})
					);

				$this.append(
					log.frame = $('<div>')
						.addClass('float_obj medium logout_frame')
						.append(
							log.form = $('<form>')
							.attr({
								'action': action,
								'method': 'post'
							})
							.append($('<p>')
								.append($('<input>')
									.attr({
										'type': 'submit',
										'title': 'Logout',
										'value': 'Logout'
									}).text('Logout')
									.addClass('button small reset')
								)
								.css({
									float: 'right'
								})
							)
						)
				);
				// set position of float_obj
				log.user
					.on('mouseover', function() {
						log.frame.css({
							position: 'absolute',
							top: $('header').position().top + 44 +'px',
							left: $(this).position().left - log.frame.width() + 'px'
						});
					})
					.on('click', function() {
					if(log.frame.is(':visible')) {
						log.frame.slideUp();
					} else {
						log.frame.slideDown();

					}
				});
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
