<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 01:50:26 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
		'name' => 'Rm',
		'modfuncs' => 'main,export,customer-list,customertop,checkin,eventcontent,submit-data',//student-list
		'submenu' => 'main',
		'is_sysmod' => 0,
		'virtual' => 1,
		'version' => '4.3.00',
		'date' => 'Tue, 18 Nov 2014 01:50:26 GMT',
		'author' => 'Mr.An (anvh.ceo@gmail.com)',
		'uploads_dir' => array($module_name),
		'note' => ''
	);