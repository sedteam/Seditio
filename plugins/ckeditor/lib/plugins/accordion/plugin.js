/*
 * Accordion - Accordion plugin for Seditio CKEditor
 * @copyright Seditio Team 2026
 *
 */
(function() {
CKEDITOR.plugins.add('accordion', {
    hidpi: true,
    lang: 'en,ru',
    icons: 'accordion',
    init: function(editor) {
        if (editor.blockless) return;

        var pluginPath = this.path;
        var cssPath = pluginPath + 'accordion.css';

        // Add button to editor toolbar
        editor.ui.addButton('Accordion', {
            command: 'addAccordionCmd',
            icon: pluginPath + 'icons/accordion.png',
            label: editor.lang.accordion.toolbar
        });

        // Append stylesheet in WYSIWYG mode
        editor.on('mode', function() {
            if (editor.mode === 'wysiwyg') {
                this.document.appendStyleSheet(cssPath);
            }
        });

        if (CKEDITOR.tools.isArray(editor.config.contentsCss)) {
            if (CKEDITOR.tools.indexOf(editor.config.contentsCss, cssPath) === -1) {
                editor.config.contentsCss.push(cssPath);
            }
        }

        var allowedContent = 'div(sedaccordion); div(accordion-item,active); div(accordion-header); div(accordion-content)[style]';

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

        // Disable toolbar button when selection is inside an accordion
        editor.on('selectionChange', function(evt) {
            if (editor.readOnly) return;
            var command = editor.getCommand('addAccordionCmd');
            if (!command) return;

            var element = evt.data.path.lastElement;
            var sedaccordion = getAscendantByClass(element, 'div', 'sedaccordion', true);

            command.setState(sedaccordion ? CKEDITOR.TRISTATE_DISABLED : CKEDITOR.TRISTATE_OFF);
        });

        // Helper function to resolve current accordion context safely
        function getAccordionContext(editor, element) {
            var start = element;
            if (!start) {
                var sel = editor.getSelection();
                start = sel ? sel.getStartElement() : null;
            }
            if (!start) return null;

            // Exclude if inside a tabber container
            var sedtabs = getAscendantByClass(start, 'div', 'sedtabs', true);
            if (sedtabs) return null;

            // Must be inside header or content
            var header = getAscendantByClass(start, 'div', 'accordion-header', true);
            var content = getAscendantByClass(start, 'div', 'accordion-content', true);

            if (!header && !content) return null;

            // Must be inside an accordion-item
            var item = getAscendantByClass(start, 'div', 'accordion-item', true);
            if (!item) return null;

            // Resolve top-level accordion-item if nested
            var parent = item.getParent();
            while (parent) {
                if (parent.is && parent.is('div') && parent.hasClass('sedaccordion')) {
                    break;
                }
                var upperItem = getAscendantByClass(parent, 'div', 'accordion-item', true);
                if (!upperItem) break;
                item = upperItem;
                parent = item.getParent();
            }

            var sedaccordion = getAscendantByClass(item, 'div', 'sedaccordion', true);
            if (!sedaccordion) return null;

            return {
                sedaccordion: sedaccordion,
                item: item,
                header: header || item.find('.accordion-header').getItem(0),
                content: content || item.find('.accordion-content').getItem(0)
            };
        }

        function createAccordionItem(editor) {
            return CKEDITOR.dom.element.createFromHtml(
                '<div class="accordion-item">' +
                '<div class="accordion-header">' + editor.lang.accordion.newitemtitle + '</div>' +
                '<div class="accordion-content" style="display: none;"><p>' + editor.lang.accordion.newitemcontent + '</p></div>' +
                '</div>'
            );
        }

        // Command to insert a new accordion block
        editor.addCommand('addAccordionCmd', {
            allowedContent: allowedContent,
            exec: function(editor) {
                var template =
                    '<div class="sedaccordion">' +
                    '<div class="accordion-item active">' +
                    '<div class="accordion-header">' + editor.lang.accordion.itemtitle + ' 1</div>' +
                    '<div class="accordion-content" style="display: block;"><p>' + editor.lang.accordion.itemcontent + ' 1</p></div>' +
                    '</div>' +
                    '<div class="accordion-item">' +
                    '<div class="accordion-header">' + editor.lang.accordion.itemtitle + ' 2</div>' +
                    '<div class="accordion-content" style="display: none;"><p>' + editor.lang.accordion.itemcontent + ' 2</p></div>' +
                    '</div>' +
                    '</div>';

                editor.insertHtml(template);
            }
        });

        // Command: Add item before
        editor.addCommand('addAccordionBefore', {
            allowedContent: allowedContent,
            exec: function(editor) {
                var ctx = getAccordionContext(editor);
                if (!ctx || !ctx.item) return;

                var newItem = createAccordionItem(editor);
                newItem.insertBefore(ctx.item);
            }
        });

        // Command: Add item after
        editor.addCommand('addAccordionAfter', {
            allowedContent: allowedContent,
            exec: function(editor) {
                var ctx = getAccordionContext(editor);
                if (!ctx || !ctx.item) return;

                var newItem = createAccordionItem(editor);
                newItem.insertAfter(ctx.item);
            }
        });

        // Command: Remove item
        editor.addCommand('removeAccordionItem', {
            exec: function(editor) {
                var ctx = getAccordionContext(editor);
                if (!ctx || !ctx.item) return;

                var items = ctx.sedaccordion.find('.accordion-item');
                if (items.count() <= 1) {
                    ctx.sedaccordion.remove();
                } else {
                    ctx.item.remove();
                }
            }
        });

        // Interactive toggle inside WYSIWYG editor
        editor.on('contentDom', function() {
            var editable = editor.editable();

            editable.attachListener(editable, 'click', function(evt) {
                var target = evt.data.getTarget();
                if (!target) return;

                var header = getAscendantByClass(target, 'div', 'accordion-header', true);

                if (header) {
                    var item = header.getParent();
                    var sedaccordion = getAscendantByClass(header, 'div', 'sedaccordion', true);

                    if (item && item.is && item.is('div') && item.hasClass('accordion-item') && sedaccordion) {
                        evt.data.preventDefault();
                        var isAlreadyActive = item.hasClass('active');

                        // Collapse all items in this accordion
                        var allItems = sedaccordion.find('.accordion-item');
                        for (var i = 0; i < allItems.count(); i++) {
                            var curItem = allItems.getItem(i);
                            curItem.removeClass('active');
                            var curContent = curItem.find('.accordion-content').getItem(0);
                            if (curContent) {
                                curContent.setStyle('display', 'none');
                            }
                        }

                        // Open clicked item only if it was NOT active before (toggles off if clicked again)
                        if (!isAlreadyActive) {
                            item.addClass('active');
                            var content = item.find('.accordion-content').getItem(0);
                            if (content) {
                                content.setStyle('display', 'block');
                            }
                        }
                    }
                }
            });
        });

        // Context menu integration (Right Click / ПКМ)
        if (editor.contextMenu) {
            editor.addMenuGroup('accordionGroup');
            editor.addMenuItem('accordionBeforeItem', {
                label: editor.lang.accordion.itembefore,
                command: 'addAccordionBefore',
                group: 'accordionGroup'
            });
            editor.addMenuItem('accordionAfterItem', {
                label: editor.lang.accordion.itemafter,
                command: 'addAccordionAfter',
                group: 'accordionGroup'
            });
            editor.addMenuItem('removeAccordionItem', {
                label: editor.lang.accordion.removeitem,
                command: 'removeAccordionItem',
                group: 'accordionGroup'
            });

            editor.contextMenu.addListener(function(element) {
                var ctx = getAccordionContext(editor, element);
                if (ctx) {
                    return {
                        accordionBeforeItem: CKEDITOR.TRISTATE_OFF,
                        accordionAfterItem: CKEDITOR.TRISTATE_OFF,
                        removeAccordionItem: CKEDITOR.TRISTATE_OFF
                    };
                }
                return null;
            });
        }
    }
});
})();
