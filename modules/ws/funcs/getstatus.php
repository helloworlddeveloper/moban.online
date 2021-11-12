<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 * Lay thong tin don hang tu don hang dat truoc cua npp
 */

if ($userid > 0 )
{

    $array_status[] = array(
        'id' => '2_0',
         'name' => $lang_module['status_ordertype_0']
    );
    $array_status[] = array(
        'id' => '2_4',
        'name' => $lang_module['status_ordertype_4']
    );
    $array_status[] = array(
        'id' => '1_0',
        'name' => $lang_module['history_payment_no']
    );
    $array_status[] = array(
        'id' => '1_4',
        'name' => $lang_module['history_payment_yes']
    );
    echo json_encode(  $array_status );
}
else
{
    echo json_encode(array());
}
