<?php

/**
 * @Project NUKEVIET 4.x
 * @Author MyNukeViet (contact@mynukeviet.com)
 * @Copyright (C) 2016 MyNukeViet. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/12/2016 06:38:53 GMT
 */
if (! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$dateview_search1 = $dateview_search2 = 0;
$where = "";
$dateview1 = $nv_Request->get_string('starttime', 'post,get', '');
$dateview2 = $nv_Request->get_string('endtime', 'post,get', '');
if ($dateview1 != '') {
    unset($m);
    preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $dateview1, $m);
    $dateview_search1 = mktime(00, 00, 00, $m[2], $m[1], $m[3]);
}
if ($dateview2 != '') {
    unset($m);
    preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $dateview2, $m);
    $dateview_search2 = mktime(23, 59, 00, $m[2], $m[1], $m[3]);
}

$page = $nv_Request->get_int('page', 'get', 1);
$userid = $nv_Request->get_int('userid', 'get', 0);
$per_page_old = $nv_Request->get_int('per_page', 'cookie', 50);
$per_page = $nv_Request->get_int('per_page', 'post', $per_page_old);

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=transaction";

if ($dateview_search1 != 0 && $dateview_search2 != 0) {
    $base_url .= "&starttime=" . $dateview1 . "&endtime=" . $dateview2;
    $where = " AND createdtime < " . $dateview_search2 . " AND createdtime > " . $dateview_search1;
} elseif ($dateview_search1 != 0 && $dateview_search2 == 0) {
    $base_url .= "&starttime=" . $dateview1;
    $where = " AND createdtime > " . $dateview_search1;
} elseif ($dateview_search2 != 0 && $dateview_search1 == 0) {
    $base_url .= "&endtime=" . $dateview2;
    $where = " AND createdtime < " . $dateview_search2;
}
if ($userid > 0) {
    $base_url .= "&userid=" . $userid;
    $where = ' AND userid = ' . $userid;
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

$search_for = $namesearch = $transaction = '';

// get info user
$arr_username = array(
    ""
);

$sql = "SELECT userid, username FROM " . NV_USERS_GLOBALTABLE;

$list_id_user = "";
$query = $db->query($sql);
while ($row = $query->fetch()) {
    $arr_username[$row['userid']] = $row['username'];
    $list_id_user .= $row['userid'] . ",";
}

$sql = "SELECT SQL_CALC_FOUND_ROWS id, createdtime, transaction_type, money, userid, module_name, transaction_data FROM " . $db_config['prefix'] . "_" . $module_data . "_transaction WHERE 1= 1" . $where;
$order = " ORDER BY createdtime DESC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page . "";

$search_for = $nv_Request->get_string('searchfor', 'post,get', '');
$namesearch = $nv_Request->get_string('namesearch', 'post,get', '');
$transaction = $nv_Request->get_string('transaction', 'post,get', '');

if ($namesearch != "") {
    $base_url .= "&namesearch=" . $namesearch;
    $sql_u = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " WHERE username LIKE '%" . $namesearch . "%'";
    $list_id_user = "";
    $query = $db->query($sql_u);
    while ($row = $query->fetch()) {
        $list_id_user .= $row['userid'] . ",";
    }
    $list_id_user = substr($list_id_user, 0, strlen($list_id_user) - 1);
    
    if ($search_for == "userid") {
        $base_url .= "&searchfor=" . $search_for;
        $sql .= " AND " . $search_for . " IN (" . $list_id_user . ")";
    } else if ($search_for == "customer_id") {
        $base_url .= "&searchfor=" . $search_for;
        $sql .= " AND ( " . $search_for . " IN (" . $list_id_user . ")";
        $sql .= " OR customer_name LIKE '%" . $namesearch . "%'";
        $sql .= " OR customer_email LIKE '%" . $namesearch . "%'";
        $sql .= " OR customer_phone LIKE '%" . $namesearch . "%'";
        $sql .= " OR customer_address LIKE '%" . $namesearch . "%'";
        $sql .= " OR customer_info LIKE '%" . $namesearch . "%' )";
    }
}
if (($transaction == "-1" || $transaction == "1") && $namesearch == "") {
    $base_url .= "&transaction=" . $transaction;
    $sql .= " AND transaction_type = " . $transaction . "";
} else if (($transaction == "-1" || $transaction == "1") && $namesearch != "") {
    $base_url .= "&transaction=" . $transaction;
    $sql .= " AND transaction_type = " . $transaction . "";
}

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
        'userid' => ($arr_username[$userid]) ? $arr_username[$userid] : "", //
        'transaction_data' => $transaction_data, //
    ); //
           //
}

$sum = - $xuatra + $congvao;
$sum = nv_affiliate_number_format($sum);


$arr_transaction = array(
    '1' => $lang_module['transaction1'],
    '-1' => $lang_module['transaction2']
);

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

foreach ($arr_transaction as $key => $val) {
    
    if ($transaction == $key) {
        $sl = "selected = \"selected\"";
    } else {
        $sl = "";
    }
    $xtpl->assign('sl_transaction', $sl);
    $xtpl->assign('key_transaction', $key);
    $xtpl->assign('val_transaction', $val);
    $xtpl->parse('main.looptransaction');
}

$number = $page > 1 ? ($per_page * ($page - 1)) + 1 : 1;
foreach ($arr_list_transaction as $element) {
    $xtpl->assign('stt', $number ++);
    $xtpl->assign('CONTENT', $element);
    $xtpl->parse('main.loop');
}

foreach ($search_per_page as $s_per_page) {
    $xtpl->assign('SEARCH_PER_PAGE', $s_per_page);
    $xtpl->parse('main.s_per_page');
}

$xtpl->assign('val_namesearch', $namesearch);

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if ($generate_page) {
    $xtpl->assign('PAGE', $generate_page);
    $xtpl->parse('main.page');
}

if ($dateview1 != "") {
    $xtpl->assign('starttime', $dateview1);
}
if ($dateview2 != "") {
    $xtpl->assign('endtime', $dateview2);
}
$xtpl->assign('sum', $sum);

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['transaction'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
