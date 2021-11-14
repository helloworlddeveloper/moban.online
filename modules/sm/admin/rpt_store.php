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

$array_search = array();

$page_title = $lang_module['report_store'];
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
$where_time = '';
if (! empty($array_search['date_from'])) {
    if (! empty($array_search['date_from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['date_from'], $m)) {
        $date_from = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $date_from = NV_CURRENTTIME;
    }
    $where_time .= ' AND addtime >= ' . $date_from;
}

if (! empty($array_search['date_to'])) {
    if (! empty($array_search['date_to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['date_to'], $m)) {
        $date_to = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    } else {
        $date_to = NV_CURRENTTIME;
    }
    $where_time .= ' AND addtime <= ' . $date_to;
}

$sql = 'SELECT COUNT(*) ';
$sql .= 'FROM (SELECT customerid, depotid, productid, sum(quantity_befor) as quan_begin, sum(quantity_in) as quan_in, ';
$sql .= '				sum(quantity_out) as quan_out, sum(quantity_after) as quan_end ';
$sql .= '		FROM nv4_vi_sm_warehouse_order ';
$sql .= $where_time;
$sql .= '		GROUP BY customerid, depotid, productid) as t1 ';
$sql .= '	INNER JOIN nv4_vi_sm_product t2 ON t1.productid=t2.id ';
$sql .= '	LEFT OUTER JOIN nv4_users t3 ON t1.customerid=t3.userid ';
$sql .= '	LEFT OUTER JOIN nv4_vi_sm_depot t4 ON t1.depotid=t4.id ';
$sql .= 'WHERE 1=1 '. $where;
$num_items = $db->query($sql)->fetchColumn();

$sql = 'SELECT t1.*, t2.title AS title_product, concat(t3.first_name, " ", t3.last_name, " (", t3.username, ")") as partner, t4.title as depotname ';
$sql .= 'FROM (SELECT customerid, depotid, productid, sum(quantity_befor) as quan_begin, sum(quantity_in) as quan_in, ';
$sql .= '				sum(quantity_out) as quan_out, sum(quantity_after) as quan_end ';
$sql .= '		FROM nv4_vi_sm_warehouse_order ';
$sql .= $where_time;
$sql .= '		GROUP BY customerid, depotid, productid) as t1 ';
$sql .= '	INNER JOIN nv4_vi_sm_product t2 ON t1.productid=t2.id ';
$sql .= '	LEFT OUTER JOIN nv4_users t3 ON t1.customerid=t3.userid ';
$sql .= '	LEFT OUTER JOIN nv4_vi_sm_depot t4 ON t1.depotid=t4.id ';
$sql .= 'WHERE 1=1 '. $where . " LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;

//die($sql);
$nv_Request->set_Session( $module_data . '_warehouse_logs', $sql);
$result = $db->query( $sql );

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
$array_sum['sum_begin'] = $array_sum['sum_in'] = $array_sum['sum_out'] = $array_sum['sum_end'] = 0;
while ($view = $result->fetch()) {
    $view['storename'] = $view['depotid'] != 0 ? $view['depotname'] : "Kho của " . ($view['partner'] != '' ? $view['partner'] : "GioShoppe7");
    $array_sum['sum_begin'] += $view['quan_begin'];
    $array_sum['sum_in'] += $view['quan_in'];
    $array_sum['sum_out'] += $view['quan_out'];
    $array_sum['sum_end'] += $view['quan_end'];

    $view['quan_begin'] = number_format( $view['quan_begin'], 0, '.', ',');
    $view['quan_in'] = number_format( $view['quan_in'], 0, '.', ',');
    $view['quan_out'] = number_format( $view['quan_out'], 0, '.', ',');
    $view['quan_end'] = number_format( $view['quan_end'], 0, '.', ',');


    $xtpl->assign('VIEW', $view);
    $xtpl->parse('main.list.loop');
}

foreach ($search_months as $month) {
    $xtpl->assign('SEARCH_MONTH', array( 'key' => $month['key'], 'value' => $month['value'], 'title' => $month['title'], 'selected' => ( !empty( $search['search_month'] ) && $month['key'] == $search['search_month']) ? 'selected="selected"' : '' ));
    $xtpl->parse('main.list.search_month');
}
$array_sum['sum_begin'] = number_format( $array_sum['sum_begin'], 0, '.', ',');
$array_sum['sum_in'] = number_format( $array_sum['sum_in'], 0, '.', ',');
$array_sum['sum_out'] = number_format( $array_sum['sum_out'], 0, '.', ',');
$array_sum['sum_end'] = number_format( $array_sum['sum_end'], 0, '.', ',');
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
