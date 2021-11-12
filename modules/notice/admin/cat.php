<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 14 Apr 2011 12:01:30 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$catList = nv_catList();

$page_title = $lang_module['cat'];
$contents = "";

if ( empty( $catList ) and ! $nv_Request->isset_request( 'add', 'get' ) )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=cat&add" );
    die();
}

if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    $id = $nv_Request->get_int( 'del', 'post', 0 );
    if ( ! isset( $catList[$id] ) ) die( $lang_module['errorCatNotExists'] );
    
    $sql = "SELECT COUNT(*) as count FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE catid=" . $id;
    $result = $db->query( $sql );
    $row = $result->fetch();
    
    if ( $row['count'] ) die( $lang_module['errorCatYesRow'] );

    $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE id = " . $id;
    $db->query( $query );
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

if ( $nv_Request->isset_request( 'add', 'get' ) or $nv_Request->isset_request( 'edit, id', 'get' ) )
{
    $post = array();
    if ( $nv_Request->isset_request( 'edit', 'get' ) )
    {
        $post['id'] = $nv_Request->get_int( 'id', 'get' );
        if ( empty( $post['id'] ) or ! isset( $catList[$post['id']] ) )
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

    if ( $nv_Request->isset_request( 'save', 'post' ) )
    {
        $post['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );
        if ( empty( $post['title'] ) )
        {
            die( $lang_module['errorIsEmpty'] . ": " . $lang_module['cattitle'] );
        }
       

        if ( isset( $post['id'] ) )
        {
            $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_cat SET                     
                    title=" . $db->quote( $post['title'] ) . " WHERE id=" . $post['id'];
            $db->query( $query );
        }
        else
        {
            $query = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_cat VALUES (NULL, " . $db->quote( $post['title'] ) . ",1);";
			//$post['id'] = $db->insert_id( $query );
            
            $db->query( $query );
        }

        $nv_Cache->delMod($module_name);
        nv_insert_logs( NV_LANG_DATA, $module_name, $log_title, "Id: " . $post['id'], $admin_info['userid'] );
        die( 'OK' );
    }

    $post['title'] = ( $nv_Request->isset_request( 'edit', 'get' ) ) ? $catList[$post['id']]['title'] : "";

    $xtpl->assign( 'CAT', $post );
    $xtpl->parse( 'action' );
    $contents = $xtpl->text( 'action' );

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme( $contents );
    include NV_ROOTDIR . '/includes/footer.php';
    exit;
}

if ( $nv_Request->isset_request( 'list', 'get' ) )
{
    $a = 0;
    $count = count( $catList );
    foreach ( $catList as $id => $values )
    {
        $values['id'] = $id;
        $xtpl->assign( 'LOOP', $values );
        $xtpl->assign( 'CLASS', $a % 2 ? " class=\"second\"" : "" );
		if (defined( 'NV_IS_GODADMIN' )){
			$xtpl->parse( 'list.loop.ad' );
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

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';