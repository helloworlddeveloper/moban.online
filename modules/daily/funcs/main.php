<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if (!defined('NV_IS_MOD_SM')) {
    die('Stop!!!');
}

if (defined('NV_IS_USER')) {
    $sql = 'SELECT t1.code, t1.possitonid, t1.agencyid, t1.salary_day, t1.benefit, t1.subcatid, t1.numsubcat, t1.datatext, t1.mobile, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_affiliate_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t2.userid=' . $user_info['userid'] . ' ORDER BY t1.sort ASC';
    $user_data_affiliate = $db->query($sql)->fetch();

    if( !empty( $user_data_affiliate )){
        //$user_data_affiliate['datatext'] = unserialize( $user_data_affiliate['datatext'] );
        $mobile = $user_data_affiliate['mobile'];
        if( !empty( $mobile )){
            list($domain) = $db->query("SELECT domain FROM " . $db_config['prefix'] . '_regsite WHERE userid=' . $user_info['userid'] )->fetch(3);
            if( !empty( $domain ) && NV_SERVER_NAME == 'daily.cash13.vn' && $domain != NV_SERVER_NAME ){
                Header('Location: http://' . $domain);
                die();
            }
        }
    }
}

$id_site = $array_domain[NV_SERVER_NAME];
if( $id_site > 0 ){
    $sql = 'SELECT t1.*, t2.photo FROM ' . $db_config['prefix'] . "_regsite AS t1 LEFT JOIN " .NV_USERS_GLOBALTABLE . " AS t2 ON t1.userid=t2.userid WHERE t1.id=" . $id_site;

    $data_content = $db->query($sql)->fetch();

    if( $data_content['status'] == 0 ){
        //bao loi khi k kich hoat
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
        exit;
    }
    if( empty( $data_content['photo'] )){
        $data_content['photo'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/default.jpg';
    }else{
        $data_content['photo'] = NV_BASE_SITEURL . $data_content['photo'];
    }
    if( empty( $data_content['image_site'] )){
        $data_content['image_site'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/default.jpg';
    }else{
        $data_content['image_site'] = NV_BASE_SITEURL . $data_content['image_site'];
    }
    if(  $id_site != 1 && !defined('NV_IS_USER') ){
        $mobile_old = $_COOKIE[$module_data . '_client_access'];
        if( $mobile_old != $data_content['mobile'] ){
            $expire = (365 * 86400) + NV_CURRENTTIME;//het han sau 1 nam
            setcookie($module_data . '_client_access', $data_content['mobile'], $expire, '/', 'cash13.vn' );
        }
    }
}
else{
    $data_content = array();
}

$contents = nv_theme_daily_main( $data_content );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';