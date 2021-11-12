<?php

/**
 * @Project PHOTOS 4.x
 * @Author KENNY NGUYEN (nguyentiendat713@gmail.com) 
 * @Copyright (C) 2017 tradacongnghe.com. All rights reserved
 * @Based on NukeViet CMS 
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 07 June 2017 08:00:00 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(  
	'name' => 'Photos',
	'modfuncs' => 'main, viewcat, detail_album, detail, detail_viewer, search, uppic',
	'submenu' => 'rss,search',
	'is_sysmod' => 0, 
	'virtual' => 1,
	'version' => '1.4.02',
	'date' => 'Wed, 07 June 2017 08:00:00 GMT',
	'author' => 'KENNY NGUYEN (nguyentiendat713@gmail.com)',
	'uploads_dir' => array(
		$module_upload,
		$module_upload.'/images',
		$module_upload.'/thumbs',
		$module_upload.'/temp'
	),  
	'note' => ''
);