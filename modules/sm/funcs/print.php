<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if (! defined('NV_IS_MOD_SM')) {
    die('Stop!!!');
}

$order_id = $nv_Request->get_string('order_id', 'get', '');
$admin = $nv_Request->get_int('admin', 'get', 0);
$cty = $nv_Request->get_int('cty', 'get', 0);
$checkss = $nv_Request->get_string('checkss', 'get', '');
$type = $nv_Request->get_int('type', 'get', 0);

if ($order_id > 0 and $checkss == md5($order_id . $global_config['sitekey'] . session_id())) {
    $data_pro = array();
    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=';

    // Thong tin don hang
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders WHERE order_id=' . $order_id);
    if ($result->rowCount() == 0) {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        die();
    }
    $data = $result->fetch();

    if (empty($data)) {
        Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true));
        die();
    }

    // Thong tin chi tiet mat hang trong don hang
    $listid = $listnum = $listprice = $data_orderid_gift = array();
    if( $admin == 1 ){
        $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id WHERE isgift=1 AND order_id=' . $order_id);

        $data_orderid_gift = array();
        while ($row = $result->fetch()) {
            $data_orderid_gift[$row['proid']] = $row;
        }

        $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id WHERE isgift=0 AND order_id=' . $order_id);
    }else{
        $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id WHERE order_id=' . $order_id);
    }

    $data_orderid = array();
    while ($row = $result->fetch()) {
        $data_orderid[] = $row;
    }

    $total_price = 0;
    foreach ( $data_orderid as $proidinfo ){
        $proidinfo['title'] = $array_product[$proidinfo['proid']]['title'];
        $unit = $array_product[$proidinfo['proid']]['unit'];
        $total_price_i = $proidinfo['price'] * $proidinfo['num'];
        $total_price = $total_price + $total_price_i;
        $proidinfo['isgift'] = ( $proidinfo['isgift'] == 1 )? $lang_module['product_gift'] : '';
        if( $admin == 1 ){
            if( isset( $data_orderid_gift[$proidinfo['proid']] )){
                $proidinfo['num'] = $proidinfo['num'] + $data_orderid_gift[$proidinfo['proid']]['num'];
            }
            $proidinfo['num'] = $proidinfo['num'] - $proidinfo['num_com'];
        }

        $data_pro[] = array(
            'title' => $proidinfo['title'],
            'isgift' => $proidinfo['isgift'],
            'price_order' => $proidinfo['price'],
            'order_number' => $proidinfo['num'],
            'num_com' => $proidinfo['num_com'],
            'total_price' => $total_price_i,
            'unit_product' => $array_unit_product[$unit]['title'],
        );

    }

    if ($data['status'] == 4) {
        $data['transaction_name'] = $lang_module['history_payment_yes'];
    } elseif ($data['status'] == 5) {
        $data['transaction_name'] = $lang_module['history_order_ships'];
    } elseif ($data['status'] == 3) {
        $data['transaction_name'] = $lang_module['history_payment_cancel'];
    } elseif ($data['status'] == 2) {
        $data['transaction_name'] = $lang_module['history_payment_check'];
    } elseif ($data['status'] == 1) {
        $data['transaction_name'] = $lang_module['history_payment_send'];
    } elseif ($data['status'] == 0) {
        $data['transaction_name'] = $lang_module['history_payment_no'];
    } elseif ($data['status'] == - 1) {
        $data['transaction_name'] = $lang_module['history_payment_wait'];
    } else {
        $data['transaction_name'] = 'ERROR';
    }


    $page_title = $data['order_code'];
    $contents = call_user_func('print_pay', $data, $data_pro, $admin, $cty);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
} else {
    Header('Location: ' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true));
    exit();
}
