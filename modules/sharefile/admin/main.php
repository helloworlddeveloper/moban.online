<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUS.,JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUS.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

// Edit file
if( $nv_Request->isset_request( 'edit', 'get' ) )
{
	$report = $nv_Request->isset_request( 'report', 'get' );

	$id = $nv_Request->get_int( 'id', 'get', 0 );

	$query = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( empty( $row ) )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		exit();
	}

	define( 'IS_EDIT', true );
	$page_title = $lang_module['download_editfile'];

	$array = array();
	$is_error = false;
	$error = '';

	if( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$array['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
		$array['introtext'] = $nv_Request->get_textarea( 'introtext', '', NV_ALLOWED_HTML_TAGS );
		$array['fileupload'] = $nv_Request->get_title( 'fileupload', 'post', '' );
        $array['link_file'] = $nv_Request->get_title( 'link_file', 'post', '' );

		$array['filesize'] = 0;
		if( ! empty( $array['fileupload'] ) && ! preg_match( '#^(http|https|ftp|gopher)\:\/\/#', $array['fileupload'] ) )
		{
			$array['fileupload'] = substr( $array['fileupload'], strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' ) );
		}
		if( file_exists( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array['fileupload'] ) and ( $filesize = filesize( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array['fileupload'] ) ) != 0 )
		{
			$array['filesize'] = $filesize;
		}
		else
		{
			$array['fileupload'] = '';
		}

		$alias = change_alias( $array['title'] );

		if( empty( $array['title'] ) )
		{
			$is_error = true;
			$error = $lang_module['file_error_title'];
		}
		elseif( empty( $array['fileupload'] ) and empty( $array['linkdirect'] ) )
		{
			$is_error = true;
			$error = $lang_module['file_error_fileupload'];
		}
		else
		{
			$stmt = $db->prepare( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET
				 title= :title,
				 alias= :alias,
				 updatetime=" . NV_CURRENTTIME . ",
				 fileupload= :fileupload,
				 link_file=:link_file,
				 filesize=" . $array['filesize'] . "
				 WHERE id=" . $id );

			$stmt->bindParam( ':title', $array['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
            $stmt->bindParam( ':link_file', $array['link_file'], PDO::PARAM_STR );
			$stmt->bindParam( ':fileupload', $array['fileupload'], PDO::PARAM_STR, strlen( $array['fileupload'] ) );

			if( ! $stmt->execute() )
			{
				$is_error = true;
				$error = $lang_module['file_error1'];
			}
			else
			{
                $nv_Cache->delMod($module_name);
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['download_editfile'], $array['title'], $admin_info['userid'] );
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
				exit();
			}
		}
	}
	else
	{
		$array['title'] = $row['title'];
		$array['fileupload'] = $row['fileupload'];
		$array['filesize'] = ( int )$row['filesize'];
        $array['link_file'] = $row['link_file'];
	}
	//Rebuild fileupload
	if( ! empty( $array['fileupload'] ) && file_exists( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array['fileupload'] ) )
	{
		$array['fileupload'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array['fileupload'];
	}

	$array['id'] = $id;

	$sql = "SELECT config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config WHERE config_name='upload_dir'";
	$result = $db->query( $sql );
	$upload_dir = $result->fetchColumn();

	if( empty( $array['filesize'] ) )
	{
		$array['filesize'] = '';
	}
	else
	{
		$array['filesize'] = number_format( $array['filesize'] / 1048576, 2 );
	}

	$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );

	$report = $report ? '&amp;report=1' : '';
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;edit=1&amp;id=' . $id . $report );

	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $array );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'IMG_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/images' );
	$xtpl->assign( 'FILES_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/' . $upload_dir );

	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

// Avtive - Deactive
if( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$query = 'SELECT status FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( empty( $row ) ) die( 'NO' );

	$status = $row['status'] ? 0 : 1;

	$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET status=' . $status . ' WHERE id=' . $id );

	nv_del_moduleCache( $module_name );
	die( 'OK' );
}

// Delete file
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$query = 'SELECT fileupload, fileimage, title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
	$row = $db->query( $query )->fetch();
	if( empty( $row ) ) die( 'NO' );

	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sharing WHERE fileid=' . $id );
	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id );

	if( ! empty( $row['fileupload'] ) && file_exists( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['fileupload'] ) )
	{
		@unlink( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['fileupload'] );
	}

	nv_del_moduleCache( $module_name );

	nv_insert_logs( NV_LANG_DATA, $module_data, $lang_module['download_filequeue_del'], $row['title'], $admin_info['userid'] );
	die( 'OK' );
}

// List file
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_' . $module_data );

$page_title = $lang_module['download_filemanager'];

$num_items = $db->query( $db->sql() )->fetchColumn();

if( empty( $num_items ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=add' );
	exit();
}

$page = $nv_Request->get_int( 'page', 'get', 1 );
$per_page = 30;

$db->select( '*' )->order( 'uploadtime DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result2 = $db->query( $db->sql() );

$array = array();

while( $row = $result2->fetch() )
{
	$array[$row['id']] = array(
		'id' => $row['id'],
		'title' => $row['title'],
		'uploadtime' => nv_date( 'd/m/Y H:i', $row['uploadtime'] ),
		'status' => $row['status'] ? ' checked="checked"' : '',
		'download_hits' => $row['download_hits'] );
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$xtpl = new XTemplate( 'main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'ADD_NEW_FILE', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add' );

if( ! empty( $array ) )
{
	foreach( $array as $row )
	{
		$xtpl->assign( 'ROW', $row );
		$xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;edit=1&amp;id=' . $row['id'] );
		$xtpl->parse( 'main.row' );
	}
}

if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
