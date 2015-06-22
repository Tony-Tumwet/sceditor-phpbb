/***********************************************************
 * Update size tag to use xx-small-xx-large instead of 1-7 *
 ***********************************************************/
var sizes = ['25', '50', '75', '100', '150', '175', '200'];
$.sceditor.plugins.bbcode.bbcode.set('size', {
	format: function ($elem, content) {
		var fontSize,
			sizesIdx = 0,
			size = $elem.data('scefontsize');

		if (!size) {
			fontSize = $elem.css('fontSize');

			// Most browsers return px value but IE returns 1-7
			if (fontSize.indexOf('px') > -1) {
				// convert size to an int
				fontSize = ~~(fontSize.replace('px', ''));

				if (fontSize > 31) {
					sizesIdx = 6;
				}
				else if (fontSize > 23) {
					sizesIdx = 5;
				}
				else if (fontSize > 17) {
					sizesIdx = 4;
				}
				else if (fontSize > 15) {
					sizesIdx = 3;
				}
				else if (fontSize > 12) {
					sizesIdx = 2;
				}
				else if (fontSize > 9) {
					sizesIdx = 1;
				}
			}
			else {
				sizesIdx = ~~fontSize;
			}

			if (sizesIdx > 6) {
				sizesIdx = 6;
			}
			else if (sizesIdx < 0) {
				sizesIdx = 0;
			}

			size = sizes[sizesIdx];
		}

		return '[size=' + size + ']' + content + '[/size]';
	},
	html: function (token, attrs, content) {
		return '<span data-scefontsize="' + attrs.defaultattr + '" style="font-size:' + attrs.defaultattr + '%">' + content + '</span>';
	}
});

$.sceditor.command.set('size', {
	_dropDown: function (editor, caller, callback) {
		var content = $('<div />'),
			clickFunc = function (e) {
				callback($(this).data('size'));
				editor.closeDropDown(true);
				e.preventDefault();
			},
			size;

		for (var i = 1; i < 7; i++) {
			if (sizes[i-1] > max_fontsize) {
				break;
			}
			content.append($('<a class="sceditor-fontsize-option" data-size="' + i + '" href="#"><font size="' + i + '">' + i + '</font></a>').click(clickFunc));
		}

		editor.createDropDown(caller, 'fontsize-picker', content);
	},
	txtExec: function (caller) {
		var editor = this;

		$.sceditor.command.get('size')._dropDown(
			editor,
			caller,
			function (sizesIdx) {
				sizesIdx = ~~sizesIdx;
				sizesIdx = (sizesIdx > 6) ? 6 : ( (sizesIdx < 0) ? 0 : sizesIdx );

				editor.insertText('[size=' + sizes[sizesIdx] + ']', '[/size]');
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