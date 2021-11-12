<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUS.,JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUS.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/9/2010, 22:27
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['file_addfile'];

$array = array();
$is_error = false;
$error = $array['filesize'] = '';

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
	$array['link_file'] = $nv_Request->get_title( 'link_file', 'post', '' );
    $array['fileupload'] = $nv_Request->get_title( 'fileupload', 'post', '' );
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
	elseif( empty( $array['fileupload'] ) )
	{
		$is_error = true;
		$error = $lang_module['file_error_fileupload'];
	}
	else
	{
		$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . " ( title, alias, uploadtime, updatetime, user_id, user_name, link_file, fileupload, filesize, status, download_hits) VALUES (
			 :title,
			 :alias ,
			 " . NV_CURRENTTIME . ",
			 " . NV_CURRENTTIME . ",
			 " . $admin_info['admin_id'] . ",
			 :username,
			 :link_file,
			 :fileupload ,
			 " . $array['filesize'] . ",
			 1,
			 0)";

		$data_insert = array();
		$data_insert['title'] = $array['title'];
		$data_insert['alias'] = $alias;
		$data_insert['username'] = $admin_info['username'];
        $data_insert['link_file'] = $admin_info['link_file'];
		$data_insert['fileupload'] = $array['fileupload'];

		if( ! $db->insert_id( $sql, 'id', $data_insert ) )
		{
			$is_error = true;
			$error = $lang_module['file_error2'];
		}
		else
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['file_addfile'], $array['title'], $admin_info['userid'] );
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
			exit();
		}
	}
}
else
{
	$array['title'] = $array['fileupload'] = '';
	$array['filesize'] = 0;
}

if( ! empty( $array['fileupload'] ) && file_exists( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array['fileupload'] ) )
{
	$array['fileupload'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array['fileupload'];
}

$array['id'] = 0;

$sql = "SELECT config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config WHERE config_name='upload_dir'";
$result = $db->query( $sql );
$upload_dir = $result->fetchColumn();

if( ! $array['filesize'] ) $array['filesize'] = '';

$xtpl = new XTemplate( 'content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );

$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=add' );

$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
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
