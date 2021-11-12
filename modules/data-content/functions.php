<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_SLIDE', true );

$array_allow_status_contnet =  array('3282', '3', '4', '5');

$alias_cat_url = isset($array_op[0]) ? $array_op[0] : '';
$sql = "SELECT * FROM " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY weight ASC";
$list = $nv_Cache->db($sql, 'id', $module_name);
if (!empty($list)) {
    foreach ($list as $l) {
        $global_array_cat[$l['id']] = $l;
        $global_array_cat[$l['id']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
        if ($alias_cat_url == $l['alias']) {
            $catid = $l['id'];
        }
    }
}
$page = 1;
$count_op = sizeof($array_op);
if (!empty($array_op) and $op == 'main') {
    $op = 'main';
    if ($count_op == 1 or substr($array_op[1], 0, 5) == 'page-') {
        if ($count_op > 1 or $catid > 0) {
            $op = 'viewcat';
            if (isset($array_op[1]) and substr($array_op[1], 0, 5) == 'page-') {
                $page = intval(substr($array_op[1], 5));
            }
        }
    }
    $parentid = $catid;
    while ($parentid > 0) {
        $array_cat_i = $global_array_cat[$parentid];
        $array_mod_title[] = array(
            'catid' => $parentid,
            'title' => $array_cat_i['title'],
            'link' => $array_cat_i['link']
        );
        $parentid = $array_cat_i['parentid'];
    }
    krsort($array_mod_title, SORT_NUMERIC);
}

/**
 * nv_setcat1()
 *
 * @param mixed $list2
 * @param mixed $id
 * @param mixed $list
 * @param integer $m
 * @param integer $num
 * @return
 */
function nv_catList($parentid = 0)
{
    global $db, $module_data, $db_config;

    if( $parentid == ''){
        $sql = "SELECT * FROM " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY sort ASC";
    }
    else{
        $sql = "SELECT * FROM " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=" . $parentid . " ORDER BY sort ASC";
    }

    $result = $db->query( $sql );
    $list = array();
    while( $row = $result->fetch() )
    {
        $xtitle_i = '';
        if ($row['lev'] > 0) {
            $xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
            for ($i = 1; $i <= $row['lev']; ++$i) {
                $xtitle_i .= '---';
            }
            $xtitle_i .= '>&nbsp;';
        }
        $xtitle_i .= $row['title'];
        $list[$row['id']] = array(
            'id' => $row['id'],
            'parentid' => $row['parentid'],
            'numsubcat' => $row['numsubcat'],
            'title' => $row['title'],
            'title_show' => $xtitle_i,
            'alias' => $row['alias'],
            'cattype' => $row['cattype'],
            'weight' => ( int )$row['weight']
        );
    }
    return $list;
}