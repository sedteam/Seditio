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

        function setSwitcher(element) {
            toggleClass(element, 'hide-icon');
            toggleClass(element, 'show-icon');
            var content = element.getParent().getParent().findOne('.spoiler-content');
            toggle(content);
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
                        var content = editor.spoilerElement.findOne('.spoiler-content');
                        setSpoilerData(content, minlevel, mingroup);
                    }
                },

                onShow: function() {
                    // If the element is not selected via double-click
                    if (!editor.spoilerElement) {
                        var selection = editor.getSelection();
                        var element = selection.getStartElement();
                        // Find the nearest spoiler among all parents
                        editor.spoilerElement = element.getAscendant(function(el) {
                            return el.type === CKEDITOR.NODE_ELEMENT && el.getName() === 'div' && el.hasClass('spoiler');
                        }, true);
                    }

                    if (editor.spoilerElement) {
                        var content = editor.spoilerElement.findOne('.spoiler-content');
                        this.setValueOf('tab-settings', 'minlevel', content.getAttribute('data-minlevel') || '');
                        this.setValueOf('tab-settings', 'mingroup', content.getAttribute('data-mingroup') || '');
                    }
                }
            };
        });

        // Function to create a spoiler
        function createSpoiler(editor, minlevel, mingroup) {
            var spoiler = editor.document.createElement('div', {
                attributes: { 'class': 'spoiler' }
            });

            var title = editor.document.createElement('div', {
                attributes: { 'class': 'spoiler-title' }
            });

            var toggle = editor.document.createElement('div', {
                attributes: { 'class': 'spoiler-toggle hide-icon' }
            });

            var content = editor.document.createElement('div', {
                attributes: { 'class': 'spoiler-content' }
            });

            setSpoilerData(content, minlevel, mingroup);

            toggle.on('click', function(event) {
                setSwitcher(event.sender);
            });

            title.append(toggle);
            spoiler.append(title);
            title.appendHtml('<p>' + editor.lang.spoiler.toolbar + '</p>');
            content.appendHtml('<p><br></p>');
            spoiler.append(content);
            return spoiler;
        }

        function setSpoilerData(element, minlevel, mingroup) {
            element.setAttribute('data-minlevel', minlevel || '');
            element.setAttribute('data-mingroup', mingroup || '');
        }

        function getDivWithClass(className) {
            var divs = editor.document.getElementsByTag('div'),
                len = divs.count(),
                elements = [],
                element;
            for (var i = 0; i < len; ++i) {
                element = divs.getItem(i);
                if (element.hasClass(className)) {
                    elements.push(element);
                }
            }
            return elements;
        }

        // Register command for the button
        editor.addCommand('spoiler', {
            exec: function(editor) {
                editor.spoilerElement = null; // Reset the element for editing
                editor.openDialog('spoilerDialog');
            }
        });

        // Add button to the toolbar
        editor.ui.addButton('Spoiler', {
            label: editor.lang.spoiler.toolbar,
            command: 'spoiler',
            toolbar: 'insert'
        });

        // Double-click handler
        editor.on('doubleclick', function(evt) {
            var element = evt.data.element;
            // Find the nearest parent spoiler
            var spoilerDiv = element.getAscendant(function(el) {
                return el.type === CKEDITOR.NODE_ELEMENT && el.getName() === 'div' && el.hasClass('spoiler');
            }, true);

            if (spoilerDiv) {
                evt.data.dialog = 'spoilerDialog';
                editor.spoilerElement = spoilerDiv; // Save for editing
            }
        });

        var path = this.path;
        // Initialize existing spoilers
        editor.on('mode', function() {
            if (this.mode != 'wysiwyg') {
                return;
            }
            registerCssFile(path + 'css/spoiler.css?v1');
            var elements = getDivWithClass('spoiler-toggle'),
                len = elements.length;
            for (var i = 0; i < len; ++i) {
                elements[i].on('click', function(event) {
                    setSwitcher(event.sender);
                });
            }
        });
    }
});