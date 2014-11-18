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
	var fileExists = function (media) {
		var response = jQuery.ajax({
			url: media,
			type: 'HEAD',
			async: false
		}).status;
		//return (response != "200") ? false : true;
		return (response == "200");
	};
	// -------------------------------------------------------------------------
	// define the methods
	// -------------------------------------------------------------------------

	var methods = {
		/*========================================================================*/
		init: function(options) {
			return this.each(function() {
				var $this = $(this),
					localdata = {},
					login,
					avatar_url;


				localdata.login = {};

				localdata.settings = {
					type: 'login', // or logout
					key: 'password',
					submit: 'login',
					action: 'index.php'
				};
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				// set the title of the document and the project specific data
				$this.append(
					localdata.login.button = $('<button>')
						.addClass('btn grp_none toggle_user')
				);

				$this.append(
					localdata.login.frame = $('<div>')
						.addClass('float_obj medium login_frame')
						.append(
							localdata.login.loginform = $('<form>')
								.attr({
									'action': localdata.settings.action,
									'method': 'post'
								})
						)
				);



				if(localdata.settings.type === 'logout') {
					// alert('logout');
					localdata.login.button.css({
						'background-image': 'url("' + NB.user.avatar + '")',
						'background-repeat': 'no-repeat',
					//	'background-attachment': 'fixed',
						'background-position': 'center',
						'background-size': '42px 42px',
						'border': 'none'
				});
					localdata.login.frame.toggleClass('medium small');
					localdata.login.size = 96;

					localdata.login.loginform.append($('<h3>')
							.html('')
						)
						.append($('<p>')
							.append($('<input>')
								.attr({
									'type': 'submit',
									'title': localdata.settings.submit,
									'value': localdata.settings.submit
								}).text(localdata.settings.submit)
								.addClass('button small reset')
						)
					);
				} else if(localdata.settings.type === 'login'){
					// alert('login');
					localdata.login.size = 228;
					localdata.login.loginform.append($('<p>')
							.append(localdata.login.name = $('<input>')
								.attr({
									'type': 'text',
									'name': 'usr',
									'title': NB.user.id,
									'placeholder': NB.user.id
								})
								.addClass('field_obj small')
						)
					)
						.append($('<p>')
							.append($('<input>')
								.attr({
									'type': 'password',
									'name': 'key',
									'title': localdata.settings.key,
									'placeholder': localdata.settings.key
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
									'title': localdata.settings.submit,
									'value': localdata.settings.submit
								}).text(localdata.settings.submit)
								.addClass('button small submit')
						)
					);
				}

				/*

						)
				);
*/
				// set position of float_obj
				localdata.login.button.on('mouseover', function() {
					localdata.login.frame.css({
						position: 'absolute',
						top: $('header').position().top + 44 +'px',
						//right: 22 + 'px'
						left: $(this).position().left - localdata.login.size - 22 + 'px'
					});

				});

				localdata.login.button.on('click', function() {
					if(localdata.login.frame.is(':visible')) {
						localdata.login.frame.slideUp();
					} else {
						localdata.login.frame.slideDown();

					}
					if(localdata.login.frame.is(':visible') && localdata.settings.type === 'login') {
						localdata.login.name.focus();
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



	$.fn.login = function(method) {
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
