<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Fri, 20 Mar 2015 02:51:05 GMT
 */

$action = $nv_Request->get_title('action', 'post', '');

if($action == 'review')
{
    $khoahocid = $nv_Request->get_int('khoahocid', 'post', 0);
    $checkress = $nv_Request->get_title('checkress', 'post', '');
    $revirecontent = $nv_Request->get_textarea('reviewcontent', 'post');

    $array_return['status'] = 0;
    $array_return['message'] = $lang_module['sendreview_error'];
    if($checkress == md5($user_info['userid'] . $khoahocid))
    {
        //ghi vao bang luot xem
        $query = "INSERT INTO " . NV_PREFIXLANG . '_' . $module_data . "_review VALUES (NULL," . $user_info['userid'] . "," . $khoahocid . "," . $db->quote($revirecontent) . "," . NV_CURRENTTIME . ",0);";
        if($db->query($query))
        {
            $array_return['status'] = 1;
            $array_return['message'] = $lang_module['sendreview_ok'];
            $nv_Request->set_Session($module_data . '_review_content', NV_CURRENTTIME);
        }
    }
    exit(json_encode($array_return, true));
}
