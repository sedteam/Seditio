/*
 Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.

 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license

*/
CKEDITOR.plugins.add("uicolor",{requires:"dialog",lang:"en,ru,tr",icons:"uicolor",hidpi:!0,init:function(a){var b=new CKEDITOR.dialogCommand("uicolor");b.editorFocus=!1;CKEDITOR.dialog.add("uicolor",this.path+"dialogs/uicolor.js");a.addCommand("uicolor",b);a.ui.addButton&&a.ui.addButton("UIColor",{label:a.lang.uicolor.title,command:"uicolor",toolbar:"tools,1"})}});