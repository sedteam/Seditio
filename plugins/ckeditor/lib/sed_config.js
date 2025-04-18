CKEDITOR.editorConfig = function(config) {
    config.toolbar = 'Basic';

    config.extraPlugins = 'more,html5video,imagepaste,spoiler,internallink,tabber';

    config.internallinkServiceURL = "/ajax/?m=pages";
	
	config.uploadUrl = 'plug/?ajx=ckeditor';
    config.filebrowserUploadUrl = 'plug/?ajx=ckeditor&fl=filebrowser';

    config.pasteUploadFileApi = 'plug/?ajx=ckeditor';
    config.pasteUploadImageUrlApi = 'plug/?ajx=ckeditor';
    config.filebrowserImageUploadUrl = 'plug/?ajx=ckeditor';

    config.contentsCss = ['plugins/ckeditor/lib/ckeditor.css?v=1'];

    config.layoutmanager_loadbootstrap = false;

    config.htmlEncodeOutput = false;
    config.entities = false;
    config.allowedContent = true;

    CKEDITOR.dtd.$removeEmpty.i = 0;

    // Full Ckeditor Tollbar (All buttons)
    config.toolbar_Full = [
        ['Save', 'NewPage', 'DocProps', 'Preview', 'Print', '-', 'Templates'],
        ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
        ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker'],
        ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
        '/', ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],
        ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],
        ['Link', 'Unlink', 'Anchor', 'InternalLink'],
        ['Image', 'Youtube', 'MediaEmbed', 'Html5video'],
        ['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'],
        '/', ['Styles', 'Format', 'Font', 'FontSize'],
        ['TextColor', 'BGColor'],
        ['Syntaxhighlight'],
        ['Maximize', 'ShowBlocks'],
        ['More', 'Templates', 'AddLayout', 'Spoiler', 'Tabber']
    ];

    config.toolbar_Extended = [
        ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'SpellChecker'],
        ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'],
        ['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak'],
        ['Link', 'Unlink', 'Anchor', 'InternalLink'],
        ['Image', 'Youtube', 'MediaEmbed', 'Html5video'],
        ['Syntaxhighlight', 'More', 'Maximize', 'ShowBlocks'],
        ['Templates', 'AddLayout', 'Spoiler', 'Tabber'],
        ['Source'],
        '/', ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
        ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', 'Blockquote', 'CreateDiv'],
        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
        ['TextColor', 'BGColor'],
        ['Styles', 'Format', 'Font', 'FontSize']
    ];

    config.toolbar_Basic = [
        ['Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'],
        ['NumberedList', 'BulletedList', '-', 'Blockquote'],
        ['Undo', 'Redo', '-', 'SelectAll', 'RemoveFormat'],
        ['Image', 'Youtube', 'MediaEmbed', 'Link', 'Unlink', 'Anchor', 'Smiley'],
        ['TextColor', 'BGColor'],
        ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'],
        ['Syntaxhighlight', 'Spoiler']
    ];

    config.toolbar_Micro = [
        ['Bold', 'Italic', 'Underline', 'Strike', 'Link', 'Unlink']
    ];

};