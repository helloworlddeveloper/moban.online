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
    $sql = 'SELECT t1.code, t1.possitonid, t1.agencyid, t1.salary_day, t1.benefit, t1.subcatid, t1.numsubcat, t1.datatext, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_affiliate_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t2.userid=' . $user_info['userid'] . ' ORDER BY t1.sort ASC';
    $user_data_affiliate = $db->query($sql)->fetch();
    if( !empty( $user_data_affiliate )){
        $user_data_affiliate['datatext'] = unserialize( $user_data_affiliate['datatext'] );
        $mobile = $user_data_affiliate['datatext']['mobile'];
        if( !empty( $mobile )){
            list($domain) = $db->query("SELECT domain FROM " . $db_config['prefix'] . '_regsite WHERE mobile=' . $db->quote( $mobile ))->fetch(3);
            if( !empty( $domain ) && $domain != NV_SERVER_NAME ){
                Header('Location: http://' . $domain);
                die();
            }
        }
    }
}
$id_site = $array_domain[NV_SERVER_NAME];
if( $id_site > 0 && $id_site != 1 ){
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . "_regsite WHERE id=" . $id_site;
    $data_content = $db->query($sql)->fetch();
    $mobile_old = $_COOKIE[$module_data . '_client_access'];

    if( $mobile_old != $data_content['mobile'] ){
        $expire = (365 * 86400) + NV_CURRENTTIME;//het han sau 1 nam
        setcookie($module_data . '_client_access', $data_content['mobile'], $expire, '/', 'cash13.xyz' );
    }
}
else{
    $data_content = array();
}
$contents = nv_theme_daily_main( $data_content );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';