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

$file_check = isset($array_op[1]) ? $array_op[1] : '';
$path_show = '';
if(! empty($file_check))
{
    if(file_exists(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $module_name . '_' . $file_check))
    {
        $content_file = file_get_contents(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $module_name . '_' . $file_check);
        $content_file = unserialize($content_file);
        $content_file['numview']++;

        file_put_contents(NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $module_name . '_' . $file_check, serialize( $content_file ));
        if( $_SERVER['HTTP_USER_AGENT'] == $content_file['user_agent'] && $content_file['sesionid'] == NV_CHECK_SESSION)
        {
            include NV_ROOTDIR . '/modules/' . $module_file . '/VideoStream.php';
            $path_show = NV_ROOTDIR . $content_file['path_file'];
            $stream = new VideoStream($path_show);
            $stream->start();
            exit;
        }
    }
    exit('Error');

}