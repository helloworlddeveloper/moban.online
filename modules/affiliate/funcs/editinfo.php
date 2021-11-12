<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

if (!defined('NV_IS_MOD_AFFILIATE')) die('Stop!!!');

if( ! defined( 'NV_IS_USER' ) )
{
    $redirect = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true );
    Header( 'Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_base64_encode( $redirect ) );
    die();
}
$array_data = array();
$savecat = $nv_Request->get_int('savecat', 'post', 0);
$return = $nv_Request->get_int('return', 'get', 0);
if (! empty($savecat)) {
    $array_data['userid'] = $user_info['userid'];
    $array_data['parentid']  = $nv_Request->get_int('parentid', 'post', 0);
    $array_data['mobile']  = $nv_Request->get_title('mobile', 'post', '', 1);
    $array_data['address']  = $nv_Request->get_title('address', 'post', '', 1);
    $array_data['cmnd']  = $nv_Request->get_title('cmnd', 'post', '', 1);
    $array_data['ngaycap']  = $nv_Request->get_title('ngaycap', 'post', '', 1);
    $array_data['noicap']  = $nv_Request->get_title('noicap', 'post', '', 1);
    $array_data['stknganhang']  = $nv_Request->get_title('stknganhang', 'post', '', 1);
    $array_data['tennganhang']  = $nv_Request->get_title('tennganhang', 'post', '', 1);
    $array_data['chinhanh']  = $nv_Request->get_title('chinhanh', 'post', '', 1);

    $userid = $db->query('SELECT userid FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $array_data['userid'])->fetchColumn();

    if ( $array_data['userid'] > 0 && $userid == 0 ) {
        $weight = $db->query('SELECT max(weight) FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE parentid=' . $array_data['parentid'])->fetchColumn();
        $weight = intval($weight) + 1;
        $subcatid = '';

        $stmt = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $module_data . "_users (userid, parentid, datatext, weight, sort, lev, numsubcat, subcatid, add_time, edit_time, status) VALUES
			(:userid, :parentid, :datatext, :weight, '0', '0', '0', :subcatid, " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ", 0)");

        $stmt->bindParam(':userid', $array_data['userid'], PDO::PARAM_INT);
        $stmt->bindParam(':parentid', $array_data['parentid'], PDO::PARAM_INT);
        $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
        $stmt->bindParam(':subcatid', $subcatid, PDO::PARAM_STR);
        $stmt->bindParam(':datatext', serialize( $array_data ), PDO::PARAM_STR, strlen( serialize( $array_data ) ));
        $stmt->execute();

        if ($stmt->rowCount()) {
            nv_fix_users_order();
            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&return=1');
        } else {
            $error = $lang_module['errorsave'];
        }
    } elseif ( $array_data['userid'] > 0 ) {
        $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET datatext=:datatext, edit_time=' . NV_CURRENTTIME . ' WHERE userid =' . $array_data['userid']);
        $stmt->bindParam(':datatext', serialize( $array_data ), PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount()) {
            $nv_Cache->delMod($module_name);
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&return=2');
        } else {
            $error = $lang_module['errorsave'];
        }
    } else {
        $error = $lang_module['error_username'];
    }
}else{
    $array_data = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $user_info['userid'])->fetch();
    $datatext = unserialize( $array_data['datatext'] );
    $array_data['mobile'] = $datatext['mobile'];
    $array_data['address'] = $datatext['address'];
    $array_data['cmnd'] = $datatext['cmnd'];
    $array_data['ngaycap'] = $datatext['ngaycap'];
    $array_data['noicap'] = $datatext['noicap'];
    $array_data['stknganhang'] = $datatext['stknganhang'];
    $array_data['tennganhang'] = $datatext['tennganhang'];
    $array_data['chinhanh'] = $datatext['chinhanh'];

}

if( $array_data['parentid'] > 0){
    $array_tmp = $db->query('SELECT userid, username,first_name, last_name, birthday, email FROM ' . NV_USERS_GLOBALTABLE  . ' WHERE userid=' . $array_data['parentid'])->fetch();
    $array_data['username'] = $array_tmp['username'];
    $array_data['birthday'] = ( $array_tmp['birthday'] > 0) ? date('d/m/Y', $array_tmp['birthday'] ) : '';
    $array_data['email'] = $array_tmp['email'];
    $array_data['fullname'] = nv_show_name_user( $array_data['first_name'], $array_data['last_name'], $array_data['username'] );
}else{
    if( isset( $array_data['lev'] ) && $array_data['lev'] == 0 ){
        $array_data['fullname'] = $lang_module['youareroot'];
    }else{
        $array_data['fullname'] = $lang_module['pending_refer'];
    }
    $array_tmp = $db->query('SELECT userid, username,first_name, last_name, birthday, email FROM ' . NV_USERS_GLOBALTABLE  . ' WHERE userid=' . $user_info['userid'])->fetch();
    $array_data['username'] = nv_show_name_user( $array_tmp['first_name'], $array_tmp['last_name'], $array_tmp['username'] );
    $array_data['birthday'] = ( $array_tmp['birthday'] > 0) ? date('d/m/Y', $array_tmp['birthday'] ) : '';
    $array_data['email'] = $array_tmp['email'];
}
$array_data['status_text'] = $lang_module['status_' . $array_data['status']];
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$contents = nv_theme_affiliate_info( $array_data, $return );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
