<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_MOD_SM')) {
    die('Stop!!!');
}

$array_info_week = getInfoWeeks(NV_CURRENTTIME);

$array_search['starttime'] = mktime(0,0,0, $array_info_week['month_current'], 1, $array_info_week['year_current'] );
$array_search['endtime'] = mktime(23,59,59, $array_info_week['month_current'], $array_info_week['maxday'], $array_info_week['year_current'] );

$array_data_by_cat = array();

foreach ( $array_cat as $catid => $cat_info){
    //lich co dinh
    $sql_catid = ' AND catid=' . $catid;
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data)
        ->where('status= 1 AND timefix=1' . $sql_catid)
        ->order('hour_minute_begin, timeevent_begin');
    $array_data_fix = array();
    $array_data = array();
    $result = $db_slave->query($db_slave->sql());
    while ($item = $result->fetch()) {

        $array_data_fix[] = $item;
    }

    foreach ( $array_data_fix as $data ){
        $day = date('d', $data['timeevent_begin']);
        for($i= 1; $i<= $array_info_week['week_num']; $i++ ){
            $day_num = vsprintf('%02s', $day);
            $key_day = $array_info_week['year_current'] . $array_info_week['month_current'] .$day_num;
            $day = $day + 7;
            $data['date_next'] = $day_num . '/' . $array_info_week['month_current'] . '/' . $array_info_week['year_current'];
            $array_data[$key_day][$data['hour_minute_begin']] = $data;
        }
    }

    //lich lam viec theo thoi gian
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data)
        ->where('status= 1 AND timeevent_begin>=' . $array_search['starttime'] . ' AND timeevent_end<=' . $array_search['endtime'] . $sql_catid )
        ->order('timeevent_begin');

    $result = $db_slave->query($db_slave->sql());
    while ($item = $result->fetch()) {
        $keydate = date('Ymd', $item['timeevent_begin']);
        $array_data[$keydate][$item['hour_minute_begin']] = $item;
    }

    //lay lich thay the neu co dang xy ly ghi de
    $db_slave->sqlreset()
        ->select('*')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_other')
        ->where('status= 1 AND timeevent_begin>=' . $array_search['starttime'] . ' AND timeevent_end<=' . $array_search['endtime']  . $sql_catid)
        ->order('timeevent_begin');

    $result = $db_slave->query($db_slave->sql());
    while ($item = $result->fetch()) {
        $keydate = date('Ymd', $item['timeevent_begin']);
        $array_data[$keydate][$item['hour_minute_begin']] = $item;
    }

    $array_data_by_cat[] = array('cat' => $cat_info, 'data' => $array_data);
}

$contents = nv_theme_calendar_main( $array_data_by_cat, $array_info_week );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
