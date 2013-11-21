CKEDITOR.editorConfig = function( config )
{
  config.toolbar = 'Basic';

   config.extraPlugins = 'syntaxhighlight,youtube,vimeo,more';
  
  config.htmlEncodeOutput = false;
  config.entities = false;
  
  // Full Ckeditor Tollbar (All buttons)
  config.toolbar_Full =
  [
    ['Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'],
    ['Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt'],
    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
    '/',
    ['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],
    ['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Vimeo','Youtube','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
    '/',
    ['Styles','Format','Font','FontSize','More'],
    ['TextColor','BGColor'],
    ['Maximize', 'ShowBlocks']
  ];
  
  config.toolbar_Extended =
  [
  	['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
  	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
  	['Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
  	'/',
  	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
  	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
  	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
  	['Link','Unlink','Anchor'], ['Image','Flash','Vimeo','Youtube'],
  	'/',  	
  	['Styles','Format','More'], ['TextColor','BGColor'],['Code'], ['Maximize', 'ShowBlocks'], ['Templates','-','Source']
  ];
  
  config.toolbar_Basic =
  [
  	['Bold','Italic','Underline','Strike'],
  	['NumberedList','BulletedList','-','Blockquote'],
  	['Image','Link','Unlink','Anchor','Smiley'],
  	['TextColor','BGColor'],
  	['Cut','Copy','Paste','PasteText','Scayt'],
  	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],['Code']
  ];
  
  config.toolbar_Micro =
  [
  	['Bold','Italic','Underline','Strike', 'Link', 'Unlink']
  ];

};