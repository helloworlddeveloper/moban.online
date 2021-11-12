<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

$nv_username = $nv_Request->get_title('username', 'post');
$nv_password = $nv_Request->get_title('password', 'post');

if( $nv_username == ''){
    $array_reponsive = array(
        'status' => 0,
        "message" => "Bạn chưa nhập tên đăng nhập!"
    );
}elseif ($nv_password == ''){
    $array_reponsive = array(
        'status' => 0,
        "message" => "Bạn chưa nhập mật khẩu!"
    );
}else{
    if (nv_check_valid_email($nv_username) == '') {
        // Email login
        $nv_username = nv_strtolower($nv_username);
        $sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE email =" . $db->quote($nv_username);
        $login_email = true;
    } else {
        // Username login
        $sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE md5username ='" . nv_md5safe($nv_username) . "'";
        $login_email = false;
    }

    $row = $db->query($sql)->fetch();
    if (!empty($row)) {
        if ((($row['md5username'] == nv_md5safe($nv_username) and $login_email == false) or ($row['email'] == $nv_username and $login_email == true)) and $crypt->validate_password($nv_password, $row['password'])) {
            if (!$row['active']) {
                $array_reponsive = array(
                    'status' => "2",
                    "message" => $lang_module['login_no_active']
                );
            } else {

                //chi nho npp dang nhap
                $sql = 'SELECT * FROM ' . NV_IS_TABLE_AFFILIATE . '_users WHERE status=1 AND userid=' . $row['userid'];
                $array_data = $db->query( $sql )->fetch();
                if( $array_data['agencyid'] > 0 ){
                    $array_reponsive = array(
                        "id" => $row['userid'],
                        "fullname" => nv_show_name_user( $row['first_name'], $row['last_name'] ),
                        "username" => $row['username'],
                        "mobile" => "0912555888",
                        "email" => $row['email'],
                        "website" => "",
                        'status' => "1",
                    );
                    validUserLog($row, 1, '');
                }
                else{
                    $array_reponsive = array(
                        'status' => "2",
                        "message" => 'Bạn không được phép đăng nhập tài khoản trên ứng dụng này. Hãy truy cập website ' . NV_MY_DOMAIN . ' để đăng nhập'
                    );
                }
            }
        }else{
            $array_reponsive = array(
                'status' => "0",
                "message" => $nv_username . ' ' . "Hệ thống không tìm thấy tài khoản phù hợp theo thông tin của bạn!"
            );
        }
    }else{
        $array_reponsive = array(
            'status' => "0",
            "message" => "Hệ thống không tìm thấy tài khoản phù hợp theo thông tin của bạn!"
        );
    }
}

echo json_encode($array_reponsive);