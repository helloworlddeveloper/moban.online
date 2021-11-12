<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 22 Nov 2014 03:27:55 GMT
 */

if( ! defined( 'NV_IS_MOD_RM' ) ) die( 'Stop!!!' );

//update trang thai khach moi sau 3h neu khong co check in se mac dinh la khong den hoi thao
$time_check = NV_CURRENTTIME - 10800;
$data_listevents = $db->query( "SELECT * FROM " . NV_PREFIXLANG . '_' . $module_data . "_listevents WHERE status=1 AND timeevent<"  . $time_check )->fetchAll();
foreach ($data_listevents as $data ){

    $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_usersevents SET status=2 WHERE status=1 AND eventid=' . $data['id'] );
    $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_listevents SET status=2 WHERE id=' . $data['id'] );
}

if( $module_config[$module_name]['sms_on'] == 1 ){

    set_time_limit(0);
    $apikey = $module_config[$module_name]['apikey'];
    $secretkey = $module_config[$module_name]['secretkey'];
    $sms_type = $module_config[$module_name]['sms_type'];
    $url = '';
    if( $sms_type == 2 ){
        $url = '&Brandname=' . $module_config[$module_name]['brandname'];
    }
    $db->sqlreset()
        ->select('t2.*, t1.timeevent, t1.addressevent, t1.title')
        ->from('' . NV_PREFIXLANG . '_' . $module_data . '_listevents AS t1')
        ->join('INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_smscontent AS t2 ON t2.eventid=t1.id')
        ->where( 't1.status=1 AND t2.status=1');

    $result = $db->query( $db->sql() );

    while ($view = $result->fetch()) {
        $time_action = $view['hoursend'] * 3600;
        $time_action = NV_CURRENTTIME - $time_action;

        //kiem tra gio gui thong tin
        if( $view['timeevent'] > $time_action ){
            //gui cho khach tham gia su kien
            $result_i = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_usersevents WHERE status=1 AND eventid=' . $view['id']);
            while ($customer = $result_i->fetch()) {
                $customer['eventname'] = $view['title'];
                $customer['timeevent'] = $view['timeevent'];
                $customer['addressevent'] = $view['addressevent'];
                $content = nv_build_content_customer( $view['content'], $customer);
                $content = urlencode($content);
                $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $customer['mobile'] . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;

                $curl = curl_init($data);
                curl_setopt($curl, CURLOPT_FAILONERROR, true);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $result_sms = curl_exec($curl);
                $obj = json_decode($result_sms, true);

                if ($obj['CodeResult'] == '100') {
                 //   $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_smscontent SET status=2 WHERE id=' . $view['id']);
                }else{
                    //gui loi
                }
            }
            //gui  cho npp neu
            if( $view['sendusers'] == 1 ){
                $result_i = $db->query( 'SELECT t1.mobile, t1.deviceid, t2.* FROM ' . NV_TABLE_AFFILIATE . '_users t1, ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.status=1 AND t1.userid=t2.userid');
                while ($customer = $result_i->fetch()) {

                    $customer['eventname'] = $view['title'];
                    $customer['timeevent'] = $view['timeevent'];
                    $customer['addressevent'] = $view['addressevent'];
                    $customer['sex'] = 0;
                    if( $customer['gender'] == 'F'){
                        $customer['sex'] = 2;
                    }elseif( $customer['gender'] == 'M'){
                        $customer['sex'] = 1;
                    }
                    $customer['full_name'] = nv_show_name_user( $customer['first_name'], $customer['last_name'] );
                    $content = nv_build_content_customer( $view['content'], $customer);
                    if( $customer['deviceid'] != '' ){
                        //thong bao qua app
                        $title = nv_build_content_customer( $view['title'], $customer);
                        //GUI TIN NHAN SANG MODULE NOTIFICATION
                        $mid = 0;
                        $url = $icon = '';
                        $sql = 'INSERT INTO ' . NV_TABLE_NOTIFICATION . '_message_queue(id, mid, userid, url, icon, title, receiver, content, timesend, active ) 
                        VALUES (NULL,  ' . $mid . ', ' . $customer['userid'] . ', ' . $db->quote($url) . ', ' . $db->quote($icon) . ', ' . $db->quote($title) . ', ' . $db->quote($customer['deviceid']) . ', ' . $db->quote($content) . ', ' . NV_CURRENTTIME. ', 1)';

                        $db->query($sql);

                    }else{
                        //thong bao qua sms
                        $content = urlencode($content);
                        $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $customer['mobile'] . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;
                        //die($data);
                        $curl = curl_init($data);
                        curl_setopt($curl, CURLOPT_FAILONERROR, true);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                       // chua gui sms $result = curl_exec($curl);
                        $obj = json_decode($result, true);

                        if ($obj['CodeResult'] == '100') {
                            //
                        }else{
                            //gui loi
                        }
                    }
                }
            }
        }
        $db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_smscontent SET status=2 WHERE id=' . $view['id']);
    }
    exit( 'SOK' );
}



exit( 'NOK' );
