<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 25 Dec 2014 02:10:12 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'act', 'post' ) )
{
	$reg_id = $nv_Request->get_int( 'id', 'post' );
	$status = $nv_Request->get_int( 'status', 'post' );
	if( $reg_id > 0 )
	{
		$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_reg SET status=' . $status . ' WHERE reg_id = ' . $db->quote( $reg_id ) );
	}
	exit( 'OK' );
}
if( $nv_Request->isset_request( 'delete_reg_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
	$id = $nv_Request->get_int( 'delete_reg_id', 'get', 0 );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_reg WHERE reg_id = ' . $id );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}

$q = $nv_Request->get_title( 'q', 'post,get' );

$per_page = 15;
$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$db->sqlreset()->select( 'COUNT(*)' )->from( '' . NV_PREFIXLANG . '_' . $module_data . '_reg AS t1 LEFT JOIN ' . $db_config['prefix'] . '_regsite AS t2 ON t1.siterefer=t2.id');


if( ! empty( $q ) )
{
	$db->where( 't1.siterefer=0 AND (reg_full_name LIKE :q_reg_full_name OR reg_email LIKE :q_reg_email OR reg_phone LIKE :q_reg_phone OR reg_address LIKE :q_reg_address OR parent_name LIKE :q_parent_name OR parent_phone LIKE :q_parent_phone)' );
}else{
    $db->where('t1.siterefer=0');
}

$sth = $db->prepare( $db->sql() );

if( ! empty( $q ) )
{
	$sth->bindValue( ':q_reg_full_name', '%' . $q . '%' );
	$sth->bindValue( ':q_reg_email', '%' . $q . '%' );
	$sth->bindValue( ':q_reg_phone', '%' . $q . '%' );
	$sth->bindValue( ':q_reg_address', '%' . $q . '%' );
	$sth->bindValue( ':q_parent_name', '%' . $q . '%' );
	$sth->bindValue( ':q_parent_phone', '%' . $q . '%' );
}
$sth->execute();
$num_items = $sth->fetchColumn();

$db->select( 't1.*, t2.domain' )->order( 'add_time DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
$sth = $db->prepare( $db->sql() );

if( ! empty( $q ) )
{
	$sth->bindValue( ':q_reg_full_name', '%' . $q . '%' );
	$sth->bindValue( ':q_reg_email', '%' . $q . '%' );
	$sth->bindValue( ':q_reg_phone', '%' . $q . '%' );
	$sth->bindValue( ':q_reg_address', '%' . $q . '%' );
	$sth->bindValue( ':q_parent_name', '%' . $q . '%' );
	$sth->bindValue( ':q_parent_phone', '%' . $q . '%' );
}
$sth->execute();

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'Q', $q );

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
if( ! empty( $q ) )
{
	$base_url .= '&q=' . $q;
}
$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
$array_reg_status = array(
	'0' => $lang_module['reg_status_0'],
	'1' => $lang_module['reg_status_1'],
	'3' => $lang_module['reg_status_3'],
	'2' => $lang_module['reg_status_2'],
    '4' => $lang_module['reg_status_4'],
	);
while( $view = $sth->fetch() )
{
	$view['add_time'] = nv_date( 'H:i - d/m/Y', $view['add_time'] );
	$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reg&amp;reg_id=' . $view['reg_id'];
	$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_reg_id=' . $view['reg_id'] . '&amp;delete_checkss=' . md5( $view['reg_id'] . NV_CACHE_PREFIX . $client_info['session_id'] );

	foreach( $array_reg_status as $key => $val )
	{
		$sl = ( $key == $view['status'] ) ? ' selected=selected' : '';
		$xtpl->assign( 'STATUS', array(
			'key' => $key,
			'val' => $val,
			'sl' => $sl,
			) );
		$xtpl->parse( 'main.loop.status' );
	}
	$xtpl->assign( 'VIEW', $view );
	$xtpl->parse( 'main.loop' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['main'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
