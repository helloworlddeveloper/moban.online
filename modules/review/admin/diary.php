<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang <contact@vinades.vn>
 * @Copyright (C) 2010 - 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
{
    die( 'Stop!!!' );
}

elseif( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
    $id = $nv_Request->get_int( 'delete_id', 'get' );
    $serviceid = $nv_Request->get_int( 'serviceid', 'get' );
    $delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
    list($catid_delete) = $db->query( $sql )->fetch(3);

    if( $id > 0 and $catid_delete > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
    {
        $db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '  WHERE id = ' . $db->quote( $id ) );
        $db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_file  WHERE rid = ' . $db->quote( $id ) );
        $nv_Cache->delMod( $module_name );
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&serviceid=' . $serviceid );
        die();
    }

}else if( $nv_Request->isset_request( 'deleteselect', 'post' ) )
{

    $listid = $nv_Request->get_string('listid', 'post', '');
    $del_array = array_map('intval', explode(',', $listid));
    $sql = 'SELECT id, title FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id IN (' . implode(',', $del_array) . ')';
    $result = $db->query($sql);
    $del_array = $no_del_array = array();
    $artitle = array();
    while (list($id, $title) = $result->fetch(3)) {

        $_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
        if ($db->exec($_sql)) {
            $db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_file  WHERE rid = ' . $db->quote( $id ) );
            $contents = 'OK_' . $id . '_' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true);
        } else {
            $contents = 'ERR_' . $lang_module['error_del_content'];
        }
        $artitle[] = $title;
        $del_array[] = $id;
    }
    $count = sizeof($del_array);
    if (!empty($no_del_array)) {
        $contents = 'ERR_' . $lang_module['error_no_del_content_id'] . ': ' . implode(', ', $no_del_array);
    }
    exit($contents);
}

$page_title = $lang_module['product_list'];

$serviceid = $nv_Request->get_int('serviceid', 'post,get', 0);
if ($serviceid) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_service WHERE id=' . $serviceid;
    $data_service = $db->query($sql)->fetch();

    if (empty($data_service)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=product');
    }

    $page_title = $lang_module['product_list'] . ' ' . $data_service['fullname'];
}

$sstatus = $nv_Request->get_int( 'sstatus', 'get', -1 );
$per_page = 50;

$q = $nv_Request->get_title( 'q', 'get', '' );
$q = str_replace( '+', ' ', $q );
$qhtml = nv_htmlspecialchars( $q );

$array_list_action = array('delete' => $lang_global['delete']);
if( $sstatus < 0 or $sstatus > 1 )
{
    $sstatus = -1;
}

$from = NV_PREFIXLANG . '_' . $module_data;

$where = array();
$page = $nv_Request->get_int( 'page', 'get', 1 );
$checkss = $nv_Request->get_string( 'checkss', 'get', '' );

$where[] = ' serviceid=' . $serviceid;
if( $checkss == NV_CHECK_SESSION )
{
    if( $sstatus != -1 )
    {
        $where[] = ' status = ' . $sstatus;
    }
    if( !empty( $q ) )
    {
        $where[] = "title LIKE '%" . $db_slave->dblikeescape( $qhtml ) . "%'";
    }
}

$db_slave->sqlreset()->select( 'COUNT(*)' )->from( $from );
if( !empty( $where )){
    $db_slave->where( implode(' AND ', $where) );
}
$_sql = $db_slave->sql();

$num_checkss = md5( NV_CHECK_SESSION . $_sql );
if( $num_checkss != $nv_Request->get_string( 'num_checkss', 'get', '' ) )
{
    $num_items = $db_slave->query( $_sql )->fetchColumn();
    $num_checkss = md5( $num_items . NV_CHECK_SESSION . $_sql );
}
$base_url_mod = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;
if( !empty( $q ) )
{
    $base_url_mod .= '&amp;q=' . $q . '&amp;checkss=' . $checkss;
}
$base_url_mod .= '&amp;num_checkss=' . $num_checkss;


for( $i = 0; $i <= 1; $i++ )
{
    $sl = ( $i == $sstatus ) ? ' selected="selected"' : '';
    $search_status[] = array(
        'key' => $i,
        'value' => $lang_module['status_' . $i],
        'selected' => $sl );
}

$base_url = $base_url_mod . '&amp;sstatus=' . $sstatus;
$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'addproduct', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&serviceid=' . $data_service['id'] );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'Q', $qhtml );
$xtpl->assign( 'DATA_SERVICE', $data_service );

$db_slave->select( '*' )->order( 'timeuse ASC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db_slave->query( $db_slave->sql() );
while( $row = $result->fetch() )
{
    $row['timeuse'] = nv_date( 'd/m/Y', $row['timeuse'] );
    $row['status'] = $lang_module['status_' . $row['status']];
    $row['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&serviceid=' . $data_service['id'] . '&id=' . $row['id'];
    $row['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $row['id'] . '&serviceid=' . $row['serviceid'] . '&amp;delete_checkss=' . md5( $row['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
    $xtpl->assign( 'ROW', $row );

    $xtpl->parse( 'main.loop' );
}

foreach( $search_status as $status_view )
{
    $xtpl->assign( 'SEARCH_STATUS', $status_view );
    $xtpl->parse( 'main.search_status' );
}

while( list( $action_i, $title_i ) = each( $array_list_action ) )
{
    $action_assign = array( 'value' => $action_i, 'title' => $title_i );
    $xtpl->assign( 'ACTION', $action_assign );
    $xtpl->parse( 'main.action' );
}

if( !empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
