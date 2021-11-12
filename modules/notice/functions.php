<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 01 Feb 2013 04:11:16 GMT
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

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
define( 'NV_IS_MOD_NOTICE', true );