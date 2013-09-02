/**
 *
 * Note - a jQuery-Plugin for a better handling with notes in notizblogg.
 *
 * @version 1.0
 *
 * @example:
 *  Create,update:
 *    jQuery.note('note','value');
 *  Delete:
 *    jQuery.note('note',null);
 *  Show:
 *    jQuery.note('note');
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
		note: function() {
			//Iterate over the current set of matched elements
			return this.each(function() {
			
			//code to be inserted here
			
			});
		}
    });
	
	
	
	
})(jQuery);


