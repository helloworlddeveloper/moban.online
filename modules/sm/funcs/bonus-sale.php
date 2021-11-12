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
$listorderid = $nv_Request->get_title('BONUS_SALEORDERIDS', 'post,get', '');
$listreturnid = $nv_Request->get_title('BONUS_SALERETURNIDS', 'post,get', '');

$xtpl = new XTemplate('bonus-sale.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);
$base_url = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$count = 0;
$order_info = array( 'num_items' => 0, 'sum_price_in' => 0, 'sum_price_out' => 0, 'sum_debt_in' => 0, 'sum_debt_out' => 0, 'sum_unit' => '',
    'total_bonus_sale' => 0, 'total_bonus_cumulative' => 0, 'total_bonus_cumulative_group' => 0, 'total_bonus_all' => 0);


$listid = $listorderid;
if (!empty($listreturnid))
    $listid = $listid . "," . $listreturnid;

if (!empty($listid)) $where = 'order_id in ('. $listid .')';

$userid = $user_info['userid'];
// Fetch Limit
$db->sqlreset()->select('COUNT(*)')
    ->from($table_name)
    ->where( $where);

$num_items = $db->query($db->sql())->fetchColumn();
$order_info['num_items'] = $num_items;

$db->select('*')->where($where)->order('status, order_id DESC')->limit($per_page)->offset(($page - 1) * $per_page);
//die($db->sql());
$query = $db->query($db->sql());
$sum_bill_bonus_sales = 0;
while ($row = $query->fetch()) {
    $price = $row['order_total'];
    $payment = $row['price_payment'];
    $discount = $row['saleoff'];
    $debt = $price - $discount - $payment;

    if( $row['ordertype'] == 1 || $row['ordertype'] == 2  ){//nhap cong ty
        $order_info['sum_price_out'] = $order_info['sum_price_out'] + $price;//tong tien nhap hang
        $order_info['sum_debt_out'] = $order_info['sum_debt_out'] + $debt;//tong no can tra
        if ($row['producttype'] == 0) {//tinh thuong doanh so voi don hang
            $sum_bill_bonus_sales = $sum_bill_bonus_sales + $price;
        }
    }else{//tra lai cong ty
        $order_info['sum_price_out'] = $order_info['sum_price_out'] - $price;
        $order_info['sum_debt_out'] = $order_info['sum_debt_out'] - $debt;//tong no can tra
        if ($row['producttype'] == 0) {//tinh thuong doanh so voi don hang
            $sum_bill_bonus_sales = $sum_bill_bonus_sales - $price;
        }
    }

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
    $row['link_user'] = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=edit&userid=" . $row['user_id'];
    $row['order_time'] = nv_date("H:i d/m/y", $row['order_time']);
    $row['order_total'] = number_format( $price, 0, '.', ',');
    $row['saleoff'] = number_format( $discount, 0, '.', ',');
    $row['total_price'] = number_format( ($price - $discount), 0, '.', ',');
    $row['price_payment'] = number_format( $payment, 0, '.', ',');
    $row['order_debt'] = number_format( ($price - $discount - $payment), 0, '.', ',');
    $row['order_debt_style'] = ($price - $discount - $payment) != 0 ? 'style="color:red; font-weight:bold"' : '';
    $row['shipcode'] = ( $row['shipcode'] == 0 )? '' : '<b>Ship COD</b>';
    if( $row['ordertype']  == 0 ){
        $row['ordertype_title'] = '<b class="red"> ' . $lang_module['return_order'] . '</b>';
    }elseif( $row['ordertype'] == 2 ){
        $row['ordertype_title'] = '<b class="blue"> ' . $lang_module['ordertype_2'] . '</b>';
    }

    $xtpl->assign('DATA', $row);

    $xtpl->assign('order_id', $row['order_id'] . "_" . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    if( in_array( $row['ordertype'], array(1,2))){
        $xtpl->assign('link_view', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_view&order_id=" . $row['order_id'] . '&checkss=' . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    }
    else{
        $xtpl->assign('link_view', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=return-or-view&order_id=" . $row['order_id'] . '&checkss=' . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    }
    if ($row['status'] < 1 || $row['ordertype'] == 2 ) {
        $xtpl->assign('link_del', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_del&order_id=" . $row['order_id'] . "&checkss=" . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
        $xtpl->parse('main.data.row.delete');
        $xtpl->assign('DIS', '');
    } else {
        $xtpl->assign('DIS', 'disabled="disabled"');
    }

    $xtpl->parse('main.data.row');
    ++$count;
}
//thong ke don hang
$order_info['sum_price_out'] = number_format( $order_info['sum_price_out'], 0, '.', ',');
$total_bonus_sale = nv_get_total_bonus_sales($sum_bill_bonus_sales);
$order_info['sum_bill_bonus_sales'] = number_format( $sum_bill_bonus_sales, 0, '.', ',');
$order_info['total_bonus_sale'] = number_format( $total_bonus_sale, 0, '.', ',');

$xtpl->assign('sql_show', base64_encode($db->sql()));
$xtpl->assign('URL_CHECK_PAYMENT', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checkpayment");
$xtpl->assign('URL_DEL', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_del");
$xtpl->assign('URL_DEL_BACK', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('PAGES', nv_generate_page($base_url, $num_items, $per_page, $page));
$xtpl->assign('ORDER_INFO', $order_info);
$xtpl->parse('main.data');

$xtpl->assign('ORDER_INFO', $order_info);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
