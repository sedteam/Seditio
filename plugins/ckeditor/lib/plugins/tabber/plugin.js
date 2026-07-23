/*
 * Tabber - tabs plugin for Seditio
 * @author Tishov Alexander [info@avego.org]
 * @copyright Tishov Alexander, Seditio Team 2025
 *
 */
(function() {
CKEDITOR.plugins.add('tabber', {
    hidpi: true,
    lang: 'en,ru',
    icons: 'tabber',
    init: function(editor) {
        function cssEscape(str) {
            if (!str) return '';
            return str.replace(/([!"#$%&'()*+,\-./:;<=>?@[\\\]^`{|}~])/g, '\\$1');
        }

        function getAscendantByClass(node, tagName, className, includeSelf) {
            if (!node) return null;
            var current = includeSelf ? node : node.getParent();
            while (current) {
                if (current.is && current.is(tagName) && current.hasClass(className)) {
                    return current;
                }
                current = current.getParent();
            }
            return null;
        }

        function getTabContentElement(start) {
            var current = start;
            while (current) {
                if (current.is && current.is('div') && current.hasClass('tabs')) {
                    var parent = current.getParent();
                    if (parent && parent.is && parent.is('div') && parent.hasClass('tab-box')) {
                        return current;
                    }
                }
                current = current.getParent();
            }
            return null;
        }
		
        editor.ui.addButton('Tabber', {
            command: 'addTabCmd',
            icon: this.path + 'icons/tabber.png',
            label: editor.lang.tabber.toolbar
        });

        var cssPath = this.path + 'tabber.css';
        editor.on('mode', function() {
            if (editor.mode === 'wysiwyg') {
                this.document.appendStyleSheet(cssPath);
            }
        });

        editor.on('selectionChange', function(evt) {
            if (editor.readOnly) return;
            var command = editor.getCommand('addTabCmd'),
                element = evt.data.path.lastElement,
                sedtabs = getAscendantByClass(element, 'div', 'sedtabs', true);

            command.setState(sedtabs ? CKEDITOR.TRISTATE_DISABLED : CKEDITOR.TRISTATE_OFF);
        });

        var allowedContent = 'div(sedtabs); ul(tabs); li; a[href]; div(tab-box); div(tabs)[id]';

        editor.addCommand('addTabCmd', {
            allowedContent: allowedContent,
            exec: function(editor) {
                var tabId1 = 'tab_' + CKEDITOR.tools.getUniqueId('tab'),
                    tabId2 = 'tab_' + CKEDITOR.tools.getUniqueId('tab');

                var template =
                    '<div class="sedtabs">' +
                    '<ul class="tabs">' +
                    '<li><a href="#' + tabId1 + '" class="selected">' + editor.lang.tabber.tabtitle + ' 1</a></li>' +
                    '<li><a href="#' + tabId2 + '">' + editor.lang.tabber.tabtitle + ' 2</a></li>' +
                    '</ul>' +
                    '<div class="tab-box">' +
                    '<div id="' + tabId1 + '" class="tabs"><p>' + editor.lang.tabber.tabcontent + ' 1</p></div>' +
                    '<div id="' + tabId2 + '" class="tabs"><p>' + editor.lang.tabber.tabcontent + ' 2</p></div>' +
                    '</div>' +
                    '</div>';

                editor.insertHtml(template);
            }
        });

        function getTabContext(editor, element) {
            var start = element;
            if (!start) {
                var sel = editor.getSelection();
                start = sel ? sel.getStartElement() : null;
            }
            if (!start) return null;

            var sedtabs = getAscendantByClass(start, 'div', 'sedtabs', true);
            if (!sedtabs) return null;

            // Exclude if inside an accordion container
            var sedaccordion = getAscendantByClass(start, 'div', 'sedaccordion', true);
            if (sedaccordion) return null;

            var context = {
                sedtabs: sedtabs,
                header: start.getAscendant('a', true),
                content: getTabContentElement(start)
            };

            if (context.header) {
                var href = context.header.getAttribute('href');
                context.id = href ? href.substring(1) : null;
                context.content = context.id ? sedtabs.find('.tab-box #' + cssEscape(context.id)).getItem(0) : null;
            } else if (context.content) {
                context.id = context.content.getAttribute('id');
                context.header = context.id ? sedtabs.find('a[href="#' + cssEscape(context.id) + '"]').getItem(0) : null;
            } else {
                return null;
            }

            return context;
        }

        editor.addCommand('addTabBefore', {
            allowedContent: allowedContent,
            exec: function(editor) {
                var ctx = getTabContext(editor);
                if (!ctx || !ctx.id) return;

                var newId = 'tab_' + CKEDITOR.tools.getUniqueId('tab'),
                    newHeader = CKEDITOR.dom.element.createFromHtml(
                        '<li><a href="#' + newId + '">' + editor.lang.tabber.newtabtitle + '</a></li>'
                    ),
                    newContent = CKEDITOR.dom.element.createFromHtml(
                        '<div id="' + newId + '" class="tabs"><p>' + editor.lang.tabber.newtabcontent + '</p></div>'
                    );

                newHeader.insertBefore(ctx.header.getParent());
                newContent.insertBefore(ctx.content);
            }
        });

        editor.addCommand('addTabAfter', {
            allowedContent: allowedContent,
            exec: function(editor) {
                var ctx = getTabContext(editor);
                if (!ctx || !ctx.id) return;

                var newId = 'tab_' + CKEDITOR.tools.getUniqueId('tab'),
                    newHeader = CKEDITOR.dom.element.createFromHtml(
                        '<li><a href="#' + newId + '">' + editor.lang.tabber.newtabtitle + '</a></li>'
                    ),
                    newContent = CKEDITOR.dom.element.createFromHtml(
                        '<div id="' + newId + '" class="tabs"><p>' + editor.lang.tabber.newtabcontent + '</p></div>'
                    );

                newHeader.insertAfter(ctx.header.getParent());
                newContent.insertAfter(ctx.content);
            }
        });

        editor.addCommand('removeTab', {
            exec: function(editor) {
                var ctx = getTabContext(editor);
                if (!ctx || !ctx.id) return;

                if (ctx.sedtabs.find('li').count() === 1) {
                    ctx.sedtabs.remove();
                    return;
                }

                ctx.header.getParent().remove();
                ctx.content.remove();
            }
        });

        function initTabSwitching(editor) {
            var editable = editor.editable();

            editable.attachListener(editable, 'click', function(evt) {
                var target = evt.data.getTarget();
                var link = target ? target.getAscendant('a', true) : null;

                if (link && link.getParent() && link.getParent().getParent() && link.getParent().getParent().hasClass('tabs')) {
                    evt.data.preventDefault();

                    var sedtabs = getAscendantByClass(link, 'div', 'sedtabs', true);
                    if (!sedtabs) return;

                    var allLinks = sedtabs.find('a');
                    for (var i = 0; i < allLinks.count(); i++) {
                        allLinks.getItem(i).removeClass('selected');
                    }

                    link.addClass('selected');

                    var contents = sedtabs.find('.tab-box > div');
                    for (i = 0; i < contents.count(); i++) {
                        contents.getItem(i).setStyle('display', 'none');
                    }

                    var href = link.getAttribute('href');
                    var targetId = href ? href.substring(1) : '';
                    var content = targetId ? sedtabs.find('#' + cssEscape(targetId)).getItem(0) : null;
                    if (content) {
                        content.setStyle('display', 'block');
                    }
                }
            });
        }

        editor.on('contentDom', function() {
            initTabSwitching(editor);

            var sedtabsElements = editor.editable().find('.sedtabs');
            for (var i = 0; i < sedtabsElements.count(); i++) {
                var sedtabs = sedtabsElements.getItem(i);

                var allLinks = sedtabs.find('.tabs a');
                for (var j = 0; j < allLinks.count(); j++) {
                    allLinks.getItem(j).removeClass('selected');
                }

                var allContents = sedtabs.find('.tab-box > div');
                for (j = 0; j < allContents.count(); j++) {
                    allContents.getItem(j).setStyle('display', 'none');
                }

                var tabsHeader = sedtabs.find('.tabs').getItem(0);
                if (!tabsHeader) continue;

                var firstLink = tabsHeader.find('a:first-child').getItem(0);
                var firstContent = sedtabs.find('.tab-box .tabs:first-child').getItem(0);

                if (firstLink && firstContent) {
                    firstLink.addClass('selected');
                    firstContent.setStyle('display', 'block');
                }
            }
        });

        if (editor.contextMenu) {
            editor.addMenuGroup('tabberGroup');
            editor.addMenuItem('tabBeforeItem', {
                label: editor.lang.tabber.tabbefore,
                command: 'addTabBefore',
                group: 'tabberGroup'
            });
            editor.addMenuItem('tabAfterItem', {
                label: editor.lang.tabber.tabafter,
                command: 'addTabAfter',
                group: 'tabberGroup'
            });
            editor.addMenuItem('removeTab', {
                label: editor.lang.tabber.removetab,
                command: 'removeTab',
                group: 'tabberGroup'
            });

            editor.contextMenu.addListener(function(element) {
                return getTabContext(editor, element) ? {
                    tabBeforeItem: CKEDITOR.TRISTATE_OFF,
                    tabAfterItem: CKEDITOR.TRISTATE_OFF,
                    removeTab: CKEDITOR.TRISTATE_OFF
                } : null;
            });
        }
    }
});
})();