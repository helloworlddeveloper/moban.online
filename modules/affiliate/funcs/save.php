<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang <thangbv@edus.vn>
 * @Copyright (C) 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if(! defined('NV_IS_MOD_AFFILIATE'))
{
    die('Stop!!!');
}

if( $nv_Request->isset_request( 'ngaycong', 'get' ) )
{
    $userid = $nv_Request->get_int( 'userid', 'post', 0 );
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $value = $nv_Request->get_float( 'value', 'post', 0 );
    $datetime_key = $nv_Request->get_int( 'datetime_key', 'post', 0 );

    $result = $db->query( "SELECT teacherid FROM " . NV_PREFIXLANG . "_" . $module_data . "_chamcong WHERE teacherid = " . $userid . ' AND id=' . $id );
    $_tmp_user = $result->fetch();
    if( empty( $_tmp_user ) && $datetime_key > 0 ){
        $numrow =  $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_chamcong WHERE datetime=' . $datetime_key . ' AND teacherid=' . $userid )->fetchColumn();
        if( $numrow == 0 ){
            $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data .
                '_chamcong (teacherid, datetime, checkin, checkout, infocheck, numcheck, ngaycong, dimuon, dimuoncophep, vesom, vesomcophep, note, status, admincheck) 
                            VALUES (:teacherid, :datetime, :checkin, :checkout, :infocheck, :numcheck, :ngaycong, :dimuon, :dimuoncophep, :vesom, :vesomcophep, :note, :status, :admincheck)');
            $stmt->bindParam(':teacherid', $userid, PDO::PARAM_INT);
            $stmt->bindParam(':datetime', $datetime_key, PDO::PARAM_INT);
            $stmt->bindValue(':checkin', '', PDO::PARAM_STR);
            $stmt->bindValue(':checkout', '', PDO::PARAM_STR);
            $stmt->bindValue(':infocheck', '', PDO::PARAM_STR);
            $stmt->bindValue(':numcheck', 0, PDO::PARAM_INT);
            $stmt->bindParam(':ngaycong', $value, PDO::PARAM_INT);
            $stmt->bindValue(':dimuon', 0, PDO::PARAM_INT);
            $stmt->bindValue(':dimuoncophep', 0, PDO::PARAM_INT);
            $stmt->bindValue(':vesom', 0, PDO::PARAM_INT);
            $stmt->bindValue(':vesomcophep', 0, PDO::PARAM_INT);
            $stmt->bindValue(':note', '', PDO::PARAM_STR);
            $stmt->bindValue(':status', 0, PDO::PARAM_INT);
            $stmt->bindValue(':admincheck', 0, PDO::PARAM_INT);
            $exc = $stmt->execute();
        }else{
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_chamcong SET ngaycong=" . $value . " WHERE datetime=" . $datetime_key . ' AND teacherid=' . $userid;
        }
    }
    elseif( !empty( $_tmp_user)){
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_chamcong SET ngaycong=" . $db->quote( $value ) . " WHERE id=" . $id;
        if( $db->query( $sql ) )
        {
            exit( 'OK' );
        }
        else
        {
            exit( 'ERROR_' . $lang_module['error_save_sql'] );
        }
    }
    else{
        exit( 'ERROR_' . $lang_module['error_data_save'] );
    }
        

    
}
elseif( $nv_Request->isset_request( 'checkcophep', 'get' ) )
{
    $userid = $nv_Request->get_int( 'userid', 'post', 0 );
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $value = $nv_Request->get_int( 'value', 'post', 0 );
    $type = $nv_Request->get_int( 'type', 'post', 0 );

    $result = $db->query( "SELECT teacherid FROM " . NV_PREFIXLANG . "_" . $module_data . "_chamcong WHERE teacherid = " . $userid . ' AND id=' . $id );
    $_tmp_user = $result->fetch();
    if( empty( $_tmp_user ) )
        exit( 'ERROR_' . $lang_module['error_data_save'] );

    if($type == 1){
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_chamcong SET vesomcophep=" . $value . " WHERE id=" . $id;
    }else{
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_chamcong SET dimuoncophep=" . $value . " WHERE id=" . $id;
    }

    if( $db->query( $sql ) )
    {
        exit( 'OK' );
    }
    else
    {
        exit( 'ERROR_' . $lang_module['error_save_sql'] );
    }
}

elseif( $nv_Request->isset_request( 'status', 'get' ) )
{
    $userid = $nv_Request->get_int( 'userid', 'post', 0 );
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $value = $nv_Request->get_int( 'value', 'post', 0 );

    $result = $db->query( "SELECT teacherid FROM " . NV_PREFIXLANG . "_" . $module_data . "_chamcong WHERE teacherid = " . $userid . ' AND id=' . $id );
    $_tmp_user = $result->fetch();
    if( empty( $_tmp_user ) )
        exit( 'ERROR_' . $lang_module['error_data_save'] );

    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_chamcong SET status=" . $value . ", admincheck=" . $user_info['userid'] . " WHERE id=" . $id;
    if( $db->query( $sql ) )
    {
        exit( 'OK' );
    }
    else
    {
        exit( 'ERROR_' . $lang_module['error_save_sql'] );
    }
}
elseif( $nv_Request->isset_request( 'ghichu', 'get' ) )
{
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $userid = $nv_Request->get_int( 'userid', 'post', 0 );
    $note_name = $nv_Request->get_string( 'note_name', 'post', '' );
    $datetime_key = $nv_Request->get_int( 'datetime_key', 'post', 0 );

    if( $id > 0 ){
        $result = $db->query( "SELECT teacherid FROM " . NV_PREFIXLANG . "_" . $module_data . "_chamcong WHERE teacherid = " . $userid . ' AND id=' . $id );
        $_tmp_user = $result->fetch();
        if( empty( $_tmp_user ) )
            exit( 'ERROR_' . $lang_module['error_data_save'] );

        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_chamcong SET note=" . $db->quote( $note_name ) . " WHERE id=" . $id;
    }else{
        $numrow =  $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_chamcong WHERE datetime=' . $datetime_key . ' AND teacherid=' . $userid )->fetchColumn();
        if( $numrow == 0 ){
            $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data .
                '_chamcong (teacherid, datetime, checkin, checkout, infocheck, numcheck, ngaycong, dimuon, dimuoncophep, vesom, vesomcophep, note, status, admincheck) 
                            VALUES (:teacherid, :datetime, :checkin, :checkout, :infocheck, :numcheck, :ngaycong, :dimuon, :dimuoncophep, :vesom, :vesomcophep, :note, :status, :admincheck)');
            $stmt->bindParam(':teacherid', $userid, PDO::PARAM_INT);
            $stmt->bindParam(':datetime', $datetime_key, PDO::PARAM_INT);
            $stmt->bindValue(':checkin', '', PDO::PARAM_STR);
            $stmt->bindValue(':checkout', '', PDO::PARAM_STR);
            $stmt->bindValue(':infocheck', '', PDO::PARAM_STR);
            $stmt->bindValue(':numcheck', 0, PDO::PARAM_INT);
            $stmt->bindValue(':ngaycong', 0, PDO::PARAM_INT);
            $stmt->bindValue(':dimuon', 0, PDO::PARAM_INT);
            $stmt->bindValue(':dimuoncophep', 0, PDO::PARAM_INT);
            $stmt->bindValue(':vesom', 0, PDO::PARAM_INT);
            $stmt->bindValue(':vesomcophep', 0, PDO::PARAM_INT);
            $stmt->bindParam(':note', $note_name, PDO::PARAM_STR);
            $stmt->bindValue(':status', 0, PDO::PARAM_INT);
            $stmt->bindValue(':admincheck', 0, PDO::PARAM_INT);
            $exc = $stmt->execute();
        }else{
            $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_chamcong SET note=" . $db->quote( $note_name ) . " WHERE datetime=" . $datetime_key . ' AND teacherid=' . $userid;
        }

    }

    if( $db->query( $sql ) )
    {
        exit( 'OK' );
    }
    else
    {
        exit( 'ERROR_' . $lang_module['error_save_sql'] );
    }
}
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
