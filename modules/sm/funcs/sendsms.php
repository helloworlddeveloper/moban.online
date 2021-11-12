<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Sat, 15 Oct 2016 03:30:10 GMT
 */
if (!defined('NV_IS_MOD_SM')) die('Stop!!!');

$mod = $nv_Request->get_title('mod', 'get', '');
exit('0');
if ( $module_config[$module_name]['sms_on'] == 1) {
    
    set_time_limit(0);
    $apikey = $module_config[$module_name]['apikey'];
    $secretkey = $module_config[$module_name]['secretkey'];
    $sms_type = $module_config[$module_name]['sms_type'];
    $url = '';
    if( $sms_type == 2 ){
        $url = '&Brandname=' . $module_config[$module_name]['brandname'];
    }
    $result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_message_queue WHERE active=1 AND timesend<=' . NV_CURRENTTIME . ' ORDER BY timesend ASC' );
    //echo date('d/m/Y H:i', 1539068257 ); die('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_message_queue WHERE active=1 AND timesend<=' . NV_CURRENTTIME . ' ORDER BY timesend ASC');
    while ($row = $result->fetch()) {

        if( $row['sendtype'] == 1 ){
            $content = urlencode(strip_tags( $row['content'] ));
			$row['receiver'] = str_replace(array(' ', '.', '-'), array('','',''), $row['receiver']);
			
            $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $row['receiver'] . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;
            $curl = curl_init($data);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $result_sms = curl_exec($curl);
            $obj = json_decode($result_sms, true);

            if ($obj['CodeResult'] == '100') {

                $db->query( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_message_history( order_id, proid, sid, sid_detail, title, receiver, content, timesend, sendtype, timesent, smsid, status ) 
                VALUES (  ' . intval( $row['order_id'] ) . ', ' . intval( $row['proid'] ) . ', ' . intval( $row['sid'] ) . ', ' . intval( $row['sid_detail'] ) . ', ' . $db->quote( $row['title'] ) . ', ' . $db->quote( $row['receiver'] ) . ', ' . $db->quote( $row['content'] ) . ', ' . $row['timesend'] . ', ' .  $row['sendtype'] . ', ' .  NV_CURRENTTIME . ', ' . $db->quote( $obj['SMSID'] ) . ', 0)');

                $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_message_queue WHERE id=' . $row['id']);
            }else{
                if( $obj['CodeResult'] == '99'){
                    //sdt khong dung
                    $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_message_queue SET active=0 WHERE id=' . $row['id']);
                }
                print_r($obj);
                //ghi lai loi
            }
        }elseif( $row['sendtype'] == 2 ){
            //gui mail

        }else{
            //ban xuong app
        }
    }
    exit('OK');
}
exit('No query');