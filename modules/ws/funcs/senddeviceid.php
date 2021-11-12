<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ($userid != '0')
{
    $action = $nv_Request->get_int('action', 'post', 0);
    $deviceid = $nv_Request->get_title('deviceid', 'post', '');

    if( $action == 1 ){
        $db->query('UPDATE ' . NV_IS_TABLE_AFFILIATE . '_users SET deviceid=' . $db->quote( $deviceid ) . ' WHERE userid =' . $userid);
    }else{
        $db->query('UPDATE ' . NV_IS_TABLE_AFFILIATE . '_users SET deviceid=' . $db->quote( $deviceid ) . ' WHERE userid =' . $userid);
    }
    $array = array('status' => 'ok');
    echo json_encode($array);
}
else
{
    $array = array('status' => 'no allow');
    echo json_encode(array()); 
}