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

(function( $ ){
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
		init: function() {
			return this.each(function() {
				var $this = $(this),
					localdata = {};

				localdata.settings = {
					project: 'Notizblogg',			// default: Notizblogg
					logo: 'nb-logo.png',			// default: nb-logo.png
					user: undefined				// undefined = guest
				};

				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				console.log('text aus panel');


			});											// end "return this.each"
		},												// end "init"


        project: function() {
            return this.each(function() {
                var $this = $(this);
                var localdata = $this.data('localdata');
				alert('project from panel plugin ' + localdata.settings.project );
            });
        },

        search: function() {
            return this.each(function() {
                var $this = $(this);
                var localdata = $this.data('localdata');
            });
        },

        login: function() {
        	return this.each(function() {
        		var $this = $(this);
        		var localdata = $this.data('localdata');
        	});
        },

		anotherMethod: function() {
			return this.each(function(){
				var $this = $(this);
				var localdata = $this.data('localdata');
			});
		}
		/*========================================================================*/
	};



	$.fn.panel = function(method) {
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
