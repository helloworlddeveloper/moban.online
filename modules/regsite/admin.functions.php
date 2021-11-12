<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );


$allow_func = array( 'main', 'otp', 'config');

$submenu['otp'] = $lang_module['otp'];
$submenu['config'] = $lang_module['config'];
define( 'NV_IS_FILE_ADMIN', true );

$db_slave->select( '*' )->from($db_config['prefix'] . '_' . $module_data );

$result = $db_slave->query( $db_slave->sql() );
while( $row = $result->fetch() )
{
    $array_data = $db->query('SELECT *FROM ' . $db_config['prefix'] . '_affiliate_users WHERE mobile = ' . $row['mobile']  )->fetch();
    $db->query( "UPDATE " . $db_config['prefix'] . "_" . $module_data . " SET userid=" . intval( $array_data['userid'] ) . " WHERE id=" . $row['id']);
}