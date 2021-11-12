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

if( $nv_Request->isset_request( 'save', 'post' ) )
{
    $eventid = $nv_Request->get_int( 'eventid', 'post', 0 );
    $userid = $nv_Request->get_int( 'userid', 'post', 0 );
    $value = $nv_Request->get_int( 'value', 'post', 0 );
    if( $userid > 0 )
    {
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_usersevents SET status=" . $value . " WHERE eventid=" . $eventid . " AND customerid=" . $userid;
        if( $db->query($query)){
            if( $value == 3 )
            {
                $note = 'Check in tại hội thảo';
            }else{
                $note = 'Hủy check in tại hội thảo';
            }
            $measureid = 0;
            $eventtype = 0;
            save_eventcontent( $userid, $measureid, $eventtype, $note );

            exit('OK');
        }
    }
    exit('ERROR');
}

if( $flag_allow != 1 ){
    Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main' );
    die();
}
$eventid = $nv_Request->get_int( 'eventid', 'get', 0 );
$data_event = array();
if( $eventid > 0 )
{
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_listevents WHERE id=' .$eventid;
    $data_event = $db->query($sql)->fetch();
}

if( empty( $data_event )){
    Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main' );
    die();
}

$array_student_status = array($lang_module['customer_event_0'], $lang_module['customer_event_1'], $lang_module['customer_event_2'], $lang_module['customer_event_3']);

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
$xtpl->assign( 'addcustomer', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=submit-data&&eventid=' . $eventid );

$keyword = $nv_Request->get_title( 'keyword', 'post,get' );
$provinceid = $nv_Request->get_int( 'provinceid', 'post,get' );
$districtid = $nv_Request->get_int( 'districtid', 'post,get' );
$status = $nv_Request->get_int( 'status', 'post,get', -1 );
$xtpl->assign( 'keyword', $keyword );

$data_event['timeevent'] = date('d/m/Y H:i', $data_event['timeevent'] );
$xtpl->assign( 'DATA_EVENT', $data_event );

$per_page = 200;
$page = $nv_Request->get_int( 'page', 'post,get', 1 );
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;

$db->sqlreset()->select( 'COUNT(*)' )->from( '' . NV_PREFIXLANG . '_' . $module_data . ' AS t1' )->join('INNER JOIN '. NV_PREFIXLANG . '_' . $module_data . '_usersevents AS t2 ON t1.id=t2.customerid' );
$sql_where = 't2.status IN (1,2,3) AND t2.eventid=' . $eventid;

if( ! empty( $q ) )
{
    $sql_where .= ' AND (t1.full_name LIKE :q_full_name OR t1.address LIKE :q_address OR t1.email LIKE :q_email OR t1.mobile LIKE :q_mobile)';
    $base_url .= '&q=' . $q;
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

$db->select( 't1.*, t2.eventid, t2.status AS statususer' )->order( 'last_name ASC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

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

$xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
while( $view = $sth->fetch() )
{
    $view['province_name'] = isset( $list_province[$view['provinceid']] ) ? $list_province[$view['provinceid']]['title'] : 'N/A';
    $view['add_time'] = date( 'd/m/Y H:i', $view['add_time'] );
    $view['edit_time'] = date( 'd/m/Y H:i', $view['edit_time'] );
    $view['status'] = $array_student_status[$view['statususer']];
    $view['status_ck'] = ( $view['statususer'] == 3 )? ' checked=checked' : '';
    $xtpl->assign( 'VIEW', $view );
    if( $flag_allow == 1 && $eventid > 0 && $view['statususer'] != 1){
        $xtpl->parse( 'main.loop.check_status' );
    }
    $xtpl->parse( 'main.loop' );
}
$xtpl->assign( 'eventid', $eventid );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
