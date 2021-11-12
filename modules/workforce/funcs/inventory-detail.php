<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang <contact@vinades.vn>
 * @Copyright (C) 2010 - 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

if (!defined('NV_IS_USER') ) {
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}

if (!nv_user_in_groups($array_config['group_add_workforce'])) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    die();
}

$id = $nv_Request->get_int( 'id', 'get', 0 );
if( $id == 0 )
{
    Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=inventory-list' );
    die();
}

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory WHERE id=' . $id;
$data_inventory = $db->query( $sql )->fetch();

//kiem tra xem co du quyen k
$array_department_allow = array();
if( !defined('NV_IS_ADMIN')){
    foreach ($array_department as $department ){
        if( $department['userid'] == $user_info['userid'] ){
            $array_department_allow[] = $department['id'];
        }
    }
    if( empty( $array_department_allow) || !in_array( $data_inventory['departmentid'], $array_department_allow)){
        Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=inventory-list' );
        die();
    }
}

$data_inventory['hour'] = date( 'H', $data_inventory['time_inventory'] );
$data_inventory['minute'] = date( 'i', $data_inventory['time_inventory'] );
$data_inventory['day'] = date( 'd', $data_inventory['time_inventory'] );
$data_inventory['month'] = date( 'm', $data_inventory['time_inventory'] );
$data_inventory['year'] = date( 'Y', $data_inventory['time_inventory'] );
if( $data_inventory['departmentid'] == 0 ){
    $data_inventory['department'] = $lang_module['all_company'];
}else{
    $data_inventory['department'] = $array_department[$data_inventory['departmentid']]['title'];
}

$page_title = $lang_module['addinventory'] . ' ' . $data_inventory['departmentid'];

$xtpl = new XTemplate( $op . '.tpl',  NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'departmentid', $data_inventory['departmentid'] );
$xtpl->assign( 'DATA', $data_inventory );

$sql = 'SELECT t1.*, t2.first_name, t2.last_name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory_users AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . ' AS t2 ON t1.userid=t2.id WHERE iid=' . $id;
$result = $db->query( $sql );
while( $row = $result->fetch() )
{
    $xtpl->assign( 'BANKIEMKE', $row );
    $xtpl->parse( 'main.bankiemke' );
}

$sql = 'SELECT t2.*, t1.price AS price_conlai, t2.amount as amount_inventory, t1.amount_broken, t1.amount_redundant, t1.amount_missing, t1.note FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory_detail AS t1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_product AS t2 ON t1.pid=t2.id WHERE iid=' . $id;
$stt = 1;
$result = $db->query( $sql );
$array_data = array();
while( $row = $result->fetch() )
{
    $row['tt'] = $stt++;
    $row['unit'] = $array_units[$row['unitid']]['title'];
    $timekhauhao = NV_CURRENTTIME - $row['time_in'];
    $row['time_in'] = date( 'm/Y', $row['time_in'] );
    $row['department'] = $array_department[$row['departmentid']]['title'];
    $giatrikhauhaongay = $row['price'] / $row['time_depreciation']/30;
    $timekhauhao = ceil( $timekhauhao/86400 );//quy doi ra ngay
    $row['status'] = $lang_module['status_' . $row['status']];
    $row['price_conlai'] = number_format( $row['price_conlai'], 0, ',', '.');
    $row['price'] = number_format( $row['price'], 0, ',', '.');
    $row['amount_using'] = $row['amount_inventory'] - $row['amount_broken'];
    $row['amount'] = number_format( $row['amount'], 0, ',', '.');
    $row['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addproduct&id=' . $row['id'];
    $row['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $row['id'] . '&amp;delete_checkss=' . md5( $row['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
    $array_data[$row['producttypeid']][] = $row;
}


foreach( $array_data as $producttypeid => $data_product )
{
    foreach( $data_product as $data )
    {
        $xtpl->assign( 'ROW', $data );
        $xtpl->parse( 'main.producttype.loop' );
    }
    $xtpl->assign( 'PRODUCTTYPE', $array_producttype[$producttypeid] );
    $xtpl->parse( 'main.producttype' );
}

$xtpl->parse( 'main' );
$contents = nv_theme_workforce_control( $array_control );
$contents .= $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

//https://www.youtube.com/watch?v=6CYar9oeR_c