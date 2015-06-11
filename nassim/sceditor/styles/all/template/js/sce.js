/***********************************************************
 * Update size tag to use xx-small-xx-large instead of 1-7 *
 ***********************************************************/
$.sceditor.plugins.bbcode.bbcode.set('size', {
	format: function ($elm, content) {
		var fontSize,
			sizes = ['25', '50', '75', '100', '150', '175', '200'],
			size = $elm.data('scefontsize');

		if (!size) {
			fontSize = $elm.css('fontSize');

			// Most browsers return px value but IE returns 1-7
			if (fontSize.indexOf('px') > -1) {
				// convert size to an int
				fontSize = fontSize.replace('px', '') - 0;
				size = 1;

				if (fontSize > 9)
					size = 2;
				if (fontSize > 12)
					size = 3;
				if (fontSize > 15)
					size = 4;
				if (fontSize > 17)
					size = 5;
				if (fontSize > 23)
					size = 6;
				if (fontSize > 31)
					size = 7;
			}
			else
				size = (~~fontSize) + 1;

			if (size > 7)
				size = 7;
			if (size < 1)
				size = 1;

			size = sizes[size - 1];
		}

		return '[size=' + size + ']' + content + '[/size]';
	},
	html: function (token, attrs, content) {
		return '<span data-scefontsize="' + attrs.defaultattr + '" style="font-size:' + attrs.defaultattr + '">' + content + '</span>';
	}
});

$.sceditor.command.set('size', {
	_dropDown: function (editor, caller, callback) {
		var content = $('<div />'),
			clickFunc = function (e) {
				callback($(this).data('size'));
				editor.closeDropDown(true);
				e.preventDefault();
			};

		for (var i = 1; i < 7; i++)
			content.append($('<a class="sceditor-fontsize-option" data-size="' + i + '" href="#"><font size="' + i + '">' + i + '</font></a>').click(clickFunc));

		editor.createDropDown(caller, 'fontsize-picker', content);
	},
	txtExec: function (caller) {
		var editor = this,
			sizes = ['xx-small', 'x-small', 'small', 'medium', 'large', 'x-large', 'xx-large'];

		$.sceditor.command.get('size')._dropDown(
			editor,
			caller,
			function (size) {
				size = (~~size);
				size = (size > 7) ? 7 : ( (size < 1) ? 1 : size );

				editor.insertText('[size=' + sizes[size] + ']', '[/size]');
			}
		);
	}
});

var textarea;
// This is needed for the smilies popup
function setSmilie(tag) {
	textarea.data('sceditor').insert(' ' + tag + ' ');
}

$(function () {
	// Don't need to select the node again and again
	textarea = $('textarea');
	// Hide the normal BBCode Buttons
	$('#format-buttons').hide();
	$('#smiley-box a img').each(function () {
		$(this).click(function () {
			setSmilie($(this).attr('alt'));
			return false;
		});
	});
});