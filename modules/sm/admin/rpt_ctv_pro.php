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

$page_title = $lang_module['report_ctv_pro'];
$table_name = NV_PREFIXLANG . "_" . $module_data . "_orders";

$checkss = $nv_Request->get_string('checkss', 'get', '');
$where = '';
$search = array();
if ($checkss == md5(session_id())) {

    $search['order_code'] = $nv_Request->get_title('order_code', 'get', '');
    $search['date_from'] = $nv_Request->get_title('from', 'get', '');
    $search['date_to'] = $nv_Request->get_title('to', 'get', '');
    $search['order_email'] = $nv_Request->get_title('order_email', 'get', '');
    $search['order_phone'] = $nv_Request->get_title('order_phone', 'get', '');
    $search['order_name'] = $nv_Request->get_title('order_name', 'get', '');
    $search['order_payment'] = $nv_Request->get_title('order_payment', 'get', '');
    $search['agencyid'] = $nv_Request->get_int('agencyid', 'get', 0);
    $search['producttype'] = $nv_Request->get_int('producttype', 'get', 0);

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
    if (! empty($search['order_phone'])) {
        $where .= ' AND order_phone like "%' . $search['order_phone'] . '%"';
    }
    if (! empty($search['order_name'])) {
        $where .= ' AND order_name like "%' . $search['order_name'] . '%"';
    }
    if ($search['order_payment'] != '-2') {
        $where .= ' AND status  = ' . $search['order_payment'];
    }

    if (! empty($search['producttype']) && $search['producttype'] != 0) {
        $producttype = $search['producttype'] - 1;
        $where .= ' AND producttype  = ' . $producttype;
    }

    if (! empty($search['agencyid']) && $search['agencyid'] != 0) {
        $where .= ' AND (customer_id  = ' . $search['agencyid'] . ' or user_id = ' . $search['agencyid'] . ')';
    }
}

$transaction_status = array(
    '2' => $lang_module['history_payment_check'],
    '4' => $lang_module['history_payment_yes'],
    '5' => $lang_module['history_order_ships'],
    '0' => $lang_module['history_payment_no']);

$search_months = array(
    array( 'key' => 1, 'value' => 31, 'title' => $lang_module['search_month_1']),
    array( 'key' => 2, 'value' => 29, 'title' => $lang_module['search_month_2']),
    array( 'key' => 3, 'value' => 31, 'title' => $lang_module['search_month_3']),
    array( 'key' => 4, 'value' => 30, 'title' => $lang_module['search_month_4']),
    array( 'key' => 5, 'value' => 31, 'title' => $lang_module['search_month_5']),
    array( 'key' => 6, 'value' => 30, 'title' => $lang_module['search_month_6']),
    array( 'key' => 7, 'value' => 31, 'title' => $lang_module['search_month_7']),
    array( 'key' => 8, 'value' => 31, 'title' => $lang_module['search_month_8']),
    array( 'key' => 9, 'value' => 30, 'title' => $lang_module['search_month_9']),
    array( 'key' => 10, 'value' => 31, 'title' => $lang_module['search_month_10']),
    array( 'key' => 11, 'value' => 30, 'title' => $lang_module['search_month_11']),
    array( 'key' => 12, 'value' => 31, 'title' => $lang_module['search_month_12'])
);

$xtpl = new XTemplate("rpt_ctv_pro.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;

$sql = 'SELECT t3.*, t5.title AS title_product, t5.code, concat(t4.first_name, " ", t4.last_name) as partner, t4.username ';
$sql .= 'FROM (SELECT t1.user_id, t2.proid, sum(t2.num) as product, t2.num*t2.price as total ';
$sql .= '		FROM nv4_vi_sm_orders t1 ';
$sql .= '			INNER JOIN nv4_vi_sm_orders_id t2 ON t1.order_id = t2.order_id ';
$sql .= '		WHERE t1.user_id > 0 ';
$sql .= $where;
$sql .= '		GROUP BY t1.user_id, t2.proid) as t3 ';
$sql .= '	LEFT OUTER JOIN nv4_users t4 ON t3.user_id=t4.userid ';
$sql .= '	INNER JOIN nv4_vi_sm_product t5 ON t3.proid=t5.id ';

//die($db->sql());
$query = $db->query($sql);
$array_sum = array();
$array_sum['sum_product'] = $array_sum['sum_total'] = 0;
$num_items = 0;
while ($row = $query->fetch()) {
    $num_items++;

    $array_sum['sum_product'] = $array_sum['sum_product'] + $row['product'];
    $array_sum['sum_total'] = $array_sum['sum_total'] + $row['total'];
    $price = $row['product'] != 0 ? $row['total']/$row['product'] : 0;
    $row['price'] = number_format( $price, 0, '.', ',');
    $row['product'] = number_format( $row['product'], 0, '.', ',');
    $row['total'] = number_format( $row['total'], 0, '.', ',');

    $xtpl->assign('DATA', $row);
    $xtpl->parse('main.data.row');
}

$array_sum['num_items'] = number_format( $num_items, 0, '.', ',');
$array_sum['sum_product'] = number_format( $array_sum['sum_product'], 0, '.', ',');
$array_sum['sum_total'] = number_format( $array_sum['sum_total'], 0, '.', ',');

$xtpl->assign('SUM', $array_sum);
$xtpl->assign('sql_show', base64_encode($db->sql()));
$xtpl->assign('PAGES', nv_generate_page($base_url, $num_items, $per_page, $page));
$xtpl->parse('main.data');

foreach ($transaction_status as $key => $lang_status) {

    $xtpl->assign('TRAN_STATUS', array( 'key' => $key, 'title' => $lang_status, 'selected' => (isset($search['order_payment']) and $key == $search['order_payment']) ? 'selected="selected"' : '' ));
    $xtpl->parse('main.transaction_status');
}

foreach ($search_months as $month) {
    $xtpl->assign('SEARCH_MONTH', array( 'key' => $month['key'], 'value' => $month['value'], 'title' => $month['title'], 'selected' => ( !empty( $search['search_month'] ) && $month['key'] == $search['search_month']) ? 'selected="selected"' : '' ));
    $xtpl->parse('main.search_month');
}
foreach ( $array_select_producttype as $key => $producttype ){
    $sl = (! empty($search['producttype']) && $search['producttype'] == $key) ? ' selected=selected' : '';
    $xtpl->assign('PRODUCTTYPE', array('key' => $key, 'value' => $producttype, 'sl' => $sl));
    $xtpl->parse('main.producttype');
}

//he thong ctv, dl
$list_Agency = nvGetListCurrentAgency();
if( !empty($list_Agency )){
    //danh sach agency
    foreach ($list_Agency as $agency){
        $agency['sl'] = (! empty($search['agencyid']) && $search['agencyid'] == $agency['key']) ? ' selected=selected' : '';
        $xtpl->assign('AGENCY', $agency);
        $xtpl->parse('main.agency.loop');
    }
    $xtpl->parse('main.agency');
}

if (! empty($search['date_from'])) {
    $search['date_from'] = nv_date('d/m/Y', $search['date_from']);
}

if (! empty($search['date_to'])) {
    $search['date_to'] = nv_date('d/m/Y', $search['date_to']);
}

$xtpl->assign('CHECKSESS', md5(session_id()));
$xtpl->assign('SEARCH', $search);


$xtpl->parse('main');
$contents = $xtpl->text('main');


include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
