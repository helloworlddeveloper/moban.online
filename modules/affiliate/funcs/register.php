<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

if (!defined('NV_IS_MOD_AFFILIATE')) die('Stop!!!');

if (!defined('NV_IS_USER')) {
    $redirect = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op, true);
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_base64_encode($redirect));
    die();
}

$array_data = array();
$array_data['agencyid'] = $array_data['provinceid'] = $array_data['districtid'] = 0;
$savecat = $nv_Request->get_int('savecat', 'post', 0);
$return = $nv_Request->get_int('return', 'get', 0);
$data_result = $nv_Request->get_string('data', 'get', '');
$data_result = unserialize(base64_decode($data_result));
$error = array();
if ($return > 0 && !empty($data_result)) {
    $data_result['agency'] = isset($array_agency[$data_result['agencyid']]) ? $array_agency[$data_result['agencyid']] : '';

    $data_result['agency_info'] = sprintf($lang_module['chossen_agency_info_show'], number_format($data_result['agency']['price_require'], 0, '.', ','), $data_result['agency']['percent_sale'] . '%');
    $array_province = nv_Province();
    $array_district = nv_District($data_result['provinceid']);
    $data_result['province_name'] = isset($array_province[$data_result['provinceid']]) ? $array_province[$data_result['provinceid']]['title'] : 'N/A';
    $data_result['district_name'] = isset($array_district[$data_result['districtid']]) ? $array_district[$data_result['districtid']]['title'] : 'N/A';
    $contents = nv_theme_affiliate_notice($data_result, $return);

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}
$userid = $nv_Request->get_int('userid', 'get', 0);
if ($userid > 0) {
    $array_data = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $userid . ' AND parentid=' . $user_info['userid'])->fetch();
    if (empty($array_data)) {
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    }
    $array_user = $db->query('SELECT email, first_name, last_name, gender, birthday FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid=' . $userid)->fetch();
    $array_user['fullname'] = nv_show_name_user($array_user['first_name'], $array_user['last_name']);

    $datatext = unserialize($array_data['datatext']);
    $array_data = array_merge($array_data, $array_user);
    $array_data['mobile'] = $datatext['mobile'];
    $array_data['address'] = $datatext['address'];
    $array_data['cmnd'] = $datatext['cmnd'];
    $array_data['ngaycap'] = $datatext['ngaycap'];
    $array_data['noicap'] = $datatext['noicap'];
    $array_data['stknganhang'] = $datatext['stknganhang'];
    $array_data['tennganhang'] = $datatext['tennganhang'];
    $array_data['chinhanh'] = $datatext['chinhanh'];
    $array_data['photo_befor'] = $datatext['photo_befor'];
    $array_data['photo_after'] = $datatext['photo_after'];
    $array_data['gpkd'] = $datatext['gpkd'];
    $array_data['photo_shops'] = $datatext['photo_shops'];
    $array_data['photo_product_in_shops'] = $datatext['photo_product_in_shops'];
}

if (!empty($savecat)) {
    $array_data['userid'] = $nv_Request->get_int('userid', 'post', 0);
    $array_data['agencyid'] = $nv_Request->get_int('agencyid', 'post', 0);
    $array_data['email'] = $nv_Request->get_title('email', 'post', '');
    $array_data['fullname'] = $nv_Request->get_title('fullname', 'post', '');
    $array_data['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
    $array_data['mobile'] = $nv_Request->get_title('mobile', 'post', '', 1);
    $array_data['address'] = $nv_Request->get_title('address', 'post', '', 1);
    $array_data['provinceid'] = $nv_Request->get_int('provinceid', 'post', 0);
    $array_data['districtid'] = $nv_Request->get_int('districtid', 'post', 0);
    $array_data['birthday'] = $nv_Request->get_title('birthday', 'post', '');
    $array_data['gender'] = $nv_Request->get_title('gender', 'post', 0);
    $array_data['jobid'] = $nv_Request->get_int('jobid', 'post', 0);
    $array_data['istype'] = $nv_Request->get_int('istype', 'post', -1);
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $array_data['birthday'], $m)) {
        $array_data['birthday'] = mktime(0, 0, 0, $m[2], $m[1], $m[3]);
    } else {
        $array_data['birthday'] = 0;
    }

    $array_data['peopleid'] = $nv_Request->get_title('peopleid', 'post', '', 1);
    $file_allowed_ext[] = 'images';
    $pathSaveUrl = nv_affiliate_get_path_dir();
    $length_substr = strlen(NV_ROOTDIR . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/');

    //hinh anh sau khi upload hoac sua
    $array_data['photo_befor'] = $nv_Request->get_title('photo_befor', 'post', '');
    $array_data['photo_after'] = $nv_Request->get_title('photo_after', 'post', '');
    $array_data['gpkd'] = $nv_Request->get_title('gpkd', 'post', '');
    $array_data['photo_shops'] = $nv_Request->get_title('photo_shops', 'post', '');
    $array_data['photo_product_in_shops'] = $nv_Request->get_title('photo_product_in_shops', 'post', '');
    $upload = new NukeViet\Files\Upload($file_allowed_ext, $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);

    /*
    $array_data['ngaycap']  = $nv_Request->get_title('ngaycap', 'post', '', 1);
    $array_data['noicap']  = $nv_Request->get_title('noicap', 'post', '', 1);
    $array_data['stknganhang']  = $nv_Request->get_title('stknganhang', 'post', '', 1);
    $array_data['tennganhang']  = $nv_Request->get_title('tennganhang', 'post', '', 1);
    $array_data['chinhanh']  = $nv_Request->get_title('chinhanh', 'post', '', 1);
*/;

    $check_phone = check_phone_avaible($array_data['mobile'] . '');
    $check_exits_mobile = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . '_affiliate_users WHERE userid!= ' . $array_data['userid'] . ' AND mobile=' . $db->quote($array_data['mobile']))->fetchColumn();
	
    if (empty($array_data['fullname'])) {
        $error[] = $lang_module['error_fullname'];
    } if (empty($array_data['birthday'])) {
        $error[] = $lang_module['error_required_birthday'];
    } if ($array_data['gender'] == 'N') {
        $error[] = $lang_module['error_required_gender'];
		
    } if ($array_data['jobid'] == 0) {
        $error[] = $lang_module['error_required_job'];
    } if (empty($array_data['mobile'])) {
        $error[] = $lang_module['error_mobile'];
    } if ($check_phone == 0) {
        $error[] = $lang_module['error_mobile_wrong'];
    } if ($check_exits_mobile > 0) {
        $error[] = $lang_module['error_mobile_exits'];
    } if ($array_data['agencyid'] == 0) {
        $error[] = $lang_module['error_agencyid'];
    } if ($array_data['provinceid'] == 0) {
        $error[] = $lang_module['error_provinceid'];
    } if ($array_data['districtid'] == 0) {
        $error[] = $lang_module['error_districtid'];
    } if (empty($array_data['address'])) {
        $error[] = $lang_module['error_address'];
    } if (empty($module_config['affiliate']['precode'])) {
        $error[] = $lang_module['error_precode'];
    }

    if ($array_data['istype'] == -1){
        $error[] = $lang_module['error_required_istype'];
    }elseif($array_data['istype'] == 0){

        if ($_FILES['photo_befor']['tmp_name'] != '') {
            $upload_info = $upload->save_file($_FILES['photo_befor'], NV_ROOTDIR . '/' . $pathSaveUrl, false, true);

            @unlink($_FILES['photo_befor']['tmp_name']);
            if (!empty($upload_info['error'])) {
                $error[] = $upload_info['error'];
            } else {
                if (!empty($array_data['photo_befor'])) {
                    @unlink(NV_ROOTDIR . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_data['photo_befor']);
                }
                $array_data['photo_befor'] = substr($upload_info['name'], $length_substr);
            }
        }
        if ($_FILES['photo_after']['tmp_name'] != '') {
            $upload_info = $upload->save_file($_FILES['photo_after'], NV_ROOTDIR . '/' . $pathSaveUrl, false, true);
            @unlink($_FILES['photo_after']['tmp_name']);
            if (!empty($upload_info['error'])) {
                $error[] = $upload_info['error'];
            } else {
                if (!empty($array_data['photo_after'])) {
                    @unlink(NV_ROOTDIR . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_data['photo_after']);
                }
                $array_data['photo_after'] = substr($upload_info['name'], $length_substr);
            }
        }

        if (empty($array_data['peopleid'])) {
            $error[] = $lang_module['error_required_peopleid'];
        }else{
            $check_exits_peopleid = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . '_affiliate_users WHERE userid!= ' . $array_data['userid'] . ' AND peopleid=' . $db->quote($array_data['peopleid']))->fetchColumn();
            if ($check_exits_peopleid > 0) {
                $error[] = $lang_module['error_exits_peopleid'];
            }
        }  if (empty($array_data['photo_befor'])) {
            $error[] = $lang_module['error_required_photo_befor'];
        } if (empty($array_data['photo_after'])) {
            $error[] = $lang_module['error_required_photo_after'];
        }
    }elseif($array_data['istype'] == 1){
        if ($_FILES['gpkd']['tmp_name'] != '') {
            $upload_info = $upload->save_file($_FILES['gpkd'], NV_ROOTDIR . '/' . $pathSaveUrl, false, true);

            @unlink($_FILES['gpkd']['tmp_name']);
            if (!empty($upload_info['error'])) {
                $error[] = $upload_info['error'];
            } else {
                if (!empty($array_data['gpkd'])) {
                    @unlink(NV_ROOTDIR . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_data['gpkd']);
                }
                $array_data['gpkd'] = substr($upload_info['name'], $length_substr);
            }
        }
        if ($_FILES['photo_shops']['tmp_name'] != '') {
            $upload_info = $upload->save_file($_FILES['photo_shops'], NV_ROOTDIR . '/' . $pathSaveUrl, false, true);
            @unlink($_FILES['photo_shops']['tmp_name']);
            if (!empty($upload_info['error'])) {
                $error[] = $upload_info['error'];
            } else {
                if (!empty($array_data['photo_shops'])) {
                    @unlink(NV_ROOTDIR . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_data['photo_shops']);
                }
                $array_data['photo_shops'] = substr($upload_info['name'], $length_substr);
            }
        }
        if ($_FILES['photo_product_in_shops']['tmp_name'] != '') {
            $upload_info = $upload->save_file($_FILES['photo_product_in_shops'], NV_ROOTDIR . '/' . $pathSaveUrl, false, true);
            @unlink($_FILES['photo_product_in_shops']['tmp_name']);
            if (!empty($upload_info['error'])) {
                $error[] = $upload_info['error'];
            } else {
                if (!empty($array_data['photo_product_in_shops'])) {
                    @unlink(NV_ROOTDIR . NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $array_data['photo_product_in_shops']);
                }
                $array_data['photo_product_in_shops'] = substr($upload_info['name'], $length_substr);
            }
        }
        if (empty($array_data['gpkd'])) {
            $error[] = $lang_module['error_required_gpkd'];
        } if (empty($array_data['photo_shops'])) {
            $error[] = $lang_module['error_required_photo_shops'];
        }if (empty($array_data['photo_product_in_shops'])) {
            $error[] = $lang_module['error_required_photo_product_in_shops'];
        }
    }
   if( empty( $error )) {
        $array_data['mobile'] = $check_phone;
        $userid = $db->query('SELECT userid FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $array_data['userid'])->fetchColumn();

        if ($userid == 0) {
            $result_return = nvCreateAgency($array_data);
            if ($result_return['customer_id'] > 0) {

                $nv_Cache->delMod($module_name);
                $data_order['customer_id'] = $result_return['customer_id'];
                nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&return=1&data=' . base64_encode(serialize($result_return)));
            } else {
                $error[] = $result_return['mess_error'];
            }

        } elseif ($array_data['userid'] > 0) {

            $result = nv_check_email_change($array_data['email'], $array_data['userid']);
            if (!empty($result)) {
                $error[] = $result;
            } else {
                $customer_name = explode(' ', $array_data['fullname']);
                $total_str = count($customer_name);
                $first_name = $customer_name[$total_str - 1];
                unset($customer_name[$total_str - 1]);
                $last_name = implode(' ', $customer_name);

                $stmt = $db->prepare('UPDATE ' . NV_USERS_GLOBALTABLE . ' SET email=:email, last_name=:last_name, first_name=:first_name, gender=:gender, birthday=:birthday WHERE userid =' . $array_data['userid']);
                $stmt->bindParam(':email', $array_data['email'], PDO::PARAM_STR);
                $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
                $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
                $stmt->bindParam(':gender', $array_data['gender'], PDO::PARAM_STR);
                $stmt->bindParam(':birthday', $array_data['birthday'], PDO::PARAM_INT);
                $stmt->execute();

                $stmt = $db->prepare('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET agencyid=' . $array_data['agencyid'] . ', istype=' . $array_data['istype'] . ', mobile=:mobile, peopleid=:peopleid, datatext=:datatext, provinceid=:provinceid, districtid=:districtid, jobid=:jobid, edit_time=' . NV_CURRENTTIME . ' WHERE userid =' . $array_data['userid']);
                $stmt->bindParam(':datatext', serialize($array_data), PDO::PARAM_STR);
                $stmt->bindParam(':mobile', $array_data['mobile'], PDO::PARAM_STR);
                $stmt->bindParam(':peopleid', $array_data['peopleid'], PDO::PARAM_STR);
                $stmt->bindParam(':provinceid', $array_data['provinceid'], PDO::PARAM_INT);
                $stmt->bindParam(':districtid', $array_data['districtid'], PDO::PARAM_INT);
                $stmt->bindParam(':jobid', $array_data['jobid'], PDO::PARAM_INT);
                $stmt->execute();

                if ($stmt->rowCount()) {
                    $nv_Cache->delMod($module_name);
                    $array_data['customer_id'] = $array_data['userid'];
                    $data_return = array('customer_id' => $array_data['userid'], 'fullname' => $array_data['fullname'], 'agencyid' => $array_data['agencyid'], 'mobile' => $array_data['mobile'], 'provinceid' => $array_data['provinceid'], 'districtid' => $array_data['districtid'], 'mess_error' => array());

                    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&return=2&data=' . base64_encode(serialize($data_return)));
                } else {
                    $error[] = $lang_module['errorsave'];
                }
            }
        }
    }
}

$agency_weight = 0;
if ($userid > 0 && isset($array_agency[$array_data['agencyid']]) && $array_agency[$array_data['agencyid']] > 0) {
    $agency_weight = $array_agency[$array_data['agencyid']]['weight'];
    //$agency_weight +=1;
} else {
    $agency_weight = 0;
}
if ($array_data['birthday'] > 0) {
    $array_data['birthday'] = date('d/m/Y', $array_data['birthday']);
}

$array_province = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . "_" . $module_data . '_province WHERE status=1 ORDER BY weight', 'id', 'location');

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$array_gender = array();
$array_gender['N'] = $lang_module['gender_0'];
$array_gender['F'] = $lang_module['gender_1'];
$array_gender['M'] = $lang_module['gender_2'];

$array_istype = array();
$array_istype['0'] = $lang_module['istype_0'];
$array_istype['1'] = $lang_module['istype_1'];

$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $module_data . "_jobs WHERE status=1 ORDER BY weight";
$array_job = $nv_Cache->db($sql, 'id', $module_name);

$contents = nv_theme_affiliate_register($array_data, $array_agency, $array_province, $agency_weight, $return, $error, $array_gender, $array_job, $array_istype);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
