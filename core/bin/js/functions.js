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
	var lastChar = string.substr(string.length - 1);
	if ((lastChar !== '?') && (lastChar !== '!')) {
		string += '.';
	}
	return(string + ' ');
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
