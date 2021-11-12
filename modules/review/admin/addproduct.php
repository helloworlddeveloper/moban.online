<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang <contact@vinades.vn>
 * @Copyright (C) 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( !defined( 'NV_IS_FILE_ADMIN' ) )
{
    die( 'Stop!!!' );
}

$rowcontent = array(
    'id' => '',
    'productid' => array(),
    'userid' => 0,
    'title' => '',
    'alias' => '',
    'image' => '',
    'status' => 1 );

$page_title = $lang_module['addproduct'];
$error = array();
$currentpath = NV_UPLOADS_DIR . '/' . $module_upload . '/';
$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );
if( $rowcontent['id'] > 0 )
{
    $rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_service where id=' . $rowcontent['id'] )->fetch();
    if( !empty( $rowcontent['id'] ) )
    {
        $rowcontent['mode'] = 'edit';
    }
    $page_title = $lang_module['product_edit'];

}

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
    $rowcontent['id'] = $nv_Request->get_int( 'id', 'post', 0 );
    $rowcontent['productid'] = $nv_Request->get_array( 'productid', 'post', array() );
    $rowcontent['userid'] = $nv_Request->get_int( 'userid', 'post', 0 );
    $rowcontent['fullname'] = nv_substr($nv_Request->get_title('fullname', 'post', '', 1), 0, 255);
    $rowcontent['address'] = nv_substr($nv_Request->get_title('address', 'post', '', 1), 0, 255);
    $rowcontent['phone'] = nv_substr($nv_Request->get_title('phone', 'post', '', 1), 0, 255);
    $rowcontent['email'] = nv_substr($nv_Request->get_title('email', 'post', '', 1), 0, 255);
    $rowcontent['description'] = $nv_Request->get_string('description', 'post', '', 1);
    $rowcontent['status'] = $nv_Request->get_int( 'status', 'post', 0 );
    $rowcontent['productid'] = implode(',', $rowcontent['productid'] );

    if( empty( $rowcontent['productid'] )){
        $error[] = $lang_module['error_no_productid'];
    }
    if( empty( $rowcontent['userid'] )){
        $error[] = $lang_module['error_no_userid'];
    }
    if( empty( $rowcontent['fullname'] )){
        $error[] = $lang_module['error_required_name'];
    }
    if( empty( $rowcontent['phone'] )){
        $error[] = $lang_module['error_required_phone'];
    }

    if( empty( $error ) )
    {
        if( $rowcontent['id'] == 0 )
        {
            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_service (
                productid, userid, fullname, address, phone, email, description, add_time, edit_time, status
            ) VALUES (
                 ' . $db->quote(  $rowcontent['productid'] ) . ',
                 ' . intval( $rowcontent['userid'] ) . ',
                 :fullname,
                 :address,
                 :phone,
                 :email,
                 :description,
                 ' . NV_CURRENTTIME . ',
                 ' . NV_CURRENTTIME . ',
                 ' . intval( $rowcontent['status'] ) . '
            )';

            $data_insert = array();
            $data_insert['fullname'] = $rowcontent['fullname'];
            $data_insert['address'] = $rowcontent['address'];
            $data_insert['phone'] = $rowcontent['phone'];
            $data_insert['email'] = $rowcontent['email'];
            $data_insert['description'] = $rowcontent['description'];

            $rowcontent['id'] = $db->insert_id( $sql, 'id', $data_insert );
            if( $rowcontent['id'] > 0 )
            {
                $nv_Cache->delMod( $module_name );
            }
            else
            {
                $error[] = $lang_module['errorsave'];
            }
        }
        else
        {
            try
            {
                $sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_service SET
                productid=' . $db->quote( $rowcontent['productid'] ) . ',
                userid=' . $rowcontent['userid'] . ',
                edit_time=' . NV_CURRENTTIME . ',
                status=' . $rowcontent['status'] . ',
                fullname=:fullname,
                address=:address,
                phone=:phone,
                email=:email,
                description=:description
                WHERE id =' . $rowcontent['id'] );

                $sth->bindParam( ':fullname', $rowcontent['fullname'], PDO::PARAM_STR );
                $sth->bindParam( ':address', $rowcontent['address'], PDO::PARAM_STR );
                $sth->bindParam( ':phone', $rowcontent['phone'], PDO::PARAM_STR );
                $sth->bindParam( ':email', $rowcontent['email'], PDO::PARAM_STR );
                $sth->bindParam( ':description', $rowcontent['description'], PDO::PARAM_STR );

                if( $sth->execute() )
                {
                    $nv_Cache->delMod( $module_name );
                }
                else
                {
                    $error[] = $lang_module['errorsave'];
                }
            }
            catch ( PDOException $Exception )
            {
                // Note The Typecast To An Integer!
                die( $Exception->getMessage() );
            }

        }
        if( empty( $error ) )
        {
            Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=product' );
            die();
        }
    }
}

$contents = '';

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'DATA', $rowcontent );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
if( !empty( $error ) )
{
    $xtpl->assign( 'ERROR', implode( '<br />', $error ) );
    $xtpl->parse( 'main.error' );
}
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'module_name', $module_name );

if( !empty( $rowcontent['productid'] )){

    $db->sqlreset()->select( 'id, ' . NV_LANG_DATA . '_title AS title' )->from( NV_IS_TABLE_SHOPS . '_rows' )->where( 'status=1 AND id IN (' .  $rowcontent['productid'] . ')');
    $sth = $db->prepare( $db->sql() );
    $sth->execute();
    while( list( $id, $title ) = $sth->fetch( 3 ) )
    {
        $xtpl->assign( 'PRODUCT', array(
            'id' => $id,
            'title' => $title,
        ) );
        $xtpl->parse( 'main.data_productid' );
    }
}

if( $rowcontent['userid'] > 0){

    $db->sqlreset()->select( 'userid, username,first_name, last_name, birthday, email' )->from( NV_USERS_GLOBALTABLE )->where( 'active=1 AND userid=' . $rowcontent['userid'] );

    $sth = $db->prepare( $db->sql() );
    $sth->execute();

    $array_data = array();
    while( list( $userid, $username, $first_name, $last_name, $birthday, $email ) = $sth->fetch( 3 ) )
    {
        $fullname = nv_show_name_user( $first_name, $last_name, $username );
        $xtpl->assign( 'USER', array(
            'userid' => $userid,
            'title' => $username . ' - ' . $fullname . ' - ' . $email,
        ) );
        $xtpl->parse( 'main.data_users' );
    }
}

$array_status = array( '0' => $lang_module['status_0'], '1' => $lang_module['status_1'] );
foreach( $array_status as $key => $_status )
{
    $sl = ( $key == $rowcontent['status'] ) ? ' selected="selected"' : '';
    $xtpl->assign( 'STATUS', array(
        'sl' => $sl,
        'key' => $key,
        'title' => $_status ) );
    $xtpl->parse( 'main.status' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
