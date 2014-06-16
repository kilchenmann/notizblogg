/* ===========================================================================
 *
 * @frame: jQuery plugin
 *
 * @author André Kilchenmann code@milchkannen.ch
 *
 * @copyright 2014 by André Kilchenmann (milchkannen.ch)
 *
 * @requires
 *  jQuery - min-version 1.11.1
 *
 * ===========================================================================
 * ======================================================================== */

(function( $ ){
	// -----------------------------------------------------------------------
	// define some functions
	// -----------------------------------------------------------------------
	var contentMenu = function(localdata) {
			alert(localdata.settings.content);
		
		},
		
		hideMenu = function() {
			$('.context-menu:visible').each(function() {
				$(this).trigger("closed");
				$(this).hide();
				$('body').unbind('click', hideMenu);
			});
		},
		showMenu = function() {
			
			
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
					default_options = {
						disable_native_context_menu: false, // disables the native contextmenu everywhere you click
						leftClick: false // show menu on left mouse click instead of right
					},
					options = $.extend(default_options, options);

					localdata.settings = {
						type: 'lookfor',
						content: ''
					};

				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				// create some stuff here
				$this.on('click', function(e) {
					contentMenu(localdata);
					showMenu();
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


	// change the pluginname !! IMPORTANT !!
	$.fn.lookFor = function(method) {
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
