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

(function ($) {
	// -----------------------------------------------------------------------
	// define some functions
	// -----------------------------------------------------------------------
	var selOption = function(select_ele, request, selected_ele, note_ele) {
			var url = NB.api + '/get.php?' + request, url2;
			$.getJSON(url, function (data) {
				for (var k in data.notes) {
//					console.log(k + ': ' + data.notes[k].split('::')[0]);
					select_ele.append($('<option>')
						.html(data.notes[k].split('::')[1])
						.attr({'value': data.notes[k].split('::')[0]})
					);
				}
				if(selected_ele) {
					selected_ele.empty();
					if(data.notes[0] !== undefined) {
						url2 = NB.api + '/get.php?source=' + data.notes[0].split('::')[0];
						$.getJSON(url2, function (data2) {
							selected_ele
							.html(getSource(data2).biblio).attr({'id': data.notes[0].split('::')[0]});
							form4note(note_ele, data2);
						});
					}
				}
			});
		},
		getAuthors = function (author) {
			// authors
			var i = 0, authors;
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
			return htmlDecode(authors);
		},
		getLocations = function (location) {
			// locations
			var i = 0, locations;
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
			return htmlDecode(locations);
		},
		getPages = function(start, end) {
			var pages;
			if(start <= end || end === '0') {
				pages = start;
			} else {
				pages = start + '-' + end;
			}
			return pages;
		},
		getSource = function (data) {
			var i, authors, locations, biblio, bibtex, footnote, source, bib, crossref, insource = {};
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
								crossref = source.detail.crossref.source;
								var crossTitle = '', crossAuthors, crossLocations;
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
				};
			}
			return bib;
		},
		getLabel = function(label) {
			var i = 0, labels;
			if(label.length !== 0) {
				while (i < label.length) {
					if (labels === undefined) {
						labels = label[i].name;
					} else {
						labels += ', ' + label[i].name;
					}
					i += 1;
				}
			}
			return htmlDecode(labels);
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

		createTool = function(tools_ele, localdata) {
			tools_ele.each(function () {
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

				if (localdata.settings.access === '1') {
					edit = false;
					edit_ele = $('<button>').addClass('btn grp_none fake_btn');
				} else {
					edit = true;
					edit_ele = $('<button>').addClass('btn grp_none edit').on('click', function() {
						edit_ele.note('edit', nID);
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

				if (note.find('.latex').length > 0) {
					tex_ele = $('<button>').addClass('btn grp_none calendar').click(function () {
						$(this).toggleClass('comment');
						note.find('.text').toggle();
						note.find('.latex').toggle();
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

				tools
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

		},

		form4note = function (ele, data) {
			var form = {};
			$.each(data.source.notes, function (i, note) {
					url = NB.api + '/get.php?note=' + note.id;
					$.getJSON(url, function (data2) {
						form.labels = getLabel(data2.note.label);
						for (var key in data2) {
							form.pages = getPages(data2.note.page.start, data2.note.page.end);
							if(data2.note.subtitle !== null && data2.note.subtitle !== '') {
								form.title = htmlDecode(data2.note.title) + '//' + htmlDecode(data2.note.subtitle);
							} else {
								form.title = htmlDecode(data2.note.title);
							}
							ele
								.append(
									form.form = $('<form>').attr({'method': 'post', 'action': '', 'id': 'form_' + note.id})
									.append(form.table = $('<table>').attr({'id': note.id})
										.append(
											$('<tr>').addClass('invisible')
											.append(
												$('<td>').attr({'colspan': '2'})
												.append($('<input>')
													.attr({'type': 'hidden', 'placeholder': 'noteID', 'title': 'noteID', 'name': 'noteID'})
													.val(data2.note.id)
												)
												.append($('<input>')
													.attr({'type': 'hidden', 'placeholder': 'checkID', 'title': 'checkID', 'name': 'checkID'})
													.val(data2.note.checkID)
												)
												.append($('<input>')
													.attr({'type': 'hidden', 'placeholder': 'sourceID', 'title': 'sourceID', 'name': 'sourceID'})
													.val(data.source.id)
												)
											)
										)
										.append(
											$('<tr>').addClass('invisible')
											.append(
												$('<td>').attr({'colspan': '2'})
												.append('<span>')
													.addClass('error_' + note.id)
													//.text('You haven\'t completed all required fields!')
											)
										)
										.append(
											$('<tr>')
											.append(
												$('<td>')
												.append($('<input>')
													.addClass('field_obj large text n_title')
													.attr({'type': 'text', 'placeholder': 'Title//Subtitle', 'title': 'Title//Subtitle', 'name': 'title'})
													.val(form.title))
											)
											.append(
												$('<td>')
												.append($('<input>')
													.addClass('field_obj small text n_pages')
													.attr({'type': 'text', 'title': 'Pages', 'placeholder': 'Pages', 'name': 'pages'})
													.val(form.pages))
											)
										)
										.append(
											$('<tr>')
											.append(
												$('<td>')
												.append($('<textarea>')
													.addClass('field_obj large tiny text n_comment')
													.text(htmlDecode(data2.note.comment))
													.attr({'placeholder': 'comment', 'name': 'comment'}))
											)
											.append(
												$('<td>')
												.append(form.upload = $('<div>')
													.addClass('field_obj small upload n_media')
													.append($('<span>').addClass('place4media').html(data2.note.media.html))
													.append($('<span>').addClass('button4media'))
												)
												.append(form.file = $('<input>')
													.addClass('field_obj small n_filename')
													.attr({'type': 'hidden', 'placeholder': 'file name', 'name': 'filename'})
													.val(data2.note.media.path)
												)
												.append(
													$('<label>')
													.attr({
														'for': 'rights_' + note.id
													}).text('Public')
												)
												.append(form.pub = $('<input>')
													.attr({'type': 'checkbox', 'name': 'public', 'id': 'rights_' + note.id})
													.val('1')			// public
													.addClass('n_rights')
												)

/*													$('<select>').addClass('field_obj small').attr({'name': 'public'})
													.append($('<option>').text('public').attr({'value': '1'}))
													.append($('<option>').text('private').attr({'value': '0'}))
*/
											)
										)
										.append(
											$('<tr>')
											.append(
												$('<td>')
												.append($('<input>')
													.addClass('field_obj large text n_label')
													.val(form.labels)
													.attr({'type': 'text', 'placeholder': 'label', 'name': 'label'}))
											)
											.append(
												$('<td>').addClass('right')
												.append(form.edit_btn = $('<button>').addClass('btn grp_none edit').attr({'id': note.id, 'type': 'button'}))
												.append(form.trash_btn = $('<button>').addClass('btn grp_none trash invisible').attr({'id': note.id, 'type': 'button'}))
												.append(form.reset_btn = $('<button>').addClass('btn grp_none close invisible').attr({'id': note.id, 'type': 'button'}))
												.append(form.save_btn = $('<button>').addClass('btn grp_none done invisible').attr({'id': note.id, 'type': 'submit'}))
											)
										)
									)
								);
							$('table#' + note.id).find('input, textarea, select').attr('readonly', true);
							$('table#' + note.id).find('input.n_rights').attr({'onclick': 'return false'});
							$('table#' + note.id).find('select').attr('disabled', true);
							if(data2.note.public === '1') $('table#' + note.id).find('input.n_rights').attr({'checked': 'checked'});
						}
						//i++;
						$('#form_' + note.id).submit(function(){
							$.ajax({
								url:NB.api + '/post.php?note=' + note.id,
								type : 'POST',
								dataType: 'json',
								data: $(this).serialize(),
								success: function(data){
									if(data.error){
										$('table#' + note.id).find('textarea.n_comment').addClass('error');
									}else {
										var filename = $('table#' + note.id).find('input.n_filename').val();
										var media = '';
										if(filename !== '') media = $('table#' + note.id).find('span.place4media').html();
										var upload_ele = $('table#' + note.id).find('div.upload');
										upload_ele.upload('cancel');

										$('table#' + note.id).find('span.place4media').removeClass('drop').html(media);
										//$('table#' + note.id).find('span.place4media input').remove();
										$('table#' + note.id).find('span.button4media').empty();
/*
										$('table#' + note.id).find('div.n_media').empty()
											.append(
												$('<span>').addClass('place4media')
												.html(media)
											);
*/
										$('table#' + note.id).find('button.trash').addClass('invisible');
										$('table#' + note.id).find('button.close').addClass('invisible');
										$('table#' + note.id).find('button.done').addClass('invisible');
										$('table#' + note.id).find('button.edit').removeClass('invisible');
										$('table#' + note.id).find('textarea.n_comment').removeClass('error');
										$('table#' + note.id).find('input, textarea, select').attr('readonly', true);
										$('table#' + note.id).find('input.n_rights').attr({'onclick': 'return false'});
										$('table#' + note.id).find('select').attr('disabled', true);
									}
								}
							});
							return false;
						});

					$('button#' + note.id + '.edit').on('click', function() {
						// collect the data for a reset
						var title_subtitle = $('table#' + note.id).find('input.n_title').val().split('//');
						var title = title_subtitle[0];
						var subtitle = title_subtitle[1];
						var comment = $('table#' + note.id).find('textarea.n_comment').val();
						var labels = $('table#' + note.id).find('input.n_label').val();
						var filename = $('table#' + note.id).find('input.n_filename').val();
						var pages = $('table#' + note.id).find('input.n_pages').val().split('-');
						var page_start = pages[0];
						var page_end = pages[1];
						var media = $('table#' + note.id).find('span.place4media').html();

						var rights = $('table#' + note.id).find('input.n_rights');
						if(rights.is(':checked')) {
							rights = 1;
						} else {
							rights = 0;
						}
						console.log(rights);
						var editnote = {
							'note': {
								'id': note.id,
								'checkID': null,
								'title': htmlDecode(title),
								'subtitle': htmlDecode(subtitle),
								'comment': htmlDecode(comment),
								'label': htmlDecode(labels),
								'media': media,
								'file': filename,
								'source': {
									'id': data.source.id,
									'name': data.source.name,
									'link': null
								},
								'page': {
									'start': page_start,
									'end': page_end
								},
								'rights': rights
							}
						};
						$('table#' + note.id).find('button').toggleClass('invisible');
						$('table#' + note.id).find('input, textarea, select').attr('readonly', false);
						$('table#' + note.id).find('select').attr('disabled', false);
						$('table#' + note.id).find('input.n_rights').attr({'onclick': 'return true'});

						var upload_ele = $('table#' + note.id).find('div.upload');
						var file_ele = $('table#' + note.id).find('input.n_filename');
						upload_ele.upload({'file': file_ele, 'media': media, 'note': note.id});

						$('button#' + note.id + '.close').on('click', function() {
							upload_ele.upload('cancel');

							if(editnote.note.subtitle !== '') {
								$('table#' + note.id).find('input.n_title').val(editnote.note.title + '//' + editnote.note.subtitle);
							} else {
								$('table#' + note.id).find('input.n_title').val(editnote.note.title);
							}
							if(editnote.note.page.end !== undefined) {
								$('table#' + note.id).find('input.n_pages').val(editnote.note.page.start + '-' + editnote.note.page.end);
							} else {
								$('table#' + note.id).find('input.n_pages').val(editnote.note.page.start);
							}
							$('table#' + note.id).find('textarea.n_comment').val(editnote.note.comment).removeClass('error');
							$('table#' + note.id).find('input.n_label').val(editnote.note.label);
							$('table#' + note.id).find('span.place4media').html(editnote.note.media);
							$('table#' + note.id).find('input.n_filename').val(editnote.note.file);
							$('table#' + note.id).find('input.n_rights').removeAttr('checked');
							if(editnote.note.rights === 1) $('table#' + note.id).find('input.n_rights').attr({'checked': 'checked'});
							
							$('table#' + note.id).find('button.trash').addClass('invisible');
							$('table#' + note.id).find('button.close').addClass('invisible');
							$('table#' + note.id).find('button.done').addClass('invisible');
							$('table#' + note.id).find('button.edit').removeClass('invisible');
							$('table#' + note.id).find('input, textarea, select').attr('readonly', true);
							$('table#' + note.id).find('input.n_rights').attr({'onclick': 'return false'});
						});
					});

						if(i !== data.source.notes.length) {
							ele.append($('<hr>'));
						}

					});
			});
		},

		dispNote = function (ele, data, localdata) {
			var note = {};
			var source = {};
			var note_id = data.note.id;
			var source_id;
			var foot = {}, fn, fc;

			if (note_id !== 0) {
				var note_ele = ele.addClass('item').attr({'id': note_id});
				note = createNote(note_ele);
				if (data.note.public === '0') {
					note_ele.addClass('private');
				}
				if(!$.isEmptyObject(data.note.media.html)) {
					note.media
						.empty()
						.html(data.note.media.html);
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
					var pageHere, page;
					if(!(source_id in localdata.footnote)) {
						localdata.footnote[source_id] = {};
						var url = NB.api + '/get.php?source=' + source_id;
							fc = data.note.source.name;				// footcite in latex
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
						page = data.note.page.start;
						var dif = Math.round(data.note.page.end - data.note.page.start);
						if (dif > 0) {
							page = data.note.page.start + '-' + data.note.page.end;
						}
						pageHere = ' S. ' + page + '.';
					}
					if (foot.footnote !== undefined) {
						fn = fn + pageHere;
						//	.html(foot.footnote + pageHere));
					} else {
						fn = fn + ',' + pageHere;
					}
					note.content4tex.append($('<p>').addClass('footnote bibtex').html('\\footcite[' + page + ']{<a href=\'?source=' + data.note.source.id + '\'>' + fc + '</a>}'));
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
			var biblio, bibtex;

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
					.html(data.source.media.html);
				note.content
					.append($('<p>').html(biblio));
				note.content4tex
					.append($('<p>').html(bibtex));


				// label ele
				createLabel(data.source.label, note.label);

				// tools ele
				createTool(note.tools, localdata);


			} else {

				$('.wrapper').warning({
					type: 'noresults',
					lang: 'de'
				});
				$('body').on('click', function () {
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
							var name = htmlDecode(decodeURI(list.name));
							$('input.search_field').attr({value: name}).html(name);
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
									});
								}
							});
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
						var qstring = htmlDecode(decodeURI(localdata.settings.query[localdata.settings.query[i]]));
						url = NB.api + '/get.php?q=' + localdata.settings.query[localdata.settings.query[i]];

						$('input.search_field').attr({
							value: qstring
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
				 $('.wrapper').warning({
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

		add: function(action) {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				//console.log(localdata);
				var form = {}, bibtyp, source, recent, recent_url, recent_data, opt_val;

				form.source = {};
				form.note = {};

				// select a source first
				$this.html(
					form.form = $('<div>')
						/*.attr({
							'action': action,
							'method': 'post'
						})*/
						.addClass('form_frame')
						.append(
							$('<div>').addClass('top source_form active')
							.append($('<table>').addClass('source_form')
							.append(form.source.info = $('<tr>')
								.append($('<th>')
									.attr({'colspan': '2'})
									.addClass('left')
									.append($('<p>').text('Select a source / theme or ')
										.append(
											form.source.new = $('<input>').attr({
											'name': 'newsource',
											'type': 'button',
											'value': 'ADD NEW'
										})
										.addClass('button medium new')
										)
										.append($('<input>')
											.attr({
												'name': 'editsource',
												'type': 'button',
												'value': 'EDIT the acitve'
											})
											.addClass('button small edit')
											.css({float: 'right'})
											.on('click', function() {
								//				console.log($('.selected.source').attr('id'));
											})
										)
									)
								)
							)
							.append(form.source.select = $('<tr>')
								.append($('<td>')
									.append(form.source.typ = $('<select>')
									.attr({
										'name': 'bibtyp'
									})
									.addClass('field_obj small select bibtyp first_form_ele')
									.change(function() {
										form.source.bib.empty();
										if(form.source.typ.val() === '0') {
											selOption(form.source.bib, 'recent=3', form.source.selected, form.note.container);
											selOption(form.source.bib, 'list=source');
										} else {
											selOption(form.source.bib, 'bibtyp=' + form.source.typ.val(), form.source.selected, form.note.container);
										}
									}))
								)
								.append($('<td>')
									.append(form.source.bib = $('<select>')
									.attr({
										'name': 'source'
									})
									.addClass('field_obj large select source')
									.change(function() {
										form.source.selected.empty();
										form.note.container.empty();
										url = NB.api + '/get.php?source=' + form.source.bib.val();
										$.getJSON(url, function (data) {
											form.source.selected.html(getSource(data).biblio).attr({'id': form.source.bib.val()});
											form4note(form.note.container, data);
										});
									}))
								)
							)

							.append($('<tr>')
								.append(form.source.selected = $('<td>')
									.attr({'colspan': '2'})
									.addClass('selected source center')
								)
							)
						)
					)
					.append(
						$('<div>').addClass('bottom note_form')
						.append(form.note.container = $('<div>').addClass('note_form')
							/*
							.append(form.note.info = $('<tr>')
								.append($('<th>')
									.attr({'colspan': '2'})
									.text('Select a source / theme...')
									.addClass('left')
								)
								.append($('<th>').text('or ADD'))
							)
							*/

						)
					)
				);
				/*
				if(form.source.select.val() === '') {
					form.source.editbtn.hide();
				} else {
					form.source.editbtn.show();
				}
				*/
				form.source.typ.html($('<option>')
					.html('recent')
					.attr({'value': '0'})
				);
				selOption(form.source.typ, 'list=bibtyp');
				selOption(form.source.bib, 'recent=3', form.source.selected, form.note.container);
				selOption(form.source.bib, 'list=source');

				/*
				recent_url = NB.api + '/get.php?recent=1';
				$.getJSON(recent_url, function (rec) {
					for (var k in rec.notes) {
						recent_data = NB.api + '/get.php?source=' + rec.notes[k].split('::')[0];
						$.getJSON(recent_data, function (data) {
							form.source.selected.html(getSource(data).biblio);
						});
					}

				});
				*/

			});

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
			throw 'Method ' + method + ' does not exist on jQuery.note';
		}
	};
})(jQuery);
