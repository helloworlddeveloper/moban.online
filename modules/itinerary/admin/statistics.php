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

$starttime = $endtime = 0;
$where = "";
$array_search = array();
$array_search['vehicle'] = $nv_Request->get_int('vehicle', 'post,get', 0);
$array_search['localtion_start'] = $nv_Request->get_int('localtion_start', 'post,get', 0);
$array_search['localtion_end'] = $nv_Request->get_string('localtion_end', 'post,get', '');

$array_search['starttime'] = $nv_Request->get_string('starttime', 'post,get', '');
$array_search['endtime'] = $nv_Request->get_string('endtime', 'post,get', '');
if ($array_search['starttime'] != '') {
    unset($m);
    preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $array_search['starttime'], $m);
    $starttime = mktime(00, 00, 00, $m[2], $m[1], $m[3]);
}
if ($array_search['endtime'] != '') {
    unset($m);
    preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/", $array_search['endtime'], $m);
    $endtime = mktime(23, 59, 00, $m[2], $m[1], $m[3]);
}

$array_itinerary_by_vehicle = array();
$_sql = 'SELECT id, vehicle FROM ' . NV_PREFIXLANG . '_' . $module_data ;
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_itinerary_by_vehicle[$_row['id']] = $_row['vehicle'];
}


$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op;

if ($starttime != 0 && $endtime != 0) {
    $base_url .= "&starttime=" . $array_search['starttime'] . "&endtime=" . $array_search['endtime'];
    $where .= " AND time_end < " . $endtime . " AND time_start > " . $starttime;
} elseif ($starttime != 0 && $endtime == 0) {
    $base_url .= "&starttime=" . $array_search['starttime'];
    $where .= " AND time_start > " . $starttime;
} elseif ($endtime != 0 && $starttime == 0) {
    $base_url .= "&endtime=" . $array_search['endtime'];
    $where .= " AND time_end < " . $endtime;
}
if ($array_search['vehicle'] > 0) {
    $base_url .= "&vehicle=" . $array_search['vehicle'];
    $where .= ' AND vehicle = ' . $array_search['vehicle'];
}
if ($array_search['localtion_start'] > 0) {
    $base_url .= "&localtion_start=" . $array_search['localtion_start'];
    $where .= ' AND localtion_start = ' . $array_search['localtion_start'];
}
if ($array_search['localtion_end'] > 0) {
    $base_url .= "&localtion_end=" . $array_search['localtion_end'];
    $where .= ' AND localtion_end = ' . $array_search['localtion_end'];
}

$array_id = array();
$sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE 1=1' . $where;
$result = $db->query( $sql );
while (list( $id ) = $result->fetch(3)){
    $array_id[] = $id;
}
$array_customer = $array_commodity = $array_cost = array();
if( !empty( $array_id )){

    $sql = 'SELECT t1.itinerary_id, t1.qty_customer, t1.price_ticket, t1.qty_customer*t1.price_ticket AS total_price, t2.vehicle FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . ' AS t2 ON t1.itinerary_id = t2.id WHERE t1.itinerary_id IN (' . implode(',', $array_id ). ')';
    $result = $db->query( $sql );
    while ($row = $result->fetch()){
        if( isset( $array_customer[$row['vehicle']] )){
            $array_customer[$row['vehicle']]['qty_customer'] += $row['qty_customer'];
            $array_customer[$row['vehicle']]['price_ticket'] += $row['price_ticket'];
            $array_customer[$row['vehicle']]['total_price'] += $row['total_price'];
        }
        else{
            $array_customer[$row['vehicle']] = $row;
        }
    }
    $sql = 'SELECT t1.itinerary_id, t1.qty, t1.price_ship, t1.qty*t1.price_ship AS total_price, t2.vehicle FROM ' . NV_PREFIXLANG . '_' . $module_data . '_commodity AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . ' AS t2 ON t1.itinerary_id = t2.id WHERE t1.itinerary_id IN (' . implode(',', $array_id ). ')';
    $result = $db->query( $sql );
    while ($row = $result->fetch()){
        if( isset( $array_commodity[$row['vehicle']] )){
            $array_commodity[$row['vehicle']]['qty'] += $row['qty'];
            $array_commodity[$row['vehicle']]['price_ship'] += $row['price_ship'];
            $array_commodity[$row['vehicle']]['total_price'] += $row['total_price'];
        }
        else{
            $array_commodity[$row['vehicle']] = $row;
        }
    }
    $sql = 'SELECT t1.itinerary_id, t1.price, t2.vehicle FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cost AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . ' AS t2 ON t1.itinerary_id = t2.id WHERE t1.itinerary_id IN (' . implode(',', $array_id ). ')';
    $result = $db->query( $sql );
    while ($row = $result->fetch()){
        if( isset( $array_cost[$row['vehicle']] )){
            $array_cost[$row['vehicle']]['price'] += $row['price'];
        }
        else{
            $array_cost[$row['vehicle']] = $row;
        }
    }
}


$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('SEARCH', $array_search);

$array_localtion = array();
$_sql = 'SELECT id,title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_location';
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_localtion[$_row['id']] = $_row;
}


foreach ($array_localtion as $value) {
    $xtpl->assign('OPTION', array(
        'key' => $value['id'],
        'title' => $value['title'],
        'selected' => ($value['id'] == $array_search['localtion_start']) ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.localtion_start');
}
foreach ($array_localtion as $value) {
    $xtpl->assign('OPTION', array(
        'key' => $value['id'],
        'title' => $value['title'],
        'selected' => ($value['id'] == $array_search['localtion_end']) ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.localtion_end');
}

$array_vehicle_itinerary = array();
$_sql = 'SELECT id,car_number_plate FROM ' . NV_PREFIXLANG . '_' . $module_data . '_vehicle';
$_query = $db->query($_sql);
while ($_row = $_query->fetch()) {
    $array_vehicle_itinerary[$_row['id']] = $_row;
}

foreach ($array_vehicle_itinerary as $value) {
    $xtpl->assign('OPTION', array(
        'key' => $value['id'],
        'title' => $value['car_number_plate'],
        'selected' => ($value['id'] == $array_search['vehicle']) ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.select_vehicle');
}
if( !empty( $array_id )){
    $stt = 1;
    foreach ($array_vehicle_itinerary as $value) {
        if( $array_search['vehicle'] == 0 or ($array_search['vehicle'] > 0 and $value['id']== $array_search['vehicle']) )
        {

            $all_loinhuan_price = $array_customer[$value['id']]['total_price'] + $array_commodity[$value['id']]['total_price'] - $array_cost[$value['id']]['price'];

            $array_customer[$value['id']]['total_price'] = number_format( $array_customer[$value['id']]['total_price'], 0, '.', ',');
            $array_commodity[$value['id']]['total_price'] = number_format( $array_commodity[$value['id']]['total_price'], 0, '.', ',');
            $array_cost[$value['id']]['price'] = number_format( $array_cost[$value['id']]['price'], 0, '.', ',');

            $xtpl->assign('stt', $stt++);
            $xtpl->assign('VALUE', $value);
            $xtpl->assign('CUSTOMER', $array_customer[$value['id']]);
            $xtpl->assign('COMMODITY', $array_commodity[$value['id']]);
            $xtpl->assign('COST', $array_cost[$value['id']]);
            $xtpl->assign('all_loinhuan_price', number_format( $all_loinhuan_price, 0, '.', ','));
            $xtpl->parse('main.data.loop');
        }
    }
    $xtpl->parse('main.data');
}else{
    $xtpl->parse('main.nodata');
}


$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['statistics'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
