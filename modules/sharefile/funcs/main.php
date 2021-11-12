<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUS.,JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUS.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:30
 */

if( ! defined( 'NV_IS_MOD_DOWNLOAD' ) ) die( 'Stop!!!' );

// Delete file
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$id = $nv_Request->get_int( 'id', 'post', 0 );

	$query = 'SELECT fileupload, title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id . ' AND user_id=' . $user_info['userid'];
	$row = $db->query( $query )->fetch();
	if( empty( $row ) ) die( 'NO' );

	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sharing WHERE fileid=' . $id );
	$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id );

	if( ! empty( $row['fileupload'] ) && file_exists( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['fileupload'] ) )
	{
		@unlink( NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['fileupload'] );
	}

	nv_del_moduleCache( $module_name );

	nv_insert_logs( NV_LANG_DATA, $module_data, $lang_module['download_filequeue_del'], $row['title'], $user_info['userid'] );
	die( 'OK' );
}
elseif( $nv_Request->isset_request( 'checkshare', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	if( $id == 0 )
	{
		exit( '' );
	}
	$array_share_user = array();
	$query = 'SELECT user_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sharing WHERE fileid=' . $id;
	$res = $db->query( $query );
	while( $row = $res->fetch() )
	{
		$array_share_user[$row['user_id']] = $row['user_id'];
	}
	echo json_encode( $array_share_user );
	exit;
}
elseif( $nv_Request->isset_request( 'sharing', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post', 0 );
	$recive_user = $nv_Request->get_title( 'recive_user', 'post', 0 );
	if( $id == 0 )
	{
		exit( 'NO_ID' );
	}
	if( ! empty( $recive_user ) )
	{
		$query = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id . ' AND user_id=' . $user_info['userid'];
		$data_file_share = $db->query( $query )->fetch();
		if( empty( $data_file_share ) ) die( 'NO_ID' );

		//check user da duoc chia se trc do
		$array_share_user = array();
		$query = 'SELECT user_id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sharing WHERE fileid=' . $id;
		$res = $db->query( $query );
		while( $row = $res->fetch() )
		{
			$array_share_user[$row['user_id']] = $row['user_id'];
		}

		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_sharing WHERE fileid=' . $id );

		$array_user = array();
		$recive_user = explode( ',', $recive_user );
		foreach( $recive_user as $userid )
		{
			if( $userid > 0 )
			{
				$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_sharing ( fileid, user_id, downloaded) VALUES (
    			 " . $id . ",
                 " . $userid . ",
    			 0)";

				if( $db->query( $sql ) )
				{
					$array_user[$userid] = $userid;
				}
			}
		}
        //loai bo cac mail da gui
        foreach( $array_share_user as $userid_old ){
            unset($array_user[$userid_old]);
        }
        
		if( ! empty( $array_user ) )
		{
			$db->sqlreset()->select( 'userid, username, email, full_name' )->from( NV_USERS_GLOBALTABLE )->where( 'userid IN(' . implode( ',', $array_user ) . ')' );
			$result = $db->query( $db->sql() );
			while( $view = $result->fetch() )
			{
				$view['full_name'] = ( empty( $view['full_name'] ) ) ? $view['username'] : $view['full_name'];
				$array_mail[] = $view['email'];
			}
			$from = array( $global_config['site_name'], $global_config['site_email'] );
			$array_mail = array_unique( $array_mail );
			$ftitle = sprintf( $lang_module['share_title_mail'], $user_info['username'] );
            $link_download = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=down/' . md5($data_file_share['id']) . $global_config['rewrite_exturl'], true );
			$fcontent = sprintf( $lang_module['share_content_mail'], $data_file_share['title'], $link_download );
			@nv_sendmail( $from, $array_mail, $ftitle, $fcontent );
		}
	}
	die( 'OK' );
}

$contents = '';

$download_config = nv_mod_down_config();

$today = mktime( 0, 0, 0, date( 'n' ), date( 'j' ), date( 'Y' ) );
$yesterday = $today - 86400;

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . '_' . $module_data )->where( 'user_id= ' . $user_info['userid'] . ' AND status=1 ' );

$num_items = $db->query( $db->sql() )->fetchColumn();
$array_item = $array_users = array();
if( $num_items )
{
	$db->select( 'id, title, alias , uploadtime, filesize, download_hits' );
	$db->order( 'uploadtime DESC' );

	$result = $db->query( $db->sql() );

	while( $row = $result->fetch() )
	{
		$uploadtime = ( int )$row['uploadtime'];
		if( $uploadtime >= $today )
		{
			$uploadtime = $lang_module['today'] . ', ' . date( 'H:i', $row['uploadtime'] );
		}
		elseif( $uploadtime >= $yesterday )
		{
			$uploadtime = $lang_module['yesterday'] . ', ' . date( 'H:i', $row['uploadtime'] );
		}
		else
		{
			$uploadtime = nv_date( 'd/m/Y H:i', $row['uploadtime'] );
		}

		$array_item[$row['id']] = array(
			'id' => ( int )$row['id'],
			'title' => $row['title'],
			'uploadtime' => $uploadtime,
			'filesize' => ! empty( $row['filesize'] ) ? nv_convertfromBytes( $row['filesize'] ) : 'N/A',
			'download_hits' => ( int )$row['download_hits'],
			'download_link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=down/' . $row['alias'] . '-' . $row['id'] . $global_config['rewrite_exturl'],
			'edit_link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=upload&amp;edit=1&amp;id=' . $row['id'],
			);
	}
	$array_users = array();
	$db->sqlreset()->select( 'userid, username, email, first_name, last_name' )->from( NV_USERS_GLOBALTABLE )->where( 'active=1 AND userid!=' . $user_info['userid'] );
	$result = $db->query( $db->sql() );
	while( $view = $result->fetch() )
	{
		$view['full_name'] = nv_show_name_user( $view['first_name'], $view['last_name'] );
		$array_users[$view['userid']] = $view;
	}
}
$contents = theme_main_download( $array_item, $array_users, $download_config );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
