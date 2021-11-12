<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 http://cash13.vn/slidehome/test/?p1=abc&p2=1235
 http://cash13.vn/index.php?nv=slidehome&op=test&p1=abc&p2=1237
 */

if ($userid  > 0)
{

    $array_data['useridedit'] = $nv_Request->get_int('useridedit', 'post', 0);//id npp can sua thong tin
    $array_data['agencyid'] = $nv_Request->get_int('typeid', 'post', 0);
    $array_data['email'] = $nv_Request->get_title('email', 'post', '');
    $array_data['fullname'] = $nv_Request->get_title('fullname', 'post', '');
    $array_data['mobile']  = $nv_Request->get_title('mobile', 'post', '', 1);
    $array_data['address']  = $nv_Request->get_title('address', 'post', '', 1);
    $array_data['provinceid'] = $nv_Request->get_int('provinceid', 'post', 0);
    $array_data['districtid'] = $nv_Request->get_int('districtid', 'post', 0);
    $array_data['peopleid']  = $nv_Request->get_title('peopleid', 'post', '', 1);
    $array_data['photo_befor']  = $nv_Request->get_string('photo_befor', 'post', '');
    $array_data['photo_after']  = $nv_Request->get_string('photo_after', 'post', '');

    $check_phone = check_phone_avaible($array_data['mobile']);
    $check_exits_peopleid = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . '_affiliate_users WHERE peopleid=' . $db->quote( $array_data['peopleid'] ))->fetchColumn();


    if( empty( $array_data['fullname'] )){
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_fullname']
        );
    }elseif( empty( $array_data['mobile'] ) ){
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_mobile']
        );
    }
    elseif( $check_phone == 0 ){
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_mobile_wrong']
        );
    }
    elseif( $array_data['agencyid'] == 0 ){
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_agencyid']
        );
    }elseif( empty( $module_config['affiliate']['precode'] ) ){
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_precode']
        );
    }elseif( empty( $array_data['peopleid'] ) ){
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_required_peopleid']
        );
    }elseif( $check_exits_peopleid > 0 ){
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_exits_peopleid']
        );
    }elseif( empty( $array_data['photo_befor'] ) ){
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_required_photo_befor']
        );
    }elseif( empty( $array_data['photo_after'] ) ){
        $array_reponsive = array(
            'status' => 0,
            "message" => $lang_module['error_required_photo_after']
        );
    }
    else{

        if( $array_data['photo_befor'] != '' )
        {
            $file_name = $array_data['peopleid'] . '_1';
            $array_data['photo_befor'] = save_file_from_base64( $array_data['photo_befor'], $file_name );
        }
        if( $array_data['photo_after'] != '' )
        {
            $file_name = $array_data['peopleid'] . '_2';
            $array_data['photo_after'] = save_file_from_base64( $array_data['photo_after'], $file_name );
        }

        $array_data['mobile'] = $check_phone;

        if ( $array_data['useridedit'] == 0 ) {
            $result_return = nvCreateAgency( $array_data );
            if( $result_return['customer_id'] > 0 ){
                $nv_Cache->delMod($module_name);
                $array_reponsive = $result_return;
                $array_reponsive['typename'] = isset( $array_agency[$array_reponsive['agencyid']] )? $array_agency[$array_reponsive['agencyid']]['title'] : 'N/A';
                $array_reponsive['province'] = isset( $array_province[$array_reponsive['provinceid']] )? $array_province[$array_reponsive['provinceid']]['title'] : 'N/A';
                $array_reponsive['district'] = '';
                $array_reponsive['status'] = 1;
                $array_reponsive['message'] = 'Tạo tài khoản NPP, ĐL thành công. Dưới đây là thông tin đăng nhập. Mật khẩu chỉ xuất hiện một lần duy nhất vì vậy hãy copy thông tin này và gửi cho NPP, ĐL tuyến dưới ngay để tránh thất lạc!';
            }else{
                $array_reponsive = array(
                    'status' => 0,
                    "message" => $result_return['message']
                );
            }

        } elseif ( $array_data['userid'] > 0 ) {
            //tren app chua lam nhg de san
            $result = nv_check_email_change( $array_data['email'], $array_data['userid'] );
            if( !empty( $result )){
                $error[] = $result;
            }else{
                $customer_name = explode(' ', $array_data['fullname'] );
                $total_str = count( $customer_name );
                $first_name = $customer_name[$total_str-1];
                unset( $customer_name[$total_str-1] );
                $last_name = implode(' ', $customer_name );

                $stmt = $db->prepare('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET email=:email, last_name=:last_name, first_name=:first_name WHERE userid =' . $array_data['userid']);
                $stmt->bindParam(':email', $array_data['email'], PDO::PARAM_STR);
                $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $stmt->execute();

                $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET agencyid=' . $array_data['agencyid'] . ', mobile=:mobile, datatext=:datatext, provinceid=:provinceid, districtid=:districtid, edit_time=' . NV_CURRENTTIME . ' WHERE userid =' . $array_data['userid']);
                $stmt->bindParam(':datatext', serialize( $array_data ), PDO::PARAM_STR);
                $stmt->bindParam(':mobile', $array_data['mobile'], PDO::PARAM_STR);
                $stmt->bindParam(':provinceid', $array_data['provinceid'], PDO::PARAM_INT);
                $stmt->bindParam(':districtid', $array_data['districtid'], PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount()) {
                    $nv_Cache->delMod($module_name);
                    $array_data['customer_id'] = $array_data['userid'];
                    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&return=2&data=' . base64_encode( serialize( $array_data ) ));
                } else {
                    $error[] = $lang_module['errorsave'];
                }
            }
        }
    }
    echo json_encode($array_reponsive);
}
else
{
    echo json_encode(array()); 
}
