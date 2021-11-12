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
function nv_catList()
{
    global $db, $module_data;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY weight ASC";
    $result = $db->query( $sql );
    $list = array();
    while( $row = $result->fetch() )
    {
        $list[$row['id']] = array(
            'id' => $row['id'],
            'title' => $row['title'],
            'alias' => $row['alias'],
            'weight' => ( int )$row['weight']
        );
    }

    return $list;
}

function fix_catWeight()
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while( $row = $db->sql_fetchrow( $result ) )
    {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_cat SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query( $query );
    }
}