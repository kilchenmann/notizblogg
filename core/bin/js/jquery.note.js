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
		getAuthors = function (author) {
			// authors
			var i = 0, authors = undefined;
			if (author.length > 4) {
				authors = '<a href=\'?author=' + author[i].id + '\'>' + author[i].name + '</a> et al.';
			} else {
				while (i < author.length) {
					if (authors === undefined) {
						authors = '<a href=\'?author=' + author[i].id + '\'>' + author[i].name + '</a>';
					} else {
						authors += ', <a href=\'?author=' + author[i].id + '\'>' + author[i].name + '</a>';
					}
					i += 1;
				}
			}
			return authors;
		},
		getLocations = function (location) {
			// locations
			var i = 0, locations = undefined;
			if (location.length > 4) {
				locations = location[i].name + ' et al.';
			} else {
				while (i < location.length) {
					if (locations === undefined) {
						locations = location[i].name;
					} else {
						locations += ', ' + location[i].name;
					}
					i += 1;
				}
			}
			return locations;
		},
		getSource = function (data) {
			var i, authors, locations, biblio, bibtex, footnote, source, bib;
			// return source.biblio, source.bibtex;
			source = data.source;
			if(source.id !== 0) {
				bibtex = '@' + source.bibTyp.name + '{';

				if ((source.bibTyp.name === 'collection' || source.bibTyp.name === 'proceedings' || source.bibTyp.name === 'book') && jQuery.isEmptyObject(source.insource) === false) {
					bibtex += '<a href=\'?collection=' + source.id + '\' >' + source.name + '</a><br>';
				} else {
					bibtex += '<a href=\'?source=' + source.id + '\' >' + source.name + '</a><br>';
				}
				biblio = '';
				authors = getAuthors(source.author);
				locations = getLocations(source.location);

				// authors
				if (data.editor === 1) {
					bibtex += 'editor = {' + authors + '},<br>';
					biblio += authors + '(Hg.): ';
				} else {
					bibtex += 'author = {' + authors + '},<br>';
					biblio += authors;
				}
				// title
				bibtex += 'title = {' + source.title + '},<br>';
				if (source.title !== '') {
					if ((source.bibTyp.name === 'collection' || source.bibTyp.name === 'proceedings' || source.bibTyp.name === 'book')  && jQuery.isEmptyObject(source.insource) === false) {
						biblio += ': <a href=\'?collection=' + source.id + '\' >' + getLastChar(source.title) + '</a> ';
					} else {
						biblio += ': <a href=\'?source=' + source.id + '\' >' + getLastChar(source.title) + '</a> ';
					}
					footnote = biblio;
					if (source.subtitle !== '') {
						bibtex += 'subtitle = {' + source.subtitle + '},<br>';
						biblio += getLastChar(source.subtitle);
					}
				}
				if ('detail' in data.source) {
					var detailKey, countDetail = Object.keys(source.detail).length;
					//var crossref = [];
					var pages;
					i = 0;
					var insource = {};
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
							case 'crossref':
								var crossref = source.detail.crossref.source, crossTitle = '', crossAuthors, crossLocations;
								bibtex += 'crossref = {' + crossref.name + '},<br>';
								//console.log(crossref.author);
								crossAuthors = getAuthors(crossref.author);
								crossLocations = getLocations(crossref.location);
								if (crossref.bibTyp.name === 'collection' || crossref.bibTyp.name === 'proceedings' || crossref.bibTyp.name === 'book') {
									crossTitle = '<a href=\'?collection=' + crossref.id + '\' >' + getLastChar(crossref.title) + '</a> ';
								} else {
									crossTitle += '<a href=\'?source=' + crossref.id + '\' >' + getLastChar(crossref.title) + '</a> ';
								}
								if (crossref.subtitle !== '') {
									crossTitle += getLastChar(crossref.subtitle);
								}
								insource.source = 'In: ' + crossAuthors + ': ' + crossTitle + ' ' + crossLocations + ', ' + crossref.date.year;
								break;
							case 'pages':
								bibtex += 'pages = {' + source.detail.pages + '},<br>';
								insource.pages = ', S. ' + source.detail.pages;
								break;
							default:
								if(source.detail.detailKey !== undefined){
									bibtex += detailKey + ' = {' + source.detail.detailKey + '},<br>';
									biblio += source.detail.detailKey;
								}
						}
						i += 1;
					}
				}

				if (insource.source && insource.pages) biblio += insource.source + insource.pages;
				if (crossref === undefined) {
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
				//biblio += '';
				bib = {
					biblio: biblio,
					bibtex: bibtex,
					footnote: footnote
				};
			} else {
				bib = {
					biblio: undefined,
					bibtex: undefined,
					footnote: undefined
				}
			}
			return bib;
		},
		createNote = function(ele) {
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
		createToolBar = function(ele) {
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

		createLabel = function (label, label_ele) {
			var size;
			if(label.length > 0) {
				var lh = ((label.length - 0.5) * 10) + 14;
					label_ele.parent().css({height: lh});
				$.each(label, function (i, noteLabel) {
					// three sizes: small, medium, large ???
					if(noteLabel.num <= 11 && noteLabel.num > 0)	size = 'small';
					if(noteLabel.num > 11 && noteLabel.num <= 22)	size = 'medium';
					if(noteLabel.num > 22)	size = 'large';
					label_ele.append(
						$('<a>').attr({href: '?label=' + noteLabel.id, title: noteLabel.name + ' (' + noteLabel.num + ')'}).html(' ' + noteLabel.name + ' ').addClass('tag_size ' + size)
					);
				});
			} else {
				label_ele.parent().empty().css({height: '5px'});
			}
		},

		dispNote = function (ele, data, localdata) {
			var note = {};
			var source = {};
			var note_id = data.note.id;
			var source_id;

			if (note_id !== 0) {
				var note_ele = ele.addClass('item').attr({'id': note_id});
				note = createNote(note_ele);
				if (data.note.public === '0') {
					note_ele.addClass('private');
				}
				if(!$.isEmptyObject(data.note.media)) {
					note.media
						.empty()
						.html(data.note.media);
					note.content.css({'border-radius': '0px', '&:hover': {'border-radius': '0px'}});
				}
				note.content
					.append($('<h3>').html(data.note.title))
					.append($('<h4>').html(data.note.subtitle))
					.append($('<p>').html(data.note.comment));
				note.content4tex
					.append($('<p>').html(data.note.comment4tex));

				// (1) do we need a footnote and (2) have we done already a request for the right footnote?
				source_id = data.note.source.id;
				if (source_id > 0) {
					if(!(source_id in localdata.footnote)) {
						localdata.footnote[source_id] = {};
						var url = NB.api + '/get.php?source=' + source_id,
							foot = {},
							fc = data.note.source.name,				// footcite in latex
							fn = fc;								// footnote in text

						$.getJSON(url, function (sourcedata) {
							foot = getSource(sourcedata);
							if(foot.footnote !== undefined) {
								fn = foot.footnote;
							}
						});
						localdata.footnote[source_id].fn = fn;
						localdata.footnote[source_id].fc = fc;

					} else {
						foot = localdata.footnote[source_id];
						fn = foot.fn;
						fc = foot.fc;
					}
					if (data.note.page.start !== 0) {
						var page = data.note.page.start;
						var dif = Math.round(data.note.page.end - data.note.page.start);
						if (dif > 0) {
							page = data.note.page.start + '-' + data.note.page.end;
						}
						var pageHere = ' S. ' + page + '.';
					}
					if (foot.footnote !== undefined) {
						fn = fn + pageHere;
						//	.html(foot.footnote + pageHere));
					} else {
						fn = fn + ',' + pageHere;
					}
					note.content4tex.append($('<p>').addClass('footnote bibtex').html('\\footcite[' + page + ']{<a href=\'?source=' + data.note.source.id + '\'>' + fc + '</a>'));
					note.content.append($('<p>').addClass('footnote biblio').append($('<a>').attr({href: '?source=' + data.note.source.id}).html(fn)));
				}
				var latex, tex_ele, classNote;
				if (data.note.biblio !== null) {
					latex = data.note.comment4tex;
					tex_ele = $('<button>').addClass('btn grp_none comment').click(function () {
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

				createLabel(data.note.label, note.label);
				// label ele


				// tools ele
				note.tools.each(function () {
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

					if (note_ele.hasClass('topic') && nID === sID) {
						type = 'source';
					} else {
						type = 'note';
					}

					if (localdata.settings.access === '1') {
						edit = false;
						edit_ele = $('<button>').addClass('btn grp_none fake_btn');
					} else {
						edit = true;
						edit_ele = $('<button>').addClass('btn grp_none edit').on('click', function() {
							edit_ele.note('edit', data);
						});

						/*
						{
							type: type,
							noteID: nID,
							sourceID: sID,
							edit: edit,
							data: note_obj,
							show: 'form'
						});
						*/

					}

					if ($note.find('.latex').length > 0) {
						tex_ele = $('<button>').addClass('btn grp_none calendar').click(function () {
							$(this).toggleClass('comment');
							$note.find('.text').toggle();
							$note.find('.latex').toggle();
						});
						exp_ele = $('<button>').addClass('btn grp_none expand').expand({
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
					$('#fullpage').warning({
						type: 'noresults',
						lang: 'de'
					});
					$('body').on('click', function () {
						window.location.href = NB.url;
					});
				}
			}


			//		return (media, text, latex, label, tools);
		},

		dispBib = function (ele, data, localdata) {
			var note = {};
			var source = {};
			var source_id = data.source.id;

			if (source_id !== 0) {
				var note_ele = ele.addClass('item topic').attr({'id': source_id});
				note = createNote(note_ele);
				if (data.source.public === '0') {
					note_ele.addClass('private');
				}

				var showsource = getSource(data);
				biblio = showsource.biblio + '.';
				bibtex = showsource.bibtex;

				note.media
					.html(data.source.media);
				note.content
					.append($('<p>').html(biblio));
				note.content4tex
					.append($('<p>').html(bibtex));


				// label ele
				createLabel(data.source.label, note.label);

				note.tools.each(function () {
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


					if (note_ele.hasClass('topic') && nID === sID) {
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
						tex_ele = $('<button>').addClass('btn grp_none calendar').click(function () {
							$(this).toggleClass('comment');
							$note.children('.text').toggle();
							$note.children('.latex').toggle();
						});
//							console.log(showsource.biblio.substr(0, 9));
// if the source is not correct recorded yet, show the bibtex
						if(showsource.biblio.substr(0, 9) === 'undefined'){
							tex_ele.toggleClass('comment');
							$note.children('.text').toggle();
							$note.children('.latex').toggle();
						}
						exp_ele = $('<button>').addClass('btn grp_none expand').expand({
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
				if($('div.note').length === 0) {
					$('#fullpage').warning({
						type: 'noresults',
						lang: 'de'
					});
					$('body').on('click', function () {
						window.location.href = NB.url;
					});
				}

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


				for(i=0; i<localdata.settings.query.length; i++ ) {
//	console.log(localdata.settings.query[i] + ': ' + localdata.settings.query[localdata.settings.query[i]]);
				switch (localdata.settings.query[i]) {
					case 'label':
					case 'author':
						// wall
						localdata.view.container.addClass('wall');
						url = NB.api + '/get.php?' + localdata.settings.query[i] + '=' + localdata.settings.query[localdata.settings.query[i]];
						$.getJSON(url, function (list) {
							$('input.search_field').attr({value: decodeURI(list.name)}).html(decodeURI(list.name));
							$.each(list.notes, function (i, note) {
								if(note.ac >= localdata.settings.access) {
									url = NB.api + '/get.php?id=' + note.id;
									$.getJSON(url, function (data) {
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
									})
								}
							})
						});
						break;
					case 'source':
						// desk
						localdata.view.container.addClass('desk')
							.append(localdata.view.left = $('<div>').addClass('left_side'))
							.append(localdata.view.right = $('<div>').addClass('right_side'));
						url = NB.api + '/get.php?source=' + localdata.settings.query[localdata.settings.query[i]];
						$.getJSON(url, function (data) {
							localdata.view.left.append(
								localdata.view.source = $('<div>').addClass('note')
							);
							dispBib(localdata.view.source, data, localdata);
							$.each(data.source.notes, function (i, note) {
								if(note.ac >= localdata.settings.access) {
									localdata.view.right.append(
										$('<div>').addClass('note').attr({'id': note.id})
									);
									url = NB.api + '/get.php?note=' + note.id;
									$.getJSON(url, function (data) {
										for (var key in data) {
											localdata.view.note = $('#' + note.id);
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
							.append(localdata.view.right = $('<div>').addClass('right_side'));
						url = NB.api + '/get.php?source=' + localdata.settings.query[localdata.settings.query[i]];
						$.getJSON(url, function (data) {
							localdata.view.left.append(
								localdata.view.collection = $('<div>').addClass('note')
							);
							dispBib(localdata.view.collection, data, localdata);

							// find "insources" with the reference to this collection
							$.each(data.source.insource, function (i, bibID) {
								localdata.view.right.append(
									$('<div>').addClass('note topic').attr({'id': bibID})
								);
								url = NB.api + '/get.php?source=' + bibID;
								$.getJSON(url, function (data) {
									for (var key in data) {
										localdata.view.note = $('#' + bibID);
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
						$.getJSON(url, function (data) {
							localdata.view.container.append(
								localdata.view.note = $('<div>').addClass('note').attr({'id': localdata.settings.query[localdata.settings.query[i]]})
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
						url = NB.api + '/get.php?q=' + localdata.settings.query[localdata.settings.query[i]];
						$('input.search_field').attr({
							value: decodeURI(localdata.settings.query[localdata.settings.query[i]])
						});
						$.getJSON(url, function (search) {
							$.each(search.notes, function (i, noteID) {
								url2 = NB.api + '/get.php?note=' + noteID;
								$.getJSON(url2, function (data) {
									localdata.view.container.append(
										localdata.view.note = $('<div>').addClass('note').attr({'id': noteID})
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
						$.getJSON(url, function (list) {
							$.each(list.notes, function (i, noteID) {
								url = NB.api + '/get.php?id=' + noteID;
								$.getJSON(url, function (data) {
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
				$(window).load(function () {

					if ($('.desk').length !== 0) {

					}
				});

				if (localdata.settings.type === 'source') {
					$.getJSON(NB.api + '/get.php?' + localdata.settings.type + '=' + localdata.settings.id, function (data) {
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

			});											// end "return this.each"
		},												// end "init"

		edit: function (data) {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				console.log(data);
				//console.log(localdata);
			});
		},

		add: function() {


		},


		setNote2Wall: function () {
			return this.each(function () {
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

		anotherMethod: function () {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
			});
		}
		/*========================================================================*/
	};


	$.fn.note = function (method) {
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
