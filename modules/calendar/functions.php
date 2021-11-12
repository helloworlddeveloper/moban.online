<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_SM', true);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_cat';
$array_cat = $nv_Cache->db($sql, 'id', $module_name );
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_group';
$array_group = $nv_Cache->db($sql, 'id', $module_name );


function getInfoWeeks($timestamp)
{
    $array_reponsive = array();
    $array_reponsive['currentday'] = date("j",$timestamp);
    $array_reponsive['maxday'] = date("t",$timestamp);
    $array_reponsive['beginweek'] = date("w",mktime(0,0,0, date('m', $timestamp ), 1, date('y', $timestamp )));
    $array_reponsive['month_current'] = date('m', NV_CURRENTTIME);
    $array_reponsive['year_current'] = date('Y', NV_CURRENTTIME);

    $thismonth = getdate($timestamp);
    $timeStamp = mktime(0,0,0,$thismonth['mon'],1,$thismonth['year']);    //Create time stamp of the first day from the give date.
    $startday  = date('w',$timeStamp);    //get first day of the given month
    $weeks = 0;
    $week_num = 0;

    for ($i=0; $i<($array_reponsive['maxday']+$startday); $i++) {
        if(($i % 7) == 0){
            $weeks++;
        }
        if($array_reponsive['maxday'] == ($i - $startday + 1)){
            $week_num = $weeks;
        }
    }
    $array_reponsive['week_num'] = $week_num;
    return $array_reponsive;
}
