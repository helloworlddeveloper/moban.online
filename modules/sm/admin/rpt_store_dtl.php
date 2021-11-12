<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
{
    die( 'Stop!!!' );
}

$wid = $nv_Request->get_int('wid', 'get', 0);
$array_search = array();
$array_warehouse = array();

$page_title = $lang_module['report_store_detail'];
$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);

$array_search = array();
$where = '';
$array_search['date_from'] = $nv_Request->get_title('from', 'get', '');
$array_search['date_to'] = $nv_Request->get_title('to', 'get', '');
$array_search['product'] = $nv_Request->get_int('product', 'get', 0);
$array_search['depotid'] = $nv_Request->get_int('depotid', 'get', 0);
$array_search['agencyid'] = $nv_Request->get_int('agencyid', 'get', 0);
$agencysl = "";
if ($array_search['product'] > 0 ) {
    $where .= ' AND t1.productid = ' . $array_search['product'];
}
if ($array_search['depotid'] > 0 ) {
    $where .= ' AND t1.depotid = ' . $array_search['depotid'];
} else if ($array_search['depotid'] == -1 ) {
    $agencysl = "selected";
    if ($array_search['agencyid'] > 0) {
        $where .= ' AND t1.customerid = ' . $array_search['agencyid'];
    } else {
        $where .= ' AND t1.depotid = 0';
    }
} else {
    //$where .= ' AND t1.depotid > 0';
}
//die($where);
if (! empty($array_search['date_from'])) {
    if (! empty($array_search['date_from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['date_from'], $m)) {
        $date_from = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $date_from = NV_CURRENTTIME;
    }
    $where .= ' AND t1.addtime >= ' . $date_from;
}

if (! empty($array_search['date_to'])) {
    if (! empty($array_search['date_to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['date_to'], $m)) {
        $date_to = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    } else {
        $date_to = NV_CURRENTTIME;
    }
    $where .= ' AND t1.addtime <= ' . $date_to;
}

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG. '_' . $module_data . '_warehouse_order t1')
    ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_product t2 ON t1.productid=t2.id  LEFT OUTER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_orders t3 ON t1.orderid=t3.order_id LEFT OUTER JOIN nv4_users t4 ON t1.customerid=t4.userid LEFT OUTER JOIN nv4_vi_sm_depot t5 ON t1.depotid=t5.id WHERE 1=1 ' . $where);

$num_items = $db->query($db->sql())->fetchColumn();
$db->select('t1.*, t2.title AS title_product, t3.order_code, concat(t4.first_name, " ", t4.last_name, " (", t4.username, ")") as partner, t5.title as depotname')->order('t2.id, t1.addtime DESC');
//die($db->sql());
$nv_Request->set_Session( $module_data . '_warehouse_logs', $db->sql());

$db->limit($per_page)->offset(($page - 1) * $per_page);
$result = $db->query( $db->sql() );


$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('SEARCH', $array_search);
$xtpl->assign('num_items', $num_items);
$xtpl->assign('agencysl', $agencysl);


foreach ($array_product as $product ){
    $product['sl'] = ( $array_search['product'] == $product['id'])? ' selected=selected' : '';
    $xtpl->assign('PRODUCT', $product);
    $xtpl->parse('main.list.product');
}

// List depot
$array_depot = array();
$sql = 'SELECT id, title, address FROM ' . NV_PREFIXLANG . '_' . $module_data . '_depot WHERE status=1 ORDER BY id';
$result_unit = $db->query($sql);
if ($result_unit->rowCount() > 0) {
    while ($row = $result_unit->fetch()) {
        $row['sl'] = ( $array_search['depotid'] == $row['id'])? ' selected=selected' : '';
        $xtpl->assign('DEPOT', $row);
        $xtpl->parse('main.list.depot');
    }
}

//he thong ctv, dl
$list_Agency = nvGetListCurrentAgency();
if( !empty($list_Agency )){
    //danh sach agency
    foreach ($list_Agency as $agency){
        $agency['sl'] = (! empty($array_search['agencyid']) && $array_search['agencyid'] == $agency['key']) ? ' selected=selected' : '';
        $xtpl->assign('AGENCY', $agency);
        $xtpl->parse('main.list.agency.loop');
    }
    $xtpl->parse('main.list.agency');
}


$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if (!empty($array_search['keywords'])) {
    $base_url .= '&keywords=' . $array_search['keywords'];
}
$array_sum = array();
$array_sum['price_total'] = $array_sum['quantity_change'] = 0;
while ($view = $result->fetch()) {
    $view['storename'] = $view['depotid'] != 0 ? $view['depotname'] : "Kho của " . $view['partner'];
    $view['quantity_change'] = ( $view['quantity_out'] > 0 )? '-' . $view['quantity_out'] : $view['quantity_in'];
    $view['price_change'] = ( $view['price_out'] > 0 )? '-' . $view['price_out'] : $view['price_in'];
    $array_sum['price_total'] += $view['price_change'];
    $array_sum['quantity_change'] += $view['quantity_change'];

    $view['price_change'] = number_format($view['price_change'], 0, '.', ',');
    $view['addtime'] = date('d/m/Y H:i', $view['addtime'] );

    $xtpl->assign('VIEW', $view);
    $xtpl->parse('main.list.loop');
}

foreach ($search_months as $month) {
    $xtpl->assign('SEARCH_MONTH', array( 'key' => $month['key'], 'value' => $month['value'], 'title' => $month['title'], 'selected' => ( !empty( $search['search_month'] ) && $month['key'] == $search['search_month']) ? 'selected="selected"' : '' ));
    $xtpl->parse('main.list.search_month');
}
$array_sum['price_total'] = number_format( $array_sum['price_total'], 0, '.', ',');
$xtpl->assign('SUM', $array_sum);

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.list.generate_page');
}
$xtpl->parse('main.list');


$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
