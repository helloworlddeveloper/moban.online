<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if (! defined('NV_IS_MOD_SM')) {
    die('Stop!!!');
}
if ($nv_Request->isset_request('loaddepot', 'post')) {
    $depotid = $nv_Request->get_int('depotid', 'post', 0 );
    if( $depotid == 0  ){
        exit('');
    }
    $array_depot_number = array();
    $sql = 'SELECT productid, quantity_in, quantity_gift_in, quantity_out, quantity_gift_out FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs WHERE customerid=' . $depotid;
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $number = ($row['quantity_in'] + $row['quantity_gift_in']) - ( $row['quantity_out'] + $row['quantity_gift_out'] );
        $array_depot_number[$row['productid']] = $number;
    }

    echo json_encode( $array_depot_number, true );
    exit;
}
if ($nv_Request->isset_request('savetranfer', 'post')) {
    $array_productid = $nv_Request->get_array('productid', 'post', array());
    $customerid_from = $nv_Request->get_int('tranfer_from', 'post', 0 );
    $customerid_to = $nv_Request->get_int('tranfer_to', 'post', 0 );
    if( $customerid_from == 0 ){
        exit('ERROR_' . $lang_module['error_selecttranfer_from'] );
    }
    if( $customerid_to == 0 ){
        exit('ERROR_' . $lang_module['error_selecttranfer_to'] );
    }
    if( $customerid_from == $customerid_to ){
        exit('ERROR_' . $lang_module['error_selecttranfer_warehouse_logs'] );
    }
    if( !empty( $array_productid )){
        $list_id = implode(',', (array_keys($array_productid)));
        $sql = 'SELECT productid, quantity_in, quantity_gift_in, quantity_out, quantity_gift_out FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs WHERE customerid=' . $customerid_from . ' AND depotid=0 AND productid IN (' . $list_id . ')';

        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            $number = ($row['quantity_in'] + $row['quantity_gift_in']) - ( $row['quantity_out'] + $row['quantity_gift_out'] );
            if( $number < $array_productid[$row['productid']] ){
                exit('ERROR_' . sprintf( $lang_module['error_number_product'], $array_product[$row['productid']]['title'], $array_productid[$row['productid']] ) );
            }
        }
        $tranfer_from = $tranfer_to = 0;
        foreach ( $array_productid as $productid => $quantity ){
            luanchuyenhanghoa( $customerid_from, $customerid_to, $tranfer_from, $tranfer_to, $productid, $quantity );
        }
        exit('OK');
    }
}
$array_data = array();
$depotid = 0;
$error = '';

// List pro_unit
$array_unit = array();
$sql = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_units';
$result_unit = $db->query($sql);
if ($result_unit->rowCount() > 0) {
    while ($row = $result_unit->fetch()) {
        $array_unit[$row['id']] = $row;
    }
}

//he thong ctv, dl
$db->sqlreset()->select('t1.userid, t2.code, t1.username, t1.first_name, t1.last_name, t1.email' )->from( NV_TABLE_AFFILIATE . '_users AS t2')->join( 'INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t1 ON t1.userid=t2.userid')->where( 't2.status=1 AND t2.parentid = ' . $user_info['userid'] );

$sth = $db->prepare( $db->sql() );
$sth->execute();
$list_Agency = array();
while( $row = $sth->fetch( ) )
{
    $row['fullname'] = nv_show_name_user( $row['first_name'], $row['last_name'], $row['username'] );

    $list_Agency[] = $row;
}

$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE status=1 ORDER BY addtime DESC';
$_query = $db->query($_sql);

while ($row = $_query->fetch()) {
    $array_data[$row['id']] = $row;
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('LISTIDPRODUCT', $listid);
if( !empty( $error )){
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}
if( !empty( $list_Agency )){
    foreach ($list_Agency as $depot ) {
        $xtpl->assign('DEPOT', $depot);
        $xtpl->parse('main.tranfer_from.loop');
        $xtpl->parse('main.tranfer_to.loop');
    }
    $xtpl->parse('main.tranfer_to');
    $xtpl->parse('main.tranfer_from');
}


if (!empty($array_data)) {
    foreach ($array_data as $data) {
        $data['product_unit'] = $array_unit[$data['unit']]['title'];
        $xtpl->assign('DATA', $data);
        $xtpl->parse('main.loop');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
