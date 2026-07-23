/*
 * Spoiler - Spoiler plugin for Seditio
 * @author Tishov Alexander [info@avego.org]
 * @copyright Tishov Alexander, Seditio Team 2025
 *
 */
CKEDITOR.plugins.add('spoiler', {
    lang: 'en,ru',
    icons: 'spoiler',
    init: function(editor) {
        if (editor.blockless) return;

        function registerCssFile(url) {
            var head = editor.document.getHead();
            var link = editor.document.createElement('link', {
                attributes: {
                    type: 'text/css',
                    rel: 'stylesheet',
                    href: url
                }
            });
            head.append(link);
        }

        function toggle(element) {
            element.setStyle('display', (element.getStyle('display') == 'none' ? '' : 'none'));
        }

        function toggleClass(element, className) {
            if (element.hasClass(className)) {
                element.removeClass(className);
            } else {
                element.addClass(className);
            }
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

        // Register dialog
        CKEDITOR.dialog.add('spoilerDialog', function(editor) {
            return {
                title: editor.lang.spoiler.title,
                minWidth: 400,
                minHeight: 200,
                contents: [{
                    id: 'tab-settings',
                    label: editor.lang.spoiler.settings,
                    elements: [{
                            type: 'select',
                            id: 'minlevel',
                            label: editor.lang.spoiler.minlevel,
                            items: [
                                ['Not specified', '']
                            ].concat((function() {
                                var result = [];
                                for (var i = 0; i < 100; i++) {
                                    result.push([i.toString(), i.toString()]);
                                }
                                return result;
                            })())
                        },
                        {
                            type: 'select',
                            id: 'mingroup',
                            label: editor.lang.spoiler.mingroup,
                            items: [
                                ['Not specified', '']
                            ].concat((function() {
                                var result = [];
                                for (var i = 0; i < 7; i++) {
                                    result.push([(i + 4).toString(), (i + 4).toString()]);
                                }
                                return result;
                            })())
                        }
                    ]
                }],
                onOk: function() {
                    var dialog = this;
                    var minlevel = dialog.getValueOf('tab-settings', 'minlevel');
                    var mingroup = dialog.getValueOf('tab-settings', 'mingroup');

                    // If creating a new spoiler
                    if (!editor.spoilerElement) {
                        var spoiler = createSpoiler(editor, minlevel, mingroup);
                        editor.insertElement(spoiler);
                    }
                    // If editing an existing spoiler
                    else {
                        var content = editor.spoilerElement.find('.spoiler-content').getItem(0);
                        setSpoilerData(content, minlevel, mingroup);
                    }
                },

                onShow: function() {
                    if (!editor.spoilerElement) {
                        var selection = editor.getSelection();
                        var element = selection.getStartElement();
                        // Find the nearest spoiler among all parents
                        editor.spoilerElement = getAscendantByClass(element, 'div', 'spoiler', true);
                    }

                    if (editor.spoilerElement) {
                        var content = editor.spoilerElement.find('.spoiler-content').getItem(0);
                        this.setValueOf('tab-settings', 'minlevel', content.getAttribute('data-minlevel') || '');
                        this.setValueOf('tab-settings', 'mingroup', content.getAttribute('data-mingroup') || '');
                    }
                }
            };
        });

        // Function to create a spoiler (no spoiler-toggle link generated!)
        function createSpoiler(editor, minlevel, mingroup) {
            var spoiler = editor.document.createElement('div', {
                attributes: { 'class': 'spoiler' }
            });

            var title = editor.document.createElement('div', {
                attributes: { 'class': 'spoiler-title' }
            });

            var content = editor.document.createElement('div', {
                attributes: { 'class': 'spoiler-content' }
            });

            setSpoilerData(content, minlevel, mingroup);

            spoiler.append(title);
            title.appendHtml('<p>' + editor.lang.spoiler.toolbar + '</p>');
            content.appendHtml('<p><br></p>');
            spoiler.append(content);
            return spoiler;
        }

        function setSpoilerData(element, minlevel, mingroup) {
            element.setAttribute('data-minlevel', minlevel || '');
            element.setAttribute('data-mingroup', mingroup || '');

            var text = '';
            if (minlevel != '') {
                text += 'Min Level: ' + minlevel;
            }
            if (mingroup != '') {
                if (text != '') {
                    text += ', ';
                }
                text += 'Min Group: ' + mingroup;
            }

            if (text != '') {
                element.setAttribute('data-info', text);
            } else {
                element.removeAttribute('data-info');
            }
        }

        // Add command
        editor.addCommand('spoiler', {
            exec: function(editor) {
                editor.spoilerElement = null; // Reset the element for editing
                editor.openDialog('spoilerDialog');
            }
        });

        // Add button to toolbar
        editor.ui.addButton('Spoiler', {
            label: editor.lang.spoiler.toolbar,
            command: 'spoiler',
            toolbar: 'insert'
        });

        // Add double click handler to edit spoiler settings
        editor.on('doubleclick', function(evt) {
            var element = evt.data.element;
            // Find the nearest parent spoiler
            var spoilerDiv = getAscendantByClass(element, 'div', 'spoiler', true);
            if (spoilerDiv) {
                evt.data.dialog = 'spoilerDialog';
                editor.spoilerElement = spoilerDiv; // Save for editing
            }
        });

        // Interactive toggle inside WYSIWYG editor
        editor.on('contentDom', function() {
            var editable = editor.editable();

            editable.attachListener(editable, 'click', function(evt) {
                var target = evt.data.getTarget();
                if (!target) return;

                var title = getAscendantByClass(target, 'div', 'spoiler-title', true);

                if (title) {
                    var spoiler = title.getParent();
                    if (spoiler && spoiler.is && spoiler.is('div') && spoiler.hasClass('spoiler')) {
                        evt.data.preventDefault();
                        var isAlreadyActive = title.hasClass('active');

                        var content = spoiler.find('.spoiler-content').getItem(0);
                        if (isAlreadyActive) {
                            title.removeClass('active');
                            if (content) content.setStyle('display', 'none');
                        } else {
                            title.addClass('active');
                            if (content) content.setStyle('display', 'block');
                        }
                    }
                }
            });
        });

        var path = this.path;
        editor.on('mode', function() {
            if (this.mode == 'wysiwyg') {
                registerCssFile(path + 'css/spoiler.css?v=2026-jul-23');
            }
        });
    }
});