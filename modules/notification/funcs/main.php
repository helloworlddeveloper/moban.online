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
    $checkallow = $nv_Request->get_int('checkallow', 'get', '');
    if($checkallow != md5($client_info['ip'] . session_id()))
    {
        $data = json_encode(array('statusok' => 0));
        exit($data);
    }
    else
    {
        //hien thi moi phien truy cap moi
        $begin_new_session = $nv_Request->get_title($module_name . '_begin_new_session', 'session');
        if($begin_new_session == '')
        {
            $nv_Request->set_Session($module_name . '_begin_new_session', session_id());
            $query = 'SELECT id, message, url, icon, author, allowed_view FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status = 1 AND showview=1 ORDER BY addtime DESC LIMIT 1';
            $result = $db->query($query);
            $data = $result->fetch();
            $data['id'] = 0;
            $data['popup'] = 0;  
        }
        else
        {
            //lay thong bao voi su kien can bao dung gio
            $timeout = NV_CURRENTTIME - intval($module_config[$module_name]['timeout']);
            $query = "SELECT id, message, url, icon, author, allowed_view FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE status = 1 AND showview=2 AND (addtime < " . NV_CURRENTTIME . " AND addtime >= " . $timeout . " ) LIMIT 1";
            $result = $db->query($query);
            $data = $result->fetch();
            if(empty($data))
            {
                $id = $nv_Request->get_int('notification', 'get', 0);
                if($id > 0)
                {
                    $query = "SELECT id, message, url, icon, author, allowed_view FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE status = 1 AND showview=0 AND id>" . $id . ' LIMIT 1';
                    $result = $db->query($query);
                    $data = $result->fetch();
                }
                else
                {
                    $getbytime = NV_CURRENTTIME - (intval($module_config[$module_name]['timeview']) * 60); // lay ve thoi diem hien tai - so phut cau hinh
                    $query = 'SELECT id, message, url, icon, author, allowed_view FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE status = 1 AND showview=0 AND addtime >=' . $getbytime . ' LIMIT 1';
                    $result = $db->query($query);
                    $data = $result->fetch();
                }
                $data['popup'] = 0;  
            }else{
              $data['id'] = 0;//gan = 0 de k thay doi thu thu ID goi thong bao tiep theo va hien thi popup
              $data['popup'] = 1;  
            }
        }
        if(! empty($data))
        {
            $data['statusok'] = (isset($data['allowed_view'])) ? nv_user_in_groups($data['allowed_view']) : 0;
            $data['iconimage'] = 0; //khong  co icon
            if(! empty($data['icon']))
            {
                if(file_exists(NV_ROOTDIR . '' . $data['icon']))
                {
                    //icon dang anh
                    $data['iconimage'] = 1;
                }
                else
                {
                    //icon dang font
                    $data['iconimage'] = 2;
                }
            }
            $data = json_encode($data);
            exit($data);
        }
    }
    $data = json_encode(array('statusok' => 0));
    exit($data);
}
$contents = '';
include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';

?>