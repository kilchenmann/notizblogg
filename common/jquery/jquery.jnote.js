/**
 *
 * jNote - a jQuery-Plugin for a better handling with notes in notizblogg.
 *
 * @version 1.0
 *
 * @example:
 *  Create,update:
 *    jQuery.jNote('note','value');
 *  Delete:
 *    jQuery.jNote('note',null);
 *  Show:
 *    jQuery.jNote('note');
 *
 * Copyright (c) 2013 André Kilchenmann (milchkannen.ch)
 *
 * @author André Kilchenmann jquery@milchkannen.ch
 * @copyright André Kilchenmann (milchkannen.ch)
 *
 * @requires
 *  jQuery JavaScript Library - http://jquery.com/
 *
 */


(function($) {
    
    //Attach this new method to jQuery
    $.fn.extend({
        
		//This is where you write your plugin's name
		jnote: function() {
			//Iterate over the current set of matched elements
			return this.each(function() {
			
			//code to be inserted here
			
			});
		}
    });
	
	
	
	
})(jQuery);


