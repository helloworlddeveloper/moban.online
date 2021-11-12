<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.An (anvh.ceo@gmail.com)
 * @Copyright (C) 2014 EDUS.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 05/07/2010 09:47
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'Download',
	'modfuncs' => 'main,down,upload,shareu',
	'submenu' => 'main,upload,shareu',
	'is_sysmod' => 0,
	'virtual' => 0,
	'version' => '4.0.00',
	'date' => 'Wed, 20 Oct 2010 00:00:00 GMT',
	'author' => 'Mr.An (anvh.ceo@gmail.com)',
	'note' => '',
	'uploads_dir' => array(
		$module_name,
	)
);