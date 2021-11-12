<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
{
    die( 'Stop!!!' );
}

$module_data = 'sm';

$xtpl = new XTemplate('exportbarcode.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );

if( !empty( $error )){
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$sql = "SELECT order_id, customer_id, order_code, order_name FROM " . NV_PREFIXLANG . "_" . $module_data . "_orders WHERE chossentype IS NULL ORDER BY order_time DESC";

$result = $db->query( $sql );
while( $row = $result->fetch( ) )
{
    $sl = '';
    $xtpl->assign('PRODUCTTYPE', array('key' => $row['order_id'], 'value' => $row['order_code'] . '-' . $row['order_name'], 'sl' => $sl));
    $xtpl->parse('main.producttype');
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
