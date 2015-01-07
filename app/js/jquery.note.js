/* ===========================================================================
 *
 * @frame: jQuery plugin for notizblogg
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

(function($) {
	// -----------------------------------------------------------------------
	// define some functions
	// -----------------------------------------------------------------------
	var createNote = function(ele) {
			var note_ele = {};
			ele.append(
					note_ele.media = $('<div>').addClass('media')
				)
				.append(
					note_ele.content = $('<div>').addClass('text')
				)
				.append(
					note_ele.content4tex = $('<div>').addClass('latex')
				)
				.append(
					$('<div>').addClass('label')
					.append(
						note_ele.label = $('<p>')
					)
				)
				.append(
					note_ele.tools = $('<div>').addClass('tools')
				);
			return note_ele;
		},

		createLabel = function(label, label_ele) {
			var size;
			if (label.length > 0) {
				var lh = ((label.length - 0.5) * 10) + 14;
				label_ele.parent().css({
					height: lh
				});
				$.each(label, function(i, noteLabel) {
					// three sizes: small, medium, large ???
					if (noteLabel.num <= 11 && noteLabel.num > 0) size = 'small';
					if (noteLabel.num > 11 && noteLabel.num <= 22) size = 'medium';
					if (noteLabel.num > 22) size = 'large';
					label_ele.append(
						$('<a>').attr({
							href: '?label=' + noteLabel.id,
							title: noteLabel.name + ' (' + noteLabel.num + ')'
						}).html(' ' + noteLabel.name + ' ').addClass('tag_size ' + size)
					);
				});
			} else {
				label_ele.parent().empty().css({
					height: '5px'
				});
			}
		},

		createTool = function(tools_ele, localdata) {
			tools_ele.each(function() {
				var tools = $(this),
					note = tools_ele.parent($('.note')),
					nID = note.attr('id'),
					sID = tools.attr('id'),
					edit_ele,
					tex_ele,
					exp_ele,
					type,
					divs = note.contents(),
					edit;
				var note_obj = {};
				for (var i = 0; i < divs.filter("div").length; i++) {
					var ele;
					switch (i) {
						case 0:
							ele = 'media';
							break;
						case 1:
							ele = 'text';
							break;
						case 2:
							ele = 'latex';
							break;
						case 3:
							ele = 'label';
							break;
						case 4:
							ele = 'tools';
							break;
						default:
							ele = 'empty';
					}
					note_obj[ele] = divs[i].innerHTML;
				}

				if (note.hasClass('topic') && nID === sID) {
					type = 'source';
				} else {
					type = 'note';
				}
				edit = false;
				edit_ele = $('<button>').addClass('btn grp_none fake_btn');
				/*
console.log(localdata.settings.access);
				if (localdata.settings.access === '1') {
					edit = false;
					edit_ele = $('<button>').addClass('btn grp_none fake_btn');
				} else {
					edit = true;
					edit_ele = $('<button>').addClass('btn grp_none edit').on('click', function() {
						edit_ele.note('edit', nID);
					});
				}
				*/

				if (note.find('.latex').length > 0) {
					tex_ele = $('<button>').addClass('btn grp_none calendar').click(function() {
						$(this).toggleClass('comment');
						note.find('.text').toggle();
						note.find('.latex').toggle();
					});
					exp_ele = $('<button>').addClass('btn grp_none fake_btn');
					/*
					exp_ele = $('<button>').addClass('btn grp_none expand').expand({
						type: type,
						noteID: nID,
						sourceID: sID,
						edit: edit,
						data: note_obj,
						show: 'booklet'
					});
					*/
				} else {
					tex_ele = $('<button>').addClass('btn grp_none fake_btn');
					exp_ele = $('<button>').addClass('btn grp_none fake_btn');
				}

				tools
					.append(
						$('<div>').addClass('left').append(edit_ele).click(function() {
							if (jQuery.inArray('text', divs)) {}
						})
					)
					.append(
						$('<div>').addClass('center').append(tex_ele)
					)
					.append(
						$('<div>').addClass('right').append(exp_ele)
					);
			});
		},

		dispNote = function(ele, data, localdata) {
			var note = {};
			var source = {};
			var note_id = data.note.id;
			var source_id;
			var foot = {};

			if (note_id !== 0) {
				var note_ele = ele.addClass('item').attr({
					'id': note_id
				});
				note = createNote(note_ele);
				if (data.note.public === '0') {
					note_ele.addClass('private');
				}
				if (!$.isEmptyObject(data.note.media.html)) {
					note.media
						.empty()
						.html(data.note.media.html);
					note.content.css({
						'border-radius': '0px',
						'&:hover': {
							'border-radius': '0px'
						}
					});
				}
				note.content
					.append($('<h3>').html(data.note.title))
					.append($('<h4>').html(data.note.subtitle))
					.append($('<p>').html(data.note.comment.replace(/\r\n/g, '<br>')));
				note.content4tex
					.append($('<p>').html(html2tex(data.note.comment4tex)));

				// (1) do we need a footnote and (2) have we done already a request for the right footnote?
				source_id = data.note.source.id;
				if (source_id > 0) {
					var pageHere, page, fc, fn, url;
					fc = data.note.source.name; // footcite in latex
					fn = fc;
					/*
					url = NB.api + '/get.php?source=' + source_id;

					if (!(source_id in localdata.footnote)) {
						localdata.footnote[source_id] = {};
						$.ajax({
							'async': false,
							'global': false,
							'url': url,
							'dataType': 'json',
							'success': function (sourcedata) {
								foot = getSource(sourcedata);
								fn = foot.footnote;
							}
						});
						localdata.footnote[source_id].fn = fn;
						localdata.footnote[source_id].fc = fc;
					} else {
						setTimeout(function(){
							fn = localdata.footnote[source_id].fn;
							fc = localdata.footnote[source_id].fc;
						}, 300);
					}
					*/

					if (data.note.page.start !== '0') {
						page = '[' + data.note.page.start + ']';
						var dif = Math.round(data.note.page.end - data.note.page.start);
						if (dif > 0) {
							page = '[' + data.note.page.start + '-' + data.note.page.end + ']';
						}
						//fn = fn + ' S. ' + page;
					}

					if (page === undefined) page = '[]';

					note.content4tex
						.append($('<p>')
							.addClass('footnote bibtex')
							.html('\\cite' + page)
							.append($('<a>')
								.attr({
									href: '?source=' + data.note.source.id
								})
								.html('{' + fc + '}')
							)
						);
					//						.html('\\footcite[' + page + ']{<a href=\'?source=' + data.note.source.id + '\'>' + fc + '</a>}'));

					note.content
						.append($('<p>')
							.addClass('footnote biblio')
							.append($('<a>')
								.attr({
									href: '?source=' + data.note.source.id
								})
								.html(fn + ' ' + page)
							)
						);
				}
				if (data.note.source.link !== '' && data.note.source.link !== null) {
					var prot = '';
					var ref = data.note.source.link;
					var start = ref.charAt(0) + ref.charAt(1) + ref.charAt(2) + ref.charAt(3);
					if(start !== 'http') prot = 'http://';
					note.content
						.append($('<p>')
							.addClass('footnote link')
							.append($('<a>')
								.attr({
									href: prot + data.note.source.link,
									target: '_blank'
								})
								.html(data.note.source.link)
							)
						);
				}

				var latex, tex_ele, classNote;
				if (data.note.biblio !== null) {
					latex = html2tex(data.note.comment4tex);
					tex_ele = $('<button>').addClass('btn grp_none comment').click(function() {
						$(this).toggleClass('calendar');
						$note.children('.text').toggle();
						$note.children('.latex').toggle();
					});
				} else {
					latex = '';
					tex_ele = $('<button>').addClass('btn grp_none fake_btn');
				}
				if (data.type === 'source') {
					classNote = 'note topic';
				} else {
					classNote = 'note item';
				}

				// label ele
				createLabel(data.note.label, note.label);

				// tools ele
				createTool(note.tools, localdata);

				/*
									var active = {};
									$('div.note')
									.mouseenter(function (e) {
									active = activator($(this));
								})
								.on('touchstart', function () {
								active = activator($(this));
							})

							.hover(function () {

						})

						.mouseleave(function (e) {
						$(this).toggleClass('active');
						$(this).children('div.media').css({'opacity': '0.8'});
						$(this).children('div.label').css({'opacity': '0.8'});
						$(this).children('div.tools').css({'opacity': '0.1'});
					})
					.on('touchend', function () {

				});
				*/
			} else {
				if ($('div.note').length === 0) {
					$('.wrapper').warning({
						type: 'noresults',
						lang: 'de'
					});
					$('body').on('click', function() {
						window.location.href = NB.url;
					});
				}
			}
			//		return (media, text, latex, label, tools);
		},

		dispBib = function(ele, data, localdata) {
			var note = {};
			var source = {};
			var source_id = data.source.id;
			var biblio, bibtex;

			if (source_id !== 0) {
				var note_ele = ele.addClass('item topic').attr({
					'id': source_id
				});
				note = createNote(note_ele);
				if (data.source.public === '0') {
					note_ele.addClass('private');
				}

				var showsource = getSource(data);
				biblio = showsource.biblio;
				bibtex = showsource.bibtex;

				note.media
					.html(data.source.media.html);
				/*
								note.content
									.append($('<p>').html(bibtex));
								note.content4tex
									.append($('<p>').html(biblio));
				*/
				if (data.source.bibTyp.id === '0' && data.source.bibTyp.name === '') {
					note.content
						.append($('<p>').html(bibtex));
					note.content4tex
						.append($('<p>').html(biblio));
				} else {
					note.content
						.append($('<p>').html(biblio));
					note.content4tex
						.append($('<p>').html(bibtex));
				}

				// label ele
				createLabel(data.source.label, note.label);
				// tools ele
				createTool(note.tools, localdata);
			} else {
				$('.wrapper').warning({
					type: 'noresults',
					lang: 'de'
				});
				$('body').on('click', function() {
					window.location.href = NB.url;
				});
			}

			//console.log(data);
			/*
			var note = {};
			var source = {};
			var source_id = data.note.id;

			//if (source_id !== 0) {
			var note_ele = ele.addClass('item topic').attr({'id': source_id});
			if (data.note.public === 0) {
			note_ele.addClass('private');
		}
		note = createNote(note_ele);
		*/
			if (source.id !== 0) {


			} else {
				//		bibtex = 'The data are not yet ready to use in laTex.';
				//		biblio = '<a href=\'?source=' + data.source.id + '\' >' + data.source.comment + '</a>';
			}
		};

	// -----------------------------------------------------------------------
	// define the methods
	// -----------------------------------------------------------------------

	var methods = {
		/*====================================================================== */
		init: function(options) {
			return this.each(function() {
				var $this = $(this),
					localdata = {},
					url;
				localdata.view = {};
				localdata.settings = {
					access: 1,
					uri: undefined,
					user: {
						id: undefined,
						name: undefined
					},
					query: {}
				};
				localdata.footnote = {};
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				$this.append(
					localdata.view.container = $('<div>')
				);
				var i = 0;
				switch (localdata.settings.query[i]) {
					case 'label':
					case 'author':
						// wall
						localdata.view.container.addClass('wall');
						url = NB.api + '/get.php?' + localdata.settings.query[i] + '=' + localdata.settings.query[localdata.settings.query[i]];

						$.getJSON(url, function(list) {
							var name = htmlDecode(decodeURI(list.name));
							$('input.search_field').attr({
								value: name
							}).html(name);
							$.each(list.notes, function(i, note) {
								if (note.ac >= localdata.settings.access) {
									url = NB.api + '/get.php?id=' + note.id;
									$.getJSON(url, function(data) {
										localdata.view.container.append(
											localdata.view.note = $('<div>').addClass('note')
										);
										for (var key in data) {
											if (key === 'source') {
												dispBib(localdata.view.note, data, localdata);
											} else {
												dispNote(localdata.view.note, data, localdata);
											}
										}
									});
								}
							});
						});

						break;
					case 'source':
						// desk
						localdata.view.container.addClass('desk')
							.append(localdata.view.left = $('<div>').addClass('left_side'))
							.append(localdata.view.right = $('<div>').addClass('right_side')
								.append(localdata.view.container = $('<div>').addClass('container'))
							);
						url = NB.api + '/get.php?source=' + localdata.settings.query[localdata.settings.query[i]];
						$.getJSON(url, function(data) {
							localdata.view.left.append(
								localdata.view.source = $('<div>').addClass('note')
							);
							dispBib(localdata.view.source, data, localdata);
							localdata.view.left.center('horizontal').css({
								'position': 'relative'
							});
							localdata.view.right.center('horizontal').css({
								'position': 'relative'
							});
							$.each(data.source.notes, function(i, note) {
								if (note.ac >= localdata.settings.access) {
									localdata.view.container.append(
										$('<div>').addClass('note').attr({
											'id': note.id
										})
									);
									url = NB.api + '/get.php?note=' + note.id;
									$.getJSON(url, function(data) {
										for (var key in data) {
											localdata.view.note = $('div#' + note.id + '.note');
											if (key === 'source') {
												//localdata.view.container.addClass('desk');
												dispBib(localdata.view.note, data, localdata);
											} else {
												//localdata.view.container.addClass('booklet');
												dispNote(localdata.view.note, data, localdata);
											}
										}
									});
								}
							});
						});
						break;
					case 'collection':
						// desk
						localdata.view.container.addClass('desk')
							.append(localdata.view.left = $('<div>').addClass('left_side'))
							.append(localdata.view.right = $('<div>').addClass('right_side')
								.append(localdata.view.container = $('<div>').addClass('container'))
							);
						url = NB.api + '/get.php?source=' + localdata.settings.query[localdata.settings.query[i]];
						$.getJSON(url, function(data) {
							localdata.view.left.append(
								localdata.view.collection = $('<div>').addClass('note')
							);
							dispBib(localdata.view.collection, data, localdata);
							localdata.view.left.center('horizontal').css({
								'position': 'relative'
							});
							localdata.view.right.center('horizontal').css({
								'position': 'relative'
							});
							// find "insources" with the reference to this collection
							$.each(data.source.insource, function(i, bibID) {
								localdata.view.container.append(
									$('<div>').addClass('note topic').attr({
										'id': bibID
									})
								);
								url = NB.api + '/get.php?source=' + bibID;
								$.getJSON(url, function(data) {
									for (var key in data) {
										localdata.view.note = $('div#' + bibID + '.note');
										if (key === 'source') {
											//localdata.view.container.addClass('desk');
											dispBib(localdata.view.note, data, localdata);
										} else {
											//localdata.view.container.addClass('booklet');
											dispNote(localdata.view.note, data, localdata);
										}
									}
								});
							});
						});
						break;
					case 'note':
						// booklet
						url = NB.api + '/get.php?note=' + localdata.settings.query[localdata.settings.query[i]];
						$.getJSON(url, function(data) {
							localdata.view.container.append(
								localdata.view.note = $('<div>').addClass('note').attr({
									'id': localdata.settings.query[localdata.settings.query[i]]
								})
							);
							for (var key in data) {
								if (key === 'source') {
									localdata.view.container.addClass('desk');
									dispBib(localdata.view.note, data, localdata);
								} else {
									localdata.view.container.addClass('booklet');
									dispNote(localdata.view.note, data, localdata);
								}
							}
						});
						break;
					case 'q':
						var url2;
						var q = localdata.settings.query[localdata.settings.query[i]];
						var qstring = htmlDecode(decodeURI(q));

						if (localdata.settings.query.length > 1) {
							q += '&filter=' + localdata.settings.query[localdata.settings.query[i + 1]];
							$('select.search_filter').val(
								localdata.settings.query[localdata.settings.query[i + 1]]
							);
						}
						$('input.search_field').attr({
							value: qstring
						});
						url = NB.api + '/get.php?q=' + q;
						$.getJSON(url, function(search) {
							$.each(search.notes, function(i, noteID) {
								url2 = NB.api + '/get.php?note=' + noteID;
								$.getJSON(url2, function(data) {
									localdata.view.container.append(
										localdata.view.note = $('<div>').addClass('note').attr({
											'id': noteID
										})
									);
									for (var key in data) {
										if (key === 'source') {
											localdata.view.container.addClass('wall');
											dispBib(localdata.view.note, data, localdata);
										} else {
											localdata.view.container.addClass('wall');
											dispNote(localdata.view.note, data, localdata);
										}
									}
								});
							});
						});
						break;
					default:
						localdata.view.container.addClass('wall');
						url = NB.api + '/get.php?new=25';
						$.getJSON(url, function(list) {
							$.each(list.notes, function(i, noteID) {
								url = NB.api + '/get.php?id=' + noteID;
								$.getJSON(url, function(data) {
									localdata.view.container.append(
										localdata.view.note = $('<div>').addClass('note')
									);
									for (var key in data) {
										if (key === 'source') {
											dispBib(localdata.view.note, data, localdata);
										} else {
											dispNote(localdata.view.note, data, localdata);
										}
									}
								});
							});
						});
				}
				$(window).load(function() {

					if ($('.desk').length !== 0) {

					}
				});

				if (localdata.settings.type === 'source') {
					$.getJSON(NB.api + '/get.php?' + localdata.settings.type + '=' + localdata.settings.id, function(data) {
						$this.empty();
						$this.append(
								$('<div>').addClass('text')
								.append((showBib(data).biblio))
							)
							.append(
								$('<div>').addClass('latex')
								.append((showBib(data).bibtex))
							);
					});

				} else {

				}

			}); // end "return this.each"
		}, // end "init"

		edit: function(data) {
			return this.each(function() {
				var $this = $(this);
				var localdata = $this.data('localdata');
				console.log(data);
				//console.log(localdata);
			});
		},

		biblatex: function(options) {
			return this.each(function() {
				var $this = $(this),
					localdata = {};
				localdata.settings = {
					access: 1,
					uri: undefined,
					user: {
						id: undefined,
						name: undefined
					},
					query: {}
				};
				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				var source, showsource;

				url = NB.api + '/get.php?list=biblatex';
				$.getJSON(url, function(data) {
					$.each(data.notes, function(i, sourceid) {
						url2 = NB.api + '/get.php?source=' + sourceid;
						$.getJSON(url2, function(sourcedata) {
							$('.paper').append(
								source = $('<div>').addClass('note')
							);

							showsource = getSource(sourcedata, 'bibtex');
							if (sourcedata.source.bibTyp.id !== '0' && sourcedata.source.bibTyp.name !== '') {
								if (i < (data.notes.length - 1)) {
									source.append($('<div>').addClass('text')
										.append($('<p>').html(showsource.bibtex + ','))
									);
								} else {
									source.append($('<div>').addClass('text')
										.append($('<p>').html(showsource.bibtex))
									);
								}
							}
						});
					});
				});
			});
		},

		setNote2Wall: function() {
			return this.each(function() {
				var $this = $(this);
				var localdata = $this.data('localdata');
				var win_width = $(window).width();
				//	if($('.wall').length !== 0) {
				var wall = $(this);
				var note_width = wall.find('.note').width();
				//		console.log('note: ' + note_width + ' window: ' + win_width)
				var num_col = Math.floor(win_width / note_width);
				wall.css({
					'-webkit-column-count': num_col,
					'-moz-column-count': num_col,
					'column-count': num_col,
					'width': num_col * note_width
				});
				//		console.log(num_col);
				//	}

			});
		},

		anotherMethod: function() {
				return this.each(function() {
					var $this = $(this);
					var localdata = $this.data('localdata');
				});
			}
			/*========================================================================*/
	};


	$.fn.note = function(method) {
		// Method calling logic
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			throw 'Method ' + method + ' does not exist on jQuery.note';
		}
	};
})(jQuery);
