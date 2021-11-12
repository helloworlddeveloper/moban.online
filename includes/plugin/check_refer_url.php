<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 07 Mar 2015 03:43:56 GMT
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$ref = $nv_Request->get_int('ref', 'get', 0);
if( $ref > 0 ){
    $nv_Request->set_Session('affiliate_ref', $ref );
}