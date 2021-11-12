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

if( $nv_Request->isset_request( 'save', 'post' ) )
{
    $row['full_name'] = $nv_Request->get_title( 'full_name', 'post', '' );
    $row['birthday'] = $nv_Request->get_title( 'birthday', 'post', '' );
    if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $row['birthday'], $m ) )
    {
        $ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
        $emin = $nv_Request->get_int( 'emin', 'post', 0 );
        $row['birthday'] = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
    }
    else
    {
        $row['birthday'] = 0;
    }
    $row['sex'] = $nv_Request->get_int( 'sex', 'post', 0 );
    $row['mobile'] = $nv_Request->get_title( 'mobile', 'post', '' );
    $row['email'] = $nv_Request->get_title( 'email', 'post', '' );
    $row['address'] = $nv_Request->get_title( 'address', 'post', '' );
    $row['from_by'] = $nv_Request->get_int( 'from_by', 'post', 0 );
    $row['provinceid'] = $nv_Request->get_int( 'provinceid', 'post', 0 );
    $row['districtid'] = $nv_Request->get_int( 'districtid', 'post', 0 );
    $row['gmap_lat'] = $nv_Request->get_float( 'gmap_lat', 'post', 0 );
    $row['gmap_lng'] = $nv_Request->get_float( 'gmap_lng', 'post', 0 );
    $row['status'] = $nv_Request->get_int( 'status', 'post', 0 );

    if( empty( $row['full_name'] ) )
    {
        $error[] = $lang_module['error_required_full_name'];
    }
    if( empty( $row['address'] ) )
    {
        $error[] = $lang_module['error_required_address'];
    }
    if( $row['provinceid'] == 0 )
    {
        $error[] = $lang_module['error_required_provinceid'];
    }
    if( empty( $row['mobile'] ) || ! preg_match( '/^0/', $row['mobile'] ) || ( strlen( $row['mobile'] ) > 11 || strlen( $row['mobile'] ) < 10 ) )
    {
        $error[] = $lang_module['error_number_phone_format'];

    }
    if( empty( $error ) )
    {
        $row['add_time'] = NV_CURRENTTIME;
        $row['edit_time'] = NV_CURRENTTIME;

        if( $row['id'] > 0 )
        {
            $data_old = $db->query( "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . " WHERE id=" . $row['id'] )->fetch();
        }

        try
        {
            $insert = 1;
            if( empty( $row['id'] ) )
            {
                $stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data .'(adminid, provinceid, districtid, full_name, birthday, sex, address, email, mobile, gmap_lat, gmap_lng, from_by, add_time, edit_time, status) 
				VALUES (:adminid, :provinceid, :districtid, :full_name, :birthday, :sex, :address, :email, :mobile, :gmap_lat, :gmap_lng, :from_by, :add_time, :edit_time, :status)' );
                $stmt->bindParam( ':add_time', $row['add_time'], PDO::PARAM_INT );
                $stmt->bindParam( ':adminid', $admin_info['userid'], PDO::PARAM_INT );
            }
            else
            {
                $insert = 0;
                $stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data .' SET provinceid=:provinceid, districtid=:districtid, full_name=:full_name, birthday=:birthday, sex=:sex, address=:address, email=:email, mobile=:mobile, gmap_lat=:gmap_lat, gmap_lng=:gmap_lng, from_by=:from_by, edit_time=:edit_time, status=:status WHERE id=' . $row['id'] );
            }
            $stmt->bindParam( ':provinceid', $row['provinceid'], PDO::PARAM_INT );
            $stmt->bindParam( ':districtid', $row['districtid'], PDO::PARAM_INT );
            $stmt->bindParam( ':full_name', $row['full_name'], PDO::PARAM_LOB );
            $stmt->bindParam( ':birthday', $row['birthday'], PDO::PARAM_INT );
            $stmt->bindParam( ':sex', $row['sex'], PDO::PARAM_INT );
            $stmt->bindParam( ':address', $row['address'], PDO::PARAM_STR );
            $stmt->bindParam( ':mobile', $row['mobile'], PDO::PARAM_STR );
            $stmt->bindParam( ':email', $row['email'], PDO::PARAM_STR );
            $stmt->bindParam( ':gmap_lat', $row['gmap_lat'], PDO::PARAM_INT );
            $stmt->bindParam( ':gmap_lng', $row['gmap_lng'], PDO::PARAM_INT );
            $stmt->bindParam( ':from_by', $row['from_by'], PDO::PARAM_INT );
            $stmt->bindParam( ':status', $row['status'], PDO::PARAM_INT );
            $stmt->bindParam( ':edit_time', $row['edit_time'], PDO::PARAM_INT );
            $exc = $stmt->execute();
            if( $exc )
            {
                if( $insert == 1 )
                {
                    $row['id'] = $db->query( 'SELECT max(id) FROM ' . NV_PREFIXLANG . '_' . $module_data )->fetchColumn();
                }
                else
                {
                    $array_data_change = array();
                    foreach( $data_old as $key => $data )
                    {
                        if( $key != 'edit_time' && $key != 'add_time' )
                        {
                            if( $row[$key] != $data )
                            {
                                if( $key == 'birthday' )
                                {
                                    $data = ( $data > 0 ) ? date( 'd/m/Y', $data ) : 'N/A';
                                    $row[$key] = ( $row[$key] > 0 ) ? date( 'd/m/Y', $row[$key] ) : 'N/A';
                                    $array_data_change[$key] = '<strong>' . $lang_module[$key] . '</strong>' . ': ' . $data . ' -> ' . $row[$key];
                                }
                                else
                                {
                                    $array_data_change[$key] = '<strong>' . $lang_module[$key] . '</strong>' . ': ' . $data . ' -> ' . $row[$key];
                                }
                            }
                        }
                    }
                    try
                    {
                        //neu phat hien co du lieu thay doi moi goi ham cap nhat
                        if( ! empty( $array_data_change ) )
                        {
                            require_once NV_ROOTDIR . '/modules/mkt/eventcontent.php';
                            $note = 'Cập nhật dữ liệu:<br />' . implode( '<br />', $array_data_change );
                            $measureid = 0;
                            $eventtype = 0;
                            $parentid = 0;
                            save_eventcontent( $row['id'], $measureid, $eventtype, $note );
                        }
                    }
                    catch ( PDOException $e )
                    {
                        die( $e->getMessage() );
                    }
                }
                $nv_Cache->delMod( $module_name );

                Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
                die();
            }
        }
        catch ( PDOException $e )
        {
            trigger_error( $e->getMessage() );
            $error[] = $e->getMessage();
        }
    }
}
elseif( $row['id'] > 0 )
{
    $row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data .' WHERE id=' . $row['id'] )->fetch();
    if( empty( $row ) )
    {
        Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
    $action = 'edit';
}
else
{
    $row['status'] = 0;
    $row['sex'] = 1;
    $row['provinceid'] = $row['from_by'] = 0;
    $row['birthday'] = NV_CURRENTTIME;
    $row['gmap_lat'] = $row['gmap_lng'] = 0;
}die('jhjgh');
if( $row['birthday'] > 0 ){
    $row['birthday'] = date( 'd/m/Y', $row['birthday'] );
}else{
    $row['birthday'] = '';
}

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

if( $row['id'] > 0 or $action == 'add' or $action == 'edit' )
{
    if( ! empty( $error ) )
    {
        $xtpl->assign( 'ERROR', implode( '<br />', $error ) );
        $xtpl->parse( 'main.allow_add.add_row.error' );
    }
    foreach( $array_student_status as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ( $key == $row['status'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.allow_add.add_row.select_status' );
    }

    $array_sex = array(
        '1' => $lang_module['sex_1'],
        '2' => $lang_module['sex_2'],
        '0' => $lang_module['sex_0'] );
    foreach( $array_sex as $key => $sex )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $sex,
            'checked' => ( $key == $row['sex'] ) ? ' checked="checked"' : '' ) );
        $xtpl->parse( 'main.allow_add.add_row.checkbox_sex' );
    }

    foreach( $list_from as $data )
    {
        $data['selected'] = ( $data['id'] == $row['from_by'] ) ? ' selected="selected"' : '';
        $xtpl->assign( 'FROM', $data );
        $xtpl->parse( 'main.allow_add.add_row.from_select' );
    }
    foreach( $list_province as $data )
    {
        $data['selected'] = ( $data['id'] == $row['provinceid'] ) ? ' selected="selected"' : '';
        $xtpl->assign( 'OPTION', $data );
        $xtpl->parse( 'main.allow_add.add_row.province_select' );
    }

    if( $row['id'] == 0 )
    {
        $xtpl->parse( 'main.allow_add.add_row.auto_get_alias' );
    }
    $xtpl->parse( 'main.allow_add.add_row' );
    if( $permissions_users[$admin_info['admin_id']][$op]['add'] == 1 )
    {
        $xtpl->parse( 'main.allow_add' );
    }
    elseif( $permissions_users[$admin_info['admin_id']][$op]['edit'] == 1 && $row['schoolid'] > 0 )
    {
        $xtpl->parse( 'main.allow_add' );
    }
}
else
{
    $eventid = $nv_Request->get_int( 'eventid', 'get', 0 );
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
    $data_event = array();
    if( $eventid > 0 )
    {
        $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_listevents WHERE id=' .$eventid;
        $data_event = $db->query($sql)->fetch();
        $array_student_status = array($lang_module['customer_event_0'], $lang_module['customer_event_1'], $lang_module['customer_event_2'], $lang_module['customer_event_3']);
    }

    if( !empty( $data_event )){
        $data_event['timeevent'] = date('d/m/Y H:i', $data_event['timeevent'] );
        $xtpl->assign( 'DATA_EVENT', $data_event );
        $xtpl->parse( 'main.view.event_info' );
    }else{
        $xtpl->parse( 'main.view.no_event' );
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

    $db->sqlreset()->select( 'COUNT(*)' )->from( '' . NV_PREFIXLANG . '_' . $module_data . ' AS t1' )->join('INNER JOIN '. NV_PREFIXLANG . '_' . $module_data . '_usersevents AS t2 ON t1.id=t2.customerid' );
    if( $flag_allow == 0 ) {
        //lay danh sach cac npp tuyen duoi
        $sql = 'SELECT subcatid FROM ' . $db_config['prefix'] . '_affiliate_users WHERE userid=' . $user_info['userid'];
        list( $subcatid ) = $db->query($sql)->fetch(3);

        $list_user = $user_info['userid'] . ',' . get_sub_nodes_users( $subcatid );
        $sql_where = 't1.adminid IN(' . $list_user . ')';
    }else{
        $sql_where = '1=1';
    }
    if( ! empty( $q ) )
    {
        $sql_where .= ' AND (t1.full_name LIKE :q_full_name OR t1.address LIKE :q_address OR t1.email LIKE :q_email OR t1.mobile LIKE :q_mobile)';
        $base_url .= '&q=' . $q;
    }
    if( $provinceid > 0 )
    {
        $sql_where .= ' AND t1.provinceid=' . $provinceid;
        $base_url .= '&provinceid=' . $provinceid;
    }
    if( $eventid > 0 )
    {
        $sql_where .= ' AND t2.eventid=' . $eventid;
        $base_url .= '&eventid=' . $eventid;
    }
    if( $districtid > 0 )
    {
        $sql_where .= ' AND t1.districtid=' . $districtid;
        $base_url .= '&districtid=' . $districtid;
    }
    if( $status >= 0 )
    {
        if( $eventid > 0 ) {
            $sql_where .= ' AND t2.status=' . $status;
        }else{
            $sql_where .= ' AND t1.status=' . $status;
        }
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
    $db->select( 't1.*, t2.eventid, t2.status AS statususer' )->order( 't2.status ASC, last_name ASC' );
    $sql_export = $db->sql();
    $xtpl->assign( 'sql_export', base64_encode( $sql_export));
    $db->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

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


    //danh sach user he thong
    $array_refer = array();
    $res = $db->query( 'SELECT userid, username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE );
    while ($row = $res->fetch()){
        $full_name = nv_show_name_user( $row['first_name'], $row['last_name'] );
        $array_refer[$row['userid']] = $full_name;
    }
    $stt = 1 + ( $per_page * ($page -1 ) );
    $xtpl->assign( 'addcustomer', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=submit-data&&eventid=' . $eventid );
    $xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );
    while( $view = $sth->fetch() )
    {
        $view['stt'] = $stt++;
        $view['event_join'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=eventcontent&amp;eventid=' . $view['eventid'] . '&id=' . $view['id'];
        $view['addevent'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=eventcontent&amp;id=' . $view['id'];
        $view['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $view['id'] . '&amp;delete_checkss=' . md5( $view['id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
        $view['province_name'] = isset( $list_province[$view['provinceid']] ) ? $list_province[$view['provinceid']]['title'] : 'N/A';
        $view['add_time'] = date( 'd/m/Y H:i', $view['add_time'] );
        $view['edit_time'] = date( 'd/m/Y H:i', $view['edit_time'] );
        $view['status'] = $array_student_status[$view['statususer']];
        $view['from_by'] = isset( $list_from[$view['from_by']] ) ? $list_from[$view['from_by']]['title'] : 'N/A';
        $view['user_refer'] = $array_refer[$view['adminid']];
        $xtpl->assign( 'VIEW', $view );
        if( $flag_allow == 1 && $eventid > 0 && $view['statususer'] < 1 && $data_event['timeevent'] < NV_CURRENTTIME ){
            $xtpl->parse( 'main.view.loop.check_status' );
        }
        $xtpl->parse( 'main.view.loop' );
    }
    $xtpl->assign( 'num_items', $num_items );
    $xtpl->assign( 'eventid', $eventid );
    $xtpl->parse( 'main.view' );
}
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
