/*
 * Tabber - tabs plugin for Seditio
 * @author Tishov Alexander [info@avego.org]
 * @copyright Tishov Alexander, Seditio Team 2025
 *
 */
(function() {
CKEDITOR.plugins.add('tabber', {
    // Indicates that the plugin supports high-resolution screens
    hidpi: true,
    // Supported languages
    lang: [ 'en', 'ru' ],
    // Plugin icon
    icons: 'tabber',
    // Plugin initialization
    init: function(editor) {
        // Function to escape special characters in CSS selectors
        function cssEscape(str) {
            return str.replace(/([!"#$%&'()*+,\-./:;<=>?@[\\\]^`{|}~])/g, '\\$1');
        }
		
		// Adding a button to the editor's toolbar
        editor.ui.addButton('Tabber', {
            // Command executed when the button is clicked
            command: 'addTabCmd',
            // Path to the button's icon
            icon: this.path + 'icons/tabber.png',
            // Button label
            label: editor.lang.tabber.toolbar
        });

        // Path to the plugin's CSS file
        var cssPath = this.path + 'tabber.css';
        // Appending the CSS file when switching to WYSIWYG mode
        editor.on('mode', function() {
            if (editor.mode === 'wysiwyg') {
                this.document.appendStyleSheet(cssPath);
            }
        });

        // Changing the command state when the selection changes
        editor.on('selectionChange', function(evt) {
            if (editor.readOnly) return;
            var command = editor.getCommand('addTabCmd'),
                element = evt.data.path.lastElement,
                sedtabs = element.getAscendant('div', true, function(el) {
                    return el.hasClass('sedtabs');
                });

            // Disabling the command if the selection is inside the tabs
            command.setState(sedtabs ? CKEDITOR.TRISTATE_DISABLED : CKEDITOR.TRISTATE_OFF);
        });

        // Allowed content for the command
        var allowedContent = 'div(sedtabs); ul(tabs); li; a[href]; div(tab-box); div(tabs)[id]';

        // Adding a command to insert tabs
        editor.addCommand('addTabCmd', {
            allowedContent: allowedContent,
            exec: function(editor) {
                // Generating unique IDs for the tabs
                var tabId1 = 'tab_' + CKEDITOR.tools.getUniqueId('tab'),
                    tabId2 = 'tab_' + CKEDITOR.tools.getUniqueId('tab');

                // HTML template for the tabs
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

                // Inserting the template into the editor
                editor.insertHtml(template);
            }
        });

        // Function to get the context of the tabs
        function getTabContext(editor) {
            var sel = editor.getSelection(),
                start = sel.getStartElement(),
                sedtabs = start.getAscendant('div', true, function(el) {
                    return el.hasClass('sedtabs');
                });

            if (!sedtabs) return null;

            var context = {
                sedtabs: sedtabs,
                header: start.getAscendant('a', true),
                content: start.getAscendant('div', true, function(el) {
                    return el.hasClass('tabs') && el.getParent().hasClass('tab-box');
                })
            };

            if (context.header) {
                context.id = context.header.getAttribute('href').substring(1);
                context.content = sedtabs.findOne('.tab-box #' + cssEscape(context.id));
            } else if (context.content) {
                context.id = context.content.getAttribute('id');
                context.header = sedtabs.findOne('a[href="#' + cssEscape(context.id) + '"]');
            }

            return context;
        }

        // Adding a command to add a tab before the current one
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

        // Adding a command to add a tab after the current one
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

        // Adding a command to remove a tab
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

        // Initializing tab switching
        function initTabSwitching(editor) {
            var editable = editor.editable();

            editable.attachListener(editable, 'click', function(evt) {
                var target = evt.data.getTarget();
                var link = target.getAscendant('a', true);

                if (link && link.getParent().getParent().hasClass('tabs')) {
                    evt.data.preventDefault();

                    var sedtabs = link.getAscendant('div', true, function(el) {
                        return el.hasClass('sedtabs');
                    });

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

                    var targetId = link.getAttribute('href').substring(1);
                    var content = sedtabs.findOne('#' + cssEscape(targetId));
                    if (content) {
                        content.setStyle('display', 'block');
                    }
                }
            });
        }

        // Initializing tabs when content is loaded
        editor.on('contentDom', function() {
            initTabSwitching(editor);

            var sedtabsElements = editor.editable().find('.sedtabs');
            for (var i = 0; i < sedtabsElements.count(); i++) {
                var sedtabs = sedtabsElements.getItem(i);

                // Resetting all selections
                var allLinks = sedtabs.find('.tabs a');
                for (var j = 0; j < allLinks.count(); j++) {
                    allLinks.getItem(j).removeClass('selected');
                }

                // Hiding all content
                var allContents = sedtabs.find('.tab-box  > div');
                for (j = 0; j < allContents.count(); j++) {
                    allContents.getItem(j).setStyle('display', 'none');
                }

                // Activating the first tab
                var tabsHeader = sedtabs.findOne('.tabs');
                if (!tabsHeader) continue;

                var firstLink = tabsHeader.findOne('a:first-child');
                var firstContent = sedtabs.findOne('.tab-box .tabs:first-child');

                if (firstLink && firstContent) {
                    firstLink.addClass('selected');
                    firstContent.setStyle('display', 'block');
                }
            }
        });

        // Adding context menu
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
                return getTabContext(editor) ? {
                    tabBeforeItem: CKEDITOR.TRISTATE_OFF,
                    tabAfterItem: CKEDITOR.TRISTATE_OFF,
                    removeTab: CKEDITOR.TRISTATE_OFF
                } : null;
            });
        }
    }
});
})();