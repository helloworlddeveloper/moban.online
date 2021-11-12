<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$q = $nv_Request->get_title( 'term', 'get', '', 1 );
if( empty( $q ) ) return;
$sql = 'SELECT id, ' . NV_LANG_DATA . '_title AS title FROM ' . NV_IS_TABLE_SHOPS . '_rows WHERE status=1';
    $db->sqlreset()->select( 'id, ' . NV_LANG_DATA . '_title AS title' )->from( NV_IS_TABLE_SHOPS . '_rows' )->where( 'status=1 AND (' . NV_LANG_DATA . '_title LIKE :title OR product_code LIKE :product_code)' )->limit( 50 );

    $sth = $db->prepare( $db->sql() );
    $sth->bindValue( ':title', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':product_code', '%' . $q . '%', PDO::PARAM_STR );
    $sth->execute();

    $array_data = array();
    while( list( $id, $title ) = $sth->fetch( 3 ) )
    {
        $array_data[] = array(
            'key' => $id,
            'value' => $title,
        );
    }


header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();
