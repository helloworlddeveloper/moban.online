<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 14 Apr 2011 12:01:30 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$parentid = $nv_Request->get_int('parentid', 'get', 0);
$catList = nv_catList('');
$page_title = $lang_module['cat'];
$contents = "";

if( empty( $catList ) and ! $nv_Request->isset_request( 'add', 'get' ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add" );
	die();
}

if( $nv_Request->isset_request( 'cWeight, id', 'post' ) )
{
	$id = $nv_Request->get_int( 'id', 'post' );
	$cWeight = $nv_Request->get_int( 'cWeight', 'post' );
	if( ! isset( $catList[$id] ) ) die( "ERROR" );

	if( $cWeight > ( $count = count( $catList ) ) ) $cWeight = $count;

	$sql = "SELECT id FROM " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat WHERE id!=" . $id . " ORDER BY weight ASC";
	$result = $db->query( $sql );
	$weight = 0;
	while( $row = $result->fetch() )
	{
		$weight++;
		if( $weight == $cWeight ) $weight++;
		$query = "UPDATE " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat SET weight=" . $weight . " WHERE id=" . $row['id'];
		$db->query( $query );
	}
	$query = "UPDATE " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat SET weight=" . $cWeight . " WHERE id=" . $id;
	$db->query( $query );
	$nv_Cache->delMod($module_name);
    nv_fix_cat_order();
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['logChangeWeight'], "Id: " . $id, $admin_info['userid'] );
	die( 'OK' );
}

if( $nv_Request->isset_request( 'del', 'post' ) )
{
	$id = $nv_Request->get_int( 'del', 'post', 0 );
	if( ! isset( $catList[$id] ) ) die( $lang_module['errorCatNotExists'] );
	$sql = "SELECT COUNT(*) as count FROM " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . " WHERE catid=" . $id;
	$result = $db->query( $sql );
	$row = $result->fetch();
	if( $row['count'] ) die( $lang_module['errorCatYesRow'] );

	$query = "DELETE FROM " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat WHERE id = " . $id;
	$db->query( $query );
    nv_fix_cat_order();
	$nv_Cache->delMod($module_name);
	nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['logDelCat'], "Id: " . $id, $admin_info['userid'] );
	die( 'OK' );
}

$xtpl = new XTemplate( $op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'MODULE_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE );
$xtpl->assign( 'UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name );
$xtpl->assign( 'parentid', $parentid );

if( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
{
	$post = array();
	if( $nv_Request->isset_request( 'edit', 'get' ) )
	{
		$post['id'] = $nv_Request->get_int( 'id', 'get' );
		if( empty( $post['id'] ) or ! isset( $catList[$post['id']] ) )
		{
			Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat" );
			die();
		}

		$xtpl->assign( 'PTITLE', $lang_module['editCat'] );
		$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&edit&id=" . $post['id'] );
		$log_title = $lang_module['editCat'];
	}
	else
	{
		$xtpl->assign( 'PTITLE', $lang_module['addCat'] );
		$xtpl->assign( 'ACTION_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add" );
		$log_title = $lang_module['addCat'];
	}

	if( $nv_Request->isset_request( 'save', 'post' ) )
	{
        $post['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
		$post['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
        $post['cattype'] = $nv_Request->get_title( 'cattype', 'post', '', 1 );
		if( empty( $post['title'] ) )
		{
			die( $lang_module['errorIsEmpty'] . ": " . $lang_module['title'] );
		}

		$alias = change_alias( $post['title'] );

		if( isset( $post['id'] ) )
		{
			$query = "UPDATE " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat SET 
             alias=" . $db->quote( $alias . "-" . $post['id'] ) . ",
             parentid=" . $post['parentid'] . ",
             cattype=" . $db->quote( $post['cattype'] ) . ", 
             title=" . $db->quote( $post['title'] ) . " WHERE id=" . $post['id'];
			$db->query( $query );
		}
		else
		{
			$weight = count( $catList );
			$weight++;

			$query = "INSERT INTO " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat VALUES (NULL, " . $post['parentid'] . ", 0, 0, 0, '', " . $db->quote( $post['title'] ) . ", " . $weight . ", " . $db->quote( $post['cattype'] ) . ");";
			$post['id'] = $db->insert_id( $query );

			$query = "UPDATE " . $db_config['dbsystem'] . '.' .  NV_PREFIXLANG . "_" . $module_data . "_cat SET alias=" . $db->quote( $alias . "-" . $post['id'] ) . " WHERE id=" . $post['id'];
			$db->query( $query );
		}
        nv_fix_cat_order();
		$nv_Cache->delMod($module_name);
		nv_insert_logs( NV_LANG_DATA, $module_name, $log_title, "Id: " . $post['id'], $admin_info['userid'] );
		die( 'OK' );
	}

	$post['title'] = ( $nv_Request->isset_request( 'edit', 'get' ) ) ? $catList[$post['id']]['title'] : "";
    $post['cattype'] = ( $nv_Request->isset_request( 'edit', 'get' ) ) ? $catList[$post['id']]['cattype'] : "";
    $post['parentid'] = ( $nv_Request->isset_request( 'edit', 'get' ) ) ? $catList[$post['id']]['parentid'] : 0;
    foreach ($array_cattype as $key => $title ){
        $sl = ( $key == $post['cattype'] )? ' selected=selected' : '';
        $xtpl->assign( 'CATTYPE', array('key' => $key, 'title' => $title, 'sl' => $sl) );
        $xtpl->parse( 'action.cattype' );
    }

    foreach ($catList as $array_cat ){
        $array_cat['sl'] = ( $array_cat['id'] == $post['parentid'] )? ' selected=selected' : '';
        $xtpl->assign( 'CAT', $array_cat );
        $xtpl->parse( 'action.cat' );
    }

	$xtpl->assign( 'CAT', $post );
	$xtpl->parse( 'action' );
	$contents = $xtpl->text( 'action' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit;
}

if( $nv_Request->isset_request( 'list', 'get' ) )
{
    $catList = nv_catList( $parentid);
	$a = 0;
	$count = count( $catList );

	foreach( $catList as $id => $values )
	{
		$values['id'] = $id;
        $values['link_cat'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&parentid=" . $id;
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