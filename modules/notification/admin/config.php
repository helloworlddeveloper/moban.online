<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2017 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-01-2017 20:08
 */

if( !defined( 'NV_IS_MESSAGE_ADMIN' ) )
    die( 'Stop!!!' );

$page_title = $lang_module['config'];

$array_config = array();

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $array_config['timeview'] = $nv_Request->get_int( 'timeview', 'post', 0 );
    $array_config['firebase_url'] = $nv_Request->get_title( 'firebase_url', 'post', '' );
    $array_config['firebase_api_access_key'] = $nv_Request->get_title( 'firebase_api_access_key', 'post', '' );
    $sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name" );
	$sth->bindParam( ':module_name', $module_name, PDO::PARAM_STR );
	foreach( $array_config as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}
    $nv_Cache->delMod('settings');
	$nv_Cache->delMod($module_name);

    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
    die();
}

$xtpl = new XTemplate( "config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $module_config[$module_name] );

for( $i = 5; $i <= 180; $i += 5 )
{
    $xtpl->assign( 'TIMEVIEW', array(
        'key' => $i,
        'title' => sprintf( $lang_module['notification_config_timeview_option'], $i ),
        'sl' => ( $i == $module_config[$module_name]['timeview'] ) ? ' selected=selected' : '' ) );
    $xtpl->parse( 'main.timeview' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';

?>