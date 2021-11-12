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

$page_title = sprintf($lang_module['warehouse_day'], nv_date('d/m/Y', NV_CURRENTTIME));

$array_data = array();
$depotid = 0;
$error = '';
$listid = $nv_Request->get_string('listid', 'get', '');

if (empty($listid)) {
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=product');
    die();
} else {
    $listid = rtrim($listid, ',');
}

if ($nv_Request->isset_request('submit', 'post')) {
    $title = $nv_Request->get_title('title', 'post', $page_title);
    $depotid = $nv_Request->get_int('depotid', 'post', 0 );
    $note = $nv_Request->get_textarea('note', '', 'br');
    $data = $nv_Request->get_array('data', 'post', array());

    if( $depotid == 0 ){
        $error = $lang_module['error_select_depotid'];
    }else{
        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_importproduct( customerid, title, note, addtime ) VALUES ( ' . $admin_info['admin_id'] . ', :title, :note,  ' . NV_CURRENTTIME . ' )';
        $data_insert = array();
        $data_insert['title'] = $title;
        $data_insert['note'] = $note;
        $iid = $db->insert_id($sql, 'iid', $data_insert);

        if ($iid > 0 and !empty($data)) {
            foreach ($data as $pro_id => $data_i) {
                $total_num = 0;
                $price_i = 0;
                $data_i['price'] = str_replace('.', '', $data_i['price'] );
                // Cap nhat logs nhap kho

                $price = $data_i['price'] * $data_i['quantity'];
                adminnhapkhohanghoa( 0, $depotid, $pro_id, $data_i['quantity'], $price, '+', 1, 0 );
                $total_num = $data_i['quantity'];
                //ghi lich su cac lan nhap hang
                $db->query('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_importproduct_history( iid, productid, quantity, totalprice ) VALUES ( ' . $iid .  ', ' . $pro_id . ', ' . $data_i['quantity'] . ', ' . $price . ' )');

                // Cap nhat tong so luong
                $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_product SET pnumber = pnumber + ' . $total_num . ' WHERE id=' . $pro_id);
            }
        }
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=product');
        die();
    }

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

$array_data_product = array();
$result_warehouse_logs = $db->query('SELECT productid, quantity_out, quantity_in FROM ' . NV_PREFIXLANG . '_' . $module_data . '_warehouse_logs WHERE customerid =0' );
while ($row = $result_warehouse_logs->fetch()) {
    $number = $row['quantity_in']  -  $row['quantity_out'];
    if( isset( $array_data_product[$row['productid']] )){
        $quantity = $array_data_product[$row['productid']]['quantity'] + $number;
    }else{
        $quantity = $number;
    }
    $array_data_product[$row['productid']] = $quantity;
}


$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product WHERE id IN (' . $listid . ') ORDER BY addtime DESC';
$_query = $db->query($_sql);

while ($row = $_query->fetch()) {
    if( isset( $array_data_product[$row['id']] )){
        $row['quantity'] = $array_data_product[$row['id']]['quantity'];
    }else{
        $row['quantity'] = 0;
    }
    $array_data[$row['id']] = $row;
}

$xtpl = new XTemplate("warehouse.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('WAREHOUSE_TITLE', sprintf($lang_module['warehouse_day'], nv_date('d/m/Y', NV_CURRENTTIME)) );
if( !empty( $error )){
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}
foreach ($array_depot as $depot ) {
    $depot['sl'] = ( $depot['id'] == $depotid )? ' selected=selected' : '';
    $xtpl->assign('DEPOT', $depot);
    $xtpl->parse('main.depotid');
}

if (!empty($array_data)) {
    $i=1;
    foreach ($array_data as $data) {
        $data['no'] = $i;
        $data['product_unit'] = $array_unit[$data['unit']]['title'];
        $xtpl->assign('DATA', $data);
        $xtpl->parse('main.loop');
        $i++;
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
