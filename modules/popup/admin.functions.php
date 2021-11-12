<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2014 Mr.Thang. All rights reserved
 * @Createdate 21 Mar 2016 03:44:56 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$allow_func = array( 'main', 'province', 'district', 'bymodule', 'config');

define( 'NV_IS_FILE_ADMIN', true );

function nv_Province()
{
	global $db, $module_data;

	$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_province WHERE status=1 ORDER BY weight ASC';
	$result = $db->query( $sql );
	$list = array();
	while( $row = $result->fetch() )
	{
		$list[$row['id']] = array( //
				'id' => $row['id'], //
                'title' => $row['title'], //
				'weight' => ( int )$row['weight'] //
				);
	}

	return $list;
}