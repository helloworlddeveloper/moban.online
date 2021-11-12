<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 01:50:26 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$array_ip_mask = array(
	'0' => '255.255.255.255',
	'1' => '255.xxx.xxx.xxx',
	'2' => '255.255.xxx.xxx',
	'3' => '255.255.255.xxx',
	);

$groups_list = nv_groups_list();
unset( $groups_list[6] );

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config = array();
	$array_config['per_page'] = $nv_Request->get_int( 'per_page', 'post', 0 );

	$array_config['permissions_users'] = array();
	$user_permisson = $nv_Request->get_array( 'user_permisson', 'post', array() );

	foreach( $user_permisson as $userid_per )
	{
		foreach( $array_permissions_action['list_op'] as $op_permisson )
		{
			$array_config['permissions_users'][$userid_per][$op_permisson]['view'] = $nv_Request->get_int( $op_permisson . '_' . $userid_per . '_view', 'post', 0 );
			$array_config['permissions_users'][$userid_per][$op_permisson]['add'] = $nv_Request->get_int( $op_permisson . '_' . $userid_per . '_add', 'post', 0 );
			if( $array_config['permissions_users'][$userid_per][$op_permisson]['add'] == 1 )
			{
				$array_config['permissions_users'][$userid_per][$op_permisson]['view'] = 1;
			}
			$array_config['permissions_users'][$userid_per][$op_permisson]['edit'] = $nv_Request->get_int( $op_permisson . '_' . $userid_per . '_edit', 'post', 0 );
			if( $array_config['permissions_users'][$userid_per][$op_permisson]['edit'] == 1 )
			{
				$array_config['permissions_users'][$userid_per][$op_permisson]['view'] = 1;
			}
			$array_config['permissions_users'][$userid_per][$op_permisson]['order'] = $nv_Request->get_int( $op_permisson . '_' . $userid_per . '_order', 'post', 0 );
			if( $array_config['permissions_users'][$userid_per][$op_permisson]['order'] == 1 )
			{
				$array_config['permissions_users'][$userid_per][$op_permisson]['view'] = 1;
			}
			$array_config['permissions_users'][$userid_per][$op_permisson]['del'] = $nv_Request->get_int( $op_permisson . '_' . $userid_per . '_del', 'post', 0 );
			if( $array_config['permissions_users'][$userid_per][$op_permisson]['del'] == 1 )
			{
				$array_config['permissions_users'][$userid_per][$op_permisson]['view'] = 1;
			}
		}
	}
	$array_config['permissions_users'] = serialize( $array_config['permissions_users'] );

    $array_config['sms_on'] = $nv_Request->get_int('sms_on', 'post', 0);
    $array_config['sms_type'] = $nv_Request->get_int('sms_type', 'post', 0);
    $array_config['apikey'] = $nv_Request->get_title('apikey', 'post', '');
    $array_config['secretkey'] = $nv_Request->get_title('secretkey', 'post', '');
    $array_config['brandname'] = $nv_Request->get_title('brandname', 'post', '');

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name" );
	$sth->bindParam( ':module_name', $module_name, PDO::PARAM_STR );
	foreach( $array_config as $config_name => $config_value )
	{
		$sth->bindParam( ':config_name', $config_name, PDO::PARAM_STR );
		$sth->bindParam( ':config_value', $config_value, PDO::PARAM_STR );
		$sth->execute();
	}

	$array_config = array();
	$array_group_id = $nv_Request->get_typed_array( 'array_group_id', 'post' );
	$array_addhistory = $nv_Request->get_typed_array( 'array_addhistory', 'post' );
    
	foreach( $array_group_id as $group_id )
	{
		if( isset( $groups_list[$group_id] ) )
		{
			$addhistory = ( isset( $array_addhistory[$group_id] ) and intval( $array_addhistory[$group_id] ) == 1 ) ? 1 : 0;
			$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_config_mkt SET addhistory = '" . $addhistory . "' WHERE group_id =" . $group_id );
		}
	}

	$nv_Cache->delMod( 'settings' );
	$nv_Cache->delMod( $module_name );
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass() );
	die();
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA', $module_config[$module_name] );


$xtpl->assign('SMS_ON', $module_config[$module_name]['sms_on'] ? ' checked="checked"' : '');
// Cau hinh hien thi nguon tin
$array_config_sms_type = array(
    2 => $lang_module['config_sms_type_2'],
    4 => $lang_module['config_sms_type_4'],
    6 => $lang_module['config_sms_type_6'],
    8 => $lang_module['config_sms_type_8']
);
foreach ($array_config_sms_type as $key => $val) {
    $xtpl->assign('SMS_TYPE', array(
        'key' => $key,
        'title' => $val,
        'selected' => $key == $module_config[$module_name]['sms_type'] ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.sms_type');
}

$permissions_users = unserialize( $module_config[$module_name]['permissions_users'] );

/*
$sql = "SELECT t1.userid, t1.username, t1.email, t1.first_name, t1.last_name FROM " . NV_USERS_GLOBALTABLE . " AS t1 INNER JOIN " . NV_AUTHORS_GLOBALTABLE . " AS t2 ON t1.userid=t2.admin_id WHERE t1.active=1 AND t2.lev=3";
$result = $db->query( $sql );
$array_mods = $result->fetchAll();

$array_config = $module_config[$module_name];
$total_op = count( $array_permissions_action['list_op'] );
$xtpl->assign( 'total_op', $total_op + 1 );

foreach( $array_mods as $user_data )
{
	$user_data['full_name'] = nv_show_name_user( $user_data['first_name'], $user_data['last_name'] );
	$xtpl->assign( 'USER_DATA', $user_data );
	foreach( $array_permissions_action['list_op'] as $list_op )
	{
		if( isset( $lang_module[$list_op] ) )
		{
			$xtpl->assign( 'checked_view', ( isset( $permissions_users[$user_data['userid']][$list_op]['view'] ) && $permissions_users[$user_data['userid']][$list_op]['view'] == 1 ) ? ' checked=checked' : '' );
			$xtpl->assign( 'checked_add', ( isset( $permissions_users[$user_data['userid']][$list_op]['add'] ) && $permissions_users[$user_data['userid']][$list_op]['add'] == 1 ) ? ' checked=checked' : '' );
			$xtpl->assign( 'checked_edit', ( isset( $permissions_users[$user_data['userid']][$list_op]['edit'] ) && $permissions_users[$user_data['userid']][$list_op]['edit'] == 1 ) ? ' checked=checked' : '' );
			$xtpl->assign( 'checked_del', ( isset( $permissions_users[$user_data['userid']][$list_op]['del'] ) && $permissions_users[$user_data['userid']][$list_op]['del'] == 1 ) ? ' checked=checked' : '' );
			$xtpl->assign( 'checked_order', ( isset( $permissions_users[$user_data['userid']][$list_op]['order'] ) && $permissions_users[$user_data['userid']][$list_op]['order'] == 1 ) ? ' checked=checked' : '' );
			$xtpl->assign( 'OP_PERMINSSION', $list_op );
			$xtpl->assign( 'LANG_OP', $lang_module[$list_op] );
			$xtpl->parse( 'main.user_permisson.list_op' );
		}
	}
	$xtpl->parse( 'main.user_permisson' );
}
*/
$array_post_data = array();

$sql = "SELECT group_id, addhistory FROM " . NV_PREFIXLANG . "_" . $module_data . "_config_mkt ORDER BY group_id ASC";
$result = $db->query( $sql );
while( list( $group_id, $addhistory ) = $result->fetch( 3 ) )
{
	if( isset( $groups_list[$group_id] ) )
	{
		$array_post_data[$group_id] = array( 'group_id' => $group_id, 'addhistory' => $addhistory );
	}
	else
	{
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_mkt WHERE group_id = ' . $group_id );
	}
}

foreach( $groups_list as $group_id => $group_title )
{
	if( ( isset( $array_post_data[$group_id] ) ) )
	{
		$addhistory = $array_post_data[$group_id]['addhistory'];
	}
	else
	{
		$addhistory = 0;
		$db->query( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_config_mkt (group_id,addhistory) VALUES ( '" . $group_id . "', '" . $addhistory . "' )" );
	}

	$xtpl->assign( 'ROW', array(
		'group_id' => $group_id,
		'group_title' => $group_title,
		'addhistory' => $addhistory ? ' checked="checked"' : '',
		) );

	$xtpl->parse( 'main.config_mkt' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['config'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
