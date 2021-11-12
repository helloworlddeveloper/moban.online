<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:33:22 AM 
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$mienList = nv_Mien();
$page_title = $lang_module['main'];

if( empty( $mienList ) and ! $nv_Request->isset_request( 'add', 'get' ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main&add" );
	die();
}

if( $nv_Request->isset_request( 'cWeight, id', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post' );
	$cWeight = $nv_Request->get_int( 'cWeight', 'post' );
	if( ! isset( $mienList[$id] ) ) die( "ERROR" );

	if( $cWeight > ( $count = count( $mienList ) ) ) $cWeight = $count;

	$sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_mien WHERE id!=" . $id . " ORDER BY weight ASC";
	$result = $db->query( $sql );
	$weight = 0;
	while( $row = $result->fetch() )
	{
		$weight++;
		if( $weight == $cWeight ) $weight++;
		$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_mien SET weight=" . $weight . " WHERE id=" . $row['id'];
		$db->query( $query );
	}
	$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_mien SET weight=" . $cWeight . " WHERE id=" . $id;
	$db->query( $query );
	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['logChangeWeight'], "Id: " . $id, $admin_info['userid'] );
	die( "OK" );
}

if( $nv_Request->isset_request( 'del', 'post' ) )
{
	$id = $nv_Request->get_int( 'del', 'post', 0 );
	if( ! isset( $mienList[$id] ) ) die( $lang_module['errorCatNotExists'] );
	$sql = "SELECT COUNT(*) as count FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE idmien=" . $id;
	$result = $db->query( $sql );
	$row = $result->fetch( 3 );
	if( $row['count'] ) die( $lang_module['errorProvinceYesRow'] );

	$query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_mien WHERE id = " . $id;
	$db->query( $query );
	fix_mienWeight();
	nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['logDelCat'], "Id: " . $id, $admin_info['userid'] );
	die( "OK" );
}

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'tieude', $lang_module['mien'] );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE );
$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );

$xtpl->assign( 'add', $lang_module['addmien'] );
$xtpl->assign( 'op', 'main' );

if( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
{
	$post = array();
	if( $nv_Request->isset_request( 'edit', 'get' ) )
	{
		$post['id'] = $nv_Request->get_int( 'id', 'get' );
		if( empty( $post['id'] ) or ! isset( $mienList[$post['id']] ) )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main" );
			die();
		}

		$xtpl->assign( 'PTITLE', $lang_module['editmien'] );
		$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main&edit&id=" . $post['id'] );
		$log_title = $lang_module['editprovince'];
	}
	else
	{
		$xtpl->assign( 'PTITLE', $lang_module['addmien'] );
		$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main&add" );
		$log_title = $lang_module['addmien'];
	}

	if( $nv_Request->isset_request( 'save', 'post' ) )
	{
		$query = '';
		$post['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
		$post['alias'] = $nv_Request->get_title( 'alias', 'post', '', 1 );

		if( empty( $post['title'] ) )
		{
			die( $lang_module['errorIsEmpty'] . ": " . $lang_module['title'] );
		}

		$alias = empty( $post['alias'] ) ? change_alias( $post['title'] ) : change_alias( $post['alias'] );

		if( isset( $post['id'] ) )
		{
			$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_mien SET 
                    alias=" . $db->dbescape( $alias ) . ", 
                    title=" . $db->dbescape( $post['title'] ) . " WHERE id=" . $post['id'];

		}
		else
		{
			$weight = count( $mienList );
			$weight++;

			$query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_mien VALUES (NULL, " . $db->dbescape( $post['title'] ) . "," . $db->dbescape( $alias ) . "," . $weight . ",1)";

		}
		if( $db->query( $query ) )
		{
			nv_del_moduleCache( $module_name );
			nv_insert_logs( NV_LANG_DATA, $module_name, $log_title, "Id: " . $post['id'], $admin_info['userid'] );
			die( "OK" );
		}
		else
		{
			die( $lang_module['error_add'] );
		}
	}

	$post['title'] = ( $nv_Request->isset_request( 'edit', 'get' ) ) ? $mienList[$post['id']]['title'] : "";
	$post['alias'] = ( $nv_Request->isset_request( 'edit', 'get' ) ) ? $mienList[$post['id']]['alias'] : "";

	$xtpl->assign( 'CAT', $post );
	$xtpl->parse( 'action' );
	$contents = $xtpl->text( 'action' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

if( $nv_Request->isset_request( 'list', 'get' ) )
{
	$a = 0;
	$count = count( $mienList );
	foreach( $mienList as $id => $values )
	{
		$values['id'] = $id;
		$values['alink1'] = "<a href=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=province&idmien=" . $id . ">";
		$values['alink2'] = "</a>";
		$xtpl->assign( 'LOOP', $values );
		$xtpl->assign( 'CLASS', $a % 2 ? " class=\"second\"" : "" );

		for( $i = 1; $i <= $count; $i++ )
		{
			$opt = array( 'value' => $i, 'selected' => $i == $values['weight'] ? " selected=\"selected\"" : "" );
			$xtpl->assign( 'NEWWEIGHT', $opt );
			$xtpl->parse( 'list.loop.option' );
		}
		$xtpl->parse( 'list.loop' );
		$a++;
	}
	$xtpl->parse( 'list' );
	$xtpl->out( 'list' );
	exit();
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>