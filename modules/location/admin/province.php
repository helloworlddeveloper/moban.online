<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES., JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:33:22 AM 
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$mienList = nv_Mien();
$page_title = $lang_module['province'];

$link_province = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main\">" . $lang_module['listmien'] . " </a>";
$page_title = $link_province;

$post['idmien'] = $nv_Request->get_int( 'idmien', 'get' );

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE status=1 AND idmien= " . $post['idmien'] . " ORDER BY weight ASC";

$result = $db->query( $sql );
$districtList = array();
while( $row = $result->fetch() )
{
	$proviceList[$row['id']] = array( //
		'idmien' => $row['idmien'], //
		'code' => $row['code'], //
		'title' => $row['title'], //
		'alias' => $row['alias'], //
		'weight' => ( int )$row['weight'] //
			);
}

if( empty( $proviceList ) and ! $nv_Request->isset_request( 'add', 'get' ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=province&idmien=" . $post['idmien'] . "&add" );
	die();
}

if( $nv_Request->isset_request( 'cWeight, id', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post' );
	$cWeight = $nv_Request->get_int( 'cWeight', 'post' );
	if( ! isset( $proviceList[$id] ) ) die( "ERROR" );

	if( $cWeight > ( $count = count( $proviceList ) ) ) $cWeight = $count;

	$sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE id!=" . $id . " AND idmien=" . $post['idmien'] . " ORDER BY weight ASC";
	$result = $db->query( $sql );
	$weight = 0;
	while( $row = $result->fetch( ) )
	{
		$weight++;
		if( $weight == $cWeight ) $weight++;
		$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_province SET weight=" . $weight . " WHERE id=" . $row['id'];
		$db->query( $query );
	}
	$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_province SET weight=" . $cWeight . " WHERE id=" . $id;
	$db->query( $query );
	$nv_Cache->delMod($module_name);
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['logChangeWeight'], "Id: " . $id, $admin_info['userid'] );
	die( "OK" );
}

if( $nv_Request->isset_request( 'del', 'post' ) )
{
	$id = $nv_Request->get_int( 'del', 'post', 0 );
	if( ! isset( $proviceList[$id] ) ) die( $lang_module['errorCatNotExists'] );
	$sql = "SELECT COUNT(*) as count FROM " . NV_PREFIXLANG . "_" . $module_data . "_district WHERE idprovince=" . $id;
	$result = $db->query( $sql );
	$row = $result->fetch( 3 );
	if( $row['count'] ) die( $lang_module['errorCatYesRow'] );

	$query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE id = " . $id;
	$db->query( $query );
	$db->query( "DROP TABLE " . NV_PREFIXLANG . "_diaoc_def_" . $id . "" );
	$db->query( "DROP TABLE " . NV_PREFIXLANG . "_diaoc_vip_" . $id . "" );
	fix_catWeight( $post['idmien'] );
	$nv_Cache->delMod($module_name);
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['logDelCat'], "Id: " . $id, $admin_info['userid'] );
	die( "OK" );
}

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'tieude', $lang_module['province'] . $lang_module['thuoc'] . $mienList[$post['idmien']]['title'] );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE );
$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );

$xtpl->assign( 'add', $lang_module['addprovince'] . $lang_module['thuoc'] . $mienList[$post['idmien']]['title'] );
$xtpl->assign( 'op', 'province&idmien=' );
$xtpl->assign( 'op1', $post['idmien'] );

if( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
{
	$post = array();
	$post['idmien'] = $nv_Request->get_int( 'idmien', 'get' );
	if( $nv_Request->isset_request( 'edit', 'get' ) )
	{
		$post['id'] = $nv_Request->get_int( 'id', 'get' );

		$sql = "SELECT idmien FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE status=1 AND id=" . $post['id'];

		$result = $db->query( $sql );

		list( $post['idmien'] ) = $result->fetch( 3 );

		if( empty( $post['id'] ) or ! isset( $proviceList[$post['id']] ) )
		{

			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=province&idmien=" . $post['idmien'] );
			die();
		}

		$xtpl->assign( 'PTITLE', $lang_module['editprovince'] );
		$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=province&idmien=" . $post['idmien'] . "&edit&id=" . $post['id'] );
		$log_title = $lang_module['editprovince'];
	}
	else
	{
		$xtpl->assign( 'PTITLE', $lang_module['addprovince'] );
		$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=province&idmien=" . $post['idmien'] . "&add" );
		$log_title = $lang_module['addprovince'];

	}

	if( $nv_Request->isset_request( 'save', 'post' ) )
	{
		$query = '';
		$post['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
		$post['code'] = $nv_Request->get_title( 'code', 'post', '', 1 );
		$post['idmien'] = $nv_Request->get_int( 'pro', 'post' );
		$post['alias'] = $nv_Request->get_title( 'alias', 'post', '', 1 );
		if( empty( $post['title'] ) )
		{
			die( $lang_module['errorIsEmpty'] . ": " . $lang_module['title'] );
		}
		elseif( empty( $post['code'] ) )
		{
			die( $lang_module['errorIsEmpty'] . ": " . $lang_module['code'] );
		}

		$alias = empty( $post['alias'] ) ? change_alias( $post['title'] ) : change_alias( $post['alias'] );

		if( isset( $post['id'] ) )
		{
			$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_province SET
            		idmien= " . $post['idmien'] . ", 
            		code=" . $db->quote( $post['code'] ) . ", 
                    alias=" . $db->quote( $alias ) . ", 
                    title=" . $db->quote( $post['title'] ) . " WHERE id=" . $post['id'];
            
            $db->query( $query );
		}
		else
		{
			$sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_province WHERE status=1 AND idmien= " . $post['idmien'] . " ORDER BY weight ASC";
			$result = $db->query( $sql );
			$List = array();
			while( $row = $result->fetch() )
			{
				$List[$row['id']] = array( //
						'id' => $row['id'] //
						);
			}

			$weight = count( $List );
			$weight++;

			$query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_province VALUES (NULL, " . $db->quote( $post['code'] ) . "," . $post['idmien'] . "," . $db->quote( $post['title'] ) . "," . $db->quote( $alias ) . "," . $weight . ",1)";
			$db->query( $query );
		}
		if( $newcatid > 0 )
		{
			$nv_Cache->delMod($module_name);
			nv_insert_logs( NV_LANG_DATA, $module_name, $log_title, "Id: " . $post['id'], $admin_info['userid'] );
			die( "OK_" . $post['idmien'] );
		}
		else
		{
			die( $lang_module['error_add_pro'] );
		}
	}

	$post['title'] = ( $nv_Request->isset_request( 'edit', 'get' ) ) ? $proviceList[$post['id']]['title'] : "";
	$post['code'] = ( $nv_Request->isset_request( 'edit', 'get' ) ) ? $proviceList[$post['id']]['code'] : "";
	$post['alias'] = ( $nv_Request->isset_request( 'edit', 'get' ) ) ? $proviceList[$post['id']]['alias'] : "";

	$xtpl->assign( 'CAT', $post );

	if( $nv_Request->isset_request( 'edit', 'get' ) )
	{
		$xtpl->assign( 'mien', $mienList[$post['idmien']]['title'] );
		$xtpl->parse( 'action.mien.edit_mien' );
	}
	elseif( $nv_Request->isset_request( 'add', 'get' ) )
	{
		if( ! empty( $mienList ) )
		{
			foreach( $mienList as $k => $p )
			{
				$p['selected'] = ( $k == $post['idmien'] ) ? 'selected="selected"' : '';
				$p['id'] = $k;
				$xtpl->assign( 'NEWWEIGHT', $p );
				$xtpl->parse( 'action.mien.add_mien.option' );
			}
			$xtpl->parse( 'action.mien.add_mien' );
		}
	}
	$xtpl->parse( 'action.mien' );
	$xtpl->parse( 'action' );
	$contents = $xtpl->text( 'action' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit;
}

if( $nv_Request->isset_request( 'list', 'get' ) )
{
	$a = 0;

	$count = count( $proviceList );
	foreach( $proviceList as $id => $values )
	{
		$values['id'] = $id;
		$values['alink1'] = "<a href=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=district&idprovince=" . $id . ">";
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
	exit;
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>