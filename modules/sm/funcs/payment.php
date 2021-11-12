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

$page_title = $lang_module['orders'];

$order_id = $nv_Request->get_int('order_id', 'get', 0);
$checkss = $nv_Request->get_string('checkss', 'get', '');
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
    $listid = $listnum = $listprice = array();
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_orders_id WHERE order_id=' . $order_id);
    while ($row = $result->fetch()) {
        $listid[] = $row['proid'];
        $listnum[$row['proid']] = $row['num'];
        $listprice[$row['proid']] = $row['price'];
    }
    if (! empty($listid)) {
        $templistid = implode(',', $listid);
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_product  WHERE id IN (' . $templistid . ') AND status =1';
        $result = $db->query($sql);
        while ( $row = $result->fetch()) {
            $row['price_order'] = $listprice[$row['id']];
            $row['order_number'] = $listnum[$row['id']];
            $row['total_price'] = $row['order_number'] * $row['price_order'];
            $row['unit_product'] = $array_unit_product[$row['unit']]['title'];
            $data_pro[] = $row;
        }
    }

    if ($data['status'] == 4) {
        $data['transaction_name'] = $lang_module['history_payment_yes'];
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


    $contents = call_user_func('payment', $data, $data_pro );

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
} else {
    Header('Location: ' . NV_BASE_SITEURL);
    die();
}
