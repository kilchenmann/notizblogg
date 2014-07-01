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
	// -------------------------------------------------------------------------
	// define the methods
	// -------------------------------------------------------------------------

	var methods = {
		/*========================================================================*/
		init: function(options) {
			return this.each(function() {
				var $this = $(this),
					localdata = {};

				localdata.expand = {};

				localdata.settings = {
					type: 'source', // or edit / delete
					noteid: '',
					sourceid: '',
					edit: false,
					content: ''
				};



				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);


				if(edit === false) {
					localdata.expand.edit_ele = $('<button>').addClass('btn grp_none fake_btn');
				} else {
					localdata.expand.edit_ele = $('<button>').addClass('btn grp_none toggle_edit');
				}

				localdata.expand.tex_ele = $('<button>').addClass('btn grp_none toggle_cite');
				localdata.expand.col_ele= $('<button>').addClass('btn grp_none toggle_collapse');



				// set the title of the document and the project specific data

				$this.click(function() {



					localdata.expand.frame = $('.float_obj.large.pamphlet').empty();

					localdata.expand.frame
						.append(
						$('<h3>').html()
					)
						.append(
						$('<div>').addClass('text').html(localdata.settings.content.text)
					)
						.append(
						$('<div>').addClass('latex').html(localdata.settings.content.latex)
					)
						.append(
						$('<div>').addClass('label').html(localdata.settings.content.label)
					)
						.append(
						$('<div>').addClass('tools').css({opacity: '1'})
							.append(
							$('<div>').addClass('left').append(localdata.expand.edit_ele)
						)
							.append(
							$('<div>').addClass('center').append(localdata.expand.tex_ele)
						)
							.append(
							$('<div>').addClass('right').append(localdata.expand.col_ele)
						)
					);

					localdata.expand.frame.toggle();
					$('.viewer').css({'opacity': '0.3'});

					if(localdata.expand.frame.is(':visible')) {
						$('button.toggle_add').toggleClass('toggle_add toggle_delete');
						localdata.expand.col_ele.click(function() {
							localdata.expand.frame.toggle();
							$('.viewer').css({'opacity': '1'});
							localdata.expand.frame.empty();
							$('button.toggle_delete').toggleClass('toggle_add toggle_delete');
						})
					} else {
						$('button.toggle_delete').toggleClass('toggle_add toggle_delete');
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



	$.fn.expand = function(method) {
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
