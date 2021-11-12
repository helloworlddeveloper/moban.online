<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Dec 3, 2010 11:33:22 AM
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$groups_list = nv_groups_list();
$error = '';

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_config['is_cus'] = $nv_Request->get_int( 'is_cus', 'post', 0 );
	$array_config['is_admin'] = $nv_Request->get_int( 'is_admin', 'post', 0 );
	$array_config['is_email'] = nv_substr( $nv_Request->get_title( 'is_email', 'post', '', 1 ), 0, 255 );
	$array_config['mark_question'] = $nv_Request->get_int( 'mark_question', 'post', 0 );
	$array_config['is_mark'] = $nv_Request->get_int( 'is_mark', 'post', 0 );
	$array_config['duyetqu'] = $nv_Request->get_int( 'duyetqu', 'post', 0 );
	$array_config['duyetan'] = $nv_Request->get_int( 'duyetan', 'post', 0 );
	$array_config['is_captcha'] = $nv_Request->get_int( 'is_captcha', 'post', 0 );
	$array_config['length_home'] = $nv_Request->get_int( 'length_home', 'post', 450 );
	$array_config['home_cat'] = $nv_Request->get_int( 'home_cat', 'post', 0 );
	$array_config['num_row_list'] = $nv_Request->get_int( 'num_row_list', 'post', 10 );
	$array_config['mark_start'] = $nv_Request->get_int( 'mark_start', 'post', 0 );
	$array_config['mark_an_add'] = $nv_Request->get_int( 'mark_an_add', 'post', 0 );
	$array_config['mark_an_cho'] = $nv_Request->get_int( 'mark_an_cho', 'post', 0 );
	$array_config['mark_an_most'] = $nv_Request->get_int( 'mark_an_most', 'post', 0 );

	$_who_post = $nv_Request->get_array( 'who_view', 'post', array() );
	$array_config['who_view'] = ! empty( $_who_post ) ? implode( ',', nv_groups_post( array_intersect( $_who_post, array_keys( $groups_list ) ) ) ) : '';

	$_who_an = $nv_Request->get_array( 'who_an', 'post', array() );
	$array_config['who_an'] = ! empty( $_who_an ) ? implode( ',', nv_groups_post( array_intersect( $_who_an, array_keys( $groups_list ) ) ) ) : '';

	if( $array_config['is_admin'] == 1 )
	{
		$check_valid_email = nv_check_valid_email( $array_config['is_email'] );
		if( ! empty( $check_valid_email ) )
		{
			$error = $check_valid_email;
		}
	}

	if( $error == '' )
	{
		if( $array_config['is_mark'] == 1 )
		{
			if( $array_config['mark_start'] <= 0 )
			{
				$error = $lang_module['error_start_mark'];
			}
			elseif( $array_config['mark_question'] <= 0 )
			{
				$error = $lang_module['error_mark'];
			}
			elseif( $array_config['mark_an_add'] <= 0 )
			{
				$error = $lang_module['error_mark_an'];
			}
			elseif( $array_config['mark_an_cho'] <= 0 )
			{
				$error = $lang_module['error_an_cho'];
			}
			elseif( $array_config['mark_an_most'] <= 0 )
			{
				$error = $lang_module['error_mark_most'];
			}
		}
	}

	if( $error == '' )
	{
		foreach( $array_config as $config_name => $config_value )
		{
			$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_config SET config_value=" . $db->quote( $config_value ) . " WHERE config_name=" . $db->quote( $config_name );
   
			$db->query( $query );
		}
		$nv_Cache->delMod($module_name);

		Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		die();
	}
}

$sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
$result = $db->query( $sql );
while( list( $c_config_name, $c_config_value ) = $result->fetch( 3 ) )
{
	$array_config[$c_config_name] = $c_config_value;
}

$array_config['is_cus'] = ! empty( $array_config['is_cus'] ) ? " checked=\"checked\"" : "";
$array_config['is_admin'] = ! empty( $array_config['is_admin'] ) ? " checked=\"checked\"" : "";
$array_config['is_mark'] = ! empty( $array_config['is_mark'] ) ? " checked=\"checked\"" : "";
$array_config['duyetan'] = ! empty( $array_config['duyetan'] ) ? " checked=\"checked\"" : "";
$array_config['duyetqu'] = ! empty( $array_config['duyetqu'] ) ? " checked=\"checked\"" : "";
$array_config['home_cat'] = ! empty( $array_config['home_cat'] ) ? " checked=\"checked\"" : "";

$who_view = explode( ',', $array_config['who_view'] );
$array['who_view'] = array();
foreach( $groups_list as $key => $title )
{
	$array['who_view'][] = array(
		'key' => $key,
		'title' => $title,
		'checked' => in_array( $key, $who_view ) ? ' checked="checked"' : ''
	);
}

$who_an = explode( ',', $array_config['who_an'] );
$array['who_an'] = array();
foreach( $groups_list as $key => $title )
{
	$array['who_an'][] = array(
		'key' => $key,
		'title' => $title,
		'checked' => in_array( $key, $who_an ) ? ' checked="checked"' : ''
	);
}

$xtpl = new XTemplate( "config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array_config );

foreach( $array['who_view'] as $group )
{
	$xtpl->assign( 'WHO_VIEW', $group );
	$xtpl->parse( 'main.who_view' );
}

foreach( $array['who_an'] as $group )
{
	$xtpl->assign( 'WHO_AN', $group );
	$xtpl->parse( 'main.who_an' );
}

$is_captcha = array(
	'0' =>  $lang_module['captcha_none'],
	'1' =>  $lang_module['captcha_question'],
	'2' =>  $lang_module['captcha_answer'],
	'3' =>  $lang_module['captcha_all'],
);

foreach( $is_captcha as $key => $title )
{
	$xtpl->assign( 'IS_CAPTCHA', array( 'id' => $key, 'title' => $title, 'selected' => $key == $array_config['is_captcha'] ? 'selected="sellected"' : '' ) );
	$xtpl->parse( 'main.is_captcha' );
}

if( $error != "" )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['config'];

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );