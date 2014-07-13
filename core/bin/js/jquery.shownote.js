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

		showBib = function(data) {
			var authors, locations, bibtex, biblio, i;

			if (data.bibTyp.id !== '0') {
				authors = '';
				locations = '';
				bibtex = '@' + data.bibTyp.name + '{' + data.name;
				biblio = '';

				i = 0;
				while (i < data.author.length) {
					if (authors === '') {
						authors = '<a href=\'?author=' + data.author[i].id + '\'>' + data.author[i].name + '</a>';
					} else {
						authors += ', <a href=\'?author=' + data.author[i].id + '\'>' + data.author[i].name + '</a>';
					}
					i += 1;
				}

				i = 0;
				while (i < data.location.length) {
					if (locations === '') {
						locations = data.location[i].name;
					} else {
						locations += ', ' + data.location[i].name;
					}
					i += 1;
				}
				if (data.editor === 1) {
					bibtex += 'editor = {' + authors + '},';
					biblio += authors + '(Hg.): ';
				} else {
					bibtex += 'author = {' + authors + '},';
					biblio += authors + ': ';
				}

				bibtex += 'title = {' + data.title + '},';

				if (data.bibTyp.name === 'collection' || data.bibTyp.name === 'proceedings' || data.bibTyp.name === 'book') {
					biblio += '<a href=\'?collection=' + data.id + '\' >' + getLastChar(data.title) + '</a> ';
				} else {
					biblio += '<a href=\'?source=' + data.id + '\' >' + getLastChar(data.title) + '</a> ';
				}
				if (data.subtitle !== '') {
					bibtex += 'subtitle = {' + data.subtitle + '}';
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

					bibtex += 'crossref = {<a href=\'?collection=' + data.crossref.id + '\'>' + data.crossref.name + '</a>},';

					biblio += 'In: ';
					if (data.crossref.editor === 1) {
						bibtex += 'editor = {' + crossAuthors + '},';
						biblio += crossAuthors + ' (Hg.): ';
					} else {
						bibtex += 'author = {' + crossAuthors + '},';
						biblio += crossAuthors + ': ';
					}
					bibtex += 'booktitle = {' + (data.crossref.title) + '},';
					biblio += '<a href=\'?collection=' + data.crossref.id + '\'>' + getLastChar(data.crossref.title) + ' </a>';

					if (data.crossref.subtitle != '') {
						bibtex += 'booksubtitle = {' + (data.crossref.subtitle) + '},';
						biblio += getLastChar(data.crossref.subtitle);
					}

					if ('location' in data.crossref) {
						bibtex += 'location = {' + crossLocations + '},';
						biblio += crossLocations + ', ';
					}
					if (data.crossref.year != '0000') {
						bibtex += 'year = {' + data.crossref.year + '},';
						biblio += data.crossref.year;
					}

				} else {
					if (locations !== '') {
						bibtex += 'location = {' + locations + '},';
						biblio += locations + ', ';
					}
					if (data.year !== '0000') {
						bibtex += 'year = {' + data.year + '},';
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
								bibtex += 'url = {<a target=\'_blank\' href=\'' + data.detail.url + '\' >' + data.detail.url + '</a>},';
								biblio += ', URL: <a target=\'_blank\' href=\'' + data.detail.url + '\'>' + data.detail.url + '</a> ';
								break;

							case 'urldate':
								bibtex += 'urldate = {' + data.detail.urldate + '},';
								biblio += '(Stand: ' + data.detail.urldate + ')';
								break;

							case 'pages':
								bibtex +=  'pages = {' + data.detail.pages + '},';
								biblio +=  ', S. ' + data.detail.pages;
								break;

							default:
								bibtex += detailKey + ' = {' + data.detail.detailKey + '},';
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
					localdata = {};

				localdata.settings = {
					type: 'source',		// source || note
					id: undefined
				};


				$.extend(localdata.settings, options);
				// initialize a local data object which is attached to the DOM object
				$this.data('localdata', localdata);

				// 1. get the data
					$.getJSON('get/' + localdata.settings.type + '/' + localdata.settings.id, function(data) {
						$this.empty();
						$this.append((showBib(data).biblio))





					})

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
