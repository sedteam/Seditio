/*
 * Iconic dialog - icon font picker grid with search.
 */
CKEDITOR.dialog.add('iconic', function(editor) {
	var columns = editor.config.iconic_columns || 8;
	var dialogRef;
	var fontsLoaded = false;
	var fontsCssUrl = CKEDITOR.getUrl('system/fonts/fonts.css');

	function getIcons() {
		return editor.config.iconic_list || [];
	}

	function insertIcon(cls) {
		editor.insertHtml('<i class="' + CKEDITOR.tools.htmlEncodeAttr(cls) + '">&nbsp;</i>');
		dialogRef.hide();
	}

	function ensureFontsCss() {
		if (fontsLoaded) {
			return;
		}
		var head = dialogRef.getElement().getDocument().getHead();
		var link = new CKEDITOR.dom.element('link');
		link.setAttributes({
			rel: 'stylesheet',
			type: 'text/css',
			href: fontsCssUrl
		});
		head.append(link);
		fontsLoaded = true;
	}

	function buildGridHtml(icons) {
		var html = [];
		var i, col, labelId = CKEDITOR.tools.getNextId() + '_iconic_label';

		html.push('<div class="cke_iconic_wrap">');
		html.push('<label class="cke_iconic_search">');
		html.push('<input type="search" class="cke_iconic_filter" placeholder="' +
			CKEDITOR.tools.htmlEncode(editor.lang.iconic.searchPlaceholder) + '" />');
		html.push('</label>');
		html.push('<span id="' + labelId + '" class="cke_voice_label">' +
			CKEDITOR.tools.htmlEncode(editor.lang.iconic.options) + '</span>');
		html.push('<table class="cke_iconic_grid" role="listbox" aria-labelledby="' + labelId +
			'" cellspacing="0" cellpadding="0" border="0"><tbody>');

		for (i = 0; i < icons.length; i++) {
			if (i % columns === 0) {
				html.push('<tr role="presentation">');
			}

			html.push(
				'<td class="cke_iconic_cell" role="presentation">' +
				'<a href="javascript:void(0)" class="cke_iconic_item" role="option"' +
				' data-icon-class="' + CKEDITOR.tools.htmlEncodeAttr(icons[i].cls) + '"' +
				' title="' + CKEDITOR.tools.htmlEncodeAttr(icons[i].title) + '">' +
				'<i class="' + CKEDITOR.tools.htmlEncodeAttr(icons[i].cls) + '"></i>' +
				'<span class="cke_voice_label">' + CKEDITOR.tools.htmlEncode(icons[i].title) + '</span>' +
				'</a></td>'
			);

			if (i % columns === columns - 1) {
				html.push('</tr>');
			}
		}

		if (icons.length % columns !== 0) {
			for (col = icons.length % columns; col < columns; col++) {
				html.push('<td class="cke_iconic_cell" role="presentation"></td>');
			}
			html.push('</tr>');
		}

		html.push('</tbody></table>');
		html.push('<div class="cke_iconic_empty" style="display:none;"></div>');
		html.push('</div>');
		return html.join('');
	}

	function filterGrid(query) {
		var element = dialogRef.getContentElement('tab-icons', 'iconGrid').getElement();
		var items = element.find('a.cke_iconic_item');
		var emptyMsg = element.findOne('.cke_iconic_empty');
		var q = (query || '').toLowerCase();
		var visible = 0;
		var i, item, cls, title;

		for (i = 0; i < items.count(); i++) {
			item = items.getItem(i);
			cls = (item.getAttribute('data-icon-class') || '').toLowerCase();
			title = (item.getAttribute('title') || '').toLowerCase();
			if (!q || cls.indexOf(q) !== -1 || title.indexOf(q) !== -1) {
				item.getParent().removeClass('cke_iconic_hidden');
				visible++;
			} else {
				item.getParent().addClass('cke_iconic_hidden');
			}
		}

		if (emptyMsg) {
			if (visible === 0) {
				emptyMsg.setText(editor.lang.iconic.emptyResults);
				emptyMsg.setStyle('display', 'block');
			} else {
				emptyMsg.setStyle('display', 'none');
			}
		}
	}

	function bindSearchInput(element) {
		var input = element.findOne('.cke_iconic_filter');
		if (!input || input.data('iconic-bound')) {
			return;
		}
		input.data('iconic-bound', true);
		input.on('input', function() {
			filterGrid(input.getValue());
		});
		input.on('keydown', function(evt) {
			if (evt.data.getKeystroke() === 13) {
				var first = element.findOne('td:not(.cke_iconic_hidden) a.cke_iconic_item');
				if (first) {
					insertIcon(first.getAttribute('data-icon-class'));
				}
				evt.data.preventDefault();
			}
		});
	}

	function onGridClick(evt) {
		var target = evt.data.getTarget();
		var link = target.getName() === 'a' ? target : target.getAscendant('a');
		if (link && link.hasClass('cke_iconic_item') && link.getAttribute('data-icon-class')) {
			insertIcon(link.getAttribute('data-icon-class'));
			evt.data.preventDefault();
		}
	}

	return {
		title: editor.lang.iconic.title,
		minWidth: Math.max(420, columns * 34 + 52),
		minHeight: 360,
		contents: [{
			id: 'tab-icons',
			label: editor.lang.iconic.title,
			elements: [{
				type: 'html',
				id: 'iconGrid',
				html: buildGridHtml(getIcons()),
				onLoad: function() {
					dialogRef = this.getDialog();
					bindSearchInput(this.getElement());
				},
				onShow: function() {
					var element = this.getElement();
					var icons = getIcons();
					element.setHtml(buildGridHtml(icons));
					var input = element.findOne('.cke_iconic_filter');
					ensureFontsCss();
					bindSearchInput(element);
					if (input) {
						input.setValue('');
						filterGrid('');
						CKEDITOR.tools.setTimeout(function() {
							input.focus(true);
						}, 0);
					}
				},
				onClick: onGridClick
			}]
		}],
		buttons: [CKEDITOR.dialog.cancelButton],
		onLoad: function() {
			dialogRef = this;
			this.parts.dialog.addClass('cke_iconic_dialog');
		}
	};
});
