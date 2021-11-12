<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2017 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-01-2017 20:08
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

define( 'NV_IS_MESSAGE_ADMIN', true );


$allow_func = array( 
    'main', 'add', 'message-queue', 'message-sent', 'config'
);
define('NV_TABLE_AFFILIATE', $db_config['prefix'] . '_affiliate');

$sql = "SELECT * FROM " . NV_TABLE_AFFILIATE . "_agency WHERE status=1 ORDER BY weight";
$array_agency = $nv_Cache->db($sql, 'id', 'affiliate');

$array_agency[0] = array( 'id' => 0, 'title' => $lang_module['all_device']);
ksort( $array_agency );

$array_personal_messenger = array(
    '[ALIAS]' => $lang_module['content_note_alias'],
    '[FULLNAME]' => $lang_module['content_note_fullname'],
    '[FIRST_NAME]' => $lang_module['content_note_firstname'],
    '[LAST_NAME]' => $lang_module['content_note_lastname'],
    '[MOBILE]' => $lang_module['content_note_phone'],
    '[EMAIL]' => $lang_module['content_note_email'],
    '[ADDRESS]' => $lang_module['content_note_address'],
    '[SITE_NAME]' => sprintf($lang_module['content_note_site_name'], $global_config['site_name']),
    '[SITE_DOMAIN]' => sprintf($lang_module['content_note_site_domain'], NV_MY_DOMAIN)
);

//update noi dung message
function nvUpdatemsQueue( $mid, $active, $insert )
{
    global $db, $module_data;

    //tao kich ban khi them ban ghi moi
    if( $insert == 1 ){
        //lay thong tin bang header
        $data_message = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $mid )->fetch();
        $data_message['message'] = nv_unhtmlspecialchars($data_message['message']);
        $data_message['description'] = nv_unhtmlspecialchars($data_message['description']);
        $data_message['icon'] = !empty( $data_message['icon'] )? NV_MY_DOMAIN . $data_message['icon'] : '';
        //gui cho theo Nhom
        if( $data_message['groupsend'] != 0 ){
            $result = $db->query('SELECT t1.userid, t1.code, t1.mobile, t1.agencyid, t1.provinceid, t1.datatext, t1.deviceid, t2.email, t2.first_name, t2.last_name, t2.gender FROM ' . NV_TABLE_AFFILIATE . '_users t1, ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.userid=t2.userid AND t1.agencyid IN (' . $data_message['groupsend'] . ')' );

        }else{
            //nhom = 0 la tat ca
            $result = $db->query('SELECT t1.userid, t1.code, t1.mobile, t1.agencyid, t1.provinceid, t1.datatext, t1.deviceid, t2.email, t2.first_name, t2.last_name, t2.gender FROM ' . NV_TABLE_AFFILIATE . '_users t1, ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.userid=t2.userid' );
        }

        while( $row = $result->fetch()){
            $row['datatext'] = unserialize( $row['datatext'] );

            $row['address'] = $row['datatext']['address'];
            $row['fullname'] = nv_show_name_user(  $row['first_name'],  $row['last_name'] );

            $message = nv_build_content_customer( $data_message['message'], $row);
            $description = nv_build_content_customer( $data_message['description'], $row);

            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_message_queue(id, mid, userid, url, icon, title, receiver, content, timesend, active ) 
                        VALUES (NULL,  ' . $mid . ', ' . $row['userid'] . ', ' . $db->quote($data_message['url']) . ', ' . $db->quote($data_message['icon']) . ', ' . $db->quote($message) . ', ' . $db->quote($row['deviceid']) . ', ' . $db->quote($description) . ', ' . intval($data_message['addtime']) . ', 1)';
            $db->query($sql);
        }
    }else{

        //lay thong tin bang header
        $data_message = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $mid )->fetch();
        $data_message['message'] = nv_unhtmlspecialchars($data_message['message']);
        $data_message['description'] = nv_unhtmlspecialchars($data_message['description']);
        $data_message['icon'] = !empty( $data_message['icon'] )? NV_MY_DOMAIN . $data_message['icon'] : '';

        $result = $db->query('SELECT id, userid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_message_queue WHERE mid=' . $mid );

        while ( list( $id, $userid ) = $result->fetch(3)) {
            $row = $db->query('SELECT t1.userid, t1.code, t1.mobile, t1.agencyid, t1.provinceid, t1.datatext, t1.deviceid, t2.email, t2.first_name, t2.last_name, t2.gender FROM ' . NV_TABLE_AFFILIATE . '_users t1, ' . NV_USERS_GLOBALTABLE . ' t2 WHERE t1.userid=t2.userid AND t1.userid=' . $userid )->fetch();
            $row['datatext'] = unserialize( $row['datatext'] );

            $row['address'] = $row['datatext']['address'];
            $row['fullname'] = nv_show_name_user(  $row['first_name'],  $row['last_name'] );

            $message = nv_build_content_customer( $data_message['message'], $row);
            $description = nv_build_content_customer( $data_message['description'], $row);

            $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_message_queue SET  
                    url=' . $db->quote($data_message['url']) . ',
                    icon=' . $db->quote($data_message['icon']) . ',
                    title=' . $db->quote($message) . ', 
                    receiver=' . $db->quote($row['deviceid']) . ', 
                    content=' . $db->quote($description) . ', 
                    timesend=' . $data_message['addtime'] . ' WHERE id=' . $id;
            $db->query($sql);

        }
    }
}

/**
 * nv_groups_post()
 *
 * @param mixed $groups_view
 * @return
 */
function nv_groups_post_message($groups_view)
{
    if (in_array(0, $groups_view)) {
        return array('0');
    }else{
        return $groups_view;
    }
}


function nv_build_content_customer( $content, $customer)
{
    global $global_config, $lang_module;

    // Thay the bien noi dung
    $array_replace = array(
        '[FULLNAME]' => !empty($customer['fullname']) ? $customer['fullname'] : $lang_module['customers'],
        '[MOBILE]' => $customer['mobile'],
        '[FIRST_NAME]' => !empty( $customer['first_name'] )? $customer['first_name'] : '',
        '[LAST_NAME]' => !empty( $customer['last_name'] )? $customer['last_name'] : '',
        '[EMAIL]' => $customer['email'],
        '[ADDRESS]' => $customer['address'],
        '[ALIAS]' => $lang_module['alias_' . strtolower($customer['gender'])],
        '[SITE_NAME]' => $global_config['site_name'],
        '[SITE_DOMAIN]' => NV_MY_DOMAIN
    );
    $html = '';
    foreach ($array_replace as $index => $value) {
        $html = str_replace($index, $value, $html);
        $content = str_replace($index, $value, $content);
    }
    return $content;
}

