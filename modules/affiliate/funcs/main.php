<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

if (!defined('NV_IS_MOD_AFFILIATE')) die('Stop!!!');

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

if( $nv_Request->isset_request( 'act', 'post' ) )
{
    $act = $nv_Request->get_title( 'act', 'post', '' );
    $select_name = $nv_Request->get_title( 'select_name', 'post', '' );

    if( $act == 'district' )
    {
        $province = $nv_Request->get_int( 'province', 'post', 0 );
        $selected_id = $nv_Request->get_int( 'selected_id', 'post', 0 );
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_district WHERE status=1';
        if( $province > 0 )
            $sql .= ' AND idprovince=' . $province;

        $result = $db->query( $sql );
        $html = '<select class="form-control"  style="width:100%;" name=' . $select_name . '>';
        $html .= '<option value="0"> --- </option>';
        while( $row = $result->fetch() )
        {
            $sl = ( $selected_id == $row['id'] ) ? ' selected=selected' : '';
            $html .= '<option value="' . $row['id'] . '" ' . $sl . '>' . $row['title'] . '</option>';
        }
        $html .= '</select>';
        die( $html );
    }
}

Header( 'Location: ' . NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=maps" );
die();

$search['transaction'] = $endtime = $starttime = 0;
$where = "";
$search['starttime'] = $nv_Request->get_title('starttime', 'post,get', '');
$search['endtime'] = $nv_Request->get_title('endtime', 'post,get', '');
$search['transaction'] = $nv_Request->get_int('transaction', 'post,get', 0);
if ($search['starttime'] != '') {
    unset($m);
    preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $search['starttime'], $m);
    $starttime = mktime(00, 00, 00, $m[2], $m[1], $m[3]);
}
if ($search['endtime'] != '') {
    unset($m);
    preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $search['endtime'], $m);
    $endtime = mktime(23, 59, 00, $m[2], $m[1], $m[3]);
}

$page = $nv_Request->get_int('page', 'get', 1);
$per_page_old = $nv_Request->get_int('per_page', 'cookie', 50);
$per_page = $nv_Request->get_int('per_page', 'get', $per_page_old);

$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=transaction";

if ($starttime != 0 && $endtime != 0) {
    $base_url .= "&starttime=" . $search['starttime'] . "&endtime=" . $search['endtime'];
    $where = " AND createdtime < " . $endtime . " AND createdtime > " . $starttime;
} elseif ($starttime != 0 && $endtime == 0) {
    $base_url .= "&starttime=" . $search['starttime'];
    $where = " AND createdtime > " . $starttime;
} elseif ($endtime != 0 && $starttime == 0) {
    $base_url .= "&endtime=" . $search['endtime'];
    $where = " AND createdtime < " . $endtime;
}


if ($search['transaction'] != 0 ) {
    $base_url .= "&transaction=" . $search['transaction'];
    $where .= " AND transaction_type = " . $search['transaction'];
}

if ($per_page < 1 and $per_page > 500) {
    $per_page = 50;
}
if ($per_page_old != $per_page) {
    $nv_Request->set_Cookie('per_page', $per_page, NV_LIVE_COOKIE_TIME);
}

$i = 5;
$search_per_page = array();
while ($i <= 1000) {
    $search_per_page[] = array(
        "page" => $i,
        "selected" => ($i == $per_page) ? " selected=\"selected\"" : ""
    );
    $i = $i + 5;
}


$sql = "SELECT SQL_CALC_FOUND_ROWS id, createdtime, transaction_type, money, userid, module_name, transaction_data FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE userid= " . $user_info['userid'] . $where;
$order = " ORDER BY createdtime DESC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;


$sql .= $order;

$result = $db->query($sql);
$result_page = $db->query("SELECT FOUND_ROWS()");
$numf = $result_page->fetchColumn();
$all_page = ($numf) ? $numf : 1;
$xuatra = $congvao = 0;
$arr_list_transaction = array();
while (list ($id, $createdtime, $transaction_type, $money_total, $userid, $module_action, $transaction_data ) = $result->fetch(3)) {
    if ($transaction_type == - 1) {
        $xuatra = $money_total + $xuatra;
    } else {
        $congvao = $money_total + $congvao;
    }
    $code = vsprintf("GD" . "%06s", $id);
    $arr_list_transaction[$id] = array(
        'id' => $id, //
        'code' => $code,
        'createdtime' => date("H:i d/m/Y", $createdtime), //
        'status' => ($transaction_type == 1) ? '+' : '-', //
        'money_total' => nv_affiliate_number_format($money_total), //
        'transaction_data' => $transaction_data, //
    ); //
    //
}

$total_price = - $xuatra + $congvao;
$total_price = nv_affiliate_number_format($total_price);


$arr_transaction = array(
    '1' => $lang_module['transaction1'],
    '-1' => $lang_module['transaction2']
);
$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
$number_begin = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;

$contents = nv_theme_affiliate_main($arr_list_transaction, $arr_transaction, $search_per_page, $generate_page, $number_begin, $total_price, $search);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
