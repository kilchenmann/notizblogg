/* ===========================================================================
 *
 * @frame: jQuery plugin lookFor: mark some text and look for this content on
 * 			other websites like Wikipedia, Google a.s.o.
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
	var hideMenu = function() {
			$('.context-menu:visible').each(function() {
				$(this).trigger("closed");
				$(this).hide();
				$('body').unbind('click', hideMenu);
			});
		},

		showMenu = function(localdata){


			if(localdata.settings.content === '') {
				alert('etwas auswählen');
			} else {
				console.log('content:  \'' + localdata.settings.content + '\'');
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
						content: ''
					};



					localdata.default = {
						contextMenu: false, // disables the native contextmenu everywhere you click
						leftClick: false // show menu on left mouse click instead of right
					};

				localdata.content = {};

				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);


				document.oncontextmenu = function() {
					return localdata.default.contextMenu;
				};

				$(document).bind('contextmenu', function(e) {
					if (localdata.default.contextMenu) {
						e.preventDefault();
					}
					hideMenu();
				});




				$(document).mousedown(function(e){

					localdata.settings.content = window.getSelection ? window.getSelection() : document.selection.createRange(); // FF : IE

					if(e.button == 2) {
						console.log(localdata.settings.content);

						if(localdata.settings.content !== '') {
							console.log('mark: ' + localdata.settings.content);
						} else {
							console.log(localdata.settings.content);

						}




						if (typeof localdata.settings.content === undefined) {
						//	alert('nothing');
						} else {
						//	localdata.settings.content = $this.val();
						//	alert(localdata.settings.content);
						}

	//					showMenu(localdata);
						//console.log('Sel: ' + sel);

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
