/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function() {
	var moreCmd = {
		exec: function(editor) {
			editor.insertHtml('<hr id="readmore" />');
		}
	};

	CKEDITOR.plugins.add('more', {
		lang: [ 'en', 'ru' ],
		init: function(editor) {
			editor.addCommand('more', moreCmd);
			editor.ui.addButton('More', {
				label: editor.lang.more.title,
				command: 'more',
				icon: this.path + 'more.gif'
			});
		}
	});
})();
