<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 24 Dec 2014 08:22:22 GMT
 */

if(! defined('NV_IS_MOD_AFFILIATE'))
{
    die('Stop!!!');
}

    
$array_search['status'] = $array_search['teacher'] = $array_search['type'] = array();
$array_search['starttime'] = '01/' . date('m/Y', NV_CURRENTTIME );
$array_search['endtime'] = date('d/m/Y', NV_CURRENTTIME );
$sql_where = array();

$array_search['status'] = $nv_Request->get_array('status', 'get', array());
$array_search['teacher'] = $nv_Request->get_array('teacher', 'get', array());
$array_search['type'] = $nv_Request->get_array('type', 'get', array());
$array_search['starttime'] = $nv_Request->get_title('starttime', 'get', $array_search['starttime']);
$array_search['endtime'] = $nv_Request->get_title('endtime', 'get', $array_search['endtime']);
if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['starttime'], $m)) {
    $starttime = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
} else {
    $starttime = 0;
}
if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_search['endtime'], $m)) {
    $endtime = mktime(23, 0, 0, $m[2], $m[1], $m[3]);
} else {
    $endtime = 0;
}

if( !empty( $array_search['status'] )){
    $sql_where[] = ' status IN (' . implode(',', $array_search['status']) . ')';
}
if( !empty( $array_search['teacher'] )){
    $sql_where[] = ' teacherid IN (' . implode(',', $array_search['teacher']) . ')';
}
if( !empty( $array_search['type'] )){
    $tmp = array();
    foreach( $array_search['type'] as $type ){
        if( $type == 1 ){
            $tmp[] = ' dimuon =1';    
        }
        elseif( $type == 2 ){
            $tmp[] = ' vesom =1';    
        } 
    }
    if (count($tmp) ==2){
      $sql_where[] = ' ( ' .  implode(' OR ', $tmp) . ')';  
    }else{
        $sql_where[] = implode(' ', $tmp );
    }
}
if( $starttime > 0 && $endtime > 0){
    $sql_where[] = ' datetime >=' . $starttime . ' AND datetime<=' . $endtime;
}elseif( $starttime > 0 && $endtime == 0){
    $sql_where[] = ' datetime >=' . $starttime;
}elseif( $starttime == 0 && $endtime > 0){
    $sql_where[] = ' datetime <=' . $endtime;
}

if( !defined('NV_IS_ADMIN') ){
    $list_id = nvGetUseridInParent($user_data_affiliate['userid'], $user_data_affiliate['subcatid'], $checksub = true, $ispossiton = true);
    if( !empty( $list_id )){
        $sql_where[] = ' teacherid IN (' . implode(',' , $list_id ) . ')';
    }else{
        $sql_where[] = ' teacherid =' . $user_info['userid'];
    }
}

$show_view = true;
$page = $nv_Request->get_int('page', 'post,get', 1);
$db->sqlreset()->select('*, SUM(ngaycong) AS ngaycong, SUM(dimuon) AS dimuon, SUM(dimuoncophep) AS dimuoncophep, SUM(vesom) AS vesom, SUM(vesomcophep) AS vesomcophep')->from('' . NV_PREFIXLANG . '_' . $module_data . '_chamcong')->order('teacherid ASC, datetime ASC')->group('teacherid');
if( !empty( $sql_where )){
    $db->where( implode(' AND ', $sql_where ));    
}
$sth = $db->prepare($db->sql());
$sth->execute();

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('DATA_SEARCH', $array_search);

if(defined('NV_IS_ADMIN'))
{
    $xtpl->parse('main.view.export');
}

while ($view = $sth->fetch())
{
    $totalinfringe = 0;
    $view['teacher'] = $list_userdata[$view['teacherid']];
    //print_r($list_userdata);die;
    $view['status'] = $lang_module['chamcong_status_' . $view['status']];
    $view['datetime'] = date('d/m/Y', $view['datetime']);
    $totalinfringe = ( $view['dimuon'] - $view['dimuoncophep'] ) + ( $view['vesom'] - $view['vesomcophep'] );
    $view['ngaycong_bi_tru'] = floor( $totalinfringe / $module_config[$module_name]['max_infringe'] );
    $ngay_cong_tinh_luong = $view['ngaycong'] - $view['ngaycong_bi_tru'];
    $view['salary'] = $view['teacher']['salary_day'] * $ngay_cong_tinh_luong;
    $view['total_salary'] = number_format($view['salary'] + $view['teacher']['benefit'], 0, '.', ',' );
    $view['teacher']['benefit'] = number_format($view['teacher']['benefit'], 0, '.', ',' );
    $view['salary'] = number_format($view['salary'], 0, '.', ',');
    $view['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $view['id'];
    $xtpl->assign('VIEW', $view);
    $xtpl->parse('main.view.loop');
}
$array_status =array(
0 => $lang_module['chamcong_status_0'],
1 => $lang_module['chamcong_status_1'],
);
foreach($array_status as $key => $status )
{
    $sl = in_array( $key, $array_search['status'])? ' selected=selected' : '';
    $xtpl->assign('STATUS', array('sl' => $sl, 'key' => $key, 'title' => $status));
    $xtpl->parse('main.view.status');
}
$array_type =array(
1 => $lang_module['type_1'],
2 => $lang_module['type_2'],
);
foreach($array_type as $key => $type )
{
    $sl = in_array( $key, $array_search['type'])? ' selected=selected' : '';
    $xtpl->assign('TYPE', array('sl' => $sl, 'key' => $key, 'title' => $type));
    $xtpl->parse('main.view.type');
}
foreach($list_userdata as $teacher )
{
    if( empty( $list_id ) || (!empty( $list_id ) && in_array( $userdata['userid'], $list_id ))){
        $teacher['full_name'] = nv_show_name_user( $teacher['first_name'], $teacher['last_name'], $teacher['username'] );
        $teacher['sl'] = in_array( $teacher['userid'], $array_search['teacher'])? ' selected=selected' : '';
        $xtpl->assign('TEACHER', $teacher);
        $xtpl->parse('main.view.teacher');
    }

}

$xtpl->parse('main.view');

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['tinhcong'];

$array_mod_title[] = array(
    'catid' => 0,
    'title' => $lang_module['quanlygiaovien'],
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
