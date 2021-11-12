<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
$listid = $nv_Request->get_string('listid', 'post,get', '');
if ($nv_Request->isset_request('loaddepot', 'post')) {
    $depotid = $nv_Request->get_int('depotid', 'post', 0 );
    if( $listid == '' || $depotid == 0  ){
        exit('');
    }
    $array_depot_number = array();
    $sql = 'SELECT productid, quantity_in, quantity_gift_in, quantity_out, quantity_gift_out FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs WHERE customerid=0 AND depotid=' . $depotid . ' AND productid IN (' . $listid . ')';
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
    $tranfer_from = $nv_Request->get_int('tranfer_from', 'post', 0 );
    $tranfer_to = $nv_Request->get_int('tranfer_to', 'post', 0 );
    if( $tranfer_from == 0 ){
        exit('ERROR_' . $lang_module['error_selecttranfer_from'] );
    }
    if( $tranfer_to == 0 ){
        exit('ERROR_' . $lang_module['error_selecttranfer_to'] );
    }
    if( $tranfer_to == $tranfer_from ){
        exit('ERROR_' . $lang_module['error_selecttranfer_warehouse_logs'] );
    }
    if( !empty( $array_productid )){
        $list_id = implode(',', (array_keys($array_productid)));
        $sql = 'SELECT productid, quantity_in, quantity_gift_in, quantity_out, quantity_gift_out FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs WHERE customerid=0 AND depotid=' . $tranfer_from . ' AND productid IN (' . $list_id . ')';
        $result = $db->query($sql);
        while ($row = $result->fetch()) {
            $number = ($row['quantity_in'] + $row['quantity_gift_in']) - ( $row['quantity_out'] + $row['quantity_gift_out'] );
            if( $number < $array_productid[$row['productid']] ){
                exit('ERROR_' . sprintf( $lang_module['error_number_product'], $array_product[$row['productid']]['title'], $array_productid[$row['productid']] ) );
            }
        }
        $customerid_from = $customerid_to = 0;
        foreach ( $array_productid as $productid => $quantity ){
            luanchuyenhanghoa( $customerid_from, $customerid_to, $tranfer_from, $tranfer_to, $productid, $quantity );
        }
        exit('OK');
    }
}
$array_data = array();
$depotid = 0;
$error = '';

if (empty($listid)) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=product');
    die();
} else {
    $listid = rtrim($listid, ',');
}

// List pro_unit
$array_unit = array();
$sql = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_units';
$result_unit = $db->query($sql);
if ($result_unit->rowCount() > 0) {
    while ($row = $result_unit->fetch()) {
        $array_unit[$row['id']] = $row;
    }
}

// List depot
$array_depot = array();
$sql = 'SELECT id, title, address FROM ' . NV_PREFIXLANG . '_' . $module_data . '_depot WHERE status=1 ORDER BY id';
$result_unit = $db->query($sql);
if ($result_unit->rowCount() > 0) {
    while ($row = $result_unit->fetch()) {
        $array_depot[$row['id']] = $row;
    }
}

$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE id IN (' . $listid . ') ORDER BY addtime DESC';
$_query = $db->query($_sql);

while ($row = $_query->fetch()) {
    $array_data[$row['id']] = $row;
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('LISTIDPRODUCT', $listid);
if( !empty( $error )){
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}
if( !empty( $array_depot )){
    foreach ($array_depot as $depot ) {
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
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
