<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 01 Feb 2013 04:11:16 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

//////$submenu['cat'] = $lang_module['cat']; //chi cho Admin cap thap sua va xoa
//An het menu cap thap voi admin thuong
if (defined( 'NV_IS_GODADMIN' )){
	////$submenu['cat'] = $lang_module['cat'];
}

$allow_func = array( 'main', 'cat');
function nv_catList()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY id ASC";
    $result = $db->query( $sql );
    $list = array();
    while ( $row = $result->fetch() )
    {
        $list[$row['id']] = array( //
        	'id' => $row['id'],
            'title' => $row['title'], //
            'status' => ( int )$row['status'] //
            );
    }

    return $list;
}

function nv_rowList()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows ORDER BY id ASC";
    $result = $db->query( $sql );
    $list = array();
    while ( $row = $result->fetch() )
    {
        $list[$row['id']] = array( //
        	'id' =>$row['id'],//
            'title' => $row['title'], //
            'weight' => ( int )$row['weight'] //
            );
    }

    return $list;
}


function fix_rowWeight()
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while ( $row = $result->fetch() )
    {
        $weight++;
        $db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET weight=" . $weight . " WHERE id=" . $row['id'] );
    }
}

define( 'NV_IS_FILE_ADMIN', true );