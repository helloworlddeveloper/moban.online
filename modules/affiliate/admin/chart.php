<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010  11:33:22 AM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$array_view_chart = array(
    $lang_module['view_day'],
    $lang_module['view_month'],
    $lang_module['view_year'],
);

$search['userid'] = $nv_Request->get_int( 'userid', 'get', 0 );
$search['starttime'] = $nv_Request->get_title( 'starttime', 'get', '' );
$search['endtime'] = $nv_Request->get_title( 'endtime', 'get', '' );
$search['view_chart'] = $nv_Request->get_int( 'view_chart', 'get', 0 );
$starttime = $endtime = 0;
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

$array_data = $array_key = array();

//bang data_content
$where = '';
$sql = 'SELECT from_unixtime(addtime, "%Y-%m-%d") AS the_date, addtime, COUNT(*) AS total FROM ' . NV_PREFIXLANG . '_data_content WHERE author=' . $search['userid'];
if ($starttime != 0 && $endtime != 0) {
    $where = " AND addtime < " . $endtime . " AND addtime > " . $starttime;
} elseif ($starttime != 0 && $endtime == 0) {
    $where = " AND addtime > " . $starttime;
} elseif ($endtime != 0 && $starttime == 0) {
    $where = " AND addtime < " . $endtime;
}
$sql .= $where . ' GROUP BY the_date ORDER BY addtime ASC';

$result = $db->query($sql);
while ($data =  $result->fetch()){
    $key = date('Ymd', $data['addtime']);
    $value = date('d/m', $data['addtime']);
    $array_data[$key] = $data;
    $array_key[$key] = $value;
}

//bang news
$where = '';
$sql = 'SELECT from_unixtime(addtime, "%Y-%m-%d") AS the_date, addtime, COUNT(*) AS total FROM ' . NV_PREFIXLANG . '_news_rows WHERE admin_id=' . $search['userid'];
if ($starttime != 0 && $endtime != 0) {
    $where = " AND addtime < " . $endtime . " AND addtime > " . $starttime;
} elseif ($starttime != 0 && $endtime == 0) {
    $where = " AND addtime > " . $starttime;
} elseif ($endtime != 0 && $starttime == 0) {
    $where = " AND addtime < " . $endtime;
}
$sql .= $where . ' GROUP BY the_date ORDER BY addtime ASC';

$result = $db->query($sql);
while ($data =  $result->fetch()){
    $key = date('Ymd', $data['addtime']);
    $value = date('d/m', $data['addtime']);
    if( isset( $array_data[$key] )){
        $array_data[$key]['total'] = $array_data[$key]['total'] + ( $data['total'] * NV_IS_NUM_CHANGE);
    }else{
        $data['total'] = $data['total'] * NV_IS_NUM_CHANGE;
        $array_data[$key] = $data;
    }
    $array_key[$key] = $value;
}

ksort ($array_data);
ksort ($array_key);

$db->sqlreset()->select( 'first_name, last_name, email' )->from( NV_USERS_GLOBALTABLE )->where( 'userid=' . $search['userid'] );

$user_info_search = $db->query( $db->sql() )->fetch();
$user_info_search['fullname'] = nv_show_name_user( $user_info_search['first_name'], $user_info_search['last_name'] );

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('SEARCH', $search);
$xtpl->assign('USER', $user_info_search);

foreach ( $array_view_chart as $key => $value ){
    $sl = ($key == $search['view_chart'])? ' selected=selected' : '';
    $xtpl->assign( 'VIEW_CHART', array('key' => $key, 'value' => $value, 'sl' => $sl) );
    $xtpl->parse( 'main.view_chart' );
}

foreach ( $array_key as $value ){
    $xtpl->assign( 'KEY', $value);
    $xtpl->parse( 'main.key' );
}
foreach ( $array_data as $value ){
    $xtpl->assign( 'VALUE', $value['total']);
    $xtpl->parse( 'main.value' );
    $xtpl->parse( 'main.value2' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>