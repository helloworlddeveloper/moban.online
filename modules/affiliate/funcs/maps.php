<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_MOD_AFFILIATE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_USER' ) )
{
    $redirect = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=maps', true );
    Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_base64_encode( $redirect ) );
    die();
}
$checkss = $nv_Request->get_string('checkss', 'get,post', '');
$userid = $nv_Request->get_int('userid', 'get,post', 0);


if( $nv_Request->isset_request( 'setactive', 'post' )){

    if ( $checkss != md5($userid . $global_config['sitekey'] . session_id())) {
        exit('Error!');
    }
    $array_reponsive = array('status' => 0, 'message' => $lang_module['message_reponsive_active_0'] );
    if( $user_data_affiliate['permission'] == 1 ){
        $sql = 'SELECT status FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE status=0 AND userid = ' . $userid;

        $result = $db->query( $sql );
        list( $active ) = $result->fetch(3);

        if ($active == 0) {
            $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET status=1 WHERE userid=' . $userid;
            $result = $db->query($sql);
            $sql = 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET active=1 WHERE userid=' . $userid;
            $result = $db->query($sql);
            $db->query('UPDATE ' . $db_config['prefix'] . '_regsite SET status=1 WHERE userid=' . $userid );
            $nv_Cache->delMod($module_name);
            $hour_current = date('H', NV_CURRENTTIME );
            if( $hour_current > 7 ){
                $text_show = $lang_module['message_reponsive_active_21'];
            }else{
                $text_show = $lang_module['message_reponsive_active_22'];
            }
            $array_reponsive = array('status' => 1, 'message' => sprintf( $lang_module['message_reponsive_active_2'],  $text_show ) );
        }
    }

    echo  json_encode( $array_reponsive );
    exit();
}
else if( $nv_Request->isset_request('del', 'post', 0) ){

    if ( $checkss != md5($userid . $global_config['sitekey'] . session_id())) {
        exit('Error!');
    }
    $array_reponsive = array('status' => 0, 'message' => $lang_module['message_reponsive_active_0'] );
    $content = 'NO_' . $userid;

    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $userid;
    $data_users = $db->query($sql)->fetch();

    if( $user_data_affiliate['permission'] == 1 || $data_users['parentid'] == $user_info['userid'] ){
        if (!empty($data_users)  ) {

            $time_pendingdelete = NV_CURRENTTIME + 86400;
            $content = 'Tai khoan co ma ' . $data_users['code'] . ', sdt: ' . $data_users['mobile'] . ' se bi xoa vinh vien sau ' . date('H:i d/m/Y', $time_pendingdelete ) . '. Ban co the huy bo lenh xoa truoc thoi diem tren tai phan SO DO CAY!';
            call_funtion_send_sms($content, $user_data_affiliate['mobile'] );

            $content = 'Tai khoan cua ban co nguy co bi xoa vinh vien sau ' . date('H:i d/m/Y', $time_pendingdelete ) . ' vi khong hoat dong! Hay lien he ngay sdt ' . $user_data_affiliate['mobile'] . ' neu ban can huy lenh xoa ngay nhe!';
            call_funtion_send_sms($content, $user_data_affiliate['mobile'] );

            $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET pendingdelete=' . $time_pendingdelete . ' WHERE userid=' . $data_users['userid'];
            $db->query($sql);

            $array_reponsive = array('status' => 1, 'message' => $lang_module['message_reponsive_delete_2'] );
            /*
            $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid = ' . $userid;
            if ($db->exec($sql)) {
                $db->exec('DELETE FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $userid );
                nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete users', 'ID: ' . $userid, $user_info['userid']);
                if( $data_users['parentid'] > 0 ){
                    //update lai thong tin tuyen tren
                    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $data_users['parentid'];
                    $data_users_parent = $db->query($sql)->fetch();
                    $subcatid = explode(',', $data_users_parent['subcatid'] );

                    $key = array_search($userid, $subcatid);
                    if (false !== $key) {
                        unset($subcatid[$key]);
                    }
                    $data_users_parent['subcatid'] = implode(',', $subcatid );

                    $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET numsubcat=numsubcat-1, subcatid=' . $db->quote( $data_users_parent['subcatid'] ) . ' WHERE userid=' . $data_users['parentid'];
                    $db->query($sql);

                }
                $nv_Cache->delMod($module_name);

            }
            */
        }
    }
    echo  json_encode( $array_reponsive );
    exit();
}else if( $nv_Request->isset_request('indel', 'post', 0) ){

    if ( $checkss != md5($userid . $global_config['sitekey'] . session_id())) {
        exit('Error!');
    }
    $array_reponsive = array('status' => 0, 'message' => 'Error!' );

    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $userid . ' AND pendingdelete> 0';
    $data_users = $db->query($sql)->fetch();

    if( !empty($data_users) && $user_data_affiliate['permission'] == 1 || $data_users['parentid'] == $user_info['userid'] ){
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET pendingdelete=0 WHERE userid=' . $data_users['userid'];
        $db->query($sql);
        $array_reponsive = array('status' => 1, 'message' => sprintf( $lang_module['message_reponsive_indelete'], $data_users['mobile'] ) );
    }
    echo  json_encode( $array_reponsive );
    exit();
}elseif( $nv_Request->isset_request('loadsub', 'get', 0) ){
    if($userid > 0 && md5($userid . $global_config['sitekey'] . session_id()) == $checkss ){
        $array_data = get_sub_nodes_shops( $userid );
        if( !empty( $array_data )){
            $contents = nv_affilate_maps_sub( $array_data );
            include NV_ROOTDIR . '/includes/header.php';
            echo $contents;
            include NV_ROOTDIR . '/includes/footer.php';
            exit;
        }
    }else{
        exit('');
    }
}

$page_title = $lang_module['maps'];
$array_search['user_code'] = $nv_Request->get_title('user_code', 'get', '');
$array_search['province'] = $nv_Request->get_int('province', 'get', 0);


$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );


$contents = $xtpl->text( 'search' );
$array_nodes = array();

$sql = 'SELECT t1.*, t2.numsubcat, t2.code, t2.possitonid, t2.lev, t2.agencyid, t2.provinceid, t2.status, t2.pendingdelete FROM ' . NV_USERS_GLOBALTABLE . ' AS t1 INNER JOIN ' . $db_config['prefix'] . '_' . $module_data . '_users AS t2 ON t1.userid=t2.userid WHERE t2.ishidden=0 AND t1.userid =' . $user_info['userid'];
$res = $db->query( $sql );
$array_data = $res->fetch();

if( $array_data['numsubcat'] > 0){
    $array_data['data'] = get_sub_nodes_shops( $array_data['userid'] );
}

$contents = nv_affilate_maps( $array_data );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
