<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2017 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-01-2017 20:08
 */

if(! defined('NV_IS_MOD_MESSAGE'))
    die('Stop!!!');

if($nv_Request->isset_request('getnotification', 'get'))
{

    $ch = curl_init();

    $headers = array
    (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    curl_setopt( $ch,CURLOPT_URL, API_URL_FIREBASE );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

    //lay thong bao voi su kien can bao dung gio
    $timeout = NV_CURRENTTIME - intval($module_config[$module_name]['timeview']) * 60;

    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_message_queue WHERE receiver!='' AND active = 1 AND (timesend < " . NV_CURRENTTIME . " AND timesend >= " . $timeout . " )";

    $result = $db->query($sql);
   while ( $data = $result->fetch() ){

       $datasend = array(
           "to" => $data['receiver'], // topic or with device id
           "notification" => array( "title" => $data['title'], "body" => $data['content'] ),
           "data" => array( "title" => $data['title'], "body" => $data['content'],"icon" => $data['icon'], "click_action" => $data['url'])
       );
       $data_string = json_encode($datasend);


       curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);
       $return = curl_exec ($ch);

       //$result_return = nv_call_curl($data_string);
       $return = json_decode( $return, true);

       if( $return['success'] == 1 ){
           $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_message_history(id, mid, userid, url, icon, title, receiver, content, timesend, timesent, smsid, status ) 
                        VALUES (NULL,  ' . $data['mid'] . ', ' . $data['userid'] . ', ' . $db->quote($data['url']) . ', ' . $db->quote($data['icon']) . ', ' . $db->quote($data['title']) . ', ' . $db->quote($data['receiver']) . ', ' . $db->quote($data['content']) . ', ' . intval($data['timesend']) . ', ' . NV_CURRENTTIME . ', ' . $db->quote($return['results'][0]['message_id']) . ', 1)';

           $db->query($sql);
           $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_message_queue WHERE id=' . $data['id']);
       }else{
           //loi thi xoa
           $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_message_queue WHERE id=' . $data['id']);
       }
   }

    curl_close ( $ch );

    $data = json_encode(array('statusok' => 0));
    exit($data);
}
$contents = 'no-content';
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
