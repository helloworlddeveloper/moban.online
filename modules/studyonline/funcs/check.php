<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 20 Mar 2015 02:51:05 GMT
 */

$lession_id = $nv_Request->get_int('lesson_id', 'post', 1);
$video_id = $nv_Request->get_int('video_id', 'post', 0);
if($lession_id > 0)
{
    if(($result = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_baihoc WHERE id=' . $lession_id)) !== false)
    {
        $news_contents = $result->fetch();
        $news_contents['list_video'] = unserialize($news_contents['list_video']);
        $path_show = NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['list_video'][$video_id]['video_path'];
        
        $array_return = array();
        $array_return['type'] = 1;//1 = url play noi bo, 2 dung qua wowza, 3 cua youtube
        $array_return['status'] = 0;
        $array_return['value'] = '';    
        if(file_exists($path_show))
        {
            //cau hih cua wowza streaming
            if($module_config[$module_name]['streaming'] == 1)
            {
                $pathinfo = pathinfo($path_show);
                $array_return['status'] = 1;
                $array_return['type'] = 2;
                $array_return['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['image'];
                $array_return['value'] = $module_config[$module_name]['server_streaming'] . $pathinfo['extension'] . ':' . $news_contents['list_video'][$video_id]['video_path'] . '/playlist.m3u8';
            }
            else
            {
                $userid_view = (isset($user_info)) ? $user_info['userid'] : 0;
                $file_name = md5($news_contents['id'] . '_' . $userid_view . '_' . NV_CURRENTTIME);

                $content_baigiang = array(
                    'numview' => 0,
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    'path_file' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['list_video'][$video_id]['video_path'],
                    'sesionid' => NV_CHECK_SESSION);
                
                $array_return['status'] = 1;
                $array_return['type'] = 1;
                $array_return['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['image'];
                $array_return['value'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=view/' . $file_name, true);
                
                file_put_contents(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $module_name . '_' . $file_name, serialize($content_baigiang));                
            }
        }
        elseif(nv_is_url($news_contents['list_video'][$video_id]['video_path']) and preg_match(NV_PREG_URL_YOUTUBE, $news_contents['list_video'][$video_id]['video_path'], $matches))
        {
            $array_return['status'] = 1;
            $array_return['type'] = 3;
            $array_return['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $news_contents['image'];
            $array_return['value'] = $matches[1];
        }
        exit(json_encode($array_return, true));
    }
}
