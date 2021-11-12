<?php

/**

 * @Project NUKEVIET 4.x

 * @Author VINADES.,JSC (contact@vinades.vn)

 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved

 * @License GNU/GPL version 2 or any later version

 * @Createdate 04/18/2017 09:47

 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['order_title'];
$table_name = NV_PREFIXLANG . "_" . $module_data . "_orders";

$checkss = $nv_Request->get_string('checkss', 'get', '');
$where = '';
$search = array();
if ($checkss == md5(session_id())) {

    $search['order_code'] = $nv_Request->get_string('order_code', 'get', '');
    $search['date_from'] = $nv_Request->get_string('from', 'get', '');
    $search['date_to'] = $nv_Request->get_string('to', 'get', '');
    $search['order_email'] = $nv_Request->get_string('order_email', 'get', '');
    $search['order_payment'] = $nv_Request->get_string('order_payment', 'get', '');

    if (! empty($search['order_code'])) {
        $where .= ' AND order_code like "%' . $search['order_code'] . '%"';
    }
    if (! empty($search['date_from'])) {
        if (! empty($search['date_from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_from'], $m)) {
            $search['date_from'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        } else {
            $search['date_from' ] = NV_CURRENTTIME;
        }
        $where .= ' AND order_time >= ' . $search['date_from'] . '';
    }

    if (! empty($search['date_to'])) {
        if (! empty($search['date_to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_to'], $m)) {
            $search['date_to'] = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
        } else {
            $search['date_to' ] = NV_CURRENTTIME;
        }
        $where .= ' AND order_time <= ' . $search['date_to'] . '';
    }

    if (! empty($search['order_email'])) {
        $where .= ' AND order_email like "%' . $search['order_email'] . '%"';
    }

    if ($search['order_payment'] != '') {
        $where .= ' AND status  = ' . $search['order_payment'] . '';
    }
}

$transaction_status = array(
    '4' => $lang_module['history_payment_yes'],
    '0' => $lang_module['history_payment_no']);

$xtpl = new XTemplate("orders-npp.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$per_page = 50;
$page = $nv_Request->get_int('page', 'get', 1);
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$count = 0;
$order_info = array( 'num_items' => 0, 'sum_price' => 0, 'sum_unit' => '' );

//don hang dat truoc
$sql_where = 't1.ordertype=1 AND t2.num_com > 0 AND t2.num_out=t2.num_com';

// Fetch Limit
$db->sqlreset()->select('COUNT(*)')
    ->from($table_name . ' AS t1')
    ->group('t1.order_id')
    ->join('INNER JOIN ' . $table_name . '_id AS t2 ON t1.order_id=t2.order_id')
    ->where($sql_where . $where);

$num_items = $db->query($db->sql())->fetchColumn();
$order_info['num_items'] = $num_items;
$db->select('t1.*')->where($sql_where . $where)
    ->order('t1.order_id DESC');

$nv_Request->set_Session('sql_export_' . $module_data, $db->sql());
$db->limit($per_page)->offset(($page - 1) * $per_page);

$query = $db->query($db->sql());
while ($row = $query->fetch()) {
    $acno = 0;
    $price = $row['order_total'];
    $order_info['sum_price'] = $order_info['sum_price'] + $price;

    if( $row['ordertype'] == 2 ){
        $transaction_status[0] = $lang_module['history_payment_no_plane'];
        $transaction_status[4] = $lang_module['history_payment_yes_plane'];
    }else{
        $transaction_status[0] = $lang_module['history_payment_no'];
        $transaction_status[4] = $lang_module['history_payment_yes'];
    }

    if ($row['status'] == 4) {
        $row['status_payment'] = $transaction_status[4];
    } elseif ($row['status'] == 5) {
        $row['status_payment'] = $transaction_status[5];
    }elseif ($row['status'] == 3) {
        $row['status_payment'] = $transaction_status[3];
    } elseif ($row['status'] == 2) {
        $row['status_payment'] = $transaction_status[2];
    } elseif ($row['status'] == 1) {
        $row['status_payment'] = $transaction_status[1];
    } elseif ($row['status'] == 0) {
        $row['status_payment'] = $transaction_status[0];
    } elseif ($row['status'] == - 1) {
        $row['status_payment'] = $transaction_status[-1];
    } else {
        $row['status_payment'] = "ERROR";
    }

    if( $row['price_payment'] == $row['order_total'] ){
        $row['status_payment'] = $lang_module['history_payment_yes'];
    }
    if( $row['ordertype']  == 0 ){
        $row['ordertype']  = '<b class="red"> ' . $lang_module['return_order'] . '</b>';
    }elseif( $row['ordertype'] == 2 ){
        $row['ordertype']  = '<b class="blue"> ' . $lang_module['ordertype_2'] . '</b>';
    }
    else{
        $ordertype = '';
    }

    $row['shipcode'] = ( $row['shipcode'] == 0 )? '' : '<b>Ship COD</b>';
    $row['link_user'] = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=edit&userid=" . $row['user_id'];
    $row['order_time'] = nv_date("H:i d/m/y", $row['order_time']);
    if( $row['status'] != 0 ){
        $row['price_payment'] = $row['order_total'] - $row['price_payment'];
    }else{
        $row['price_payment'] = $row['order_total'];
    }

    $row['order_total'] = number_format( $price, 0, '.', ',');
    $row['price_payment'] = number_format( $row['price_payment'], 0, '.', ',');
    $row['feeship'] = number_format( $row['feeship'], 0, '.', ',');

    $xtpl->assign('DATA', $row);

    $xtpl->assign('order_id', $row['order_id'] . "_" . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    $xtpl->assign('link_view', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=orview_shareholder&order_id=" . $row['order_id']);

    $xtpl->parse('main.data.row');
    ++$count;
}

$xtpl->assign('URL_EXPORT', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=export");
$xtpl->assign('PAGES', nv_generate_page($base_url, $num_items, $per_page, $page));
$xtpl->parse('main.data');

foreach ($transaction_status as $key => $lang_status) {
    $xtpl->assign('TRAN_STATUS', array( 'key' => $key, 'title' => $lang_status, 'selected' => (isset($search['order_payment']) and $key == $search['order_payment']) ? 'selected="selected"' : '' ));
    $xtpl->parse('main.transaction_status');
}

if (! empty($search['date_from'])) {
    $search['date_from'] = nv_date('d/m/Y', $search['date_from']);
}

if (! empty($search['date_to'])) {
    $search['date_to'] = nv_date('d/m/Y', $search['date_to']);
}

$order_info['sum_price'] = number_format( $order_info['sum_price'], 0, '.', ',');
$xtpl->assign('ORDER_INFO', $order_info);
$xtpl->assign('CHECKSESS', md5(session_id()));
$xtpl->assign('SEARCH', $search);


$xtpl->parse('main');
$contents = $xtpl->text('main');


include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
