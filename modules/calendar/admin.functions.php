<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 2:29
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

define('NV_IS_FILE_ADMIN', true);
define('NV_TABLE_AFFILIATE_LANG', NV_PREFIXLANG . '_affiliate');

$allow_func = array(
    'main', 'other', 'cat', 'group', 'alias'
);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_cat';
$array_cat = $nv_Cache->db($sql, 'id', $module_name );
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_group';
$array_group = $nv_Cache->db($sql, 'id', $module_name );

$array_status = array( 1 => $lang_module['status_1'], 0 => $lang_module['status_0']);


//kiem tra xem lich nay co bi trung khong
function nv_calendar_check_exits( $calendarid, $timebegin, $timeend, $catid, $groupid )
{
    global $module_data, $lang_module, $db;

    //cac  truong hop neu co ban ghi nghia la da co lich
    $sql_check = ' AND ( ' . $timebegin . ' <  timeevent_end) AND ( ' . $timeend . ' > timeevent_begin) AND catid=' . intval( $catid ) . ' AND groupid=' . intval( $groupid );
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE timefix=0 AND id!=' . $calendarid . $sql_check;
    $result = $db->query( $sql );
    $data_using = $result->fetch();
    if( !empty( $data_using)){
        return sprintf( $lang_module['error_exits_calendar'], $data_using['title'], date('H:i', $data_using['timeevent_begin']), date('H:i', $data_using['timeevent_end']), $data_using['addressevent'] );
    }
}

//kiem tra xem lich nay co bi trung khong
function nv_calendar_check_timefix_exits( $calendarid, $timebegin, $timeend, $catid, $groupid )
{
    global $module_data, $db;

    $hour_minute_begin = date('Hi', $timebegin );
    $hour_minute_end = date('Hi', $timeend );
    $day_week = date('N', $timebegin);//thu trong tuan 1 = monday, 7 sunday

    $sql_check = ' WHERE id!=' . $calendarid . ' AND ( ' . $hour_minute_begin . ' <  hour_minute_end) AND ( ' . $hour_minute_end . ' > hour_minute_begin) AND timefix=1 AND day_week=' . $day_week . ' AND catid=' . $catid . ' AND groupid=' . $groupid;
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . $sql_check;

    $result = $db->query($sql);
    return $result->fetchColumn();
}