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
	var blur = function(btn, action) {
		var vague = $('.wrapper').Vague({
			intensity:      3,		// Blur Intensity
			forceSVGUrl:    false,	// Force absolute path to the SVG filter,
			// default animation options
		});
		if(action === 'remove' && btn.hasClass('active')) {
			vague.destroy();
		} else {
			// open the form
			vague.blur();
		}
		btn.toggleClass('active');
	},

		checkframe = function() {
			if($('header .btn').hasClass('active')) {
				if($('.float_obj').is(':visible')) {
					$('.float_obj').slideUp();
				}
				$('.btn').removeClass('active');
			}
	};

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


		project: function (name, logo) {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				var project;
				$this
				.append($('<a>')
					.attr({
						href: NB.url
					})
					.append($('<img>')
						.attr({
							src: NB.media + '/project/' + logo
						})
						.addClass('title project logo')
					)
					.append($('<h2>')
						.text(name)
						.addClass('title project name')
					)
				);

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
								'name': 'q',
								'accept-charset': 'utf-8'
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
							.addClass('btn grp_right search')
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
					login.user = $('<button>')
					.addClass('btn grp_none user')
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
							.append($('<p>')
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
							)
						)
				);
				// set position of float_obj
				login.user
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
							blur(login.user, 'remove');
						} else {
							checkframe();
							blur(login.user);
							login.frame.slideDown();
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
						log.user = $('<button>')
						.addClass('btn grp_none user img')
						.css({
							'background-image': 'url("' + NB.user.avatar + '")',
							'background-repeat': 'no-repeat',
							'background-position': 'center',
							'background-size': '40px',
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
							blur(log.user, 'remove');
						} else {
							checkframe();
							blur(log.user);
							log.frame.slideDown();
						}
					});
			});
		},



		add: function () {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				var form = {};
				$this.append(
					form.frame = $('<div>')
						.addClass('float_obj large form_frame')
						.note('add', NB.api + '/post.php')
				);
				$this.append(
					form.new = $('<button>')
						.attr({
							'type': 'button',
							'title': 'add new'
						})
						.addClass('btn grp_none plus')

						.on('mouseover', function() {
							form.frame.css({
								position: 'absolute',
								top: $('header').position().top + 44 +'px'
							});
						})
						.on('click', function(){
							if(form.frame.is(':visible')) {
								form.frame.slideUp();
								blur(form.new, 'remove');
							} else {
								checkframe();
								blur(form.new);
								form.frame.slideDown();
								$('.first_form_ele').focus();
							}
						})
					);
				});
			},

		foot:function () {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				var curDate = new Date(),
					curYear = curDate.getFullYear();
				$this.append(
					$('<p>').addClass('small')
					.append(
						$('<span>').addClass('project')
						.append($('<a>')
							.attr({
								'href': 'http://notizblogg.ch'
							})
							.html('Notizblogg')
						)
					)
					.append(
						$('<span>').addClass('definition').html(' | ')
						.append($('<a>')
							.attr({
								'href': 'http://notizblogg.ch'
							})
							.html('Idea, Concept and Design')
						)
					)
					.append(
						$('<span>').addClass('copyright').html(' &copy; ')
						.append($('<a>')
							.attr({
								'href': 'https://plus.google.com/u/0/102518416171514295136/posts?rel=author'
							})
							.html('André Kilchenmann')
						)
					)
					.append(
						$('<span>').addClass('year').text(' | 2006-' + curYear)
					)
					.append(
						$('<span>').addClass('partner')
						.append($('<a>')
							.attr({
								'href': 'http://milchkannen.ch'
							})
							.append(
								$('<img>').attr({
									'src': 'core/style/img/akM-logo-small.png',
									'alt': 'milchkannen | kilchenmann',
									'title': 'milchkannen | andré kilchenmann'
								})

							)
						)
					)
				);
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
