/**
 *
 * select - a jQuery-Plugin for a better handling with selects in notizblogg.
 *
 * @version 1.0
 *
 * @example:
 *  Show:
 *    jQuery.select('table');
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

	$.fn.livesearch = function(table){
		var i = 1;
		var tableName = table + "Name";
		var selectName = "select" + table + i;
		var inputName = "input" + table + i;
		var options = "<?php echo formSelect(; ?> " + table + " <?php echo ); ?>";
		
		this.append(
				$('<select>').attr({'name': selectName }).addClass(selectName + ' smalldown')
				.append('<option>').text(options)
			)
		.append(
				$('<input>').attr({'name': inputName, 'placeholder': i + '. ' + table, 'required' : 'required' }).addClass(inputName + ' newselect')
			)
			//formSelect($table);

		return this;
	};








}(jQuery));


