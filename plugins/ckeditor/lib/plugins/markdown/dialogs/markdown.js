CKEDITOR.dialog.add('markdownDialog', function(editor) {
    var lang = editor.lang.markdown;

    // Language mapping: Markdown -> Seditio / SyntaxHighlighter brush
    var SedBrushMap = {
        'js': 'js', 'javascript': 'js', 'jscript': 'js', 'node': 'js', 'json': 'js',
        'ts': 'js', 'typescript': 'js',
        'php': 'php', 'phtml': 'php',
        'html': 'xml', 'xml': 'xml', 'svg': 'xml', 'xhtml': 'xml', 'htm': 'xml', 'xslt': 'xml',
        'css': 'css', 'scss': 'css', 'sass': 'css', 'less': 'css',
        'bash': 'bash', 'sh': 'bash', 'shell': 'bash', 'zsh': 'bash',
        'sql': 'sql', 'mysql': 'sql', 'pgsql': 'sql',
        'py': 'python', 'python': 'python',
        'c': 'cpp', 'cpp': 'cpp', 'c++': 'cpp',
        'cs': 'csharp', 'csharp': 'csharp', 'c#': 'csharp', 'c-sharp': 'csharp',
        'java': 'java', 'javafx': 'javafx', 'jfx': 'javafx',
        'perl': 'perl', 'pl': 'perl',
        'ruby': 'ruby', 'rb': 'ruby', 'rails': 'ruby', 'ror': 'ruby',
        'diff': 'diff', 'patch': 'diff',
        'delphi': 'delphi', 'pascal': 'delphi', 'pas': 'delphi',
        'erl': 'erl', 'erlang': 'erl',
        'groovy': 'groovy',
        'scala': 'scala',
        'vb': 'vb', 'vbnet': 'vb',
        'ps': 'ps', 'powershell': 'ps', 'posh': 'ps',
        'yaml': 'yaml', 'yml': 'yaml',
        'plain': 'plain', 'text': 'plain', 'txt': 'plain'
    };

    function resolveBrush(langName) {
        if (!langName) return 'plain';
        langName = langName.toLowerCase().trim();
        return SedBrushMap[langName] || 'plain';
    }

    // Scoped DOM element lookup within dialog root
    function getDialogEl(dialog, selector) {
        if (!dialog || !dialog.getElement()) return null;
        var root = dialog.getElement().$;
        return root ? root.querySelector(selector) : null;
    }

    // Insert markdown snippet into textarea
    function insertSnippet(textarea, before, after, defaultText) {
        if (!textarea) return;
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var val = textarea.value;
        var selected = val.substring(start, end) || defaultText;
        var replacement = before + selected + after;

        var inserted = false;
        try {
            inserted = document.execCommand('insertText', false, replacement);
        } catch (err) {}
        if (!inserted) {
            if (typeof textarea.setRangeText === 'function') {
                textarea.setRangeText(replacement, start, end, 'end');
            } else {
                textarea.value = val.substring(0, start) + replacement + val.substring(end);
                textarea.selectionStart = textarea.selectionEnd = start + replacement.length;
            }
        }
    }

    // Verify and ensure parser libraries are loaded
    function ensureLibraries(ed, callback) {
        if (window.markdownit && window.TurndownService) {
            if (callback) callback();
            return;
        }
        var pluginPath = ed.plugins.markdown ? ed.plugins.markdown.path : '';
        var ver = '20261119_' + (CKEDITOR.timestamp || '3');
        CKEDITOR.scriptLoader.load([
            pluginPath + 'lib/markdown-it.min.js?v=' + ver,
            pluginPath + 'lib/turndown.min.js?v=' + ver
        ], function() {
            if (callback) callback();
        });
    }

    // Initialize Markdown-it parser
    var md = null;
    function getMdParser() {
        if (!md && window.markdownit) {
            md = window.markdownit({
                html: false, // XSS protection (raw HTML is escaped)
                xhtmlOut: false,
                breaks: true,
                langPrefix: 'language-',
                linkify: true,
                typographer: false
            });

            // Custom code block renderer rule for integration with Seditio Syntaxhighlight plugin
            md.renderer.rules.fence = function(tokens, idx, options, env, self) {
                var token = tokens[idx];
                var rawInfo = token.info ? token.info.trim() : '';
                var langTag = rawInfo ? rawInfo.split(/\s+/)[0] : 'plain';
                var brush = resolveBrush(langTag);

                var escapedCode = md.utils.escapeHtml(token.content);
                return '<pre class="brush: ' + brush + ';" data-lang="' + brush + '">' + escapedCode + '</pre>\n';
            };
        }
        return md;
    }

    // Initialize Turndown reverse converter (HTML -> Markdown)
    var td = null;
    function getTurndown() {
        if (!td && window.TurndownService) {
            td = new window.TurndownService({
                headingStyle: 'atx',
                codeBlockStyle: 'fenced',
                bulletListMarker: '-'
            });

            td.addRule('seditioStrikethrough', {
                filter: ['del', 's', 'strike'],
                replacement: function(content) {
                    return '~~' + content + '~~';
                }
            });

            function tableCell(content, node) {
                var index = Array.prototype.indexOf.call(node.parentNode.childNodes, node);
                var prefix = ' ';
                if (index === 0) prefix = '| ';
                var clean = content.replace(/\n+/g, ' ').replace(/\|/g, '\\|').trim();
                return prefix + clean + ' |';
            }

            function isHeadingRow(tr) {
                var parent = tr.parentNode;
                if (parent && parent.nodeName === 'THEAD') {
                    return tr === parent.lastElementChild;
                }
                var table = tr.closest ? tr.closest('table') : null;
                if (!table) {
                    var p = parent;
                    while (p && p.nodeName !== 'TABLE') p = p.parentNode;
                    table = p;
                }
                if (table) {
                    var firstTr = table.querySelector('tr');
                    if (firstTr === tr) return true;
                }
                return false;
            }

            td.addRule('seditioTableCell', {
                filter: ['th', 'td'],
                replacement: function(content, node) {
                    return tableCell(content, node);
                }
            });

            td.addRule('seditioTableRow', {
                filter: 'tr',
                replacement: function(content, node) {
                    var borderCells = '';
                    var alignMap = { left: ':---', right: '---:', center: ':---:' };

                    if (isHeadingRow(node)) {
                        for (var i = 0; i < node.childNodes.length; i++) {
                            var child = node.childNodes[i];
                            if (child.nodeName === 'TH' || child.nodeName === 'TD') {
                                var border = '---';
                                var align = (child.getAttribute('align') || (child.style ? child.style.textAlign : '') || '').toLowerCase();
                                if (alignMap[align]) {
                                    border = alignMap[align];
                                }
                                borderCells += tableCell(border, child);
                            }
                        }
                    }
                    return '\n' + content + (borderCells ? '\n' + borderCells : '');
                }
            });

            td.addRule('seditioTable', {
                filter: 'table',
                replacement: function(content) {
                    return '\n\n' + content.replace(/^\n+/, '').replace(/\n+$/, '') + '\n\n';
                }
            });

            td.addRule('seditioTableSection', {
                filter: ['thead', 'tbody', 'tfoot'],
                replacement: function(content) {
                    return content;
                }
            });

            td.addRule('seditioSyntaxHighlight', {
                filter: function(node, options) {
                    return node.nodeName === 'PRE';
                },
                replacement: function(content, node) {
                    var brush = 'plain';
                    if (node.className) {
                        var match = /brush:\s*([a-zA-Z0-9_\-#]+)/i.exec(node.className);
                        if (match) {
                            brush = resolveBrush(match[1]);
                        }
                    }
                    var dataLang = node.getAttribute('data-lang');
                    if (dataLang && brush === 'plain') {
                        brush = resolveBrush(dataLang);
                    }

                    var clone = node.cloneNode(true);
                    var brs = clone.getElementsByTagName('br');
                    while (brs.length > 0) {
                        brs[0].parentNode.replaceChild(document.createTextNode('\n'), brs[0]);
                    }
                    var code = clone.textContent || '';
                    code = code.replace(/\r\n/g, '\n').replace(/\r/g, '\n').replace(/\n+$/, '');
                    return '\n\n```' + brush + '\n' + code + '\n```\n\n';
                }
            });
        }
        return td;
    }

    var timerDebounce = null;
    function updateOutputs(dialog) {
        var inputEl = getDialogEl(dialog, '.sed-md-input');
        var htmlCodeEl = getDialogEl(dialog, '.sed-html-code');
        var livePreviewEl = getDialogEl(dialog, '.sed-md-preview-container');

        if (!inputEl || !htmlCodeEl || !livePreviewEl) return;

        var text = inputEl.value || '';
        if (text.trim() === '') {
            htmlCodeEl.value = '';
            livePreviewEl.innerHTML = '<span style="color:#8c8c8c;font-style:italic;">' + lang.previewEmpty + '</span>';
            return;
        }

        var parser = getMdParser();
        if (parser) {
            var html = parser.render(text);
            htmlCodeEl.value = html;
            livePreviewEl.innerHTML = html;
        } else {
            var err = lang.errParserNotLoaded || 'Error: markdown-it parser not loaded';
            htmlCodeEl.value = err;
            livePreviewEl.innerHTML = '<div style="color:red;">' + err + '</div>';
        }
    }

    function setRightMode(dialog, mode) {
        var btnCode = getDialogEl(dialog, '.sed-btn-mode-code');
        var btnLive = getDialogEl(dialog, '.sed-btn-mode-live');
        var htmlCodeEl = getDialogEl(dialog, '.sed-html-code');
        var livePreviewEl = getDialogEl(dialog, '.sed-md-preview-container');

        if (!btnCode || !btnLive || !htmlCodeEl || !livePreviewEl) return;

        if (mode === 'code') {
            btnCode.className = 'sed-md-mode-btn sed-btn-mode-code active';
            btnLive.className = 'sed-md-mode-btn sed-btn-mode-live';
            btnCode.style.background = '#fff';
            btnCode.style.color = '#0969da';
            btnLive.style.background = 'transparent';
            btnLive.style.color = '#555';
            htmlCodeEl.style.display = 'block';
            livePreviewEl.style.display = 'none';
        } else {
            btnLive.className = 'sed-md-mode-btn sed-btn-mode-live active';
            btnCode.className = 'sed-md-mode-btn sed-btn-mode-code';
            btnLive.style.background = '#fff';
            btnLive.style.color = '#0969da';
            btnCode.style.background = 'transparent';
            btnCode.style.color = '#555';
            htmlCodeEl.style.display = 'none';
            livePreviewEl.style.display = 'block';
        }
    }

    var safePlaceholder = CKEDITOR.tools.htmlEncodeAttr ? CKEDITOR.tools.htmlEncodeAttr(lang.placeholder) : lang.placeholder;

    return {
        title: lang.title,
        minWidth: 840,
        minHeight: 480,
        contents: [{
            id: 'tab-main',
            label: lang.inputTab,
            elements: [
                {
                    type: 'html',
                    html: '<div class="sed-md-container" style="display:flex; flex-direction:column; gap:8px; width:100%; box-sizing:border-box;">' +

                              '<!-- Toolbar: formatting buttons on left, mode toggle on right -->' +
                              '<div style="display:flex; justify-content:space-between; align-items:center; background:#f6f8fa; border:1px solid #d0d7de; border-radius:6px; padding:6px 10px; box-sizing:border-box;">' +
                                  '<div class="sed-md-toolbar" style="display:flex; gap:4px; align-items:center; flex-wrap:wrap;">' +
                                      '<span style="font-weight:600; font-size:12px; margin-right:6px; color:#24292f;">' + lang.labelMarkdown + '</span>' +
                                      '<button type="button" class="sed-md-tool-btn" data-action="bold" title="' + lang.toolBold + '"><b>B</b></button>' +
                                      '<button type="button" class="sed-md-tool-btn" data-action="italic" title="' + lang.toolItalic + '"><i>I</i></button>' +
                                      '<button type="button" class="sed-md-tool-btn" data-action="h2" title="' + lang.toolH2 + '">H2</button>' +
                                      '<button type="button" class="sed-md-tool-btn" data-action="h3" title="' + lang.toolH3 + '">H3</button>' +
                                      '<span style="display:inline-block; width:1px; height:16px; background:#d0d7de; margin:0 4px;"></span>' +
                                      '<button type="button" class="sed-md-tool-btn" data-action="link" title="' + lang.toolLink + '">🔗 ' + lang.toolLink + '</button>' +
                                      '<button type="button" class="sed-md-tool-btn" data-action="quote" title="' + lang.toolQuote + '">❝ ' + lang.toolQuote + '</button>' +
                                      '<button type="button" class="sed-md-tool-btn" data-action="code" title="' + lang.toolCode + '">&lt;/&gt; ' + lang.toolCode + '</button>' +
                                      '<button type="button" class="sed-md-tool-btn" data-action="ul" title="' + lang.toolList + '">• ' + lang.toolList + '</button>' +
                                      '<button type="button" class="sed-md-tool-btn" data-action="table" title="' + lang.toolTable + '">⊞ ' + lang.toolTable + '</button>' +
                                  '</div>' +

                                  '<div style="display:flex; align-items:center; gap:8px;">' +
                                      '<span style="font-weight:600; font-size:12px; color:#24292f;">' + lang.labelResult + '</span>' +
                                      '<div class="sed-md-modes" style="display:inline-flex; border:1px solid #c9c9c9; border-radius:4px; overflow:hidden; background:#ebebeb;">' +
                                          '<button type="button" class="sed-md-mode-btn sed-btn-mode-live active" style="padding:4px 12px; font-size:12px; font-weight:600; cursor:pointer; border:none; background:#fff; color:#0969da;">' + lang.modeLive + '</button>' +
                                          '<button type="button" class="sed-md-mode-btn sed-btn-mode-code" style="padding:4px 12px; font-size:12px; font-weight:600; cursor:pointer; border:none; background:transparent; color:#555;">' + lang.modeHtmlCode + '</button>' +
                                      '</div>' +
                                  '</div>' +
                              '</div>' +

                              '<!-- Main two-column split-view area -->' +
                              '<div class="sed-md-wrapper" style="display:flex; gap:12px; width:100%; box-sizing:border-box;">' +
                                  '<!-- Left column: Markdown editor -->' +
                                  '<div class="sed-md-col" style="flex:1 1 0; min-width:0; width:50%; box-sizing:border-box;">' +
                                      '<textarea id="sed-md-input" class="sed-md-input" spellcheck="false" placeholder="' + safePlaceholder + '" ' +
                                          'style="width:100% !important; height:390px !important; min-height:390px !important; max-height:390px !important; box-sizing:border-box !important; border:1px solid #c9c9c9 !important; border-radius:4px !important; padding:10px 12px !important; font-family:Consolas, \'Liberation Mono\', Menlo, Courier, monospace !important; font-size:13px !important; line-height:1.5 !important; background:#fff !important; color:#1f2328 !important; resize:none !important; outline:none !important;"></textarea>' +
                                  '</div>' +

                                  '<!-- Right column: Live Preview / HTML code -->' +
                                  '<div class="sed-md-col" style="flex:1 1 0; min-width:0; width:50%; box-sizing:border-box;">' +
                                      '<div id="sed-md-preview-container" class="sed-md-preview sed-md-preview-container" ' +
                                          'style="display:block; width:100% !important; height:390px !important; min-height:390px !important; max-height:390px !important; box-sizing:border-box !important; border:1px solid #c9c9c9 !important; border-radius:4px !important; padding:10px 14px !important; background:#fff !important; overflow-y:auto !important; overflow-x:auto !important; word-break:break-word !important;">' +
                                          '<span style="color:#8c8c8c; font-style:italic;">' + lang.previewEmpty + '</span>' +
                                      '</div>' +
                                      '<textarea id="sed-html-code" class="sed-html-code" readonly spellcheck="false" ' +
                                          'style="display:none; width:100% !important; height:390px !important; min-height:390px !important; max-height:390px !important; box-sizing:border-box !important; border:1px solid #c9c9c9 !important; border-radius:4px !important; padding:10px 12px !important; font-family:Consolas, \'Liberation Mono\', Menlo, Courier, monospace !important; font-size:13px !important; line-height:1.5 !important; background:#fafbfc !important; color:#24292f !important; resize:none !important; outline:none !important;"></textarea>' +
                                  '</div>' +
                              '</div>' +

                              '<!-- Bottom info bar -->' +
                              '<div style="font-size:11.5px; color:#6e7781; display:flex; justify-content:space-between; align-items:center; padding:2px 4px; box-sizing:border-box;">' +
                                  '<span>' + lang.tipOk + '</span>' +
                                  '<span>' + lang.tipTab + '</span>' +
                              '</div>' +

                          '</div>'
                }
            ]
        }],
        onLoad: function() {
            var dialog = this;

            var btnCode = getDialogEl(dialog, '.sed-btn-mode-code');
            var btnLive = getDialogEl(dialog, '.sed-btn-mode-live');
            var inputEl = getDialogEl(dialog, '.sed-md-input');

            if (btnCode) {
                btnCode.onclick = function() { setRightMode(dialog, 'code'); };
            }
            if (btnLive) {
                btnLive.onclick = function() { setRightMode(dialog, 'live'); };
            }

            // Handle toolbar button clicks
            var rootEl = dialog.getElement().$;
            if (rootEl) {
                var toolBtns = rootEl.querySelectorAll('.sed-md-tool-btn');
                for (var i = 0; i < toolBtns.length; i++) {
                    (function(btn) {
                        btn.onclick = function() {
                            var action = btn.getAttribute('data-action');
                            if (!inputEl) return;
                            switch (action) {
                                case 'bold':
                                    insertSnippet(inputEl, '**', '**', lang.snippetBold || 'bold text');
                                    break;
                                case 'italic':
                                    insertSnippet(inputEl, '*', '*', lang.snippetItalic || 'italic text');
                                    break;
                                case 'h2':
                                    insertSnippet(inputEl, '\n## ', '\n', lang.snippetH2 || 'Heading');
                                    break;
                                case 'h3':
                                    insertSnippet(inputEl, '\n### ', '\n', lang.snippetH3 || 'Subheading');
                                    break;
                                case 'link':
                                    insertSnippet(inputEl, '[', '](https://example.com)', lang.snippetLink || 'link text');
                                    break;
                                case 'quote':
                                    insertSnippet(inputEl, '\n> ', '\n', lang.snippetQuote || 'Quote');
                                    break;
                                case 'code':
                                    insertSnippet(inputEl, '\n```php\n', '\n```\n', lang.snippetCode || '// your code');
                                    break;
                                case 'ul':
                                    insertSnippet(inputEl, '\n- ', '\n', lang.snippetList || 'List item');
                                    break;
                                case 'table':
                                    var col1 = lang.snippetTableCol1 || 'Column 1';
                                    var col2 = lang.snippetTableCol2 || 'Column 2';
                                    var val1 = lang.snippetTableVal1 || 'Value 1';
                                    var val2 = lang.snippetTableVal2 || 'Value 2';
                                    var tableSnippet = '\n| ' + col1 + ' | ' + col2 + ' |\n| :--- | :--- |\n| ' + val1 + ' | ' + val2 + ' |\n';
                                    insertSnippet(inputEl, tableSnippet, '', '');
                                    break;
                            }
                            updateOutputs(dialog);
                            inputEl.focus();
                        };
                    })(toolBtns[i]);
                }
            }

            if (inputEl) {
                inputEl.addEventListener('input', function() {
                    if (timerDebounce) clearTimeout(timerDebounce);
                    timerDebounce = setTimeout(function() {
                        updateOutputs(dialog);
                    }, 50);
                });

                inputEl.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        e.preventDefault();
                        var start = this.selectionStart;
                        var end = this.selectionEnd;
                        var inserted = false;
                        try {
                            inserted = document.execCommand('insertText', false, '  ');
                        } catch (err) {}
                        if (!inserted) {
                            if (typeof this.setRangeText === 'function') {
                                this.setRangeText('  ', start, end, 'end');
                            } else {
                                this.value = this.value.substring(0, start) + '  ' + this.value.substring(end);
                                this.selectionStart = this.selectionEnd = start + 2;
                            }
                        }
                        updateOutputs(dialog);
                    }
                });
            }
        },
        onShow: function() {
            var dialog = this;
            var inputEl = getDialogEl(dialog, '.sed-md-input');
            if (!inputEl) return;

            // Live Preview mode is active by default
            setRightMode(dialog, 'live');

            ensureLibraries(editor, function() {
                var sel = editor.getSelection();
                var selectedHtml = '';
                if (sel) {
                    var ranges = sel.getRanges();
                    if (ranges && ranges.length > 0 && !ranges[0].collapsed) {
                        var fragment = ranges[0].cloneContents();
                        var tempDiv = new CKEDITOR.dom.element('div');
                        tempDiv.append(fragment);
                        selectedHtml = tempDiv.getHtml();
                    }
                }

                var tdService = getTurndown();
                if (selectedHtml && selectedHtml.trim() !== '' && tdService) {
                    try {
                        var markdown = tdService.turndown(selectedHtml);
                        inputEl.value = markdown;
                    } catch (e) {
                        inputEl.value = '';
                    }
                } else {
                    inputEl.value = '';
                }

                updateOutputs(dialog);
                setTimeout(function() {
                    inputEl.focus();
                }, 60);
            });
        },
        onOk: function() {
            var dialog = this;
            var inputEl = getDialogEl(dialog, '.sed-md-input');
            var text = inputEl ? inputEl.value : '';
            if (!text || text.trim() === '') return;

            var parser = getMdParser();
            if (parser) {
                var html = parser.render(text);
                editor.insertHtml(html);
            }
        }
    };
});
