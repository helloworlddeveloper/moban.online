<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 25 Dec 2014 02:13:32 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

if( $nv_Request->isset_request( 'delete_tag_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
	$tag_id = $nv_Request->get_int( 'delete_tag_id', 'get' );
	$delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
	if( $tag_id > 0 and $delete_checkss == md5( $tag_id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
	{
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tag  WHERE tag_id = ' . $db->quote( $tag_id ) );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}

$row = array();
$error = array();
$row['tag_id'] = $nv_Request->get_int( 'tag_id', 'post,get', 0 );
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$row['tag_name'] = $nv_Request->get_title( 'tag_name', 'post', '' );
	$row['tag_icon'] = $nv_Request->get_title( 'tag_icon', 'post', '' );
	if( !empty( $row['tag_icon'] ) && is_file( NV_DOCUMENT_ROOT . $row['tag_icon'] ) )
	{
		$row['tag_icon'] = substr( $row['tag_icon'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' ) );
	}
	else
	{
		$row['tag_icon'] = '';
	}

	if( empty( $row['tag_name'] ) )
	{
		$error[] = $lang_module['error_required_tag_name'];
	}
	if( empty( $error ) )
	{
		try
		{
			if( empty( $row['tag_id'] ) )
			{
				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tag (tag_name, tag_icon) VALUES (:tag_name, :tag_icon)' );
			}
			else
			{
				$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tag SET tag_name = :tag_name, tag_icon = :tag_icon WHERE tag_id=' . $row['tag_id'] );
			}
			$stmt->bindParam( ':tag_name', $row['tag_name'], PDO::PARAM_STR );
			$stmt->bindParam( ':tag_icon', $row['tag_icon'], PDO::PARAM_STR );

			$exc = $stmt->execute();
			if( $exc )
			{
				$nv_Cache->delMod($module_name);
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
				die();
			}
		}
		catch ( PDOException $e )
		{
			trigger_error( $e->getMessage() );
			die( $e->getMessage() ); //Remove this line after checks finished
		}
	}
}
elseif( $row['tag_id'] > 0 )
{
	$row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tag WHERE tag_id=' . $row['tag_id'] )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
		die();
	}
}
else
{
	$row['tag_id'] = 0;
	$row['tag_name'] = '';
	$row['tag_icon'] = '';
}
if( ! empty( $row['tag_icon'] ) and is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['tag_icon'] ) )
{
	$row['tag_icon'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['tag_icon'];
}

$q = $nv_Request->get_title( 'q', 'post,get' );

// Fetch Limit
$show_view = false;
if( ! $nv_Request->isset_request( 'id', 'post,get' ) )
{
	$show_view = true;
	$per_page = 5;
	$page = $nv_Request->get_int( 'page', 'post,get', 1 );
	$db->sqlreset()->select( 'COUNT(*)' )->from( '' . NV_PREFIXLANG . '_' . $module_data . '_tag' );

	if( ! empty( $q ) )
	{
		$db->where( 'tag_name LIKE :q_tag_name OR tag_icon LIKE :q_tag_icon' );
	}
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_tag_name', '%' . $q . '%' );
		$sth->bindValue( ':q_tag_icon', '%' . $q . '%' );
	}
	$sth->execute();
	$num_items = $sth->fetchColumn();

	$db->select( '*' )->order( 'tag_id DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
	$sth = $db->prepare( $db->sql() );

	if( ! empty( $q ) )
	{
		$sth->bindValue( ':q_tag_name', '%' . $q . '%' );
		$sth->bindValue( ':q_tag_icon', '%' . $q . '%' );
	}
	$sth->execute();
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
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'Q', $q );

if( $show_view )
{
	$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
	if( ! empty( $q ) )
	{
		$base_url .= '&q=' . $q;
	}
	$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );

	$number = 0;
	while( $view = $sth->fetch() )
	{
		$view['number'] = ++$number;
		$view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;tag_id=' . $view['tag_id'];
		$view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_tag_id=' . $view['tag_id'] . '&amp;delete_checkss=' . md5( $view['tag_id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
        if( ! empty( $view['tag_icon'] ) and is_file( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $view['tag_icon'] ) )
		{
			$xtpl->assign( 'tag_icon', NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $view['tag_icon'] );
			$xtpl->parse( 'main.view.loop.tag_icon' );
		}
		$xtpl->assign( 'VIEW', $view );
		$xtpl->parse( 'main.view.loop' );
	}
	$xtpl->parse( 'main.view' );
}

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}
if( empty( $row['tag_id'] ) )
{
	$xtpl->parse( 'main.auto_get_alias' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['tag'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
