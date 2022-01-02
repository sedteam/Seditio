CKEDITOR.editorConfig = function( config )
{
	config.toolbar = 'Basic';

	var ua = window.navigator.userAgent;
	var isIE = /MSIE|Trident/.test(ua);

	if ( isIE ) {
		config.extraPlugins = 'more';
	} else {
		config.extraPlugins = 'more,uploadimage,uploadwidget,layoutmanager';
	}
	
	config.uploadUrl = 'plug.php?ajx=ckeditor';
	config.filebrowserUploadUrl = '/plug.php?ajx=ckeditor&fl=filebrowser';

	config.pasteUploadFileApi = 'plug.php?ajx=ckeditor';
	config.pasteUploadImageUrlApi = 'plug.php?ajx=ckeditor';
	config.filebrowserImageUploadUrl = 'plug.php?ajx=ckeditor';
	
	config.contentsCss = ['/plugins/ckeditor/lib4/ckeditor.css?v=10'];
	
	config.layoutmanager_loadbootstrap = false;

	config.htmlEncodeOutput = false;
	config.entities = false;
	config.allowedContent = true;
  
	CKEDITOR.dtd.$removeEmpty.i = 0; 	
  
	// Full Ckeditor Tollbar (All buttons)
	config.toolbar_Full =
	[
	['Save','NewPage','DocProps','Preview','Print','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'],
	['Find','Replace','-','SelectAll','-','SpellChecker'],
	['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
	'/',
	['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],
	['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'],
	['Link','Unlink','Anchor'],
	['Image','AddLayout','Youtube','MediaEmbed','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
	'/',
	['Styles','Format','Font','FontSize'],
	['TextColor','BGColor'],['Syntaxhighlight'],
	['Maximize', 'ShowBlocks'], ['More','Templates'],
	];

	config.toolbar_Extended =
	[
	['Cut','Copy','Paste','PasteText','PasteFromWord','-','SpellChecker'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
	['Link','Unlink','Anchor'], ['Image','Youtube','MediaEmbed'],  
	['Syntaxhighlight','More','Maximize', 'ShowBlocks'],['Templates','AddLayout'],['Source'],
	'/',
	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	['TextColor','BGColor'],	
	['Styles','Format','Font','FontSize']
	];

	config.toolbar_Basic =
	[
	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	['NumberedList','BulletedList','-','Blockquote'],
	['Undo','Redo','-','SelectAll','RemoveFormat'],
	['Image','Youtube','MediaEmbed','Link','Unlink','Anchor','Smiley'],
	['TextColor','BGColor'],
	['Cut','Copy','Paste','PasteText','PasteFromWord'],
	['Syntaxhighlight']
	];

	config.toolbar_Micro =
	[
	['Bold','Italic','Underline','Strike', 'Link', 'Unlink']
	];

};
