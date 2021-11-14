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

    if (! empty($search['order_code'])) {
        $where .= ' AND t1.order_code like "%' . $search['order_code'] . '%"';
    }
    if (! empty($search['date_from'])) {
        if (! empty($search['date_from']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_from'], $m)) {
            $search['date_from'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
        } else {
            $search['date_from' ] = NV_CURRENTTIME;
        }
        $where .= ' AND t1.order_time >= ' . $search['date_from'] . '';
    }

    if (! empty($search['date_to'])) {
        if (! empty($search['date_to']) and preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $search['date_to'], $m)) {
            $search['date_to'] = mktime(23, 59, 59, $m[2], $m[1], $m[3]);
        } else {
            $search['date_to' ] = NV_CURRENTTIME;
        }
        $where .= ' AND t1.order_time <= ' . $search['date_to'] . '';
    }
    if ($search['order_payment'] != '') {
        $where .= ' AND t1.status  = ' . $search['order_payment'];
    }
}

$transaction_status = array(
    '4' => $lang_module['history_payment_yes'],
    '5' => $lang_module['history_order_ships'],
    '0' => $lang_module['history_payment_no']);

$xtpl = new XTemplate('doanhso.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$per_page = 20;
$page = $nv_Request->get_int('page', 'get', 1);
$userid = $nv_Request->get_int('userid', 'get', 1);
$checkss = $nv_Request->get_string('checkss', 'get', '');

//lay userid theo user cap duoi
if ($userid > 0 and $checkss == md5($userid . $global_config['sitekey'] . session_id())) {
    $sql = 'SELECT t1.code, t1.possitonid, t1.agencyid, t1.salary_day, t1.benefit, t1.subcatid, t1.datatext, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . NV_TABLE_AFFILIATE . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t2.userid=' . $userid . ' ORDER BY t1.sort ASC';
    $user_data_affiliate = $db->query($sql)->fetch();
}

$base_url = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;
$count = 0;
$order_info = array( 'num_items' => 0, 'sum_price_in' => 0, 'sum_price_out' => 0, 'sum_unit' => '' );

$where_customer = '';
//lay danh sach cac tk cap duoi trong he thong

$list_customerid = nvGetUseridInParent($user_data_affiliate['userid'], $user_data_affiliate['subcatid'], true, true );
if ( !empty( $list_customerid )){
    $where_customer .= '(t1.user_id IN (' . implode(',', $list_customerid ) . ')';
}

//lay danh sach cac khach hang le do minh gioi thieu
$list_customerid = nvGetUseridCustomer( $user_data_affiliate['userid'] );
if ( !empty( $list_customerid )){
    $where_customer .= ' OR ( t1.customer_id IN (' . implode(',', $list_customerid ) . ') AND t1.chossentype=3)';
}

$where_customer .= ')';
// Fetch Limit

$db->sqlreset()->select('COUNT(*)')
    ->from($table_name . ' AS t1')
    ->join('INNER JOIN ' . NV_USERS_GLOBALTABLE . " AS t2 ON t1.user_id=t2.userid" )
    ->where( $where_customer . $where);

$num_items = $db->query($db->sql())->fetchColumn();
$order_info['num_items'] = $num_items;

$db->select('t1.*, t2.username,t2.last_name,t2.first_name')->where($where_customer . $where)->order('t1.order_id DESC')->limit($per_page)->offset(($page - 1) * $per_page);

$query = $db->query($db->sql());
while ($row = $query->fetch()) {

    $acno = 0;
    $price = $row['order_total'];
    if ( $row['chossentype'] != 1 or ( $row['chossentype'] == 1 && $row['customer_id'] != $user_info['userid'] )) {
        if( $row['ordertype'] == 1 || $row['ordertype'] == 2 ){
            $order_info['sum_price_in'] = $order_info['sum_price_in'] + $price;
        }else{
            $order_info['sum_price_out'] = $order_info['sum_price_out'] + $price;
        }
    }else{
        $order_info['sum_price_out'] = $order_info['sum_price_out'] + $price;
    }
    
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

    $ordertype = '';
    if( $row['ordertype'] == 0 ){
       $ordertype = '<b class="red"> ' . $lang_module['return_order'] . '</b>';
    }elseif( $row['ordertype'] == 2 ){
        $ordertype = '<b class="blue"> ' . $lang_module['ordertype_2'] . '</b>';
    }

    //neu tien da thanh toan = tong so tien cua don hang thi
    if( $row['price_payment'] == $row['order_total'] ){
      //  $row['status_payment'] = $lang_module['history_payment_yes'];
    }
    $row['fullname'] = nv_show_name_user( $row['first_name'], $row['last_name'], $row['username'] );
    $row['link_user'] = NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=edit&userid=" . $row['user_id'];
    $row['order_time'] = nv_date("H:i d/m/y", $row['order_time']);
    $row['order_total'] = number_format( $price, 0, '.', ',');
    $xtpl->assign('order_id', $row['order_id'] . "_" . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    if( $row['ordertype'] == 1 || $row['ordertype'] == 2 ){
        $xtpl->assign('link_view', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_view&order_id=" . $row['order_id'] . '&checkss=' . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    }
    else{
        $xtpl->assign('link_view', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=return-or-view&order_id=" . $row['order_id'] . '&checkss=' . md5($row['order_id'] . $global_config['sitekey'] . session_id()));
    }

    $row['shipcode'] = ( $row['shipcode'] == 0 )? '' : '<b>Ship COD</b>';    $row['ordertype'] = ( $row['ordertype'] == 1 )? '' : $ordertype;
    $xtpl->assign('DATA', $row);

    $xtpl->parse('main.data.row');
    ++$count;
}

$xtpl->assign('URL_CHECK_PAYMENT', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=checkpayment");
$xtpl->assign('URL_DEL', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=or_del");
$xtpl->assign('URL_DEL_BACK', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
$xtpl->assign('PAGES', nv_generate_page($base_url, $num_items, $per_page, $page));
$xtpl->parse('main.data');

foreach ($transaction_status as $key => $lang_status) {
    $xtpl->assign('TRAN_STATUS', array( 'key' => $key, 'title' => $lang_status, 'selected' => ( !empty( $search['order_payment'] ) && $key == $search['order_payment']) ? 'selected="selected"' : '' ));
    $xtpl->parse('main.transaction_status');
}

if (! empty($search['date_from'])) {
    $search['date_from'] = nv_date('d/m/Y', $search['date_from']);
}

if (! empty($search['date_to'])) {
    $search['date_to'] = nv_date('d/m/Y', $search['date_to']);
}
$order_info['total_sotienlai'] = number_format( $order_info['sum_price_in'] - $order_info['sum_price_out'], 0, '.', ',');
$order_info['sum_price_in'] = number_format( $order_info['sum_price_in'], 0, '.', ',');
$order_info['sum_price_out'] = number_format( $order_info['sum_price_out'], 0, '.', ',');

$xtpl->assign('ORDER_INFO', $order_info);
$xtpl->assign('CHECKSESS', md5(session_id()));
$xtpl->assign('SEARCH', $search);

$xtpl->parse('main');
$contents = $xtpl->text('main');


include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
