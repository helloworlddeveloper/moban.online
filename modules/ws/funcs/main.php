<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

$username = $nv_Request->get_title('username', 'post');
$password = $nv_Request->get_title('password', 'post');

$array = array(
    "fullname" => "Trần Thị Hương",
    "username" => $username,
    "mobile" => "0912555888",
    "email" => "huongtt@cash13.vn",
    "website" => "huongtran.cash13.vn",
);

echo json_encode($array);