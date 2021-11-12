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

$page_title = $lang_module['order_title'];
$table_name = NV_PREFIXLANG . "_" . $module_data . "_orders";

$checkss = $nv_Request->get_string('checkss', 'post,get', '');
$where = '';
$search = array();
if ($checkss != md5(session_id())) {
    Header('Location: ' . NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=order");
    exit();
}
$listorderid = $nv_Request->get_title('BONUS_DIRECTORDERIDS', 'post,get', '');
$listreturnid = $nv_Request->get_title('BONUS_DIRECTRETURNIDS', 'post,get', '');

$xtpl = new XTemplate('bonus-direct.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);
$base_url = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$count = 0;
$order_info = array( 'num_items' => 0, 'sum_price_in' => 0, 'sum_price_out' => 0, 'sum_unit' => '',
    'total_bonus_sale' => 0, 'total_bonus_cumulative' => 0, 'total_bonus_cumulative_group' => 0, 'total_bonus_all' => 0);

$listid = $listorderid;
if (!empty($listreturnid))
    $listid = $listid . "," . $listreturnid;

if (!empty($listid)) $where = 'order_id in ('. $listid .')';

$sql = "SELECT count(*) ";
$sql .= "FROM nv4_vi_sm_orders t1 ";
$sql .= "	INNER JOIN nv4_vi_sm_orders_id t2 ON t1.order_id = t2.order_id ";
$sql .= "	INNER JOIN nv4_vi_sm_product t3 ON t2.proid = t3.id ";
$sql .= "WHERE t1.order_id in (". $listid .")";
$num_items = $db->query($sql)->fetchColumn();

$sql = "SELECT t1.order_id, t1.ordertype, t1.order_code, t1.order_time, t1.order_name, t1.order_phone, t1.order_address, t1.order_total, t1.price_payment, t1.status, ";
$sql .= "	t2.id, t2.proid, t3.code, t3.title, t2.num, t2.price, t2.num*t2.price as total ";
$sql .= "FROM nv4_vi_sm_orders t1 ";
$sql .= "	INNER JOIN nv4_vi_sm_orders_id t2 ON t1.order_id = t2.order_id ";
$sql .= "	INNER JOIN nv4_vi_sm_product t3 ON t2.proid = t3.id ";
$sql .= "WHERE t1.order_id in (". $listid .")";
//die($sql);
$query = $db->query($sql);
while ($row = $query->fetch()) {
    if( $row['ordertype'] == 2 ){
        $transaction_status[0] = $lang_module['history_payment_no_plane'];
        $transaction_status[4] = $lang_module['history_payment_yes_plane'];
    }else{
        $transaction_status[-1] = $lang_module['history_payment_wait'];
        $transaction_status[0] = $lang_module['history_payment_no'];
        $transaction_status[4] = $lang_module['history_payment_yes'];
    }
    
    if ($row['status'] == 4) {
        $row['status_payment'] = $transaction_status[4];
    }  elseif ($row['status'] == 5) {
        $row['status_payment'] = $transaction_status[5];
    } elseif ($row['status'] == 3) {
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
    if( $row['chossentype'] != 3 && $row['customer_id'] == $user_info['userid']){

        if( $row['ordertype'] == 1 ){
            $row['order_name'] = '<strong>' . $lang_module['you_book_product'] . '</strong>';
        }elseif( $row['ordertype'] == 2 ){
            $row['order_name'] = '<strong>' . $lang_module['you_import_plan_product'] . '</strong>';
        }else{

            $row['order_name'] = '<strong>' . $lang_module['you_return_product'] . '</strong>';
        }
    }
    $row['order_time'] = nv_date("H:i d/m/y", $row['order_time']);
    $row['total'] = number_format( $row['total'], 0, '.', ',');
    if( $row['ordertype']  == 0 ){
        $row['ordertype_title'] = '<b class="red"> ' . $lang_module['return_order'] . '</b>';
    }elseif( $row['ordertype'] == 2 ){
        $row['ordertype_title'] = '<b class="blue"> ' . $lang_module['ordertype_2'] . '</b>';
    }
    $xtpl->assign('DATA', $row);

    $xtpl->assign('id', $row['id'] . "_" . md5($row['id'] . $global_config['sitekey'] . session_id()));
    if( in_array( $row['ordertype'], array(1,2))){
        $xtpl->assign('link_view', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_view&order_id=" . $row['order_id'] . '&checkss=' . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    }
    else{
        $xtpl->assign('link_view', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=return-or-view&order_id=" . $row['order_id'] . '&checkss=' . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    }

    $xtpl->parse('main.data.row');
    ++$count;
}

$xtpl->assign('sql_show', base64_encode($db->sql()));
$xtpl->assign('URL_CHECK_PAYMENT', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checkpayment");
$xtpl->assign('URL_DEL', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_del");
$xtpl->assign('URL_DEL_BACK', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('PAGES', nv_generate_page($base_url, $num_items, $per_page, $page));
$xtpl->assign('num_items', $num_items);

$xtpl->parse('main.data');
$xtpl->assign('ORDER_INFO', $order_info);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
