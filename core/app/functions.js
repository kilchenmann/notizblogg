function getAuthors(author, sep) {
	// authors
	var i = 0, authors;
	if(sep === undefined) sep = ', ';
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
	if(sep !== ', ') {
		return htmlDecode(authors);
	} else {
		return authors;
	}
}
function getLocations(location, sep) {
	// locations
	var i = 0, locations;
	if(sep === undefined) sep = ', ';
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
	if(start <= end || end === '0') {
		pages = start;
	} else {
		pages = start + '-' + end;
	}
	return pages;
}

function getLabel(label, sep) {
	var i = 0, labels;
	if(sep === undefined) sep = ', ';
	if(label.length !== 0) {
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

function getSource(data) {
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
		if (source.editor === '1') {
			bibtex += 'editor = {' + authors + '},<br>';
			biblio += authors + ' (Hg.): ';
		} else {
			bibtex += 'author = {' + authors + '},<br>';
			biblio += authors + ': ';
		}
		// title
		bibtex += 'title = {' + source.title + '},<br>';
		if (source.title !== '') {
			if ((source.bibTyp.name === 'collection' || source.bibTyp.name === 'proceedings' || source.bibTyp.name === 'book')  && jQuery.isEmptyObject(source.insource) === false) {
				biblio += '<a href=\'?collection=' + source.id + '\' >' + getLastChar(source.title) + '</a> ';
			} else {
				biblio += '<a href=\'?source=' + source.id + '\' >' + getLastChar(source.title) + '</a> ';
			}
			footnote = biblio;
			if (source.subtitle !== '') {
				bibtex += 'subtitle = {' + source.subtitle + '},<br>';
				biblio += getLastChar(source.subtitle);
			}
		}
		if ('detail' in data.source) {
			switch (source.bibTyp.name) {
				case 'article':
					bibtex += 'journaltitle = {' + source.detail.journaltitle + '},<br>';
					bibtex += 'number = {' + source.detail.number + '},<br>';
					bibtex += 'year = {' + source.detail.year + '},<br>';
					bibtex += 'pages = {' + source.detail.pages + '},<br>';
					biblio += ' In: ' + source.detail.journaltitle + ' ' + source.detail.number + '/' + source.detail.year + ', ' + source.detail.pages + '.';
					break;

				case 'online':
					bibtex += 'url = {<a target=\'_blank\' href=\'' + source.detail.url + '\' >' + source.detail.url + '</a>},<br>';
					bibtex += 'urldate = {' + source.detail.urldate + '},<br>';
					biblio += ', URL: <a target=\'_blank\' href=\'' + source.detail.url + '\'>' + source.detail.url + '</a> (Stand: ' + source.detail.urldate + ')';
					break;

				case 'proceedings':
					bibtex += 'eventtitle = {' + source.detail.eventtitle + '},<br>';
					bibtex += 'venue = {' + source.detail.venue + '},<br>';
					bibtex += 'location = {' + locations + '},<br>';
					biblio += getLastChar(source.detail.eventtitle);
					biblio += getLastChar(source.detail.venue);
					//biblio += locations + ', ' + source.date.year + '.';
					break;

				case 'report':
				case 'thesis':
					bibtex += 'type = {' + source.detail.type + '},<br>';
					bibtex += 'institution = {' + source.detail.institution + '},<br>';
					biblio += '(' + source.detail.type + ')' + getLastChar(source.detail.institution);
					break;

				case 'inbook':
				case 'incollection':
				case 'inproceedings':
					crossref = source.detail.crossref.source;
					var crossTitle = '', crossAuthors, crossLocations;
					bibtex += 'crossref = {' + crossref.name + '},<br>';
					//console.log(crossref.author);
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
					} else {
						crossTitle += '<a href=\'?source=' + crossref.id + '\' >' + getLastChar(crossref.title) + '</a> ';
					}
					if (crossref.subtitle !== '') {
						crossTitle += getLastChar(crossref.subtitle);
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
						if(source.detail.detailKey !== undefined){
							bibtex += detailKey + ' = {' + source.detail.detailKey + '},<br>';
							biblio += source.detail.detailKey;
						}
						i += 1;
					}
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
function getUrlVars()
{
	var vars = [], hash;
	var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	for(var i = 0; i < hashes.length; i++)
	{
		hash = hashes[i].split('=');
		vars.push(hash[0]);
		vars[hash[0]] = hash[1];
	}
	return vars;
}

function fileExists(media)
{
	var response = jQuery.ajax({
		url: media,
		type: 'HEAD',
		async: false
	}).status;
	//return (response != "200") ? false : true;
	return (response == "200");
}

function isTouchDevice()
{
	var el = document.createElement('div');
	el.setAttribute('ongesturestart', 'return;');
	return typeof el.ongesturestart == "function"; // true or false
}

function getLastChar(string)
{
	string = string.charAt(0).toUpperCase() + string.slice(1)
	var lastChar = string.substr(string.length - 1);
	if ((lastChar !== '?') && (lastChar !== '!')) {
		string += '.';
	}
	return(string + ' ');
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
