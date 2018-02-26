/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';		
	config.filebrowserBrowseUrl = site_url4kc_finder+'assets/grocery_crud/texteditor/ckeditor/kcfinder/browse.php?type=files';
	config.filebrowserImageBrowseUrl = site_url4kc_finder+'assets/grocery_crud/texteditor/ckeditor/kcfinder/browse.php?type=images';
	config.filebrowserFlashBrowseUrl = site_url4kc_finder+'assets/grocery_crud/texteditor/ckeditor/kcfinder/browse.php?type=flash';
	config.filebrowserUploadUrl = site_url4kc_finder+'assets/grocery_crud/texteditor/ckeditor/kcfinder/upload.php?type=files';
	config.filebrowserImageUploadUrl = site_url4kc_finder+'assets/grocery_crud/texteditor/ckeditor/kcfinder/upload.php?type=images';
	config.filebrowserFlashUploadUrl = site_url4kc_finder+'assets/grocery_crud/texteditor/ckeditor/kcfinder/upload.php?type=flash';
};
