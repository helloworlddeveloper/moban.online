<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 01:50:26 GMT
 */

if( ! defined( 'NV_IS_MOD_RM' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

if( $nv_Request->isset_request( 'loaddistrict', 'post' ) )
{
    $provinceid = $nv_Request->get_int( 'provinceid', 'post', 0 );
    $districtid = $nv_Request->get_int( 'districtid', 'post', 0 );

    $html = '<select style="width: 100%;" class="form-control" name="districtid">';
    $html .= '<option value="0">---------</option>';
    if( $provinceid > 0 )
    {
        $sql = "SELECT * FROM " . NV_TABLE_AFFILIATE_LANG . "_district WHERE status=1 AND idprovince=" . $provinceid . " ORDER BY weight ASC";
        $result = $db->query( $sql );
        $list = array();
        while( $row = $result->fetch() )
        {
            $sl = ( $row['id'] == $districtid ) ? ' selected="selected"' : '';
            $html .= '<option value="' . $row['id'] . '" ' . $sl . '>' . $row['title'] . '</option>';
        }
    }
    $html .= '</select>';
    exit( $html );
}

$action = $nv_Request->get_title( 'action', 'get', '' );

if( $nv_Request->isset_request( 'delete_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
    $id = $nv_Request->get_int( 'delete_id', 'get' );
    $delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
    if( $id > 0 and $delete_checkss == md5( $id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
    {
        $db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $db->quote( $id ) );
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
}
$row = array();
$error = array();
$row['id'] = $nv_Request->get_int( 'id', 'post,get', 0 );

$list_province = nv_Province();
$list_from = nv_From();

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'action', $action );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );

$keyword = $nv_Request->get_title( 'keyword', 'post,get' );
$provinceid = $nv_Request->get_int( 'provinceid', 'post,get' );
$districtid = $nv_Request->get_int( 'districtid', 'post,get' );
$status = $nv_Request->get_int( 'status', 'post,get', -1 );
$xtpl->assign( 'keyword', $keyword );
if( $provinceid > 0 )
{
    $xtpl->assign( 'provinceid', $provinceid );
    $xtpl->assign( 'districtid', $districtid );
    $xtpl->parse( 'main.view.loaddistrict' );
}


foreach( $array_student_status as $key => $title )
{
    $selected = ( $key == $status ) ? ' selected="selected"' : '';
    $xtpl->assign( 'OPTION', array(
        'key' => $key,
        'title' => $title,
        'selected' => $selected ) );
    $xtpl->parse( 'main.view.status_select' );
}

$per_page = 30;
$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$db->sqlreset()->select( 'COUNT(*)' )->from( '' . NV_PREFIXLANG . '_' . $module_data );
$sql_where = 'adminid=0';

if( ! empty( $q ) )
{
    $sql_where .= ' AND (full_name LIKE :q_full_name OR address LIKE :q_address OR email LIKE :q_email OR mobile LIKE :q_mobile)';
    $base_url .= '&q=' . $q;
}
if( $provinceid > 0 )
{
    $sql_where .= ' AND provinceid=' . $provinceid;
    $base_url .= '&provinceid=' . $provinceid;
}
if( $districtid > 0 )
{
    $sql_where .= ' AND districtid=' . $districtid;
    $base_url .= '&districtid=' . $districtid;
}
if( $status >= 0 )
{

    $sql_where .= ' AND status=' . $status;
    $base_url .= '&status=' . $status;
}
$db->where( $sql_where );

$sth = $db->prepare( $db->sql() );

if( ! empty( $q ) )
{
    $sth->bindValue( ':q_full_name', '%' . $q . '%' );
    $sth->bindValue( ':q_address', '%' . $q . '%' );
    $sth->bindValue( ':q_email', '%' . $q . '%' );
    $sth->bindValue( ':q_mobile', '%' . $q . '%' );
}
$sth->execute();
$num_items = $sth->fetchColumn();

$db->select( '*' )->order( 'add_time DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$sth = $db->prepare( $db->sql() );

if( ! empty( $q ) )
{
    $sth->bindValue( ':q_full_name', '%' . $q . '%' );
    $sth->bindValue( ':q_address', '%' . $q . '%' );
    $sth->bindValue( ':q_email', '%' . $q . '%' );
    $sth->bindValue( ':q_mobile', '%' . $q . '%' );
}
$sth->execute();

$page_title = $lang_module['list_customer'];

$list_district = nv_District();
foreach( $list_province as $data )
{
    $data['selected'] = ( $data['id'] == $provinceid ) ? ' selected="selected"' : '';
    $xtpl->assign( 'OPTION', $data );
    $xtpl->parse( 'main.view.province_select' );
}
$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
while( $view = $sth->fetch() )
{
    $view['event_join'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=eventcontent&id=' . $view['id'];
    $view['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
    $view['province_name'] = isset( $list_province[$view['provinceid']] ) ? $list_province[$view['provinceid']]['title'] : 'N/A';
    $view['add_time'] = date( 'd/m/Y H:i', $view['add_time'] );
    $view['edit_time'] = date( 'd/m/Y H:i', $view['edit_time'] );
    $view['status'] = $array_student_status[$view['status']];
    $view['from_by'] = isset( $list_from[$view['from_by']] ) ? $list_from[$view['from_by']]['title'] : 'N/A';

    $xtpl->assign( 'VIEW', $view );
    if( $flag_allow == 1 ){
        $xtpl->parse( 'main.view.loop.check_status' );
    }
    $xtpl->parse( 'main.view.loop' );
}
$xtpl->parse( 'main.view' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
