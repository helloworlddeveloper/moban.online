<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (quanglh268@gmail.com)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Fri, 12 Jan 2018 02:38:03 GMT
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_WORKFORCE', true );
global $array_config;
$array_config = $module_config[$module_name];

$array_gender = array(
    1 => $lang_module['male'],
    0 => $lang_module['female']
);


$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_company';
$array_company = $nv_Cache->db($sql, 'id', $module_name);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_producer';
$array_global_cat = $nv_Cache->db($sql, 'id', $module_name);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_unit';
$array_units = $nv_Cache->db($sql, 'id', $module_name);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_department';
$array_department = $nv_Cache->db($sql, 'id', $module_name);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_producttype';
$array_producttype = $nv_Cache->db($sql, 'id', $module_name);

function nv_workforce_delete($id)
{
    global $db, $module_data;

    $count = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id);
    if ($count) {
        //
    }
}
$link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=';
    $array_control = array(
        'main' => array(
            'link' => $link . 'main',
            'title' => $lang_module['inventory_main']
        ),
        'inventory-list' => array(
            'link' => $link . 'inventory-list',
            'title' => $lang_module['inventory_list']
        ),
        'list' => array(
            'link' => $link . 'list',
            'title' => $lang_module['content_customer']
        ),
        'producttype' => array(
            'link' => $link . 'producttype',
            'title' => $lang_module['producttype']
        ),
        'producer' => array(
            'link' => $link . 'producer',
            'title' => $lang_module['producer']
        ),
        'department' => array(
            'link' => $link . 'department',
            'title' => $lang_module['department']
        ),
        'unit' => array(
            'link' => $link . 'unit',
            'title' => $lang_module['unit']
        ),
        'product' => array(
            'link' => $link . 'product',
            'title' => $lang_module['product']
        ),
);