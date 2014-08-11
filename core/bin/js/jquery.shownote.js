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

	var getLastChar = function(string){

		var lastChar = string.substr(string.length - 1);

		if((lastChar !== '?') && (lastChar !== '!')) {
			string +=  '.';
		}
		return(string + ' ');
	},
		getTypeID = function(query) {
			alert(query);
		},

		dispNote = function(ele, data, localdata) {
			var media, latex, classNote, classLabel, label;
			if(data.id !== 0) {
				if (data.biblio !== null) {
					latex = '``' + data.comment + '\'\'';
				} else {
					latex = '';
				}
				if (data.type === 'source') {
					classNote = 'note topic';
				} else {
					classNote = 'note item';
				}

				if (data.public === '1') {
					classLabel = 'label private'
				} else {
					classLabel = 'label'
				}

				ele.append(
					$('<div>').addClass(classNote).attr({'id': data.id})
						.append(
						$('<div>').addClass('media')
					)
						.append(
						$('<div>').addClass('text')
							.append($('<h3>').html(data.title))
							.append($('<p>').html(data.comment))
					)
						.append(
						$('<div>').addClass('latex')
							.append($('<h3>').html(data.title))
							.append($('<p>').html(latex))
					)
						.append(
						label = $('<div>').addClass(classLabel)
					)
						.append(
						$('<div>').addClass('tool')
					)
				);

				$.each(data.label, function (i, noteLabel) {
					label.append(
						$('<a>').attr({href: '?label=' + noteLabel.id, title: noteLabel.name}).html(' ' + noteLabel.name)
					)
				})
			} else {
				$('#fullpage').warning({
					type: 'noresults',
					lang: 'de'
				});
				$('body').on('click', function(){
					window.location.href = localdata.settings.url;
				})
			}
		},

		dispBib = function(ele, data, localdata) {
			var authors, locations, bibtex, biblio, i;

			if (data.biblio.bibTyp.id !== '0') {
				authors = '';
				locations = '';
				bibtex = '@' + data.biblio.bibTyp.name + '{' + data.biblio.name + '<br>';
				biblio = '';

				i = 0;
				while (i < data.biblio.author.length) {
					if (authors === '') {
						authors = '<a href=\'?author=' + data.biblio.author[i].id + '\'>' + data.biblio.author[i].name + '</a>';
					} else {
						authors += ', <a href=\'?author=' + data.biblio.author[i].id + '\'>' + data.biblio.author[i].name + '</a>';
					}
					i += 1;
				}

				i = 0;
				while (i < data.biblio.location.length) {
					if (locations === '') {
						locations = data.biblio.location[i].name;
					} else {
						locations += ', ' + data.biblio.location[i].name;
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

				bibtex += 'title = {' + data.title + '},<br>';

				if (data.biblio.bibTyp.name === 'collection' || data.biblio.bibTyp.name === 'proceedings' || data.biblio.bibTyp.name === 'book') {
					biblio += '<a href=\'?collection=' + data.biblio.id + '\' >' + getLastChar(data.title) + '</a> ';
				} else {
					biblio += '<a href=\'?source=' + data.biblio.id + '\' >' + getLastChar(data.title) + '</a> ';
				}
				if (data.subtitle !== '') {
					bibtex += 'subtitle = {' + data.subtitle + '},<br>';
					biblio += getLastChar(data.subtitle);
				}


				if ('crossref' in data) {
					var crossAuthors = '';
					// set the authors
					i = 0;
					while (i < data.crossref.author.length) {

						if (crossAuthors == '') {
							crossAuthors = '<a href=\'?author=' + data.crossref.author[i].id + '\'>' + data.crossref.author[i].name + '</a>';
						} else {
							crossAuthors += ', <a href=\'?author=' + data.crossref.author[i].id + '\'>' + data.crossref.author[i].name + '</a>';
						}
						i += 1;
					}
					var crossLocations = '';
					// set the locations
					i = 0;
					while (i < data.crossref.location.length) {
						if (crossLocations === '') {
							crossLocations = data.crossref.location[i].name;
						} else {
							crossLocations += ', ' + data.crossref.location[i].name;
						}
						i += 1;
					}

					bibtex += 'crossref = {<a href=\'?collection=' + data.crossref.id + '\'>' + data.crossref.name + '</a>},<br>';

					biblio += 'In: ';
					if (data.crossref.editor === 1) {
						bibtex += 'editor = {' + crossAuthors + '},<br>';
						biblio += crossAuthors + ' (Hg.): ';
					} else {
						bibtex += 'author = {' + crossAuthors + '},<br>';
						biblio += crossAuthors + ': ';
					}
					bibtex += 'booktitle = {' + (data.crossref.title) + '},<br>';
					biblio += '<a href=\'?collection=' + data.crossref.id + '\'>' + getLastChar(data.crossref.title) + ' </a>';

					if (data.crossref.subtitle != '') {
						bibtex += 'booksubtitle = {' + (data.crossref.subtitle) + '},<br>';
						biblio += getLastChar(data.crossref.subtitle);
					}

					if ('location' in data.crossref) {
						bibtex += 'location = {' + crossLocations + '},<br>';
						biblio += crossLocations + ', ';
					}
					if (data.crossref.year != '0000') {
						bibtex += 'year = {' + data.crossref.year + '},<br>';
						biblio += data.crossref.year;
					}

				} else {
					if (locations !== '') {
						bibtex += 'location = {' + locations + '},<br>';
						biblio += locations + ', ';
					}
					if (data.year !== '0000') {
						bibtex += 'year = {' + data.year + '},<br>';
						biblio += data.year;
					}
				}

				if('detail' in data) {

					var detailKey, countDetail = Object.keys(data.detail).length;
					i = 0;
					while (i < countDetail) {
						detailKey = Object.keys(data.detail)[i];
						switch (detailKey) {
							case 'url':
								bibtex += 'url = {<a target=\'_blank\' href=\'' + data.detail.url + '\' >' + data.detail.url + '</a>},<br>';
								biblio += ', URL: <a target=\'_blank\' href=\'' + data.detail.url + '\'>' + data.detail.url + '</a> ';
								break;

							case 'urldate':
								bibtex += 'urldate = {' + data.detail.urldate + '},<br>';
								biblio += '(Stand: ' + data.detail.urldate + ')';
								break;

							case 'pages':
								bibtex +=  'pages = {' + data.detail.pages + '},<br>';
								biblio +=  ', S. ' + data.detail.pages;
								break;

							default:
								bibtex += detailKey + ' = {' + data.detail.detailKey + '},<br>';
								biblio += data.detail.detailKey;
						}
						i += 1;
					}
				}
				bibtex += 'note = {' + data.comment + '}}';
				biblio += '.';
			} else {
				bibtex = 'The data are not yet ready to use in laTex.';
				biblio = '<a href=\'?source=' + data.id + '\' >' + data.comment + '</a>';
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
		init: function(options) {
			return this.each(function() {
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
					localdata.view.wall = $('<div>').addClass('wall')
				);

				// which data do we need?
				// 'api/get/[id]' brings all notes and sources with the [id]
				// 'api/list/label/[id]' brings a list of noteIDs with the label [id]
				// 'api/list/author([id]' brings a list of noteIDs with the author [id]


				switch(localdata.settings.query.type) {
					case 'note':
						url= localdata.settings.url + '/get/' + localdata.settings.query.id;
						$.getJSON(url, function(data) {
//							$.each(data,function(i,note){
								dispNote(localdata.view.wall, data, localdata);

//							})

						});
						break;
					case 'source':
						url= localdata.settings.url + '/get/' + localdata.settings.query.id;
						$.getJSON(url, function(data) {
//							$.each(data,function(i,note){
							localdata.view.source = dispBib(localdata.view.wall, data, localdata);
							localdata.view.wall.append(localdata.view.source.biblio);

//							})

						});
						break;

					case 'label':
						url= localdata.settings.url + '/list/' + localdata.settings.query.type + '/' + localdata.settings.query.id;
						$.getJSON(url, function(list) {
							$.each(list.notes,function(i,noteID){
								url= localdata.settings.url + '/get/' + noteID;
								$.getJSON(url, function(data) {
//									$.each(data, function (i, note) {
										dispNote(localdata.view.wall, data, localdata);

//									})
								})

							})

						});
						break;

					case 'author':
						url= localdata.settings.url + '/list/' + localdata.settings.query.type + '/' + localdata.settings.query.id;
						$.getJSON(url, function(list) {
							$.each(list.notes,function(i,noteID){
								url= localdata.settings.url + '/get/' + noteID;
								$.getJSON(url, function(data) {
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

				}


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



				if(localdata.settings.type === 'source'){
					$.getJSON('get/' + localdata.settings.type + '/' + localdata.settings.id, function(data) {
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



		setNote2Wall: function() {
			return this.each(function(){
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
							console.log(num_col);
			//	}

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



	$.fn.shownote = function(method) {
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
