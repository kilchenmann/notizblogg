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
	var function1 = function (value) {
			if (value === 1) {
				return value;
			} else {
				return 0;
			}
		},

		function2 = function () {
			//nothing to do?
		};
	// -------------------------------------------------------------------------
	// define the methods
	// -------------------------------------------------------------------------

	var methods = {
		/*========================================================================*/
		init: function(options) {
			return this.each(function() {
				var $this = $(this),
					localdata = {};
					
					localdata.settings = {
						opt1: 'par1',
						opt2: 'par2'
					};

					localdata.default = {
						contextMenu: false, // disables the native contextmenu everywhere you click
						leftClick: false // show menu on left mouse click instead of right
					};
					
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				document.oncontextmenu = function() {
					return localdata.default.contextMenu;
				};

				$(document).mousedown(function(e){
					if(e.button == 2) {
						var sel = window.getSelection ? window.getSelection() : document.selection.createRange(); // FF : IE
						console.log('Sel: ' + sel);
/*
						if(sel.getRangeAt){ // thats for FF
							var range = sel.getRangeAt(0);
							console.log('Range: ' + range);
						} else { //and thats for IE7
							console.log('htmlText: ' + sel.htmlText);
						}
*/





//						console.log(document.getSelection().toString());
						return false;
					}
					return true;
				});

				// create some stuff here

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
