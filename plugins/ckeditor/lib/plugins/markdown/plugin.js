/*
 * Markdown Plugin for CKEditor 4 / Seditio CMS
 * Converts Markdown to HTML with native Seditio SyntaxHighlighter integration
 * Supports two-way conversion (MD <-> HTML)
 */

CKEDITOR.plugins.add('markdown', {
    requires: 'dialog',
    lang: 'en,ru,tr',
    icons: 'markdown',
    init: function(editor) {
        var pluginPath = this.path;
        var ver = '20261119_' + (CKEDITOR.timestamp || '3');

        // Attach preview modal stylesheets
        CKEDITOR.document.appendStyleSheet(pluginPath + 'markdown.css?v=' + ver);

        // Load external parser libraries
        CKEDITOR.scriptLoader.load([
            pluginPath + 'lib/markdown-it.min.js?v=' + ver,
            pluginPath + 'lib/turndown.min.js?v=' + ver
        ]);

        // Register dialog window
        CKEDITOR.dialog.add('markdownDialog', pluginPath + 'dialogs/markdown.js?v=' + ver);

        // Register dialog command
        editor.addCommand('markdownDialog', new CKEDITOR.dialogCommand('markdownDialog', {
            allowedContent: 'pre[class,data-lang](*); h1 h2 h3 h4 h5 h6; p br; ul ol[start] li; blockquote; hr; strong em s del; a[!href,title]; img[!src,alt,title]; table thead tbody tfoot tr th[style,align] td[style,align]; code',
            requiredContent: 'p'
        }));

        // Add toolbar button
        if (editor.ui.addButton) {
            editor.ui.addButton('Markdown', {
                label: editor.lang.markdown.button,
                command: 'markdownDialog',
                toolbar: 'insert,99',
                icon: pluginPath + 'icons/markdown.png'
            });
        }
    }
});
