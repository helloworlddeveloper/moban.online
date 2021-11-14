<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2017 14:43
 */

if( ! defined( 'NV_IS_MOD_SM' ) ) die( 'Stop!!!' );


$q = $nv_Request->get_title( 'term', 'get', '', 1 );
$custype = $nv_Request->get_int( 'custype', 'get', 0 );
if( empty( $q ) ) return;

$db->sqlreset()->select( '*' )->from( NV_PREFIXLANG . '_' . $module_data . '_product')->where( 'code LIKE :code OR title LIKE :title' )->limit( 30 );

$sth = $db->prepare( $db->sql() );
$sth->bindValue( ':code', '%' . $q . '%', PDO::PARAM_STR );
$sth->bindValue( ':title', '%' . $q . '%', PDO::PARAM_STR );
$sth->execute();

$array_data = array();
while( $data_product = $sth->fetch(  ) )
{
    if( $custype == 0){
        $data_product['price_show'] = number_format($data_product['price_retail'], 0, '.', ',');
    }else{
        $data_product['price_show'] = number_format($data_product['price_wholesale'], 0, '.', ',');
    }
    $array_data[] = array(
        'key' => $data_product['id'],
        'title' => $data_product['title'],
        'value' => $data_product['code'] . ' - ' . $data_product['title'],
        'price_show' => $data_product['price_show']
    );
}

header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();
