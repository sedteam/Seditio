'use strict';
(function() {
    CKEDITOR.plugins.add('internallink', {
        lang: 'en,ru',
        requires: 'dialog',
        icons: 'internallink',
        hidpi: true,
        onLoad: function() {},
        init: function(editor) {

            //Add command
            editor.addCommand('internallink', new CKEDITOR.dialogCommand('internallink', {
                allowedContent: 'a[!href]',
                requiredContent: 'a[href]'
            }));
            //Add button to link section in toolbar
            if (editor.ui.addButton) {
                editor.ui.addButton('InternalLink', {
                    label: 'Autocomplete Link',
                    command: 'internallink',
                    toolbar: 'links'
                });
            }
            //Attach dialog
            CKEDITOR.dialog.add('internallink', this.path + 'dialogs/link.js');
        }
    });

    CKEDITOR.plugins.internallink = {
        getSelectedLink: function(editor, returnMultiple) {
            var selection = editor.getSelection(),
                selectedElement = selection.getSelectedElement(),
                ranges = selection.getRanges(),
                links = [],
                link,
                range,
                i;


            for (i = 0; i < ranges.length; i++) {
                range = selection.getRanges()[i];

                // Skip bogus to cover cases of multiple selection inside tables (#tp2245).
                range.shrink(CKEDITOR.SHRINK_TEXT, false, { skipBogus: true });
                link = editor.elementPath(range.getCommonAncestor()).contains('a', 1);

                if (link) {
                    links.push(link);
                }
            }

            return links;
        },

        parseLinkAttributes: function(editor, element) {
            var href = (element && (element.data('cke-saved-href') || element.getAttribute('href'))) || '';

            return {
                type: 'url',
                url: {
                    protocol: '',
                    url: href
                }
            };
        },

        getLinkAttributes: function(editor, data) {
            var url = (data.url && CKEDITOR.tools.trim(data.url.url)) || '';
            return {
                set: {
                    'data-cke-saved-href': url,
                    'href': url
                },
                removed: ['target', 'onclick', 'data-cke-pa-onclick', 'download']
            };
        },

        getLinks: function(editor, params, cb) {

            if (!editor.config.internallinkServiceURL) {
                alert("Can't fetch links: Missing config.internallinkServiceURL");
                return;
            }

            var serviceURL = editor.config.internallinkServiceURL + '&';

            serviceURL += Object.keys(params)
                .map(function(key) {
                    return [key, params[key]].join('=');
                }).join('&');

            var httpRequest = new XMLHttpRequest();
            httpRequest.onreadystatechange = function() {
                if (httpRequest.readyState === XMLHttpRequest.DONE) {
                    if (httpRequest.status === 200) {
                        cb(null, JSON.parse(httpRequest.responseText).suggestions.map(function(result) {
                            return [result.title, result.url];
                        }));
                    } else {
                        cb('There was a problem with the request.');
                    }
                }
            };

            httpRequest.open('GET', serviceURL, true);
			// Indicate AJAX request
			httpRequest.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); 
			httpRequest.setRequestHeader('X-Seditio-Csrf', document.querySelector('meta[name="csrf-token"]').content || '');
            httpRequest.send();
        }
    };
})();