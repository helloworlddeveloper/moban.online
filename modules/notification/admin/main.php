<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2017 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-01-2017 20:08
 */

if( ! defined( 'NV_IS_MESSAGE_ADMIN' ) )
    die( 'Stop!!!' );

// Kich hoat - dinh chi
if( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
    $id = $nv_Request->get_int( 'id', 'post', 0 );

    if( empty( $id ) )
        die( 'NO' );

    $query = "SELECT status FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $result = $db->query( $query );
    $numrows = $result->rowCount();
    if( $numrows != 1 )
        die( 'NO' );

    $status = $result->fetchColumn();
    $status = $status ? 0 : 1;
    $db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET status=" . $status . " WHERE id=" . $id );
    $db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_message_queue SET active=" . $status . " WHERE mid=" . $id );
    die( 'OK' );
}
// Delete mess
if( $nv_Request->isset_request( 'del', 'post' ) )
{
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    if( ! $id )
        die( 'NO' );

    $query = "SELECT message FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $result = $db->query( $query );
    $numrows = $result->rowCount();
    if( $numrows != 1 )
        die( 'NO' );

    $row = $result->fetch();

    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $db->query( $sql );
    $db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_message_queue WHERE mid=" . $id );

    nv_insert_logs( NV_LANG_DATA, $module_data, 'delete_notification', $lang_module['delete_notification'] . $id . '-' . $row['message'], $admin_info['userid'] );

    die( 'OK' );
}

//List Mess
$page_title = $lang_module['notification'];

$db->sqlreset()->select( 'COUNT(*)' )->from( NV_PREFIXLANG . "_" . $module_data );
$num_items = $db->query( $db->sql() )->fetchColumn();

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;

if( ! $num_items )
{
    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=add" );
    exit();
}


$page = $nv_Request->get_int( 'page', 'get', 1 );
$per_page = 30;
$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'TABLE_CAPTION', $page_title );
$xtpl->assign( 'SEND_NEW_MESS', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add" );

$db->select( '*' )->order( 'addtime DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
$result = $db->query( $db->sql() );

$array = array();
while( $row = $result->fetch() )
{
    $row['status'] = ( $row['status'] == 1 ) ? " checked=\"checked\"" : "";
    $xtpl->assign( 'ROW', $row );
    $xtpl->assign( 'EDIT_URL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=add&amp;id=" . $row['id'] );
    $xtpl->parse( 'main.row' );

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

?>