<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (anvh.ceo@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 14 Mar 2017 10:56:01 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
		'name' => 'Study Online',
		'modfuncs' => 'main,detail,viewcat,giao-vien,mon,bai-giang,xembaigiang,tag,history,saved',
		'submenu' => '',
		'is_sysmod' => 0,
		'virtual' => 1,
		'version' => '4.3.0',
		'date' => 'Tue, 14 Mar 2017 10:56:01 GMT',
		'author' => 'CASH13 (anvh.ceo@cash13.vn)',
		'uploads_dir' => array($module_name, $module_name . '/icon', $module_name . '/teacher', $module_name . '/khoahoc'),
		'note' => 'Module dạy học online'
	);