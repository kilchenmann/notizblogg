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

(function( $ ) {
	// -----------------------------------------------------------------------
	// define some functions
	// -----------------------------------------------------------------------
	// open or close the drawer on the left side
	toggle_drawer = function (localdata) {
		/*
		var kremoWidth = localdata.kremo.width(),
			drawerWidth = localdata.drawer.main.width();
		if (localdata.drawer.main.hasClass('active') === false) {
			if (kremoWidth <= 1023) {
				localdata.kr_workspace.main.animate({
					width: (kremoWidth - 70) + 'px'
				});
				localdata.kr_drawer.main.animate({
					width: '69px'
				});
			} else {
				localdata.kr_workspace.main.animate({
					width: (kremoWidth - 215) + 'px'
				});
				localdata.kr_drawer.main.animate({
					width: '214px'
				});
			}
			localdata.kr_drawer.main.addClass('active');
		} else {
			localdata.kr_drawer.main.animate({
				width: '0px'
			});
			localdata.kr_workspace.main.animate({
				width: (kremoWidth - 1) + 'px'
			});
			localdata.kr_drawer.main.removeClass('active');
		}
		*/
	};


	//
	// -------------------------------------------------------------------------
	// define the methods
	// -------------------------------------------------------------------------

	var methods = {

		init: function(options) {
			return this.each(function() {
				var $this = $(this),
					localdata = {};


				localdata.drawer = {};

				localdata.settings = {
					menu: ''
				};

				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				// set the title of the document and the project specific data
				$this.append(
			//		localdata.drawer.menu = $('<span>').addClass('drawer_menu').html('Auswahl hier')
				).addClass('open').append(
					/*
					localdata.drawer.btn = $('<input>')
						.attr({
							'type': 'button',
							'title': 'toggle drawer'
						})
						.addClass('btn grp_right toggle_drawer')
				*/
				)
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



	$.fn.drawer = function(method) {
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