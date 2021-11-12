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
$array_warehouse = array();
//$per_page = 50;
//$page = $nv_Request->get_int('page', 'post,get', 1);
$customer_id = $nv_Request->get_int('id', 'post,get', 0);
$user_id = $nv_Request->get_int('uid', 'post,get', 0);

if( $customer_id < 0 ){
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . 'customer');
    die();
}
else{
    if (!empty($customer_id) && $customer_id > 0) {
        $sql = 'SELECT t1.code, t1.fullname, t1.phone, t1.address, t1.email, t2.purchase_points 
            FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer AS t1
                INNER JOIN  ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.refer_userid = t2.userid
            WHERE t1.customer_id=' . $customer_id;
    } else {
        $sql = 'SELECT t2.username, concat(t2.first_name, " ", t2.last_name) as fullname, t2.phone, t2.address, t2.email, t2.purchase_points 
            FROM ' . NV_USERS_GLOBALTABLE . ' AS t2
            WHERE t2.userid=' . $user_id;
    }

    $user_data_agencey = $db->query($sql)->fetch();
    $user_data_agencey['gift_status'] = "";
//    if (empty($user_data_agencey['bonus_gifts']))
//        $user_data_agencey['gift_status'] = $user_data_agencey['isgift_ready'] == 2 ? 'Đã nhắn tin địa chỉ nhận hàng' : "chưa đủ điều kiện đổi quà";
}

$array_search = array();
$where = '';
$date_from_default = NV_CURRENTTIME - (30 *86400);
$array_search['date_from'] = $nv_Request->get_title('from', 'post,get', '');//date('d/m/Y', $date_from_default ));
$array_search['date_to'] = $nv_Request->get_title('to', 'post,get', '');//date('d/m/Y', NV_CURRENTTIME ));
$array_search['product'] = $nv_Request->get_title('product', 'post,get', '');
$array_search['act'] = $nv_Request->get_title('act', 'post,get', 'gift');

if (! empty($array_search['date_from'])) {
    if (! empty($array_search['date_from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['date_from'], $m)) {
        $date_from = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $date_from = NV_CURRENTTIME;
    }
    $where .= $array_search['act'] == 'barcode' ?
                    ' AND t1.used_date >= ' . $date_from :
                    ' AND (t1.created_date >= ' . $date_from . ' or t1.updated_date >= ' . $date_from . ')';
}

if (! empty($array_search['date_to'])) {
    if (! empty($array_search['date_to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['date_to'], $m)) {
        $date_to = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
    } else {
        $date_to = NV_CURRENTTIME;
    }
    $where .= $array_search['act'] == 'barcode' ?
        ' AND t1.used_date <= ' . $date_to :
        ' AND (t1.created_date <= ' . $date_to . ' or t1.updated_date <= ' . $date_to . ')';
}
//echo $where;die();
$active_detail = $active_all = '';

if( $array_search['act'] == 'barcode'){
    $active_detail = ' active';
    if (!empty($customer_id) && $customer_id > 0) {
        $sql = 'SELECT t1.*, t3.title as gift
            FROM ' . NV_PREFIXLANG. '_' . $module_data . '_barcode t1
                INNER JOIN ' . NV_PREFIXLANG. '_' . $module_data . '_customer t2 ON t1.customerid = t2.refer_userid
                LEFT OUTER JOIN ' . NV_PREFIXLANG. '_' . $module_data . '_giftcode t3 ON t1.bonus_gift=t3.code 
            WHERE t2.customer_id = ' . $customer_id . $where . '
            ORDER BY t1.used_date DESC';// .  $per_page . ' OFFSET ' . (($page - 1) * $per_page);
    } else {
        $sql = 'SELECT t1.*, t3.title as gift
            FROM ' . NV_PREFIXLANG. '_' . $module_data . '_barcode t1
                INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.customerid = t2.userid
                LEFT OUTER JOIN ' . NV_PREFIXLANG. '_' . $module_data . '_giftcode t3 ON t1.bonus_gift=t3.code 
            WHERE t2.userid = ' . $user_id . $where . '
            ORDER BY t1.used_date DESC';// .  $per_page . ' OFFSET ' . (($page - 1) * $per_page);
    }

    //echo $sql;die();
    $result = $db->query($sql);
}
else{
    $active_all = ' active';
    if (!empty($array_search['product'])) {
        $where .= ' AND t1.gift = ' . $db->quote($array_search['product']);
    }
    if (!empty($customer_id) && $customer_id > 0) {
        $sql = 'SELECT t1.*, t3.first_name, t3.last_name, t3.username, t3.phone
            FROM ' . NV_PREFIXLANG. '_' . $module_data . '_customer_gifts t1
                INNER JOIN ' . NV_PREFIXLANG. '_' . $module_data . '_customer t2 ON t1.userid = t2.refer_userid
                LEFT OUTER JOIN ' . NV_USERS_GLOBALTABLE . ' t3 ON t3.userid=t1.agencyid 
            WHERE t2.customer_id = ' . $customer_id . $where;
    } else {
        $sql = 'SELECT t1.*, t3.first_name, t3.last_name, t3.username, t3.phone
            FROM ' . NV_PREFIXLANG. '_' . $module_data . '_customer_gifts t1
                INNER JOIN ' . NV_USERS_GLOBALTABLE . ' t2 ON t1.userid = t2.userid
                LEFT OUTER JOIN ' . NV_USERS_GLOBALTABLE . ' t3 ON t3.userid=t1.agencyid 
            WHERE t2.userid = ' . $user_id . $where;
    }

    $result = $db->query( $sql );
}

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

$xtpl->assign('active_all', $active_all);
$xtpl->assign('active_detail', $active_detail);
$xtpl->assign('LINK_VIEW', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&id=' . $customer_id . '&uid=' . $user_id);
$xtpl->assign('SEARCH', $array_search);
$xtpl->assign('DATA_USER', $user_data_agencey);
$xtpl->assign('customer_id', $customer_id);

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if (!empty($array_search['keywords'])) {
    $base_url .= '&keywords=' . $array_search['keywords'];
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_giftcode';
$array_gift = $db->query( $sql );

while ($product = $array_gift->fetch()) {
    $product['sl'] = ( $array_search['product'] == $product['code'])? ' selected=selected' : '';
    $xtpl->assign('PRODUCT', $product);
    $xtpl->parse('main.product');
}

if( $array_search['act'] == 'barcode'){
    $num_barcodes = 0;
    $stt = 0;
    while ($view = $result->fetch()) {
        $num_barcodes++;
        $view['STT'] = ++$stt;
        $view['used_date'] = date('d/m/Y H:i', $view['used_date'] );

        $xtpl->assign('CODE', $view);
        $xtpl->parse('main.barcode.loop');
    }

    $xtpl->assign('NUM_BARCODE', $num_barcodes);
    $xtpl->parse('main.barcode');
}
else{
    $num_gifts = 0;
    $stt = 0;

    while ($view = $result->fetch()) {
        $num_gifts += $view['quantity'];
        $view['STT'] = ++$stt;
        $view['created_date'] = date('d/m/Y H:i', $view['created_date'] );
        $view['updated_date'] = empty($view['updated_date']) ? '<b style="color: red">chưa nhận quà</b>' : date('d/m/Y H:i', $view['updated_date'] );
        $view['agency'] = empty($view['first_name']) ? '' : ($view['first_name'] . ' ' . $view['last_name'] . ' (' . $view['username'] . ')');

        $xtpl->assign('GIFT', $view);
        $xtpl->parse('main.or_gift.loop');
    }
    $xtpl->assign('NUM_GIFT', $num_gifts);
    $xtpl->parse('main.or_gift');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['customer_gifts'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

