/*
 * Iconic - Seditio icon font picker for CKEditor
 * Inserts <i class="ic-*">&nbsp;</i> elements.
 */
(function() {
	CKEDITOR.plugins.add('iconic', {
		requires: 'dialog',
		lang: 'en,ru',
		icons: 'iconic',

		init: function(editor) {
			var pluginPath = this.path;
			var iconsReady = false;
			var fontsCssPath = 'system/fonts/fonts.css';
			var fontsCssUrl = CKEDITOR.getUrl(fontsCssPath);

			CKEDITOR.document.appendStyleSheet(CKEDITOR.getUrl(pluginPath + 'iconic.css'));

			if (CKEDITOR.tools.isArray(editor.config.contentsCss)) {
				if (CKEDITOR.tools.indexOf(editor.config.contentsCss, fontsCssPath) === -1) {
					editor.config.contentsCss.push(fontsCssPath);
				}
			}

			function loadIcons(callback) {
				if (iconsReady && editor.config.iconic_list && editor.config.iconic_list.length) {
					callback();
					return;
				}

				CKEDITOR.ajax.load(CKEDITOR.getUrl(pluginPath + 'icons.json'), function(data) {
					if (data) {
						try {
							editor.config.iconic_list = JSON.parse(data);
						} catch (e) {
							editor.config.iconic_list = [];
						}
					} else if (!editor.config.iconic_list) {
						editor.config.iconic_list = [];
					}
					iconsReady = true;
					callback();
				});
			}

			editor.addCommand('iconic', {
				allowedContent: 'i[class](*)',
				requiredContent: 'i[class]',
				exec: function(editor) {
					loadIcons(function() {
						editor.openDialog('iconic');
					});
				}
			});

			editor.ui.addButton('Iconic', {
				label: editor.lang.iconic.toolbar,
				command: 'iconic',
				toolbar: 'insert,51',
				icon: pluginPath + 'icons/iconic.png'
			});

			CKEDITOR.dialog.add('iconic', pluginPath + 'dialogs/iconic.js');

			editor.on('mode', function() {
				if (editor.mode === 'wysiwyg') {
					editor.document.appendStyleSheet(fontsCssUrl);
				}
			});
		}
	});
})();
