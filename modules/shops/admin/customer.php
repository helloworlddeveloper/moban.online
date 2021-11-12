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

if( $nv_Request->isset_request( 'sadmin', 'get' ) )
{
    $q = $nv_Request->get_title( 'term', 'get', '', 1 );
    if( empty( $q ) )
        return;

    $db->sqlreset()->select( 't1.userid, t1.username, t1.email, t1.full_name' )->from( NV_USERS_GLOBALTABLE . ' AS t1 INNER JOIN ' . NV_AUTHORS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.admin_id' )->where( 't1.full_name LIKE :fullname OR t1.username LIKE :username OR t1.email LIKE :email' )->limit( 50 );

    $sth = $db->prepare( $db->sql() );
    $sth->bindValue( ':fullname', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':username', '%' . $q . '%', PDO::PARAM_STR );
    $sth->bindValue( ':email', '%' . $q . '%', PDO::PARAM_STR );
    $sth->execute();

    $array_data = array();
    while( list( $userid, $username, $email, $fullname ) = $sth->fetch( 3 ) )
    {
        $array_data[] = array( 'key' => $userid, 'value' => $fullname . ' - ' . $username . ' - ' . $email );
    }

    header( 'Cache-Control: no-cache, must-revalidate' );
    header( 'Content-type: application/json' );

    ob_start( 'ob_gzhandler' );
    echo json_encode( $array_data );
    exit();

}
if( $nv_Request->isset_request( 'delete_customer_id', 'get' ) and $nv_Request->isset_request( 'delete_checkss', 'get' ) )
{
    $customer_id = $nv_Request->get_int( 'delete_customer_id', 'get' );
    $delete_checkss = $nv_Request->get_string( 'delete_checkss', 'get' );
    if( $customer_id > 0 and $delete_checkss == md5( $customer_id . NV_CACHE_PREFIX . $client_info['session_id'] ) )
    {
        $db->query( 'DELETE FROM ' . TABLE_SHARE . '_customer  WHERE customer_id = ' . $db->quote( $customer_id ) );
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
}

$row = array();
$error = array();
$row['customer_id'] = $nv_Request->get_int( 'customer_id', 'post,get', 0 );
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
    if( ! isset( $array_chuc_danh['kinhdoanh'] ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
    $row['fullname'] = $nv_Request->get_title( 'fullname', 'post', '' );
    $row['address'] = $nv_Request->get_title( 'address', 'post', '' );
    $row['province'] = $nv_Request->get_int( 'province', 'post', 0 );
    $row['phone'] = $nv_Request->get_title( 'phone', 'post', '' );
    $row['email'] = $nv_Request->get_title( 'email', 'post', '' );
    $row['facebook'] = $nv_Request->get_title( 'facebook', 'post', '' );
    $row['jobs'] = $nv_Request->get_int( 'jobs', 'post', 0 );
    $row['passport'] = $nv_Request->get_title( 'passport', 'post', '' );
    $row['description'] = $nv_Request->get_string( 'description', 'post', '' );
    $row['status'] = $nv_Request->get_int( 'status', 'post', 0 );
    $row['fromby'] = $nv_Request->get_int( 'fromby', 'post', 0 );
    $row['customer_type'] = $nv_Request->get_int( 'cus_type_value', 'post', 0 );

    $row['company_name'] = $nv_Request->get_title( 'company_name', 'post', '' );
    $row['ten_nh'] = $nv_Request->get_title( 'ten_nh', 'post', '' );
    $row['stk_nh'] = $nv_Request->get_title( 'stk_nh', 'post', '' );
    $row['tentk_nh'] = $nv_Request->get_title( 'tentk_nh', 'post', '' );
    $row['company_mst'] = $nv_Request->get_title( 'company_mst', 'post', '' );
    $row['company_address'] = $nv_Request->get_title( 'company_address', 'post', '' );
    $row['company_phone'] = $nv_Request->get_title( 'company_phone', 'post', '' );
    $row['company_email'] = $nv_Request->get_title( 'company_email', 'post', '' );
    $row['company_gpkd'] = $nv_Request->get_title( 'company_gpkd', 'post', '' );
    $row['company_fax'] = $nv_Request->get_title( 'company_fax', 'post', '' );

    $row['rtag'] = $nv_Request->get_array( 'add_tag', 'post', array() );

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
    if( $row['customer_type'] == 1 || $row['customer_type'] == 2 )
    {
        if( empty( $row['company_name'] ) )
        {
            $error[] = $lang_module['error_required_company_name'];
        }
        if( empty( $row['company_address'] ) )
        {
            $error[] = $lang_module['error_required_company_address'];
        }
    }

    if( empty($row['rtag'])){
        $error[] = $lang_module['error_required_rtag'];
    }

    $sql = "SELECT customer_type FROM " . TABLE_SHARE . "_customer WHERE phone LIKE '%" . $row['phone'] . "%' AND fullname LIKE '%" . $row['fullname'] . "%' AND customer_id !=" . $row['customer_id'];
    $data_exist = $db->query( $sql )->fetch();

    if( ! empty( $data_exist ) )
    {
        $error[] = sprintf( $lang_module['customer_exits'], $lang_module['customer_type_' . $data_exist['customer_type']] );
    }
    if( empty( $error ) )
    {
        try
        {
            $row['edit_time'] = NV_CURRENTTIME;
            if( empty( $row['customer_id'] ) )
            {

                $sql = 'INSERT INTO ' . TABLE_SHARE . '_customer  (user_id, fullname, address, province, phone, email, company_name, facebook, jobs, description, ten_nh, stk_nh, tentk_nh, passport, company_mst, company_address, company_phone, company_email, company_gpkd, company_fax, add_time, edit_time, status, fromby, customer_type) VALUES (:user_id, :fullname, :address, :province, :phone, :email, :company_name, :facebook, :jobs, :description, :ten_nh, :stk_nh, :tentk_nh, :passport, :company_mst, :company_address, :company_phone, :company_email, :company_gpkd, :company_fax, :add_time, :edit_time, :status, :fromby, :customer_type)';

                $data_insert = array();
                $data_insert['user_id'] = $admin_info['admin_id'];
                $data_insert['fullname'] = $row['fullname'];
                $data_insert['address'] = $row['address'];
                $data_insert['province'] = $row['province'];
                $data_insert['phone'] = $row['phone'];
                $data_insert['email'] = $row['email'];
                $data_insert['facebook'] = $row['facebook'];
                $data_insert['jobs'] = $row['jobs'];
                $data_insert['company_name'] = $row['company_name'];
                $data_insert['description'] = $row['description'];

                $data_insert['ten_nh'] = $row['ten_nh'];
                $data_insert['stk_nh'] = $row['stk_nh'];
                $data_insert['tentk_nh'] = $row['tentk_nh'];
                $data_insert['passport'] = $row['passport'];
                $data_insert['company_mst'] = $row['company_mst'];
                $data_insert['company_address'] = $row['company_address'];
                $data_insert['company_phone'] = $row['company_phone'];
                $data_insert['company_email'] = $row['company_email'];
                $data_insert['company_gpkd'] = $row['company_gpkd'];
                $data_insert['company_fax'] = $row['company_fax'];


                $data_insert['add_time'] = NV_CURRENTTIME;
                $data_insert['edit_time'] = NV_CURRENTTIME;
                $data_insert['status'] = $row['status'];
                $data_insert['fromby'] = $row['fromby'];
                $data_insert['customer_type'] = $row['customer_type'];
                $customer_id = $db->insert_id( $sql, 'customer_id', $data_insert );
                if( $customer_id > 0 )
                {
                    if( ! empty( $row['rtag'] ) )
                    {
                        foreach( $row['rtag'] as $tag )
                        {
                            $db->query( 'INSERT INTO ' . TABLE_SHARE . '_rtag ( tagid, customer_id ) VALUES ( ' . $tag . ', ' . $customer_id . ' )' );
                        }
                    }
                    nv_delete_all_cache();
                    Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
                    die();
                }
            }
            else
            {
                $stmt = $db->prepare( 'UPDATE ' . TABLE_SHARE . '_customer SET fullname = :fullname, address = :address, province = :province, phone = :phone, email = :email, company_name = :company_name, facebook=:facebook, jobs=:jobs, description = :description, ten_nh=:ten_nh ,stk_nh=:stk_nh ,tentk_nh=:tentk_nh ,passport=:passport ,company_mst=:company_mst ,company_address=:company_address ,company_phone=:company_phone ,company_email=:company_email ,company_gpkd=:company_gpkd ,company_fax=:company_fax, status = :status, fromby=:fromby, customer_type=:customer_type, edit_time=:edit_time WHERE customer_id=' . $row['customer_id'] );
                $stmt->bindParam( ':fullname', $row['fullname'], PDO::PARAM_STR );
                $stmt->bindParam( ':address', $row['address'], PDO::PARAM_STR );
                $stmt->bindParam( ':province', $row['province'], PDO::PARAM_INT );
                $stmt->bindParam( ':phone', $row['phone'], PDO::PARAM_STR );
                $stmt->bindParam( ':email', $row['email'], PDO::PARAM_STR );
                $stmt->bindParam( ':company_name', $row['company_name'], PDO::PARAM_STR );
                $stmt->bindParam( ':ten_nh', $row['ten_nh'], PDO::PARAM_STR );
                $stmt->bindParam( ':stk_nh', $row['stk_nh'], PDO::PARAM_STR );
                $stmt->bindParam( ':tentk_nh', $row['tentk_nh'], PDO::PARAM_STR );
                $stmt->bindParam( ':passport', $row['passport'], PDO::PARAM_STR );
                $stmt->bindParam( ':company_mst', $row['company_mst'], PDO::PARAM_STR );
                $stmt->bindParam( ':company_address', $row['company_address'], PDO::PARAM_STR );
                $stmt->bindParam( ':company_phone', $row['company_phone'], PDO::PARAM_STR );
                $stmt->bindParam( ':company_email', $row['company_email'], PDO::PARAM_STR );
                $stmt->bindParam( ':company_gpkd', $row['company_gpkd'], PDO::PARAM_STR );
                $stmt->bindParam( ':company_fax', $row['company_fax'], PDO::PARAM_STR );
                $stmt->bindParam( ':facebook', $row['facebook'], PDO::PARAM_STR );
                $stmt->bindParam( ':jobs', $row['jobs'], PDO::PARAM_INT );
                $stmt->bindParam( ':description', $row['description'], PDO::PARAM_STR, strlen( $row['description'] ) );
                $stmt->bindParam( ':status', $row['status'], PDO::PARAM_INT );
                $stmt->bindParam( ':fromby', $row['fromby'], PDO::PARAM_INT );
                $stmt->bindParam( ':customer_type', $row['customer_type'], PDO::PARAM_INT );
                $stmt->bindParam( ':edit_time', $row['edit_time'], PDO::PARAM_INT );

                $exc = $stmt->execute();
                if( $exc )
                {
                    $db->query( 'DELETE FROM ' . TABLE_SHARE . '_rtag WHERE customer_id=' . $row['customer_id'] );
                    if( ! empty( $row['rtag'] ) )
                    {
                        foreach( $row['rtag'] as $tag )
                        {
                            $db->query( 'INSERT INTO ' . TABLE_SHARE . '_rtag ( tagid, customer_id ) VALUES ( ' . intval( $tag ) . ', ' . intval( $row['customer_id'] ) . ' )' );
                        }
                    }
                    nv_delete_all_cache( );
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
    if( ! isset( $array_chuc_danh['kinhdoanh'] ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
    $row = $db->query( 'SELECT * FROM ' . TABLE_SHARE . '_customer WHERE customer_id=' . $row['customer_id'] )->fetch();
    if( empty( $row ) )
    {
        Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
        die();
    }
    $row['rtag'] = $db->query( 'SELECT * FROM ' . TABLE_SHARE . '_rtag WHERE customer_id=' . $row['customer_id'] )->fetchAll();
}
else
{
    $row['customer_id'] = 0;
    $row['name'] = '';
    $row['address'] = '';
    $row['province'] = 0;
    $row['phone'] = '';
    $row['email'] = '';
    $row['company_name'] = '';
    $row['description'] = '';
    $row['status'] = 1;
    $row['fromby'] = 1;
    $row['jobs'] = 0;
    $row['rtag'] = array();
    $row['customer_type'] = 0;
}

//$row['customer_type'] = ( $row['customer_type'] == 1 ) ? ' checked=checked' : '';

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
    $fromby = $nv_Request->get_int( 'fromby', 'post,get', 0 );
    $tagid = $nv_Request->get_int( 'customer_tag', 'post,get', 0 );
    $customer_type = $nv_Request->get_int( 'customer_type', 'post,get', -1 );
    $aid = $nv_Request->get_int( 'aid', 'post,get', 0 );
    if( $aid > 0 )
    {
        $sql = "SELECT userid, username, email, full_name FROM " . NV_USERS_GLOBALTABLE . " WHERE userid=" . $aid;
        $data_admin = $db->query( $sql )->fetch();

    }

    $sql = ' SELECT COUNT(*) FROM ' . TABLE_SHARE . '_customer';

    $db->sqlreset()->select( 'COUNT(*)' )->from( '' . TABLE_SHARE . '_customer AS t1' );
    $sql_where = '';

    $sql_where = '';
    if( ! empty( $q ) )
    {
        $sql_where = "(t1.fullname LIKE '%" . $q . "%' OR t1.address LIKE '%" . $q . "%' OR t1.phone LIKE '%" . $q . "%' OR t1.email LIKE '%" . $q . "%' OR t1.company_name LIKE '%" . $q . "%')";
        $base_url .= '&q=' . $q;
    }
    if( $fromby > 0 )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND t1.fromby=' . $fromby : 'fromby=' . $fromby;
        $base_url .= '&fromby=' . $fromby;
    }
    if( $customer_type > -1 )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND t1.customer_type=' . $customer_type : 't1.customer_type=' . $customer_type;
        $base_url .= '&customer_type=' . $customer_type;
    }
    if( $aid > 0 )
    {
        $sql_where .= ( ! empty( $sql_where ) ) ? ' AND t1.user_id=' . $aid : 't1.user_id=' . $aid;
        $base_url .= '&aid=' . $aid;
    }
    if( $tagid > 0 ){
        $db->join( ' LEFT JOIN ' . TABLE_SHARE . '_rtag AS t2 ON t1.customer_id=t2.customer_id' );

        $base_url .= '&customer_tag=' . $tagid;
        $sql_where .= ( ! empty( $sql_where ) ) ?  ' AND t2.tagid =' . $tagid : 't2.tagid =' . $tagid ;
    }


    $db->where( $sql_where ); //gan vao lenh where
    $sth = $db->prepare( $db->sql() );

    $sth->execute();
    $num_items = $sth->fetchColumn();

    $db->select( 't1.*' )->order( 't1.customer_id DESC' )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
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
if( ! empty( $data_admin ) )
{
    $xtpl->assign( 'ADMIN', $data_admin );
    $xtpl->parse( 'main.view.data_admin' );
}

$array_select_from = array();
$array_select_from[1] = $lang_module['customer_from_1'];
$array_select_from[2] = $lang_module['customer_from_2'];
$array_select_from[3] = $lang_module['customer_from_3'];
$array_select_from[4] = $lang_module['customer_from_4'];

if( $show_view )
{
    $sql = 'SELECT province_id, title FROM ' . TABLE_SHARE . '_province ORDER BY weight';
    $list_province = nv_db_cache( $sql, 'province_id', $module_name );
    $xtpl->assign( 'NV_GENERATE_PAGE', nv_generate_page( $base_url, $num_items, $per_page, $page ) );

    $number = 0;
    while( $view = $sth->fetch() )
    {
        $view['number'] = ++$number;
        $view['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;customer_id=' . $view['customer_id'];
        $view['link_view'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=viewcustomer&amp;customer_id=' . $view['customer_id'];
        $view['link_history'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=history&amp;customerid=' . $view['customer_id'];
        $view['link_delete'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_customer_id=' . $view['customer_id'] . '&amp;delete_checkss=' . md5( $view['customer_id'] . NV_CACHE_PREFIX . $client_info['session_id'] );
        $view['edit_time'] = date( 'd/m/Y H:i', $view['edit_time'] );
        $view['status'] = $lang_module['active_' . $view['status']];
        $view['customer_type'] = $lang_module['customer_type_' . $view['customer_type']];
        $view['province'] = ( isset( $list_province[$view['province']] ) ) ? $list_province[$view['province']]['title'] : '';
        $xtpl->assign( 'VIEW', $view );
        if( isset( $array_chuc_danh['kinhdoanh'] ) || defined( 'NV_IS_SPADMIN' ) )
        {
            $xtpl->parse( 'main.view.loop.admin' );
        }
        $xtpl->parse( 'main.view.loop' );
    }
    foreach( $array_select_from as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ( $key == $fromby ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.view.search_fromby' );
    }
    foreach( $array_customer_type_search as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ( $key == $customer_type ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.view.search_customer_type' );
    }
    foreach( $list_tags as $key => $tags )
    {
        $tags['selected'] = ( $key == $tagid ) ? ' selected="selected"' : '';
        $xtpl->assign( 'OPTION', $tags );
        $xtpl->parse( 'main.view.search_customer_tag' );
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

    $sql = 'SELECT province_id, title FROM ' . TABLE_SHARE . '_province ORDER BY weight';
    $list_province = nv_db_cache( $sql, 'province_id', $module_name );
    foreach( $list_province as $province )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $province['province_id'],
            'title' => $province['title'],
            'selected' => ( $province['province_id'] == $row['province'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.add_customer.select_province' );
    }

    $sql = 'SELECT jobs_id, jobs_name FROM ' . TABLE_SHARE . '_jobs ORDER BY weight';
    $list_jobs = nv_db_cache( $sql, 'jobs_id', $module_name );
    foreach( $list_jobs as $jobs )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $jobs['jobs_id'],
            'title' => $jobs['jobs_name'],
            'selected' => ( $jobs['jobs_id'] == $row['jobs'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.add_customer.select_jobs' );
    }

    $array_select_status = array();

    $array_select_status[0] = $lang_module['active_0'];
    $array_select_status[1] = $lang_module['active_1'];
    foreach( $array_select_status as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ( $key == $row['status'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.add_customer.select_status' );
    }

    foreach( $array_select_from as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ( $key == $row['fromby'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.add_customer.customer_from' );
    }

    foreach( $array_customer_type as $key => $title )
    {
        $xtpl->assign( 'OPTION', array(
            'key' => $key,
            'title' => $title,
            'selected' => ( $key == $row['customer_type'] ) ? ' selected="selected"' : '' ) );
        $xtpl->parse( 'main.add_customer.customer_type' );
    }
    foreach( $list_tags as $tag )
    {
        $tag['ck'] = '';
        foreach( $row['rtag'] as $taginfo )
        {
            if( $taginfo['tagid'] == $tag['tagid'] )
            {
                $tag['ck'] = 'checked=checked';
            }
        }
        $xtpl->assign( 'TAG', $tag );
        $xtpl->parse( 'main.add_customer.add_tag' );
    }
    if( empty( $row['customer_id'] ) )
    {
        $xtpl->parse( 'main.add_customer.auto_get_alias' );
    }
    $xtpl->parse( 'main.add_customer' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

$page_title = $lang_module['customer'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
