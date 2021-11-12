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
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'refer_by_parent', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=customertop' );
$xtpl->assign( 'addcustomer', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&action=add' );

$q = $nv_Request->get_title( 'keyword', 'post,get' );
$provinceid = $nv_Request->get_int( 'provinceid', 'post,get' );
$xtpl->assign( 'Q', $q );

$per_page = 30;
$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$db->sqlreset()->select( 'COUNT(*)' )->from( '' . NV_PREFIXLANG . '_' . $module_data . '_listevents' );
$sql_where = 'status=1';
$search = 0;
if( ! empty( $q ) )
{
    $search = 1;
    $sql_where .= ' AND (title LIKE :title OR addressevent LIKE :addressevent)';
    $base_url .= '&keyword=' . $q;
}
if( $provinceid > 0 )
{
    $search = 1;
    $sql_where .= ' AND provinceid=' . $provinceid;
    $base_url .= '&provinceid=' . $provinceid;
}
//khong phai tim kiem thi chi show su kien chua dien ra
if( $search == 0){
    $sql_where .= ' AND timeevent>=' . NV_CURRENTTIME;
}
$db->where( $sql_where );

$sth = $db->prepare( $db->sql() );

if( ! empty( $q ) )
{
    $sth->bindValue( ':title', '%' . $q . '%' );
    $sth->bindValue( ':addressevent', '%' . $q . '%' );
}
$sth->execute();
$num_items = $sth->fetchColumn();

if($search == 1 ){
    $db->select( '*' )->order( 'weight DESC' );
}else{
    $db->select( '*' )->order( 'timeevent ASC' );
}
$db->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$sth = $db->prepare( $db->sql() );

if( ! empty( $q ) )
{
    $sth->bindValue( ':title', '%' . $q . '%' );
    $sth->bindValue( ':addressevent', '%' . $q . '%' );
}
$sth->execute();

$page_title = $lang_module['list_customer'];
foreach( $list_province as $data )
{
    $data['selected'] = ( $data['id'] == $provinceid ) ? ' selected="selected"' : '';
    $xtpl->assign( 'OPTION', $data );
    $xtpl->parse( 'main.data.province_select' );
}

$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
while( $view = $sth->fetch() )
{
    $view['customer_list'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=customer-list&amp;eventid=' . $view['id'];
    $view['addevent'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=eventcontent&amp;id=' . $view['id'];
    $view['checkin'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checkin&amp;eventid=' . $view['id'];
    $view['province_name'] = isset( $list_province[$view['provinceid']] ) ? $list_province[$view['provinceid']]['title'] : 'N/A';
    $view['timeclose'] = date( 'd/m/Y H:i', $view['timeclose'] );
    $view['timeevent_day'] = date( 'd/m/Y', $view['timeevent'] );
    $view['timeevent'] = date( 'd/m/Y H:i', $view['timeevent'] );
    $view['status'] = $lang_module['customer_status_' . $view['status']];
    $xtpl->assign( 'VIEW', $view );

    $xtpl->parse( 'main.data.loop' );
}
$xtpl->parse( 'main.data' );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
