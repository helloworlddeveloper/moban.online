<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['main'] = $lang_module['main'];
$submenu['cat'] = $lang_module['cat'];

$allow_func = array( 'main', 'cat', 'config');

define( 'NV_IS_FILE_ADMIN', true );

$array_cattype = array();
$array_cattype['video'] = $lang_module['cattype_video'];
$array_cattype['link'] = $lang_module['cattype_link'];
$array_cattype['content'] = $lang_module['cattype_content'];


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

function fix_catWeight()
{
    global $db, $module_data, $db_config;

    $sql = "SELECT id FROM " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while( $row = $db->sql_fetchrow( $result ) )
    {
        $weight++;
        $query = "UPDATE " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query( $query );
    }
}


/**
 * nv_fix_cat_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_cat_order($parentid = 0, $order = 0, $lev = 0)
{
    global $db, $db_config, $module_data;

    $sql = 'SELECT id, parentid FROM ' . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
    $result = $db->query($sql);
    $array_cat_order = array();
    while ($row = $result->fetch()) {
        $array_cat_order[] = $row['id'];
    }
    $result->closeCursor();
    $weight = 0;
    if ($parentid > 0) {
        ++$lev;
    } else {
        $lev = 0;
    }
    foreach ($array_cat_order as $catid_i) {
        ++$order;
        ++$weight;
        $sql = 'UPDATE ' . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ', sort=' . $order . ', lev=' . $lev . ' WHERE id=' . intval($catid_i);
        $db->query($sql);
        $order = nv_fix_cat_order($catid_i, $order, $lev);
    }
    $numsubcat = $weight;
    if ($parentid > 0) {
        $sql = 'UPDATE ' . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . '_' . $module_data . '_cat SET numsubcat=' . $numsubcat . ' WHERE id=' . intval($parentid);
        $db->query($sql);
    }
    return $order;
}
