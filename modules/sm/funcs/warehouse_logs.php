<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 04/18/2017 09:47
 */

if( !defined( 'NV_IS_MOD_SM' ) )
{
    die( 'Stop!!!' );
}

$wid = $nv_Request->get_int('wid', 'get', 0);
$array_search = array();
$array_warehouse = array();

$page_title = $lang_module['warehouse_logs'];
$per_page = 50;
$page = $nv_Request->get_int('page', 'get', 1);
$userid = $nv_Request->get_int('userid', 'get', 0);
if( $userid == 0 ){
    $userid = $user_info['userid'];
    $user_data_agencey = $user_data_affiliate;
}
else{
    $checkss = $nv_Request->get_string('checkss', 'get', '');

    if ( $checkss != md5($userid . $global_config['sitekey'] . session_id())) {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
    $sql = 'SELECT t1.code, t1.possitonid, t1.agencyid, t1.salary_day, t1.benefit, t1.subcatid, t1.datatext, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . NV_TABLE_AFFILIATE . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t2.userid=' . $userid;

    $user_data_agencey = $db->query($sql)->fetch();
    //chua phai trong he thong thi khong vao dc chuc nang nay
    if( !isset( $user_data_affiliate ))
    {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users');
        die();
    }
    $user_data_agencey['fullname'] = nv_show_name_user( $user_data_agencey['first_name'], $user_data_agencey['last_name'], $user_data_agencey['username']);
    $user_data_agencey['datatext'] = unserialize( $user_data_agencey['datatext']);
    $user_data_agencey['agencytitle'] = ( $user_data_agencey['agencyid']> 0 )? $array_agency[$user_data_agencey['agencyid']]['title'] : $array_possiton[$user_data_agencey['possitonid']]['title'];
}
$array_search = array();
$where = '';
$date_from_default = NV_CURRENTTIME - (30 *86400);
$array_search['date_from'] = $nv_Request->get_title('from', 'get', date('d/m/Y', $date_from_default ));
$array_search['date_to'] = $nv_Request->get_title('to', 'get', date('d/m/Y', NV_CURRENTTIME ));
$array_search['product'] = $nv_Request->get_int('product', 'get', 0);
$array_search['act'] = $nv_Request->get_title('act', 'get', 'all');
if ($array_search['product'] > 0 ) {
    $where .= ' AND t1.productid = ' . $array_search['product'];
}
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
    $where .= ' AND t1.addtime <= ' . $date_to . '';
}

$active_detail = $active_all = '';

if( $array_search['act'] == 'detail'){
     $active_detail = ' active';
    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG. '_' . $module_data . '_warehouse_order t1')
        ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_product t2 ON t1.productid=t2.id WHERE t1.customerid=' . $userid . $where);

    $num_items = $db->query($db->sql())->fetchColumn();
    $db->select('t1.*, t2.title AS title_product')->order('t2.id, t1.addtime DESC');

    $nv_Request->set_Session( $module_data . '_warehouse_logs', $db->sql());

    $db->limit($per_page)->offset(($page - 1) * $per_page);
    $result = $db->query( $db->sql() );
}else{
    $active_all = ' active';
    $db->sqlreset()
        ->select('t1.*, t2.title AS title_product')
        ->from(NV_PREFIXLANG. '_' . $module_data . '_warehouse_order t1')
        ->order('t2.id, t1.addtime')
        ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_product t2 ON t1.productid=t2.id WHERE t1.customerid=' . $userid . $where);

    $result = $db->query( $db->sql() );
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('NV_UPLOADS_DIR', NV_UPLOADS_DIR);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('active_all', $active_all);
$xtpl->assign('active_detail', $active_detail);
$xtpl->assign('LINK_VIEW', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&userid=' . $userid . '&checkss=' . md5($userid . $global_config['sitekey'] . session_id()) );
$xtpl->assign('SEARCH', $array_search);
$xtpl->assign('DATA_USER', $user_data_agencey);

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if (!empty($array_search['keywords'])) {
    $base_url .= '&keywords=' . $array_search['keywords'];
}

$checkss = md5($userid . $global_config['sitekey'] . session_id());
$xtpl->assign('checkss', $checkss);
$xtpl->assign('userid', $userid);

foreach ($array_product as $product ){
    $product['sl'] = ( $array_search['product'] == $product['id'])? ' selected=selected' : '';
    $xtpl->assign('PRODUCT', $product);
    $xtpl->parse('main.product');
}

if( $array_search['act'] == 'detail'){

    $array_sum = array();
    $array_sum['price_total_in'] = $array_sum['price_total_out'] = $array_sum['quantity_change'] = 0;
    while ($view = $result->fetch()) {

        $array_sum['price_total_in'] += $view['price_in'];
        $array_sum['price_total_out'] += $view['price_out'];
        $array_sum['quantity_total_out'] += $view['quantity_out'];
        $array_sum['quantity_total_in'] += $view['quantity_in'];

        $view['price_in'] = number_format($view['price_in'], 0, '.', ',');
        $view['price_out'] = number_format($view['price_out'], 0, '.', ',');
        $view['addtime'] = date('d/m/Y H:i', $view['addtime'] );

        $xtpl->assign('VIEW', $view);
        $xtpl->parse('main.chitiet.loop');
    }

    $array_sum['price_total_in'] = number_format( $array_sum['price_total_in'], 0, '.', ',');
    $array_sum['price_total_out'] = number_format( $array_sum['price_total_out'], 0, '.', ',');
    $array_sum['quantity_total_out'] = number_format( $array_sum['quantity_total_out'], 0, '.', ',');
    $array_sum['quantity_total_in'] = number_format( $array_sum['quantity_total_in'], 0, '.', ',');
    $xtpl->assign('SUM', $array_sum);

    $generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);
    if (!empty($generate_page)) {
        $xtpl->assign('NV_GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.chitiet.generate_page');
    }
    $xtpl->parse('main.chitiet');
}
else{
    $array_statistic = array();
    while ($view = $result->fetch()) {

        //lay ton dau
        if( !isset( $array_statistic[$view['productid']]['begin'])){
            $array_statistic[$view['productid']]['begin'] = $view['quantity_befor'];
        }

        if( isset( $array_statistic[$view['productid']]['quantity_in'] )){
            $array_statistic[$view['productid']]['quantity_in'] += $view['quantity_in'];
            $array_statistic[$view['productid']]['quantity_out'] += $view['quantity_out'];
        }else{
            $array_statistic[$view['productid']]['quantity_in'] = $view['quantity_in'];
            $array_statistic[$view['productid']]['quantity_out'] = $view['quantity_out'];
        }
    }
    if( !empty( $array_statistic )){
        foreach ( $array_statistic as $productid => $statistic ){
            $statistic['end'] = $statistic['begin'] + $statistic['quantity_in'] - $statistic['quantity_out'];
            $statistic['product_title'] = $array_product[$productid]['title'];
            $xtpl->assign('VIEW', $statistic);
            $xtpl->parse('main.all.loop');
        }
        $xtpl->parse('main.all');
    }
}


$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
