<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 10:21:15 GMT
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) )
    die( 'Stop!!!' );


if( $nv_Request->isset_request( 'delete_customer_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
    $customer_id = $nv_Request->get_int( 'delete_customer_id', 'get' );
    $delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
    if( $customer_id > 0 and $delete_checkss == md5( $customer_id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
    {
        $db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer  WHERE customer_id = ' . $db->quote( $customer_id ) );
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
}

$row = array();
$error = array();
$row['customer_id'] = $nv_Request->get_int( 'customer_id', 'post,get', 0 );
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
    $row['code'] = $nv_Request->get_title( 'code', 'post', '' );
    $row['fullname'] = $nv_Request->get_title( 'fullname', 'post', '' );
    $row['address'] = $nv_Request->get_title( 'address', 'post', '' );
    $row['phone'] = $nv_Request->get_title( 'phone', 'post', '' );
    $row['email'] = $nv_Request->get_title( 'email', 'post', '' );
    $row['description'] = $nv_Request->get_string( 'description', 'post', '' );
    $row['refer_userid'] = $nv_Request->get_int( 'refer_userid', 'post', 0 );
    $row['custype'] = $nv_Request->get_int( 'custype', 'post', 0 );
    $row['status'] = $nv_Request->get_int( 'status', 'post', 0 );

    if( empty( $row['fullname'] ) )
    {
        $error[] = $lang_module['error_required_name'];
    }
    elseif( empty( $row['phone'] ) && empty( $row['email'] ) )
    {
        $error[] = $lang_module['error_required_phone_or_email'];
    }
    if( ! preg_match( '/^[0-9]{10,11}$/', $row['phone'] ) )
    {
        $error[] = $lang_module['error_fomart_phone'];
    }

    $sql = "SELECT customer_id FROM " . NV_PREFIXLANG . '_' . $module_data . "_customer WHERE phone LIKE '%" . $row['phone'] . "%' AND fullname LIKE '%" . $row['fullname'] . "%' AND customer_id !=" . $row['customer_id'];
    $data_exist = $db->query( $sql )->fetch();

    if( ! empty( $data_exist ) )
    {
        $error[] = $lang_module['customer_exits'];
    }
    if( empty( $error ) )
    {
        try
        {
            $row['edit_time'] = NV_CURRENTTIME;
            if( empty( $row['customer_id'] ) )
            {

                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_customer  (refer_userid, code, fullname, address, phone, email, description, add_time, edit_time, custype, status) VALUES (:refer_userid, :code, :fullname, :address, :phone, :email, :description, :add_time, :edit_time, :custype, :status)';

                $data_insert = array();
                $data_insert['refer_userid'] = $row['refer_userid'];
                $data_insert['code'] = $row['code'];
                $data_insert['fullname'] = $row['fullname'];
                $data_insert['address'] = $row['address'];
                $data_insert['phone'] = $row['phone'];
                $data_insert['email'] = $row['email'];
                $data_insert['description'] = $row['description'];
                $data_insert['add_time'] = NV_CURRENTTIME;
                $data_insert['edit_time'] = NV_CURRENTTIME;
                $data_insert['custype'] = $row['custype'];
                $data_insert['status'] = $row['status'];
                $customer_id = $db->insert_id( $sql, 'customer_id', $data_insert );
                if( $customer_id > 0 )
                {
                    $nv_Cache->delMod($module_name);
                    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
                    die();
                }
            }
            else
            {
                $stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_customer SET refer_userid=:refer_userid, code=:code, fullname = :fullname, address = :address, phone = :phone, email = :email, description = :description, status = :status, custype=:custype, edit_time=:edit_time WHERE customer_id=' . $row['customer_id'] );
                $stmt->bindParam( ':code', $row['code'], PDO::PARAM_STR );
                $stmt->bindParam( ':refer_userid', $row['refer_userid'], PDO::PARAM_INT );
                $stmt->bindParam( ':fullname', $row['fullname'], PDO::PARAM_STR );
                $stmt->bindParam( ':address', $row['address'], PDO::PARAM_STR );
                $stmt->bindParam( ':phone', $row['phone'], PDO::PARAM_STR );
                $stmt->bindParam( ':email', $row['email'], PDO::PARAM_STR );
                $stmt->bindParam( ':description', $row['description'], PDO::PARAM_STR, strlen( $row['description'] ) );
                $stmt->bindParam( ':status', $row['status'], PDO::PARAM_INT );
                $stmt->bindParam( ':custype', $row['custype'], PDO::PARAM_INT );
                $stmt->bindParam( ':edit_time', $row['edit_time'], PDO::PARAM_INT );

                $exc = $stmt->execute();
                if( $exc )
                {
                    $nv_Cache->delMod($module_name);
                    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
                    die();
                }
            }

        }
        catch ( PDOException $e )
        {
            trigger_error( $e->getMessage() );
            die( $e->getMessage() ); //Remove this line after checks finished
        }
    }
}
elseif( $row['customer_id'] > 0 )
{
    $row = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE customer_id=' . $row['customer_id'] )->fetch();
    if( empty( $row ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
    $sql = 'SELECT username, first_name, last_name, email FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $row['refer_userid'];
    $result = $db->query( $sql );
    $tmp = $result->fetch();
    if( !empty( $tmp )){
        $tmp['refer_fullname'] = nv_show_name_user( $tmp['first_name'] , $tmp['last_name'] , $tmp['username'] );
        $tmp['refer_title'] = $tmp['username'] . ' - ' . $tmp['refer_fullname'] . ' - ' . $tmp['email'];
        $row = array_merge( $row, $tmp );
    }
    else{
        $row['refer_fullname'] = '';
    }
}
else
{
    $row['custype'] =  $row['customer_id'] = 0;
    $row['name'] = '';
    $row['address'] = '';
    $row['phone'] = '';
    $row['email'] = '';
    $row['description'] = '';
    $row['status'] = 1;
}

$q = $nv_Request->get_title( 'q', 'post,get' );
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
// Fetch Limit
$show_view = false;
$data_admin = array();
if( ! $nv_Request->isset_request( 'customer_id', 'post,get' ) && ! $nv_Request->isset_request( 'add', 'post,get' ) )
{
    $show_view = true;
    $per_page = 20;
    $page = $nv_Request->get_int( 'page', 'post,get', 1 );
    $customer_type = $nv_Request->get_int( 'customer_type', 'post,get', -1 );

    $sql = ' SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer';

    $db->sqlreset()->select( 'COUNT(*)' )->from( '' . NV_PREFIXLANG . '_' . $module_data . '_customer' );
    $sql_where = '';

    $sql_where = '';
    if( ! empty( $q ) )
    {
        $sql_where = "(code LIKE '%" . $q . "%' OR fullname LIKE '%" . $q . "%' OR address LIKE '%" . $q . "%' OR phone LIKE '%" . $q . "%' OR email LIKE '%" . $q . "%')";
        $base_url .= '&q=' . $q;
    }
    
    $db->where( $sql_where ); //gan vao lenh where
    $sth = $db->prepare( $db->sql() );

    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select( '*' )->order( 'customer_id DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
    $sth = $db->prepare( $db->sql() );

    $sth->execute();
}

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'UPLOAD_CURRENT', NV_UPLOADS_DIR . '/shared' );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'ROW', $row );
$xtpl->assign( 'Q', $q );

if( $show_view )
{
    $xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );

    $number = 0;
    while( $view = $sth->fetch() )
    {
        $view['number'] = ++$number;
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;customer_id=' . $view['customer_id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_customer_id=' . $view['customer_id'] . '&amp;delete_checkss=' . md5( $view['customer_id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
        $view['edit_time'] = date( 'd/m/Y H:i', $view['edit_time'] );
        $view['status'] = $lang_module['active_' . $view['status']];
        $view['custype'] = $lang_module['custype_' . $view['custype']];
        $xtpl->assign( 'VIEW', $view );
        $xtpl->parse( 'main.view.loop' );
    }
    $xtpl->parse( 'main.view' );
}
else
{
    if( ! empty( $error ) )
    {
        $xtpl->assign( 'ERROR', implode( '<br />', $error ) );
        $xtpl->parse( 'main.add_customer.error' );
    }

    if( ! empty( $row['refer_fullname'] ) )
    {
        $xtpl->parse( 'main.add_customer.data_users' );
    }
    foreach( $array_select_status as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ( $key == $row['status'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.add_customer.select_status' );
    }

    foreach( $array_select_custype as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ( $key == $row['custype'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.add_customer.select_custype' );
    }


    $xtpl->parse( 'main.add_customer' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['customer'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
