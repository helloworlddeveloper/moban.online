<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 9/9/2010, 6:38
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}


$per_page = 100;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$xtpl = new XTemplate('otp.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_data);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;
$from = $db_config['prefix'] . '_' . $module_data . '_code';

$db_slave->sqlreset()->select( 'COUNT(*)' )->from( $from );
$_sql = $db_slave->sql();
$num_items = $db_slave->query( $_sql )->fetchColumn();

$db_slave->select( '*' )->order( 'addtime DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$stt = 1;
$result = $db_slave->query( $db_slave->sql() );
while( $row = $result->fetch() )
{
    $row['stt'] = $stt++;
    $row['addtime'] = date('H:i d/m/Y', $row['addtime']);
    $row['status'] = $lang_module['status_' . $row['status']];
    $xtpl->assign( 'ROW', $row );
    $xtpl->parse( 'main.loop' );
}
$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$xtpl->parse('main');
$content = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($content);
include NV_ROOTDIR . '/includes/footer.php';
