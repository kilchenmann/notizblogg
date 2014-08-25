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

(function ($) {
	// -----------------------------------------------------------------------
	// define some functions
	// -----------------------------------------------------------------------
	var getLastChar = function (string) {
			var lastChar = string.substr(string.length - 1);
			if ((lastChar !== '?') && (lastChar !== '!')) {
				string += '.';
			}
			return(string + ' ');
		},
		activator = function (element) {
			$('div.media').css({'opacity': '0.8'});
			element.toggleClass('active');
			element.children('div.media').css({'opacity': '1'});
			element.children('div.label').css({'opacity': '1'});
			element.children('div.tools').css({'opacity': '1'});
			var type = undefined,
				typeID = undefined;
			if (!element.attr('id')) {
				// title element
				type = 'title';
				typeID = 0;
			} else {
				if (element.hasClass('topic')) {
					type = 'source';
				} else {
					type = 'note';
				}
				typeID = element.attr('id');
			}
			//var activeNote = $('.active .tools button').attr('id');
			var activeNote = {
				type: type,
				id: typeID
			};
			return(activeNote);
		},
		getSource = function(id, localdata) {
			var i, authors, locations, biblio, bibtex;
			// return source.biblio, source.bibtex;
			var url = localdata.settings.url + '/get/source/' + id;
			$.getJSON(url, function (data) {
				source = data.source;
				bibtex = '@' + source.bibTyp.name + '{' + source.name + '<br>';
				biblio = '';
				// authors
				i = 0;
				while (i < source.author.length) {
					if (authors === undefined) {
						authors = '<a href=\'?author=' + source.author[i].id + '\'>' + source.author[i].name + '</a>';
					} else {
						authors += ', <a href=\'?author=' + source.author[i].id + '\'>' + source.author[i].name + '</a>';
					}
					i += 1;
				}
				// authors
				if (data.editor === 1) {
					bibtex += 'editor = {' + authors + '},<br>';
					biblio += authors + '(Hg.): ';
				} else {
					bibtex += 'author = {' + authors + '},<br>';
					biblio += authors + ': ';
				}
				// title
					bibtex += 'title = {' + source.title + '},<br>';
				if (source.bibTyp.name === 'collection' || source.bibTyp.name === 'proceedings' || source.bibTyp.name === 'book') {
					biblio += '<a href=\'?collection=' + source.id + '\' >' + getLastChar(source.title) + '</a> ';
				} else {
					biblio += '<a href=\'?source=' + source.id + '\' >' + getLastChar(source.title) + '</a> ';
				}
				if (source.subtitle !== '') {
					bibtex += 'subtitle = {' + source.subtitle + '},<br>';
					biblio += getLastChar(source.subtitle);
				}
				// locations
				i = 0;
				while (i < source.location.length) {
					if (locations === undefined) {
						locations = source.location[i].name;
					} else {
						locations += ', ' + source.location[i].name;
					}
					i += 1;
				}


				if ('detail' in data.source) {
					var detailKey, countDetail = Object.keys(source.detail).length;
					var crossref = {};
					var pages;
					i = 0;
					while (i < countDetail) {
						detailKey = Object.keys(source.detail)[i];
						switch (detailKey) {
							case 'url':
								bibtex += 'url = {<a target=\'_blank\' href=\'' + source.detail.url + '\' >' + source.detail.url + '</a>},<br>';
								biblio += ', URL: <a target=\'_blank\' href=\'' + source.detail.url + '\'>' + source.detail.url + '</a> ';
								break;

							case 'urldate':
								bibtex += 'urldate = {' + source.detail.urldate + '},<br>';
								biblio += '(Stand: ' + source.detail.urldate + ')';
								break;

							case 'pages':
								bibtex += 'pages = {' + source.detail.pages + '},<br>';
								pages = ', S. ' + source.detail.pages;
								break;

							default:
								bibtex += detailKey + ' = {' + source.detail.detailKey + '},<br>';
								biblio += source.detail.detailKey;
						}
						i += 1;
					}

				}
				if ('crossref' in source.detail || crossref === undefined) {
					bibtex += 'crossref = {' + source.detail.crossref.name + '},<br>';
					//crossref = getSource(source.detail.crossref.id, localdata);
					biblio += 'In: ' + getSource(source.detail.crossref.id, localdata) + ', ';
				} else {
					if (locations !== undefined) {
						bibtex += 'location = {' + locations + '},<br>';
						biblio += locations + ', ';
					}
					if (source.date.year !== '0000') {
						bibtex += 'year = {' + source.date.year + '},<br>';
						biblio += source.date.year;
					}
				}

				bibtex += 'note = {' + source.comment + '}}';
				biblio += '.';
/*
				var source = {
					biblio: biblio,
					bibtex: bibtex
				};
				*/
//				console.log(source.biblio);
//				source.shortBib = bib.source.name;



				console.log(biblio);


				return (biblio);
			});
		},

		dispNote = function (ele, data, localdata) {
			var note = data.note.id,
				media = data.note.media,
				title = data.note.title,
				subtitle = data.note.subtitle,
				text = data.note.comment,
				latex = data.note.comment4tex,
				sourceID = data.note.source.id,
				source = {},
				label,
				tools;

			if(note !== 0) {
				ele.append(
					note = $('<div>').addClass('note item').attr({'id': note})
						.append(
						media = $('<div>').addClass('media').html(media)
					)
						.append(
						text = $('<div>').addClass('text')
							.append($('<h3>').html(title))
							.append($('<h4>').html(subtitle))
							.append($('<p>').html(text))
					)
						.append(
						latex = $('<div>').addClass('latex')
							.append($('<h3>').html(title))
							.append($('<h4>').html(subtitle))
							.append($('<p>').html(latex))
					)
						.append(
						$('<div>').addClass('label')
							.append(
							label = $('<p>')
						)
					)
						.append(
						tools = $('<div>').addClass('tools')
					)
				);
				if(data.note.public === 0) {
					label.addClass('private');
				}
				if(sourceID !== 0){
					source = getSource(sourceID, localdata);
					console.log(source);

					//text.append(source.biblio);
					latex.append(data.note.source.name);
				}



			} else {
				$('#fullpage').warning({
					type: 'noresults',
					lang: 'de'
				});
				$('body').on('click', function () {
					window.location.href = localdata.settings.url;
				})
			}





			var classNote, classLabel;
			if (data.note.id !== 0) {
				if (data.note.biblio !== null) {
					latex = data.note.comment4tex;
					tex_ele = $('<button>').addClass('btn grp_none toggle_cite').click(function () {
						$(this).toggleClass('toggle_comment');
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
				$.each(data.note.label, function (i, noteLabel) {
					label.append(
						$('<a>').attr({href: '?label=' + noteLabel.id, title: noteLabel.name}).html(' ' + noteLabel.name)
					)
				});

				tools.each(function () {
					var $tools = $(this),
						$note = $tools.parent($('.note')),
						nID = $note.attr('id'),
						sID = $tools.attr('id'),
						edit_ele,
						tex_ele,
						exp_ele,
						type,
						divs = $note.contents(),
						edit;
			//		localdata.settings.access = '<?php echo $access; ?>';

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

					if (localdata.settings.access === '1') {
						edit = false;
						edit_ele = $('<button>').addClass('btn grp_none fake_btn');
					} else {
						edit = true;
						edit_ele = $('<button>').addClass('btn grp_none toggle_edit').expand({
							type: type,
							noteID: nID,
							sourceID: sID,
							edit: edit,
							data: note_obj,
							show: 'form'
						});

					}

					if ($note.children('.latex').length > 0) {
						tex_ele = $('<button>').addClass('btn grp_none toggle_cite').click(function () {
							$(this).toggleClass('toggle_comment');
							$note.children('.text').toggle();
							$note.children('.latex').toggle();
						});
						exp_ele = $('<button>').addClass('btn grp_none toggle_expand').expand({
							type: type,
							noteID: nID,
							sourceID: sID,
							edit: edit,
							data: note_obj,
							show: 'booklet'
						});
					} else {
						tex_ele = $('<button>').addClass('btn grp_none fake_btn');
						exp_ele = $('<button>').addClass('btn grp_none fake_btn');
					}

					$tools
						.append(
						$('<div>').addClass('left').append(edit_ele).click(function () {
							if (jQuery.inArray('text', divs)) {
								//console.log(note_obj);

							}
						})
					)
						.append(
						$('<div>').addClass('center').append(tex_ele)
					)
						.append(
						$('<div>').addClass('right').append(exp_ele)
					);

					//		console.log(note_obj);

				});

				var active = {};
				$('div.note')
					.mouseenter(function (e) {
						active = activator($(this));
					})
					.on('touchstart', function () {
						active = activator($(this));
					})

					.hover(function () {
						/*
						 if($(this).hasClass('active')) {

						 }
						 */
					})

					.mouseleave(function (e) {
						$(this).toggleClass('active');
						$(this).children('div.media').css({'opacity': '0.8'});
						$(this).children('div.label').css({'opacity': '0.8'});
						$(this).children('div.tools').css({'opacity': '0.1'});
					})
					.on('touchend', function () {

					});

			} else {

			}

			return (media, text, latex, label, tools);
		},

		dispBib = function (ele, data, localdata) {
			var authors, locations, bibtex, biblio, i;
			var classNote, classLabel;

			if (data.source.id !== '0') {
				authors = '';
				locations = '';
				bibtex = '@' + data.source.bibTyp.name + '{' + data.source.name + '<br>';
				biblio = '';

				if (data.type === 'source') {
					classNote = 'note topic';
				} else {
					classNote = 'note item';
				}

				if (data.source.public === '1') {
					classLabel = 'label'
				} else {
					classLabel = 'label private'
				}
				i = 0;
				while (i < data.source.author.length) {
					if (authors === '') {
						authors = '<a href=\'?author=' + data.source.author[i].id + '\'>' + data.source.author[i].name + '</a>';
					} else {
						authors += ', <a href=\'?author=' + data.source.author[i].id + '\'>' + data.source.author[i].name + '</a>';
					}
					i += 1;
				}

				i = 0;
				while (i < data.source.location.length) {
					if (locations === '') {
						locations = data.source.location[i].name;
					} else {
						locations += ', ' + data.source.location[i].name;
					}
					i += 1;
				}
				if (data.editor === 1) {
					bibtex += 'editor = {' + authors + '},<br>';
					biblio += authors + '(Hg.): ';
				} else {
					bibtex += 'author = {' + authors + '},<br>';
					biblio += authors + ': ';
				}


			ele.append(
				note = $('<div>').addClass('note topic').attr({'id': data.source.id})
					.append(
					media = $('<div>').addClass('media').html(data.source.media)
				)
					.append(
					text = $('<div>').addClass('text').html(biblio)
				)
					.append(
					latex = $('<div>').addClass('latex').html(bibtex)
				)
					.append(
					$('<div>').addClass(classLabel)
						.append(
						label = $('<p>')
					)
				)
					.append(
					tools = $('<div>').addClass('tools')
				)
			);

			// label ele
			$.each(data.source.label, function (i, noteLabel) {
				label.append(
					$('<a>').attr({href: '?label=' + noteLabel.id, title: noteLabel.name}).html(' ' + noteLabel.name)
				)
			});

			tools.each(function () {
				var $tools = $(this),
					$note = $tools.parent($('.note')),
					nID = $note.attr('id'),
					sID = $tools.attr('id'),
					edit_ele,
					tex_ele,
					exp_ele,
					type,
					divs = $note.contents(),
					edit;
				//		localdata.settings.access = '<?php echo $access; ?>';

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

				if (localdata.settings.access === '1') {
					edit = false;
					edit_ele = $('<button>').addClass('btn grp_none fake_btn');
				} else {
					edit = true;
					edit_ele = $('<button>').addClass('btn grp_none toggle_edit').expand({
						type: type,
						noteID: nID,
						sourceID: sID,
						edit: edit,
						data: note_obj,
						show: 'form'
					});

				}

				if ($note.children('.latex').length > 0) {
					tex_ele = $('<button>').addClass('btn grp_none toggle_cite').click(function () {
						$(this).toggleClass('toggle_comment');
						$note.children('.text').toggle();
						$note.children('.latex').toggle();
					});
					exp_ele = $('<button>').addClass('btn grp_none toggle_expand').expand({
						type: type,
						noteID: nID,
						sourceID: sID,
						edit: edit,
						data: note_obj,
						show: 'booklet'
					});
				} else {
					tex_ele = $('<button>').addClass('btn grp_none fake_btn');
					exp_ele = $('<button>').addClass('btn grp_none fake_btn');
				}

				$tools
					.append(
					$('<div>').addClass('left').append(edit_ele).click(function () {
						if (jQuery.inArray('text', divs)) {
							//console.log(note_obj);

						}
					})
				)
					.append(
					$('<div>').addClass('center').append(tex_ele)
				)
					.append(
					$('<div>').addClass('right').append(exp_ele)
				);

				//		console.log(note_obj);

			});

			var active = {};
			$('div.note')
				.mouseenter(function (e) {
					active = activator($(this));
				})
				.on('touchstart', function () {
					active = activator($(this));
				})

				.hover(function () {
					/*
					 if($(this).hasClass('active')) {

					 }
					 */
				})

				.mouseleave(function (e) {
					$(this).toggleClass('active');
					$(this).children('div.media').css({'opacity': '0.8'});
					$(this).children('div.label').css({'opacity': '0.8'});
					$(this).children('div.tools').css({'opacity': '0.1'});
				})
				.on('touchend', function () {

				});

		} else {
		//		bibtex = 'The data are not yet ready to use in laTex.';
		//		biblio = '<a href=\'?source=' + data.source.id + '\' >' + data.source.comment + '</a>';
		$('#fullpage').warning({
			type: 'noresults',
			lang: 'de'
		});
		$('body').on('click', function () {
			window.location.href = localdata.settings.url;
		})
	}
			return({
				'biblio': biblio,
				'bibtex': bibtex
			});

		};


// php to js

	/*
	 if(array_key_exists('detail', data)) {

	 }


	 return array(bibtex,biblio);




	 */


	// -----------------------------------------------------------------------
	// define the methods
	// -----------------------------------------------------------------------

	var methods = {
		/*====================================================================== */
		init: function (options) {
			return this.each(function () {
				var $this = $(this),
					localdata = {},
					url;
				localdata.view = {};
				localdata.settings = {
					access: 1,
					url: 'https://www.notizblogg.ch',
					uri: undefined,
					user: {
						id: undefined,
						name: undefined
					},
					query: {
						id: undefined,
						type: undefined
					}
				};


				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				$this.append(
					localdata.view.container = $('<div>')
				);
				switch(localdata.settings.query.type) {
					case 'label':
					case 'author':
						// wall
						localdata.view.container.addClass('wall');
						url = localdata.settings.url + '/get/' + localdata.settings.query.type + '/' + localdata.settings.query.id;
						$.getJSON(url, function (list) {
							$('input.search_field').attr({value: list.name});
							$.each(list.notes, function (i, noteID) {
								url = localdata.settings.url + '/get/' + noteID;
								$.getJSON(url, function (data) {
									for(var key in data) {
										if(key === 'source') {
											dispBib(localdata.view.container, data, localdata);
										} else {
											dispNote(localdata.view.container, data, localdata);}
									}
								})
							})
						});
						break;
					case 'source':
						// desk
						localdata.view.container.addClass('desk');
						url = localdata.settings.url + '/get/source/' + localdata.settings.query.id;
						$.getJSON(url, function (data) {
							dispBib(localdata.view.container, data, localdata);
						});
						break;
					case 'note':
						// booklet
						url = localdata.settings.url + '/get/note/' + localdata.settings.query.id;
						$.getJSON(url, function (data) {
							for(var key in data) {
								if(key === 'source') {
									localdata.view.container.addClass('desk');
									dispBib(localdata.view.container, data, localdata);
								} else {
									localdata.view.container.addClass('booklet');
									dispNote(localdata.view.container, data, localdata);}
							}
						});
						break;
					default:
						localdata.view.container.addClass('wall');
				}





				// which data do we need?
				// 'api/get/[id]' brings all notes and sources with the [id]
				// 'api/get/label/[id]' brings a list of noteIDs with the label [id]
				// 'api/get/author([id]' brings a list of noteIDs with the author [id]

/*
				switch (localdata.settings.query.type) {
					case 'note':
						url = localdata.settings.url + '/get/note/' + localdata.settings.query.id;
						$.getJSON(url, function (data) {
//							$.each(data,function(i,note){
							dispNote(localdata.view.wall, data, localdata);

//							})

						});
						break;
					case 'source':
						url = localdata.settings.url + '/get/source/' + localdata.settings.query.id;
						$.getJSON(url, function (data) {
//							$.each(data,function(i,note){
							localdata.view.source = dispBib(localdata.view.wall, data, localdata);
							localdata.view.wall.append(localdata.view.source.biblio);

//							})

						});
						break;

					case 'label':
						url = localdata.settings.url + '/get/' + localdata.settings.query.type + '/' + localdata.settings.query.id;
						$.getJSON(url, function (list) {
							$.each(list.notes, function (i, noteID) {
								url = localdata.settings.url + '/get/' + noteID;
								$.getJSON(url, function (data) {
									dispNote(localdata.view.wall, data, localdata);
								})
							})
						});
						break;

					case 'author':
						url = localdata.settings.url + '/get/' + localdata.settings.query.type + '/' + localdata.settings.query.id;
						$.getJSON(url, function (list) {
							$.each(list.source, function (i, bibID) {
								url = localdata.settings.url + '/get/source/' + bibID;
								$.getJSON(url, function (data) {
//									$.each(data, function (i, note) {
									localdata.view.source = dispBib(localdata.view.wall, data, localdata);
									localdata.view.wall.append(localdata.view.source.biblio);
//									})
								})
							});
						});
						break;

					case 'collection':

						break;

					default:
						url = localdata.settings.url + '/get/new/100';
						$.getJSON(url, function (list) {
							$.each(list.notes, function (i, noteID) {
								url = localdata.settings.url + '/get/note/' + noteID;
								$.getJSON(url, function (data) {
//									$.each(data, function (i, note) {
									localdata.view.note = dispNote(localdata.view.wall, data, localdata);
									localdata.view.wall.append(localdata.view.source.biblio);
//									})
								})
							});
						});
				}
*/

				/*
				 $(".note")//.html($('<div>').addClass('note')
				 .append($('<h3>').html(note.title))
				 .append($('<p>').html(note.content))
				 .append($('<p>')
				 .append($('<a>').attr({href: '?type=note&part=category&id=' + note.category.id }).html(note.category.name))
				 .append($('<span>').html(' | '))
				 .append($('<a>').attr({href: '?type=note&part=project&id=' + note.project.id }).html(note.project.name))
				 );
				 //);
				 */


// warning if no note exist
				/*
				 if ($('.note').length === 0) {
				 $('#fullpage').warning({
				 type: 'noresults',
				 lang: 'de'
				 });
				 $('body').on('click', function(){
				 window.location.href = localdata.settings.url;
				 })
				 }
				 */


				if (localdata.settings.type === 'source') {
					$.getJSON('get/' + localdata.settings.type + '/' + localdata.settings.id, function (data) {
						$this.empty();
						$this.append(
							$('<div>').addClass('text')
								.append((showBib(data).biblio))
						)
							.append(
							$('<div>').addClass('latex')
								.append((showBib(data).bibtex))
						)
					})

				} else {

				}


			});											// end "return this.each"
		},												// end "init"


		setNote2Wall: function () {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				var win_width = $(window).width();
				//	if($('.wall').length !== 0) {
				var wall = $(this);
				var note_width = wall.find('.note').width() + 60;
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

		anotherMethod: function () {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
			});
		}
		/*========================================================================*/
	};


	$.fn.shownote = function (method) {
		// Method calling logic
		if (methods[method]) {
			return methods[ method ].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			throw 'Method ' + method + ' does not exist on jQuery.tooltip';
		}
	};
})(jQuery);
