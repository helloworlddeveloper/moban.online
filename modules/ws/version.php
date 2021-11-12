<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
		'name' => 'ws',
		'modfuncs' => 'regisevent,login,updateaff,listaff,getaffs,order,getorders,products,sales,store,tonkho,news,proinfo,policy,event,changeprice,getstatus,senddeviceid',
    	'is_sysmod' => 0,
    	'virtual' => 0,	
		'version' => '4.0.0',
		'date' => 'Thu, 19 Jul 2018 09:53:22 GMT',
		'author' => 'VINADES (contact@vinades.vn)',
		'uploads_dir' => array($module_name),
		'note' => ''
	);