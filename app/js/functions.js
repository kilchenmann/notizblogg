function split(val) {
	return val.split(/ \/ \s*/);
}

function extractLast(term) {
	return split(term).pop();
}

function completeMultipleValues(list, field, sep) {
	if (sep === undefined) sep = ' / ';
	var availableTags = [];
	var url = NB.api + '/get.php?list=' + list;
	$.ajax({
		'async': false,
		'global': false,
		'url': url,
		'dataType': 'json',
		'success': function(data) {
			for (var k in data.notes) {
				availableTags.push(data.notes[k].split('::')[1]);
			}
		}
	});
	/*
		$.getJSON(url, function (data) {
			for (var k in data.notes) {
				availableTags.push(data.notes[k].split('::')[1]);
			}
		});
	*/
	// don't navigate away from the field on tab when selecting an item
	field
		.bind("keydown", function(event) {
			if (event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 2,
			source: function(request, response) {
				// delegate back to autocomplete, but extract the last term
				response($.ui.autocomplete.filter(
					availableTags, extractLast(request.term)));
			},
			focus: function() {
				// prevent value inserted on focus
				return false;
			},
			select: function(event, ui) {
				var terms = split(this.value);
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push(ui.item.value);
				// add placeholder to get the comma-and-space at the end
				terms.push("");
				this.value = terms.join(sep);
				return false;
			}
		});
}


function tex2html(string) {
	if (string !== undefined) {
		string = string.replace(/ ``/g, ' "');
		string = string.replace(/\'\' /g, '" ');
		string = string.replace(/\'\'\, /g, '", ');
		string = string.replace(/\'\'\. /g, '". ');
		string = string.replace(/ `/g, ' \'');
		string = string.replace(/ -- /g, ' — ');
		string = string.replace(/ß/g, 'ss');
		string = string.replace(/\_/g, '_');
		string = string.replace(/\§/g, '§');
		string = string.replace(/\%/g, '%');
		string = string.replace(/\$/g, '$');
		string = string.replace(/\#/g, '#');
		string = string.replace(/\{/g, '{');
		string = string.replace(/\}/g, '}');
		string = string.replace(/\\textasciitilde/g, '~');
		string = string.replace(/\\texteuro/g, '€');
		return string;
	}
}
// ' &quot;'=>' ``', '&quot; '=>'\'\' ', '&quot;, '=>'\'\', ', '&quot;. '=>'\'\'. ', ' &#039;'=>' `', '&#039; '=>'\' ', '&#039;, '=>'\', ', '&#039;. '=>'\'. '
function html2tex(string, author) {
	if (string !== undefined) {
		string = string.replace(/ "/g, ' ``');
		string = string.replace(/ &quot;/g, ' ``');
		string = string.replace(/" /g, '\'\' ');
		string = string.replace(/&quot; /g, '\'\' ');
		string = string.replace(/", /g, '\'\', ');
		string = string.replace(/&quot;, /g, '\'\', ');
		string = string.replace(/". /g, '\'\'. ');
		string = string.replace(/&quot;. /g, '\'\'. ');
		string = string.replace(/ '/g, ' `');
		string = string.replace(/ &#039;/g, ' `');
		string = string.replace(/ — /g, ' -- ');
		string = string.replace(/ &ndash; /g, ' -- ');
		string = string.replace(/ &mdash; /g, ' -- ');
		string = string.replace(/ —, /g, ' --, ');
		string = string.replace(/ &ndash;, /g, ' --, ');
		string = string.replace(/ &mdash;, /g, ' --, ');
		string = string.replace(/ß/g, 'ss');
		string = string.replace(/_/g, '\\_');
		string = string.replace(/§/g, '\\§');
		string = string.replace(/%/g, '\\%');
		string = string.replace(/\$/g, '\\$');
		string = string.replace(/& /g, '\\& ');
		string = string.replace(/&amp;/g, '\\&');
		string = string.replace(/ #/g, ' \\#');
		string = string.replace(/~/g, '\\textasciitilde');
		string = string.replace(/€/g, '\\texteuro');
		if (author !== 'author') {
			string = string.replace(/{/g, '\\{');
			string = string.replace(/}/g, '\\}');
		}
		string = string.replace(/ä/g, '\\"{a}');
		string = string.replace(/ö/g, '\\"{o}');
		string = string.replace(/ü/g, '\\"{u}');
		string = string.replace(/Ä/g, '\\"{A}');
		string = string.replace(/Ö/g, '\\"{O}');
		string = string.replace(/Ü/g, '\\"{U}');
		string = string.replace(/é/g, '\\´{e}');
		string = string.replace(/è/g, '\\`{e}');
		string = string.replace(/à/g, '\\`{a}');
		string = string.replace(/ù/g, '\\`{u}');
		string = string.replace(/ô/g, '\\^{o}');

		return string;
	}
}


function getAuthors(author, sep) {
	// authors
	var i = 0,
		authors;
	if (sep === undefined) sep = ', ';

	while (i < author.length) {
		//		alert(author[i].name);
		//		if(author[i].name.charAt(0) === '{') author[i].name = '{' + author[i].name.substring(1, author[i].name.length) + '}';
		if (authors === undefined) {
			authors = '<a href=\'' + NB.url + '?author=' + author[i].id + '\'>' + author[i].name + '</a>';
		} else {
			authors += sep + '<a href=\'' + NB.url + '?author=' + author[i].id + '\'>' + author[i].name + '</a>';
		}
		i += 1;
	}

	/*
		if (author.length > 4) {
			authors = '<a href=\'?author=' + author[i].id + '\'>' + author[i].name + '</a> et al.';
		} else {
			while (i < author.length) {

				if (authors === undefined) {
					authors = '<a href=\'' + NB.url + '?author=' + author[i].id + '\'>' + author[i].name + '</a>';
				} else {
					authors += sep + '<a href=\'' + NB.url + '?author=' + author[i].id + '\'>' + author[i].name + '</a>';
				}
				i += 1;
			}
		}
		*/
	if (sep !== ', ' && sep !== ' and ') {
		return htmlDecode(authors);
	} else {
		return authors;
	}
}

function getLocations(location, sep) {
	// locations
	var i = 0,
		locations;
	if (sep === undefined) sep = ', ';
	if (location.length > 4) {
		locations = location[i].name + ' et al.';
	} else {
		while (i < location.length) {
			if (locations === undefined) {
				locations = location[i].name;
			} else {
				locations += sep + location[i].name;
			}
			i += 1;
		}
	}
	return htmlDecode(locations);
}

function getPages(start, end) {
	var pages;
	if (start >= end || end === '0') {
		pages = start;
	} else {
		pages = start + '-' + end;
	}
	if (start !== '0') {
		return pages;
	} else {
		return '';
	}

}

function getLabel(label, sep) {
	var i = 0,
		labels;
	if (sep === undefined) sep = ', ';
	if (label.length !== 0) {
		while (i < label.length) {
			if (labels === undefined) {
				labels = label[i].name;
			} else {
				labels += sep + label[i].name;
			}
			i += 1;
		}
	}
	return htmlDecode(labels);
}

function getSource(data, list) {
	var i, authors, authors4tex, locations, biblio, bibtex, footnote, source, bib, crossref, insource = {};
	// return source.biblio, source.bibtex;
	source = data.source;
	if (source.id !== 0) {
		bibtex = '@' + source.bibTyp.name + '{';

		if ((source.bibTyp.name === 'collection' || source.bibTyp.name === 'proceedings' || source.bibTyp.name === 'book') && jQuery.isEmptyObject(source.insource) === false) {
			bibtex += '<a href=\'?collection=' + source.id + '\' >' + source.name + ',</a><br>';
		} else {
			bibtex += '<a href=\'?source=' + source.id + '\' >' + source.name + ',</a><br>';
		}
		biblio = '';
		locations = getLocations(source.location);

		authors4tex = getAuthors(source.author, ' and ');
		authors = getAuthors(source.author);
		// authors
		if (source.editor === '1') {
			bibtex += 'editor = {' + html2tex(authors4tex, 'author') + '},<br>';
			biblio += authors + ' (Hg.): ';
		} else {
			bibtex += 'author = {' + html2tex(authors4tex, 'author') + '},<br>';
			biblio += authors + ': ';
		}
		// title
		bibtex += 'title = {' + html2tex(getLastChar(source.title)) + '},<br>';
		if (source.title !== '') {
			if ((source.bibTyp.name === 'collection' || source.bibTyp.name === 'proceedings' || source.bibTyp.name === 'book') && jQuery.isEmptyObject(source.insource) === false) {
				biblio += '<a href=\'?collection=' + source.id + '\' >' + getLastChar(source.title) + '</a> ';
			} else {
				biblio += '<a href=\'?source=' + source.id + '\' >' + getLastChar(source.title) + '</a> ';
			}
			footnote = biblio;
			if (source.subtitle !== '') {
				bibtex += 'subtitle = {' + html2tex(getLastChar(source.subtitle)) + '},<br>';
				biblio += getLastChar(source.subtitle);
			}
		}
		if ('detail' in data.source) {
			switch (source.bibTyp.name) {
				case 'article':
					bibtex += 'journal = {' + html2tex(getLastChar(source.detail.journaltitle)) + '},<br>';
					bibtex += 'volume = {' + source.detail.number + '},<br>';
					bibtex += 'date = {' + source.detail.date + '},<br>';
					bibtex += 'pages = {' + source.detail.pages + '},<br>';
					biblio += ' In: ' + source.detail.journaltitle + ' Nr. ' + source.detail.number + ': ' + source.detail.date + ', S. ' + source.detail.pages + '.';
					break;

				case 'online':
					bibtex += 'url = {<a target=\'_blank\' href=\'' + source.detail.url + '\' >' + source.detail.url + '</a>},<br>';
					bibtex += 'urldate = {' + source.detail.urldate + '},<br>';
					biblio += 'URL: <a target=\'_blank\' href=\'' + source.detail.url + '\'>' + source.detail.url + '</a> (Besucht am: ' + source.detail.urldate + ').';
					//					bibtex += 'year = {' + source.date.year + '},<br>';
					break;

				case 'proceedings':
					bibtex += 'eventtitle = {' + html2tex(getLastChar(source.detail.eventtitle)) + '},<br>';
					bibtex += 'venue = {' + html2tex(getLastChar(source.detail.venue)) + '},<br>';
					//	bibtex += 'location = {' + html2tex(locations) + '},<br>';
					biblio += getLastChar(source.detail.eventtitle);
					biblio += getLastChar(source.detail.venue);
					//biblio += locations + ', ' + source.date.year + '.';
					break;

				case 'report':
				case 'thesis':
					bibtex += 'type = {' + html2tex(source.detail.type) + '},<br>';
					bibtex += 'institution = {' + html2tex(source.detail.institution) + '},<br>';
					biblio += '(' + source.detail.type + ')' + getLastChar(source.detail.institution);
					break;

				case 'inbook':
				case 'incollection':
				case 'inproceedings':
					crossref = source.detail.crossref.source;
					var crossTitle = '',
						crossAuthors, crossLocations;
					bibtex += 'crossref = {' + crossref.name + '},<br>';
					//console.log(crossref.author);
					crossAuthors = getAuthors(crossref.author, ' and ');
					bibtex += 'editor = {' + html2tex(crossAuthors) + '},<br>';

					crossAuthors = getAuthors(crossref.author);
					// authors
					if (source.detail.crossref.source.editor === '1') {
						crossAuthors += ' (Hg.): ';
					} else {
						crossAuthors += ': ';
					}
					crossLocations = getLocations(crossref.location);
					if (crossref.bibTyp.name === 'collection' || crossref.bibTyp.name === 'proceedings' || crossref.bibTyp.name === 'book') {
						crossTitle = '<a href=\'?collection=' + crossref.id + '\' >' + getLastChar(crossref.title) + '</a> ';
						bibtex += 'booktitle = {<a href=\'?collection=' + crossref.id + '\' >' + html2tex(getLastChar(crossref.title)) + '</a>},<br>';
					} else {
						crossTitle += '<a href=\'?source=' + crossref.id + '\' >' + getLastChar(crossref.title) + '</a> ';
						bibtex += 'booktitle = {<a href=\'?source=' + crossref.id + '\' >' + html2tex(getLastChar(crossref.title)) + '</a>},<br>';

					}
					if (crossref.subtitle !== '') {
						crossTitle += getLastChar(crossref.subtitle);
						bibtex += 'booksubtitle = {' + html2tex(getLastChar(crossref.subtitle)) + '},<br>';
					}
					insource.source = 'In: ' + crossAuthors + crossTitle + ' ' + crossLocations + ', ' + crossref.date.year;
					// pages
					bibtex += 'pages = {' + source.detail.pages + '},<br>';
					insource.pages = ', S. ' + source.detail.pages;
					break;

				default:
					var detailKey, countDetail = Object.keys(source.detail).length;
					i = 0;
					while (i < countDetail) {
						detailKey = Object.keys(source.detail)[i];
						if (source.detail[detailKey] !== undefined) {
							bibtex += detailKey + ' = {' + html2tex(source.detail[detailKey]) + '},<br>';
							//biblio += source.detail[detailKey];
						}
						i += 1;
					}
			}
		}

		if ('detailPlus' in data.source) {
			var detailPlusKey, countDetailPlus = Object.keys(source.detailPlus).length;
			i = 0;
			while (i < countDetailPlus) {
				detailPlusKey = Object.keys(source.detailPlus)[i];
				if (source.detailPlus[detailPlusKey] !== undefined) {
					bibtex += detailPlusKey + ' = {' + html2tex(source.detailPlus[detailPlusKey]) + '},<br>';
				}
				i += 1;
			}
		}

		if (insource.source && insource.pages) biblio += insource.source + insource.pages;
		if (crossref === undefined && source.bibTyp.name !== 'online') {
			if (locations !== '') {
				bibtex += 'location = {' + html2tex(locations) + '},<br>';
				biblio += locations + ', ';
			}
			if (source.date.year !== '0000' && source.date.year !== '0' && source.date.year !== null && source.bibTyp.name !== 'article') {
				bibtex += 'year = {' + source.date.year + '},<br>';
				biblio += source.date.year + '.';
				footnote += source.date.year;
			}
		}
		if (!list) {
			bibtex += 'note = {' + html2tex(source.comment) + '}}';
		} else {
			bibtex += 'note = {}}';
		}
		//biblio += '';
		bib = {
			biblio: tex2html(biblio),
			bibtex: bibtex,
			footnote: tex2html(footnote)
		};
	} else {
		bib = {
			biblio: undefined,
			bibtex: undefined,
			footnote: undefined
		};
	}
	return bib;
}

function getStorage(type) {
	/*
  var storage = window[type + 'Storage'],
    delta = 0,
    li = document.createElement('li');

  if (!window[type + 'Storage']) return;

  if (storage.getItem('value')) {
    delta = ((new Date()).getTime() - (new Date()).setTime(storage.getItem('timestamp'))) / 1000;

    li.innerHTML = type + 'Storage: ' + storage.getItem('value') + ' (last updated: ' + delta + 's ago)';
  } else {
    li.innerHTML = type + 'Storage is empty';
  }

  document.querySelector('#previous').appendChild(li);
  */
}

// Read a page's GET URL variables and return them as an associative array.
function getUrlVars() {
	var vars = [],
		hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for (var i = 0; i < hashes.length; i++) {
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}

function fileExists(media) {
	var response = jQuery.ajax({
		url: media,
		type: 'HEAD',
		async: false
	}).status;
	//return (response != "200") ? false : true;
	return (response == "200");
}

function isTouchDevice() {
	var el = document.createElement('div');
	el.setAttribute('ongesturestart', 'return;');
	return typeof el.ongesturestart == "function"; // true or false
}

function getLastChar(string) {
	if (string !== null) {
		string = $.trim(string);
		string = string.charAt(0).toUpperCase() + string.slice(1);
		var lastChar = string.substr(string.length - 1);
		if ((lastChar !== '?') && (lastChar !== '!') && (lastChar !== ':') && (lastChar !== '.') && (lastChar !== '"')) {
			string += '.';
		}
		return (string + ' ');
	} else {
		return (' ');
	}
}

function formatFileSize(bytes) {
	if (typeof bytes !== 'number') {
		return '';
	}

	if (bytes >= 1000000000) {
		return (bytes / 1000000000).toFixed(2) + ' GB';
	}

	if (bytes >= 1000000) {
		return (bytes / 1000000).toFixed(2) + ' MB';
	}

	return (bytes / 1000).toFixed(2) + ' KB';
}

function htmlEncode(value) {
	return $('<div/>').text(value).html();
}

function htmlDecode(value) {
	return $('<div/>').html(value).text();
}

/*
var activator = function (element) {
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
	};
	*/
