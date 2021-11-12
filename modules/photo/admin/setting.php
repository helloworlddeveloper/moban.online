<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Webdep24.com. All rights reserved
 * @Blog  http://dangdinhtu.com
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Wed, 21 Jan 2015 14:00:59 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['setting'];

$savesetting = $nv_Request->get_int( 'savesetting', 'post', 0 );
if( ! empty( $savesetting ) )
{
	$photo_config = array();
	$photo_config['per_page_album'] = $nv_Request->get_int( 'per_page_album', 'post', 0 );
	$photo_config['per_page_photo'] = $nv_Request->get_int( 'per_page_photo', 'post', 20 );
	$photo_config['home_view'] = $nv_Request->get_title( 'home_view', 'post', '', 0 );
	$photo_config['album_view'] = $nv_Request->get_title( 'album_view', 'post', '', 0 );
    
	$sth = $db->prepare( 'UPDATE ' . TABLE_PHOTO_NAME . '_setting SET config_value = :config_value WHERE config_name = :config_name');
	foreach( $photo_config as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}
	$sth->closeCursor();

    $nv_Cache->delMod($module_name);
	$nv_Request->set_Session( $module_data . '_success', $lang_module['setting_update_success'] );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	die();
}

$xtpl = new XTemplate( 'setting.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA', $photo_config );
$xtpl->assign( 'CANCEL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

} 

foreach( $array_home_view as $key => $title )
{
	$xtpl->assign( 'HOME_VIEW', array( 'key' => $key, 'title' => $title, 'selected' => $key == $photo_config['home_view'] ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.home_view' );	
	
}
foreach( $array_album_view as $key => $title )
{
	$xtpl->assign( 'ALBUM_VIEW', array( 'key' => $key, 'title' => $title, 'selected' => $key == $photo_config['album_view'] ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.album_view' );	
	
}
// So bai viet tren mot trang
for( $i = 5; $i <= 60; ++ $i )
{
	$xtpl->assign( 'PER_PAGE_ALBUM', array( 'key' => $i, 'title' => $i, 'selected' => $i == $photo_config['per_page_album'] ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.per_page_album' );
}
for( $i = 5; $i <= 60; ++ $i )
{
	$xtpl->assign( 'PER_PAGE_PHOTO', array( 'key' => $i, 'title' => $i, 'selected' => $i == $photo_config['per_page_photo'] ? ' selected="selected"' : '' ) );
	$xtpl->parse( 'main.per_page_photo' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';