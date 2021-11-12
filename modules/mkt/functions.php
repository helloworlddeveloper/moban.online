<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 01:50:26 GMT
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';
define( 'NV_IS_MOD_RM', true );


if( $nv_Request->isset_request( 'submitladi', 'get,post' ) )
{
    $row['fullname'] = $nv_Request->get_title( 'fullname', 'post', '' );
    $pos = strrpos( $row['fullname'], ' ' );
    if( $pos === false )
    {
        $row['first_name'] = '';
        $row['last_name'] = $row['fullname'];
    }
    else
    {
        $row['first_name'] = substr( $row['fullname'], 0, $pos + 1 );
        $row['last_name'] = substr( $row['fullname'], $pos );
    }
    $row['event'] = $nv_Request->get_int( 'eventid', 'get', 0 );
    $row['address'] = $nv_Request->get_title( 'address', 'post', '' );
    $row['mobile'] = $nv_Request->get_title( 'mobile', 'post', '' );
    $row['mobilereferer'] = $nv_Request->get_title( 'mobilereferer', 'post', '' );
    $row['email'] = $nv_Request->get_title( 'email', 'post' );
    if( $row['mobilereferer'] != '' ){
        $sql = 'SELECT userid FROM ' . $db_config['prefix'] . '_affiliate_users WHERE mobile=' . $db->quote( $row['referer'] ) ;
        list( $row['userid'] ) = $db->query($sql)->fetch(3);
    }
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE mobile=' . $db->quote( $row['mobile'] ) ;
    $data_content = $db->query($sql)->fetch();
    if( !empty( $data_content )){
        if( $data_content['adminid'] != $row['userid'] ){
            $error = $lang_module['error_exits_data_by_other'];
        }else{
            $row['id'] = $data_content['id'];
        }
    }
    $check_phone = check_phone_avaible($row['mobile']);

    if( empty( $row['fullname'] ) )
    {
        $error = $lang_module['error_required_fullname'];
    }
    elseif( empty( $row['mobile'] ) )
    {
        $error = $lang_module['error_required_phone'];
    }
    elseif( $check_phone == 0 ){
        $error = $lang_module['error_mobile_wrong'];
    }
    elseif( ! empty( $row['email'] ) and ( $error_email = nv_check_valid_email( $row['email'] ) ) != '' )
    {
        $error = $error_email;
    }

    if( empty( $error ) )
    {
        try
        {

            $row['edit_time'] = $row['add_time'] = NV_CURRENTTIME;
            $row['from_by'] = $row['gmap_lat'] = $row['gmap_lng'] = $row['sex'] = $row['birthday'] = $row['provinceid'] = $row['districtid'] = $row['status'] = 0;
            $row['facebook'] = '';
            if( empty( $row['id'] ) )
            {
                $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (adminid, provinceid, districtid, first_name, last_name, full_name, birthday, sex, address, email, mobile, facebook, from_by, gmap_lat, gmap_lng, add_time, edit_time, mkt_time, remkt_time, status) 
                VALUES (' . intval( $row['userid'] ) . ', ' . $row['provinceid'] . ', 0, ' . $db->quote( $row['first_name'] ) . ', ' . $db->quote( $row['last_name'] ) . ', ' . $db->quote( $row['fullname'] ) . ', 0, 0, ' . $db->quote( $row['address'] ) . ', 
                ' . $db->quote( $row['email'] ) . ', ' . $db->quote( $row['mobile'] ) . ', ' . $db->quote( $row['facebook'] ) . ', 3, 0, 0, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 0, 0)';

                $data_insert = array();
                $id = $db->insert_id($sql, 'id', $data_insert);

                if( $id > 0 ){
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data .'_usersevents(eventid, customerid, full_name, sex, address, email, mobile, addtime, status) 
				    VALUES (' . intval( $row['event'] ) . ', ' . $id . ', ' . $db->quote( $row['fullname'] ) . ', 0, ' . $db->quote( $row['address'] ) . ', ' . $db->quote( $row['email'] ) . ', ' . $db->quote( $row['mobile'] ) . ', ' . NV_CURRENTTIME . ', 0)';
                    if( $db->query($sql) ){
                        //cap nhat so luong dang ky
                        $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET num_register=num_register+1 WHERE id=' . $row['event'] );

                        $note = sprintf( $lang_module['event_content'], $array_listevents[$row['event']]['title'], date('d/m/Y', $array_listevents[$row['event']]['timeevent'] ), $array_listevents[$row['event']]['addressevent']);
                        save_eventcontent( $id, NV_MEASURE_ID_ACCEPT, NV_EVENT_ID_REGISTER, $note );
                    }
                    die( 'OK' );
                }else{
                    die( 'ERROR' );
                }
            }
            else
            {
                $insert = 0;
                $stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data .' SET provinceid=:provinceid, districtid=:districtid, full_name=:full_name, first_name=:first_name, last_name=:last_name, birthday=:birthday, sex=:sex, address=:address, email=:email, mobile=:mobile, gmap_lat=:gmap_lat, gmap_lng=:gmap_lng, from_by=:from_by, edit_time=:edit_time, status=:status WHERE id=' . $row['id'] );
                $stmt->bindParam( ':provinceid', $row['provinceid'], PDO::PARAM_INT );
                $stmt->bindParam( ':districtid', $row['districtid'], PDO::PARAM_INT );
                $stmt->bindParam( ':first_name', $row['first_name'], PDO::PARAM_STR );
                $stmt->bindParam( ':last_name', $row['last_name'], PDO::PARAM_STR );
                $stmt->bindParam( ':full_name', $row['fullname'], PDO::PARAM_STR );
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
                    $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data .'_usersevents(eventid, customerid, full_name, sex, address, email, mobile, addtime, status) 
				    VALUES (' . intval( $row['event'] ) . ', ' . $row['id'] . ', ' . $db->quote( $row['fullname'] ) . ', 0, ' . $db->quote( $row['address'] ) . ', ' . $db->quote( $row['email'] ) . ', ' . $db->quote( $row['mobile'] ) . ', ' . NV_CURRENTTIME . ', 0)';
                    if( $db->query($sql) ){
                        //cap nhat so luong dang ky
                        $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET num_register=num_register+1 WHERE id=' . $row['event'] );

                        $note = sprintf( $lang_module['event_content'], $array_listevents[$row['event']]['title'], date('d/m/Y', $array_listevents[$row['event']]['timeevent'] ), $array_listevents[$row['event']]['addressevent']);
                        save_eventcontent( $row['id'], NV_MEASURE_ID_ACCEPT, NV_EVENT_ID_REGISTER, $note );
                    }
                    die( 'OK' );
                }
            }
        }
        catch ( PDOException $e )
        {
            trigger_error( $e->getMessage());
            die( 'Khách hàng ' . $row['last_name'] . ' đã được đăng ký vào sự kiện này rồi!'); //Remove this line after checks finished
        }
    }else{
        exit($error);
    }
    die('ggggggggggggg');
}


if( ! defined( 'NV_IS_USER' ) )
{
    $nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    $nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt( $nv_redirect );


    $info = $lang_module['login_users'] . "<br /><br />\n";
    $info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . NV_ASSETS_DIR .  "/images/load_bar.gif\"><br /><br />\n";
    $info .= '[<a href="' . $nv_redirect . '">' . $lang_module['redirect_to_login'] . '</a>]';

    $xtpl = new XTemplate( 'info_exit.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'INFO', $info );
    $xtpl->parse( 'main' );
    $contents =  $xtpl->text( 'main' );
    $contents .= '<meta http-equiv="refresh" content="2;url=' . nv_url_rewrite( $nv_redirect, true ) . '" />';

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme( $contents );
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}


// check user post content
$array_post_config = array();
$sql = 'SELECT group_id, addhistory FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config_mkt';
$result = $db->query( $sql );
while( list( $group_id, $addhistory ) = $result->fetch( 3 ) )
{
    $array_post_config[$group_id] = array( 'addhistory' => $addhistory );
}
$user_info['in_groups'] = is_array( $user_info['in_groups'] )? $user_info['in_groups'] : explode(',', $user_info['in_groups'] );
$flag_allow = 0;

foreach( $user_info['in_groups'] as $group_id_i )
{
    if( $group_id_i > 0 and isset( $array_post_config[$group_id_i] ) )
    {
        if( $array_post_config[$group_id_i]['addhistory'] )
        {
            $flag_allow = 1;
        }
    }
}

function check_phone_avaible( $string ){
    $string = str_replace(array('-', '.', ' '), '', $string);
    if (!preg_match('/^(01[2689]|03|05|07|08|09)[0-9]{8}$/', $string)){
        return 0;
    }
    return $string;

}

function get_sub_nodes_users( $subcatid )
{
    global $db_config, $db;
    $sub_users = '';
    if( !empty( $subcatid )){
        $sub_users = $subcatid;
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_affiliate_users WHERE userid IN( ' . $subcatid . ' )';
        $res = $db->query( $sql );
        while ($row = $res->fetch()){
            if( $row['numsubcat'] > 0 ){
                $sub_users = $sub_users . ','. get_sub_nodes_users( $row['subcatid'] );
            }
        }
    }
    return $sub_users;
}