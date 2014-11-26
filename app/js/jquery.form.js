/* ===========================================================================
 *
 * @frame: jQuery plugin template
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
	// define some functions first
	// -----------------------------------------------------------------------
	var selOption = function(select_ele, request, selected_ele, note_ele) {
		var url = NB.api + '/get.php?' + request, url2;
		$.getJSON(url, function (data) {
			for (var k in data.notes) {
				select_ele.append($('<option>')
					.html(data.notes[k].split('::')[1])
					.val(data.notes[k].split('::')[0])
				);
			}
			if(selected_ele) {
				selected_ele.empty();
				if(data.notes[0] !== undefined) {
					url2 = NB.api + '/get.php?source=' + data.notes[0].split('::')[0];
					$.getJSON(url2, function (data2) {
						selected_ele.html(getSource(data2).biblio)
									.attr({'id': data.notes[0].split('::')[0]});
						selected_ele.append(
							$('<input>').attr({
								'name': 'editsource',
								'type': 'button'
							})
							.val('EDIT')
							.css({'float': 'right'})
							.addClass('button medium edit')
							.on('click', function() {
								var sourceID = selected_ele.attr('id');
								if($(this).hasClass('close')) {
									$(this).toggleClass('edit close');
									$(this).val('EDIT');
									note_ele.empty();
									form4note(note_ele, sourceID);
								} else {
									if(sourceID !== '0') {
										$(this).toggleClass('edit close');
										$(this).val('CANCEL');
										note_ele.empty();
										form4source(note_ele, sourceID);
									} else {
										alert('You have to choose a source first');
									}
								}
							})
						);
						form4note(note_ele, data.notes[0].split('::')[0]);
					});
				} else {
					selected_ele.attr({'id': 0});
				}
			}
		});
	};
	var addFields = function (ele, val, data) {
		var plus = {};
		var detail = {};
		var loc_ele;
		detail.location = getLocations(data.location, ' / ');
		ele.empty();
		switch(val) {
			case 'article':
				detail.num = '3';
				plus.field = $('<span>')
					.append(
						$('<input>')
						.addClass('field_obj large text journaltitle')
						.attr({'type': 'text', 'placeholder': 'Journal Title', 'title': 'Journal Title', 'name': 'journaltitle'})
						.val(data.detail.journaltitle)
					)
					.append($('<br>'))
					.append(
						$('<input>')
						.addClass('field_obj small number')
						.attr({'type': 'number', 'placeholder': 'Number', 'title': 'Journal Number', 'name': 'number'})
						.val(data.detail.number)
					).append(
						$('<input>')
						.addClass('field_obj small year')
						.attr({'type': 'number', 'placeholder': 'Year', 'title': 'Year', 'name': 'year'})
						.val(data.detail.year)
					).append(
						$('<input>')
						.addClass('field_obj small pages')
						.attr({'type': 'text', 'placeholder': 'Pages', 'title': 'Page Start - Page End', 'name': 'pages'})
						.val(data.detail.pages)
					);
				break;

			case 'book':
			case 'booklet':
			case 'collection':
			case 'project':
				plus.field = $('<span>')
					.append(loc_ele = $('<input>')
						.addClass('field_obj large text noteLocation')
						.attr({'type': 'text', 'placeholder': 'Location 1 / Location 2', 'title': 'Location 1 / Location 2 / etc.', 'name': 'noteLocation'})
						.val(detail.location)
					);
					completeMultipleValues('location', loc_ele);
				break;

			case 'online':
				plus.field = $('<span>')
					.append(
						$('<input>')
						.addClass('field_obj large text url')
						.attr({'type': 'text', 'placeholder': 'URL', 'title': 'URL', 'name': 'url'})
						.val(data.detail.url)
					)
					.append($('<br>'))
					.append(
						$('<input>')
						.addClass('field_obj small urldate')
						.attr({'type': 'date', 'placeholder': 'URL Date', 'title': 'Date of request (yyyy-mm-dd)', 'name': 'urldate'})
						.val(data.detail.urldate)
					);
				break;
			case 'proceedings':
				plus.field = $('<span>')
					.append(
						$('<input>')
						.addClass('field_obj large text eventtitle')
						.attr({'type': 'text', 'placeholder': 'Event Title', 'title': 'Title of Event', 'name': 'eventtitle'})
						.val(data.detail.eventtitle)
					)
					.append($('<br>'))
					.append(
						$('<input>')
						.addClass('field_obj large text venue')
						.attr({'type': 'date', 'placeholder': 'Event Venue', 'title': 'Venue of Event', 'name': 'venue'})
						.val(data.detail.venue)
					)
					.append(loc_ele = $('<input>')
						.addClass('field_obj large text noteLocation')
						.attr({'type': 'text', 'placeholder': 'Location 1 / Location 2', 'title': 'Location 1 / Location 2 / etc.', 'name': 'noteLocation'})
						.val(detail.location)
					);
					completeMultipleValues('location', loc_ele);
				break;

			case 'report':
			case 'thesis':
				plus.field = $('<span>')
					.append(
						$('<input>')
						.addClass('field_obj large text type')
						.attr({'type': 'text', 'placeholder': 'Type', 'title': 'Type', 'name': 'type'})
						.val(data.detail.type)
					)
					.append($('<br>'))
					.append(
						$('<input>')
						.addClass('field_obj large text institution')
						.attr({'type': 'text', 'placeholder': 'Institution', 'title': 'Institution', 'name': 'institution'})
						.val(data.detail.institution)
					);
				break;

			case 'inbook':
			case 'incollection':
			case 'inproceedings':
				var intyp;
				if(val === 'inbook') intyp = 'book';
				if(val === 'incollection') intyp = 'collection';
				if(val === 'inproceedings') intyp = 'proceedings';
				plus.field = $('<span>')
					.append(plus.insource =
						$('<select>')
						.attr({'name': 'crossref'})
						.addClass('field_obj large select insource')
					)
					.append($('<br>'))
					.append(
						$('<input>')
						.addClass('field_obj small text inpages')
						.attr({'type': 'text', 'placeholder': 'Pages', 'title': 'Pages (11-22)', 'name': 'pages'})
						.val(data.detail.pages)
					);
					selOption(plus.insource, 'bibtyp=' + intyp);
					setTimeout(function(){
						if(data.detail.crossref !== undefined) plus.insource.val(data.detail.crossref.source.id);
					}, 300);
				break;


			case 'manual':
			case 'misc':
			case 'periodical':
			case 'unpublished':
				plus.field = $('<span>')
					.append(loc_ele = $('<input>')
						.addClass('field_obj large text noteLocation')
						.attr({'type': 'text', 'placeholder': 'Location 1 / Location 2', 'title': 'Location 1 / Location 2 / etc.', 'name': 'noteLocation'})
						.val(detail.location)
					);
					completeMultipleValues('location', loc_ele);
				break;
		}


		ele.append($('<td>').addClass('medium')
			.append(
				$('<input>')
				.addClass('field_obj small text bibTypName')
				.attr({'type': 'hidden', 'placeholder': 'bibTypName', 'name': 'bibTypName'})
				.val(val)
			)
		)
		.append($('<td>').addClass('large')
			.append(plus.field)

		)
		.append($('<td>').addClass('small')

		);

	};
	var form4newnote = function(ele) {
		url = NB.api + '/get.php?source=0';
		$.getJSON(url, function (data) {
			form4note(ele, 0);
		});
	};

	var form4note = function (ele, source) {
		var form = {};
		var url = NB.api + '/get.php?source=' + source;
		$.getJSON(url, function (data) {
			$.each(data.source.notes, function (i, note) {
					url = NB.api + '/get.php?note=' + note.id;
					$.getJSON(url, function (data2) {
	//					if(data2.note.checkID === null) data2.note.checkID = check_id();
						form.labels = getLabel(data2.note.label, ' / ');
						for (var key in data2) {
							form.pages = getPages(data2.note.page.start, data2.note.page.end);
							if(form.pages === '0') form.pages = '';

							if(data2.note.subtitle !== null && data2.note.subtitle !== '') {
								form.title = htmlDecode(data2.note.title) + '//' + htmlDecode(data2.note.subtitle);
							} else {
								form.title = htmlDecode(data2.note.title);
							}
							if(data2.note.id === '0') {
								data2.note.source.id = $('.bottom').find('button.addnote.close').attr('id');
							}
							ele.append($('<form>').attr({'method': 'post', 'action': '', 'id': 'form_' + note.id}).addClass('')
								.append(form.table = $('<table>').attr({'id': note.id})
									.append($('<tr>').addClass('invisible')
										.append($('<td>').attr({'colspan': '3'})
											.append($('<input>')
												.attr({'type': 'hidden', 'placeholder': 'noteID', 'title': 'noteID', 'name': 'noteID'})
												.val(data2.note.id)
												.addClass('invisible noteID')
											)
											.append($('<input>')
												.attr({'type': 'hidden', 'placeholder': 'checkID', 'title': 'checkID', 'name': 'checkID'})
												.val(data2.note.checkID)
												.addClass('invisible checkID')
											)
											.append($('<input>')
												.attr({'type': 'hidden', 'placeholder': 'bibID', 'title': 'bibID', 'name': 'bibID'})
												.val(data2.note.source.id)
												.addClass('invisible bibID')
											)
										)
									)
									/*
									.append(
										$('<tr>').addClass('invisible')
										.append(
											$('<td>').attr({'colspan': '2'})
											.append('<span>')
												.addClass('error_' + note.id)
												//.text('You haven\'t completed all required fields!')
										)
									)
									*/
									.append($('<tr>')
										.append($('<td>').addClass('medium')
											.append($('<input>')
												.addClass('field_obj large text noteTitle')
												.attr({'type': 'text', 'placeholder': 'Title', 'title': 'Title', 'name': 'noteTitle'})
												.val(data2.note.title))
										)
										.append($('<td>').addClass('medium')
											.append($('<input>')
												.addClass('field_obj small text notePages')
												.attr({'type': 'text', 'title': 'Pages', 'placeholder': 'Pages', 'name': 'notePages'})
												.val(form.pages))
										)
										.append($('<td>').addClass('small')

										)
									)
									.append($('<tr>')
										.append($('<td>').addClass('large')
											.append($('<input>')
												.addClass('field_obj large text noteSubtitle')
												.attr({'type': 'text', 'placeholder': 'Subtitle', 'title': 'Subtitle', 'name': 'noteSubtitle'})
												.val(data2.note.subtitle))
										)
										.append($('<td>').addClass('medium')
											.append(form.file = $('<input>')
												.addClass('field_obj small text noteMedia')
												.attr({'type': 'text', 'title': 'media/file', 'placeholder': 'media/file', 'name': 'noteMedia'})
												.val(data2.note.media.path)
											)
										)
										.append($('<td>').addClass('small')

										)
									)
									.append($('<tr>')
										.append($('<td>').addClass('large')
											.append($('<textarea>')
												.addClass('field_obj large tiny text noteComment')
												.text(htmlDecode(data2.note.comment))
												.attr({'placeholder': 'Comment', 'name': 'noteComment'}))
										)
										.append($('<td>').addClass('medium')
											.append(form.upload = $('<div>')
												.addClass('upload media')
												.append($('<span>').addClass('place4media').html(data2.note.media.html))
												.append($('<span>').addClass('button4media center'))
											)
										)
										.append($('<td>').addClass('small')

										)
									)
									.append($('<tr>')
										.append($('<td>').addClass('large')
											.append(form.label = $('<input>')
												.addClass('field_obj large text noteLabel')
												.val(form.labels)
												.attr({'type': 'text', 'placeholder': 'Label 1 / Label 2', 'name': 'noteLabel', 'title': 'Label 1 / Label 2 / etc.'}))
										)
										.append($('<td>').addClass('medium right')
											.append(form.reset_btn = $('<button>').addClass('btn grp_none view invisible').attr({'type': 'button'}))
											.append(form.reset_box = $('<input>').addClass('invisible notePublic').attr({'type': 'checkbox', 'name': 'notePublic'}).val('1'))
											.append(form.delete_btn = $('<button>').addClass('btn grp_none trash invisible').attr({'type': 'button'}))
											.append(form.delete_box = $('<input>').addClass('invisible deleteNote').attr({'type': 'checkbox', 'name': 'deleteNote'}).val('1'))
										)
										.append($('<td>').addClass('small')

										)
									)
									.append($('<tr>')
										.append($('<td>').addClass('large')
											.append($('<input>')
												.addClass('field_obj large text noteLink')
												.val(data2.note.source.link)
												.attr({'type': 'text', 'placeholder': 'URL', 'name': 'noteLink', 'title': 'Hypertext Reference (URL)'}))
										)
										.append($('<td>').addClass('medium right')
											.append(form.edit_btn = $('<button>').addClass('btn grp_none edit').attr({'id': note.id, 'type': 'button'}))
											.append(form.reset_btn = $('<button>').addClass('btn grp_none close invisible').attr({'id': note.id, 'type': 'button'}))
											.append(form.save_btn = $('<button>').addClass('btn grp_none done invisible').attr({'id': note.id, 'type': 'submit'}))
										)
										.append($('<td>').addClass('small')

										)
									)
								)
							);
							if(data2.note.public === '1') {
								$('table#' + note.id).find('input.notePublic').click();
								$('table#' + note.id).find('button.view').toggleClass('active invisible');
							}
							$('table#' + note.id).find('input, textarea, select').attr('readonly', true);
							$('table#' + note.id).find('input.notePublic').attr({'disabled': true});
							if(data2.note.id === '0') {
								setTimeout(function(){
									$('button#0.edit').click();
								}, 200);
								//alert('klick jetzt');

							//	$('button#0.edit').trigger('click');
							}
						}
					$('button#' + note.id + '.edit').on('click', function() {
						var checkID = $('table#' + note.id).find('input.checkID');
						if(checkID.val() === '') checkID.val(check_id()); //alert(check_id());


						var values = {};
						$.each($('#form_' + note.id).serializeArray(), function(i, field) {
							values[field.name] = field.value;
						});

						completeMultipleValues('label', $('table#' + note.id).find('input.noteLabel'));

						if($('table#' + note.id).find('input.notePublic').is(':checked') === true) values.notePublic = 1;
						values.mediaHTML = $('#form_' + note.id).find('span.place4media').html();

						$('table#' + note.id).find('button.edit').addClass('invisible');
						$('table#' + note.id).find('button.close').removeClass('invisible');
						$('table#' + note.id).find('button.done').removeClass('invisible');

						$('table#' + note.id).find('input, textarea, select').attr('readonly', false);
						$('table#' + note.id).find('input.notePublic').attr({'disabled': false});

						$('table#' + note.id).find('button.view').removeClass('invisible').on('click', function(){
							$('table#' + note.id).find('input.notePublic').click();
							$(this).toggleClass('active');
						});
						$('table#' + note.id).find('button.trash').removeClass('invisible').on('click', function(){
							$('table#' + note.id).find('input.deleteNote').click();
							$(this).toggleClass('active');
							$('table#' + note.id).toggleClass('delete');
						});

						var upload_ele = $('table#' + note.id).find('div.upload');
						var file_ele = $('table#' + note.id).find('input.noteMedia');
						upload_ele.upload({'file': file_ele, 'media': values.mediaHTML, 'note': note.id});

						//
						// if you cancel the edit action
						//
						$('button#' + note.id + '.close').on('click', function() {
							upload_ele.upload('cancel');
							var table = $('table#' + note.id);
							table.find('button.view').removeClass('active').addClass('invisible');
							$.each(values, function(i, v) {
								if(i === 'noteComment') {
									table.find('textarea.' + i).val(v);
								} else if (i === 'notePublic') {
									if(table.find('input.notePublic').is(':checked') === false) {
										table.find('input.notePublic').click();
										table.find('button.view').addClass('active').removeClass('invisible');
									}
								} else {
									table.find('input.' + i).val(v);
								}
							});
							if(values.hasOwnProperty('noteMedia')) table.find('span.place4media').html(values.mediaHTML);

							$('table#' + note.id).find('input.deleteNote').removeAttr('checked');
							$('table#' + note.id).find('button.trash').addClass('invisible').removeClass('active');
							$('table#' + note.id).find('button.close').addClass('invisible');
							$('table#' + note.id).find('button.done').addClass('invisible');
							$('table#' + note.id).find('button.edit').removeClass('invisible');
							$('table#' + note.id).find('textarea.noteComment').removeClass('error');
							$('table#' + note.id).removeClass('delete');
							//$('table#' + note.id).find('button').toggleClass('invisible');
							$('table#' + note.id).find('input, textarea, select').attr('readonly', true);
							$('table#' + note.id).find('input.notePublic').attr({'disabled': true});
						});
					});

					$('#form_' + note.id).submit(function(){
						$.ajax({
							url:NB.api + '/post.php?note=' + note.id,
							type : 'POST',
							dataType: 'json',
							data: $(this).serialize(),
							success: function(data){
								if(data.error){
									$('table#' + note.id).find('textarea.noteComment').addClass('error');
								}else {
									$('table#' + note.id).removeClass('delete');
									ele.empty();
									form4note(ele, source);
									/*
									var filename = $('table#' + note.id).find('input.noteMedia').val();
									var media = '';
									if(filename !== '') media = $('table#' + note.id).find('span.place4media').html();
									var upload_ele = $('table#' + note.id).find('div.upload');
									upload_ele.upload('save');

									$('table#' + note.id).find('span.place4media').removeClass('drop').html(media);
									$('table#' + note.id).find('span.button4media').empty();

									if($('table#' + note.id).find('input.notePublic').is(':checked') === true) {
										$('table#' + note.id).find('button.view').addClass('active').removeClass('invisible');
									} else {
										$('table#' + note.id).find('button.view').removeClass('active').addClass('invisible');
									}

									$('table#' + note.id).find('button.trash').addClass('invisible');
									$('table#' + note.id).find('button.close').addClass('invisible');
									$('table#' + note.id).find('button.done').addClass('invisible');
									$('table#' + note.id).find('button.edit').removeClass('invisible');
									$('table#' + note.id).find('textarea.noteComment').removeClass('error');
									$('table#' + note.id).removeClass('delete');
									$('table#' + note.id).find('input, textarea, select').attr('readonly', true);
									$('table#' + note.id).find('input.notePublic').attr({'disabled': true});
									*/
								}
							}
						});
						return false;
					});

						if(i !== data.source.notes.length) {
							ele.append($('<hr>'));
						}

					});
			});
		});
	};

	var form4source = function (ele, id) {
		var form = {};
		var url = NB.api + '/get.php?source=' + id;
		$.getJSON(url, function (data) {
			var noteID = data.source.noteID;
			if(data.source.checkID === null) data.source.checkID = check_id();
			form.author = getAuthors(data.source.author, ' / ');
			form.location = getLocations(data.source.location, ' / ');
			form.pages = getPages(data.source.page.start, data.source.page.end);
			form.labels = getLabel(data.source.label, ' / ');
			if(form.pages === '0') form.pages = '';
			ele.append($('<form>').attr({'method': 'post', 'action': '', 'id': 'form_' + noteID}).addClass('')
				.append(form.table = $('<table>').attr({'id': noteID})
					.append($('<tr>').addClass('invisible')
						.append($('<td>').attr({'colspan': '3'})
							.append($('<input>')
								.attr({'type': 'hidden', 'placeholder': 'noteID', 'title': 'noteID', 'name': 'noteID'})
								.val(noteID)
							)
							.append($('<input>')
								.attr({'type': 'hidden', 'placeholder': 'checkID', 'title': 'checkID', 'name': 'checkID'})
								.val(data.source.checkID)
							)
							.append($('<input>')
								.attr({'type': 'hidden', 'placeholder': 'bibID', 'title': 'bibID', 'name': 'bibID'})
								.val(data.source.id)
							)
						)
					)
					.append($('<tr>')
						.append($('<td>').addClass('medium')
							.append(form.typ = $('<select>')
								.attr({
									'name': 'bibTyp'
								})
								.addClass('field_obj small select bibTyp')
							)
						)
						.append($('<td>').addClass('large')
							.append($('<input>')
								.addClass('field_obj medium text bibName')
								.attr({'type': 'text', 'placeholder': 'bibTex ID', 'title': 'bibTex ID', 'name': 'bibName'})
								.val(data.source.name)
							)
							.append($('<input>')
								.addClass('field_obj small integer dateYear')
								.attr({'type': 'number', 'title': 'Year', 'placeholder': 'year', 'name': 'dateYear'})
								.val(data.source.date.year)
							)
						)
						.append($('<td>').addClass('small')

						)
					)
					.append($('<tr>')
						.append($('<td>').addClass('medium')
							.append($('<select>')
								.attr({
									'name': 'bibEditor',
									'placeholder': 'editor'
								})
								.addClass('field_obj small select bibEditor')
								.append($('<option>').text('').val('0'))
								.append($('<option>').text('editor').val('1'))
							)

						)
						.append($('<td>').addClass('large')
							.append($('<input>')
								.addClass('field_obj large text noteAuthor')
								.attr({'type': 'text', 'placeholder': 'Author 1 / Author 2', 'title': 'Author 1 / Author 2 / etc.', 'name': 'noteAuthor'})
								.val(form.author))
						)
						.append($('<td>').addClass('small')

						)
					)
					.append($('<tr>')
						.append($('<td>').addClass('medium')

						)
						.append($('<td>').addClass('large')
							.append($('<input>')
								.addClass('field_obj large text noteTitle')
								.attr({'type': 'text', 'placeholder': 'Title', 'title': 'Title', 'name': 'noteTitle'})
								.val(data.source.title))
						)
						.append($('<td>').addClass('small')

						)
					)
					.append($('<tr>')
						.append($('<td>').addClass('medium')

						)
						.append($('<td>').addClass('large')
							.append($('<input>')
								.addClass('field_obj large text noteSubtitle')
								.attr({'type': 'text', 'placeholder': 'Subtitle', 'title': 'Subtitle', 'name': 'noteSubtitle'})
								.val(data.source.subtitle))
						)
						.append($('<td>').addClass('small')

						)
					)
					.append(form.res = $('<tr>').addClass('field_plus')

					)
					.append($('<tr>')
						.append($('<td>').addClass('medium')
							.append(form.upload = $('<div>')
								.addClass('upload media')
								.append($('<span>').addClass('place4media').html(data.source.media.html))
								.append($('<span>').addClass('button4media center'))
							)
						)
						.append($('<td>').addClass('large')
							.append($('<textarea>')
								.addClass('field_obj large tiny text noteComment')
								.text(htmlDecode(data.source.comment))
								.attr({'placeholder': 'Comment', 'name': 'noteComment'})
								.css({'height': '100px'})
							)
						)
						.append($('<td>').addClass('small')

						)
					)
					.append($('<tr>')
						.append($('<td>').addClass('medium')
							.append(form.file = $('<input>')
								.addClass('field_obj small text noteMedia')
								.attr({'type': 'text', 'title': 'media/file', 'placeholder': 'media/file', 'name': 'noteMedia'})
								.val(data.source.media.path)
							)
						)
						.append($('<td>').addClass('large')
							.append($('<input>')
								.addClass('field_obj large text noteLabel')
								.val(form.labels)
								.attr({'type': 'text', 'placeholder': 'Label 1 / Label 2', 'name': 'noteLabel', 'title': 'Label 1 / Label 2 / etc.'})
							)
						)
						.append($('<td>').addClass('small')

						)
					)
					.append($('<tr>')
						.append($('<td>').addClass('medium')
							.append(form.reset_btn = $('<button>').addClass('btn grp_none view').attr({'type': 'button'}))
							.append(form.reset_box = $('<input>').addClass('invisible notePublic').attr({'type': 'checkbox', 'name': 'notePublic'}).val('1'))
							.append(form.delete_btn = $('<button>').addClass('btn grp_none trash').attr({'type': 'button'}))
							.append(form.delete_box = $('<input>').addClass('invisible deleteNote').attr({'type': 'checkbox', 'name': 'deleteNote'}).val('1'))
						)
						.append($('<td>').addClass('large right')
							.append(form.save_btn = $('<button>').addClass('btn grp_none done').attr({'id': noteID, 'type': 'submit'}))
						)
						.append($('<td>').addClass('small')

						)
					)
				)
			);
			selOption(form.typ, 'list=bibtyp');

			var upload_ele = $('table#' + noteID).find('div.upload');
			var file_ele = $('table#' + noteID).find('input.noteMedia');
			// upload_ele.upload({'file': file_ele, 'media': values.mediaHTML, 'note': noteID});

			if(data.source.public === '1') {
				$('table#' + noteID).find('input.notePublic').click();
				$('table#' + noteID).find('button.view').toggleClass('active');
			}

			$('table#' + noteID).find('button.view').on('click', function(){
				$('table#' + noteID).find('input.notePublic').click();
				$(this).toggleClass('active');
			});
			$('table#' + noteID).find('button.trash').on('click', function(){
				$('table#' + noteID).find('input.deleteNote').click();
				$(this).toggleClass('active');
				$('table#' + noteID).toggleClass('delete');
			});

			$('table#' + noteID).find('select.bibEditor').val(data.source.editor);
				$('#form_' + noteID).find('select.bibTyp').change(function(){
					var plus_ele = $('#form_' + noteID).find('tr.field_plus');
					var plus_val = $(this).find(':selected').text();
					addFields(plus_ele, plus_val, data.source);

				});
			setTimeout(function(){
				$('#form_' + noteID).find('select.bibTyp').val(data.source.bibTyp.id);
				var plus_ele = $('#form_' + noteID).find('tr.field_plus');
				var plus_val = $('#form_' + noteID).find('select.bibTyp').find(':selected').text();
				addFields(plus_ele, plus_val, data.source);
			}, 300);


			completeMultipleValues('author', $('table#' + noteID).find('input.noteAuthor'));
			completeMultipleValues('location', $('table#' + noteID).find('input.noteLocation'));
			completeMultipleValues('label', $('table#' + noteID).find('input.noteLabel'));


//			$('table#' + id).find('input, textarea, select').attr('readonly', true);
//			$('table#' + id).find('input.notePublic').attr({'disabled': true});
			$('#form_' + noteID).submit(function() {
				$.ajax({
					url: NB.api + '/post.php?source=' + data.source.id,
					type : 'POST',
					dataType: 'json',
					data: $(this).serialize(),
					success: function(data){
						if(data.error){
			//				$('table#' + noteID).find('textarea.noteComment').addClass('error');
						} else {
							var esb = $('table.source_form').find('input.close');		// edit source button
							if(esb.hasClass('close')) {
								esb.toggleClass('edit close');
								esb.val('EDIT');
							}
							$('table#' + noteID).removeClass('delete');
							ele.empty();
							form4note(ele, id);
//							$('td.' + data.source.bibID).empty();
						//	var filename = $('table#' + noteID).find('input.noteMedia').val();
						//	var media = '';
						//	if(filename !== '') media = $('table#' + noteID).find('span.place4media').html();
						//	var upload_ele = $('table#' + noteID).find('div.upload');
						//	upload_ele.upload('save');

						//	$('table#' + noteID).find('span.place4media').removeClass('drop').html(media);
						//	$('table#' + noteID).find('span.button4media').empty();

							/*
							if($('table#' + noteID).find('input.notePublic').is(':checked') === true) {
								$('table#' + noteID).find('button.view').addClass('active');
							} else {
								$('table#' + noteID).find('button.view').removeClass('active');
							}
							*/
						}
					}
				});
				return false;
			});
		});

/*
		$.each(data.source.notes, function (i, note) {
				url = NB.api + '/get.php?note=' + note.id;
				$.getJSON(url, function (data2) {

				$('button#' + note.id + '.edit').on('click', function() {
					var values = {};
					$.each($('#form_' + note.id).serializeArray(), function(i, field) {
						values[field.name] = field.value;
					});

					if($('table#' + note.id).find('input.notePublic').is(':checked') === true) values.notePublic = 1;
					values.mediaHTML = $('#form_' + note.id).find('span.place4media').html();

					$('table#' + note.id).find('button.edit').addClass('invisible');
					$('table#' + note.id).find('button.close').removeClass('invisible');
					$('table#' + note.id).find('button.done').removeClass('invisible');

					$('table#' + note.id).find('input, textarea, select').attr('readonly', false);
					$('table#' + note.id).find('input.notePublic').attr({'disabled': false});

					$('table#' + note.id).find('button.view').removeClass('invisible').on('click', function(){
						$('table#' + note.id).find('input.notePublic').click();
						$(this).toggleClass('active');
					});
					$('table#' + note.id).find('button.trash').removeClass('invisible').on('click', function(){
						$('table#' + note.id).find('input.deleteNote').click();
						$(this).toggleClass('active');
					});

					var upload_ele = $('table#' + note.id).find('div.upload');
					var file_ele = $('table#' + note.id).find('input.noteMedia');
					upload_ele.upload({'file': file_ele, 'media': values.mediaHTML, 'note': note.id});

					//
					// if you cancel the edit action
					//
					$('button#' + note.id + '.close').on('click', function() {
						upload_ele.upload('cancel');
						var table = $('table#' + note.id);
						table.find('button.view').removeClass('active').addClass('invisible');
						$.each(values, function(i, v) {
							if(i === 'noteComment') {
								table.find('textarea.' + i).val(v);
							} else if (i === 'notePublic') {
								if(table.find('input.notePublic').is(':checked') === false) {
									table.find('input.notePublic').click();
									table.find('button.view').addClass('active').removeClass('invisible');
								}
							} else {
								table.find('input.' + i).val(v);
							}
						});
						if(values.hasOwnProperty('noteMedia')) table.find('span.place4media').html(values.mediaHTML);

						$('table#' + note.id).find('input.deleteNote').removeAttr('checked');
						$('table#' + note.id).find('button.trash').addClass('invisible').removeClass('active');
						$('table#' + note.id).find('button.close').addClass('invisible');
						$('table#' + note.id).find('button.done').addClass('invisible');
						$('table#' + note.id).find('button.edit').removeClass('invisible');

						//$('table#' + note.id).find('button').toggleClass('invisible');
						$('table#' + note.id).find('input, textarea, select').attr('readonly', true);
						$('table#' + note.id).find('input.notePublic').attr({'disabled': true});
					});
				});



					if(i !== data.source.notes.length) {
						ele.append($('<hr>'));
					}

				});

		});
		*/
	};






	// -------------------------------------------------------------------------
	// define the methods here
	// -------------------------------------------------------------------------

	var methods = {
		/*========================================================================*/
		init: function() {
			return this.each(function() {
				var $this = $(this),
					localdata = {};

				localdata.settings = {};

				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);



			});											// end "return this.each"
		},												// end "init"


		add: function(action) {
			return this.each(function () {
				var $this = $(this);
				var localdata = $this.data('localdata');
				//console.log(localdata);
				var form = {}, bibtyp, source, recent, recent_url, recent_data, opt_val;

				form.source = {};
				form.note = {};

				// select a source first
				$this.html($('<div>')
					.addClass('form_frame')
					.append($('<div>').addClass('top active')
						.append($('<table>').addClass('source_form')
							.append(form.source.info = $('<tr>')
								.append($('<th>')
									.attr({'colspan': '2'})
									.addClass('left')
									.append($('<p>').text('Select a source / theme or ')
										.append(form.source.new = $('<input>')
											.attr({
												'name': 'newsource',
												'type': 'button',
												'value': 'ADD NEW'
											})
											.css({'float': 'right'})
											.addClass('button medium new')
											.on('click', function() {
												form.source.selected.empty();
												form.note.container.empty();
												form4source(form.note.container, '0');
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
										form.note.container.empty();
										if(form.source.typ.val() === '0') {
											$.ajax({
												'async': false,
												'global': false,
												'url': NB.api + 'get.php?recent=3',
												'dataType': 'json',
												'success': function (data) {
													selOption(form.source.bib, 'recent=3', form.source.selected, form.note.container);
												}
											});
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
											form.source.selected
											.append(form.source.edit =
												$('<input>').attr({
													'name': 'editsource',
													'type': 'button'
												})
												.val('EDIT')
												.css({'float': 'right'})
												.addClass('button medium edit')

												.on('click', function() {
													var sourceID = form.source.selected.attr('id');
													if($(this).hasClass('close')) {
														$(this).toggleClass('edit close');
														$(this).val('EDIT');
														form.note.container.empty();
														form4note(form.note.container, sourceID);
													} else {
														if(sourceID !== '0') {
															$(this).toggleClass('edit close');
															$(this).val('CANCEL');
															form.note.container.empty();
															form4source(form.note.container, sourceID);
														} else {
															alert('You have to choose a source first');
														}
													}
												})
											);
											form4note(form.note.container, form.source.bib.val());
										});
									}))
								)
							)

							.append($('<tr>')
								.append(form.source.selected = $('<td>')
									.attr({'colspan': '2'})
									.addClass('selected center')
								)
							)
						)
					)
					.append(
						$('<div>').addClass('bottom')
						.append(form.source.addnote =
							$('<button>')
							.addClass('btn grp_none plus addnote')
							.attr({'type': 'button', 'title': 'ADD new note to this source'})
							.on('click', function() {
								var sourceID = form.source.selected.attr('id');
								if($(this).hasClass('close')) {
									$(this).toggleClass('plus close');
									$(this).removeAttr('id');
									$('table.source_form').find('input, textarea, select').attr('disabled', false);
									form.note.container.empty();
									form4note(form.note.container, sourceID);
								} else {
									if(sourceID !== '0') {
										$(this).toggleClass('plus close');
										$(this).attr({'id': sourceID});
										$('table.source_form').find('input, textarea, select').attr('disabled', true);
										form.note.container.empty();
										form4newnote(form.note.container);
									} else {
										alert('You have to choose a source first');
									}
								}
							})
						)
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

				form.source.new.on('click', function() {

				});
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

		anotherMethod: function() {
			return this.each(function(){
				var $this = $(this);
				var localdata = $this.data('localdata');
			});
		}
		/*========================================================================*/
	};



	$.fn.form = function(method) {
		// Method calling logic
		if ( methods[method] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			throw 'Method ' + method + ' does not exist on jQuery.form';
		}
	};
})( jQuery );
