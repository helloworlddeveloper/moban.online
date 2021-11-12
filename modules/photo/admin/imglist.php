<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
	die( 'Stop!!!' );

$per_page = 50;

$page = $nv_Request->get_int( 'page', 'get', 1 );
$did = $nv_Request->get_int( 'did', 'get', 1 );
$type = $nv_Request->get_string( 'type', 'get', 'image' );
$order = $nv_Request->get_int( 'order', 'get', 0 );

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;path=' . $path . '&amp;type=' . $type . '&amp;order=' . $order;

$check_like = false;

$db->sqlreset();

$_where = 'did = ' . $did . " AND type='image'";

$db->select( 'COUNT(*)' )->from( NV_UPLOAD_GLOBALTABLE . '_file' )->where( $_where );
$num_items = $db->query( $db->sql() )->fetchColumn();

$db->select( '*' );
if( $order == 1 )
{
	$db->order( 'mtime ASC' );
}
elseif( $order == 2 )
{
	$db->order( 'title ASC' );
}
else
{
	$db->order( 'mtime DESC' );
}

if( $num_items )
{
	$xtpl = new XTemplate( 'listimg.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

	$sth = $db->prepare( $db->sql() );
	
	$sth->execute();
	while( $file = $sth->fetch() )
	{
		$file['data'] = $file['sizes'];
		if( $file['type'] == 'image' or $file['ext'] == 'swf' )
		{
			$file['size'] = str_replace( '|', ' x ', $file['sizes'] ) . ' pixels';
		}
		else
		{
			$file['size'] = nv_convertfromBytes( $file['filesize'] );
		}

		$file['data'] .= '|' . $file['ext'] . '|' . $file['type'] . '|' . nv_convertfromBytes( $file['filesize'] ) . '|' . $file['userid'] . '|' . nv_date( 'l, d F Y, H:i:s P', $file['mtime'] ) . '|';
		$file['data'] .= ( empty( $q ) ) ? '' : $file['dirname'];
		$file['data'] .= '|' . $file['mtime'];

		$file['sel'] = in_array( $file['title'], $selectfile ) ? ' imgsel' : '';
		$file['src'] = NV_BASE_SITEURL . $file['src'] . '?' . $file['mtime'];

		$xtpl->assign( 'IMG', $file );
		$xtpl->parse( 'main.loopimg' );
	}

	if( $num_items > $per_page )
	{
		$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page, true, true, 'nv_urldecode_ajax', 'imglist' );
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}

exit();
