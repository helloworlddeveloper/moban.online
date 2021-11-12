<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUS.,JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUS.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:30
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

$page_title = $lang_module['upload'];

$download_config = nv_mod_down_config();

if( ! $download_config['is_addfile_allow'] )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
	exit();
}

if( $nv_Request->isset_request( 'uploadfile', 'get' ) )
{
	if( $download_config['is_upload_allow'] )
	{
		$is_error = 1;
		if( isset( $_FILES['upload_fileupload'] ) and is_uploaded_file( $_FILES['upload_fileupload']['tmp_name'] ) )
		{
			require_once NV_ROOTDIR . '/includes/class/upload.class.php';
			$upload = new upload( $global_config['file_allowed_ext'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], $download_config['maxfilesize'] );
			$upload_info = $upload->save_file( $_FILES['upload_fileupload'], NV_UPLOADS_REAL_DIR . '/' . $module_name, false );

			@unlink( $_FILES['upload_fileupload']['tmp_name'] );

			if( empty( $upload_info['error'] ) )
			{
				if( in_array( $upload_info['ext'], $download_config['upload_filetype'] ) )
				{
					mt_srand( ( double )microtime() * 1000000 );
					$maxran = 1000000;
					$random_num = mt_rand( 0, $maxran );
					$random_num = md5( $random_num );

					$nv_pathinfo_filename = nv_pathinfo_filename( $upload_info['name'] );
					$new_name = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $nv_pathinfo_filename . '.' . $random_num . '.' . $upload_info['ext'];

					@chmod( $fileupload, 0644 );
					$fileupload = str_replace( NV_ROOTDIR . '/' . NV_UPLOADS_DIR, '', $fileupload );
					$array['filesize'] = $upload_info['size'];
				}
				else
				{
					@nv_deletefile( $upload_info['name'] );
					$is_error = 0;
					$error = $lang_module['upload_error4'];
				}
			}
			else
			{
				$is_error = 0;
				$error = $upload_info['error'];
			}
			$array_message = array(
				'status' => $is_error,
				'message' => $error,
				'filename' => $upload_info['basename'] );
			if( $is_error == 1 )
			{
				$fileshare = $nv_Request->get_title( $module_name . '_fileshare', 'session', '' );
				if( ! empty( $fileshare ) )
				{
					@unlink( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $fileshare );
				}
				$nv_Request->set_Session( $module_name . '_fileshare', $upload_info['basename'] );
			}
			echo json_encode( $array_message );
			exit;
			unset( $upload, $upload_info );
		}
	}
}
$error = '';
$fileshare = $nv_Request->get_title( $module_name . '_fileshare', 'session', '' );

if( $nv_Request->isset_request( 'addfile', 'post' ) )
{

	$addfile = $nv_Request->get_string( 'addfile', 'post', '' );

	if( empty( $addfile ) or $addfile != md5( $client_info['session_id'] ) )
	{
		Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, true ) );
		exit();
	}

	$array = array();

	$array['id'] = $nv_Request->get_int( 'id', 'post', 0 );
	$array['title'] = nv_substr( $nv_Request->get_title( 'upload_title', 'post', '', 1 ), 0, 255 );
    $array['link_file'] = nv_substr( $nv_Request->get_title( 'link_file', 'post', '', 1 ), 0, 255 );
	$array['filename_uploaded'] = $nv_Request->get_title( 'filename_uploaded', 'post', '', 1 );
	$array['user_id'] = 0;

	if( defined( 'NV_IS_USER' ) )
	{
		$array['user_name'] = $user_info['username'];
		$array['user_id'] = $user_info['userid'];
	}

	if( empty( $array['filename_uploaded'] ) )
	{
		$is_error = true;
		$error = $lang_module['file_error_fileupload'];
	}
	else
	{
		if( empty( $array['title'] ) )
		{
			$array['title'] = $array['filename_uploaded'];
		}
        $array['alias'] = change_alias( $array['title'] );
		$array['filesize'] = 0;
		if( file_exists( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array['filename_uploaded'] ) and ( $filesize = filesize( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array['filename_uploaded'] ) ) != 0 )
		{
			$array['filesize'] += $filesize;
		}
		if( $array['id'] == 0 )
		{
			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' ( title, alias, uploadtime, user_id, user_name, link_file, fileupload, filesize, status) VALUES (
				 :title,
                 :alias,
				 ' . NV_CURRENTTIME . ',
				 ' . $array['user_id'] . ',
				 :user_name,
				 :link_file,
				 :fileupload,
				 ' . $array['filesize'] . ', 1)';

			$data_insert = array();
			$data_insert['title'] = $array['title'];
			$data_insert['alias'] = $array['alias'];
			$data_insert['user_name'] = $array['user_name'];
            $data_insert['link_file'] = $array['link_file'];
			$data_insert['fileupload'] = $array['filename_uploaded'];

			if( ! $db->insert_id( $sql, 'id', $data_insert ) )
			{
				$is_error = true;
				$error = $lang_module['upload_error3'];
			}
			else
			{
				$nv_Request->set_Session( $module_name . '_fileshare', '' );
				$contents = "<div class=\"info_exit\">" . $lang_module['file_upload_ok'] . "</div>";
				$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . "\" />";

				$user_post = defined( "NV_IS_USER" ) ? " | " . $user_info['username'] : "";
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['upload_files_log'], $array['title'] . " | " . $client_info['ip'] . $user_post, 0 );

				include NV_ROOTDIR . '/includes/header.php';
				echo nv_site_theme( $contents );
				include NV_ROOTDIR . '/includes/footer.php';
				exit();
			}
		}
		else
		{
			$query = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $array['id'] . ' AND user_id=' . $user_info['userid'];
			$array_old = $db->query( $query )->fetch();
			if( empty( $array_old ) )
			{
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
				exit();
			}
			//xoa file cu
			if( $array_old['fileupload'] != $array['filename_uploaded'] )
			{
				@unlink( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_old['fileupload'] );
			}
			$stmt = $db->prepare( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET
				 title= :title,
				 alias= :alias,
				 updatetime=" . NV_CURRENTTIME . ",
				 fileupload= :fileupload,
				 link_file=:link_file,
				 filesize=" . $array['filesize'] . "
				 WHERE id=" . $array['id'] );

			$stmt->bindParam( ':title', $array['title'], PDO::PARAM_STR );
			$stmt->bindParam( ':alias', $array['alias'], PDO::PARAM_STR );
            $stmt->bindParam( ':link_file', $array['link_file'], PDO::PARAM_STR );
			$stmt->bindParam( ':fileupload', $array['filename_uploaded'], PDO::PARAM_STR, strlen( $array['filename_uploaded'] ) );

			if( ! $stmt->execute() )
			{
				$is_error = true;
				$error = $lang_module['file_error1'];
			}
			else
			{
				$nv_Request->set_Session( $module_name . '_fileshare', '' );
				$contents = "<div class=\"info_exit\">" . $lang_module['file_update_ok'] . "</div>";
				$contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . "\" />";

				include NV_ROOTDIR . '/includes/header.php';
				echo nv_site_theme( $contents );
				include NV_ROOTDIR . '/includes/footer.php';
				exit();
			}
		}

	}
}
elseif( $nv_Request->isset_request( 'edit', 'get' ) )
{
	$id = $nv_Request->get_int( 'id', 'get' );
	if( $id > 0 )
	{
		$query = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
		$array = $db->query( $query )->fetch();
		if( empty( $array ) )
		{
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
			exit();
		}
		$fileshare = $array['fileupload'];
	}
}
else
{
	$array['filesize'] = 0;
	$array['title'] = $array['description'] = $array['introtext'] = $array['author_name'] = $array['author_email'] = $array['author_url'] = $array['linkdirect'] = $array['version'] = $array['copyright'] = $array['user_name'] = '';
	if( defined( 'NV_IS_USER' ) )
	{
		$array['user_name'] = $user_info['username'];
		$array['user_id'] = $user_info['userid'];
	}
}

$array['disabled'] = '';
if( defined( 'NV_IS_USER' ) )
{
	$array['disabled'] = ' disabled="disabled"';
}
$array['addfile'] = md5( $client_info['session_id'] );

$contents = theme_upload( $array, $download_config, $fileshare, $error );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
