<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ( ! defined( 'NV_IS_MOD_REGSITE' ) ) die( 'Stop!!!' );

if (!defined('NV_IS_USER') ) {
    $redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($redirect));
    die();
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];
$image_site = $nv_Request->get_title($module_data . '_image_site', 'session');

$mobile = $nv_Request->get_title('mobile', 'get', '');
if( $mobile != ''){
    $nv_Request->set_Session($module_data . '_mobile_refer', $mobile );
}
$error = $array_data = array();
$array_data['mobile_refer'] = $nv_Request->get_title($module_data . '_mobile_refer', 'session', '');

if( $nv_Request->isset_request( 'check_domain', 'post' ) )
{
    $domain_name = $nv_Request->get_title('domain_name', 'post', 0);
    $domain_name = $domain_name . '.cash13.vn';
    $numdomain = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . ' WHERE domain = ' . $db->quote( $domain_name ) )->fetchColumn();
    if( $numdomain == 0 ){
        nv_jsonOutput(array(
            'status' => 'ok',
            'mess' => 'Bạn có thể sử dụng tên miền này'));
    }else{
        nv_jsonOutput(array(
            'status' => 'error',
            'mess' => 'Tên miền này đã được sử dụng! Hãy chọn tên miền khác'));
    }
}
elseif( $nv_Request->isset_request( 'check_code', 'post' ) )
{
    $code = $nv_Request->get_int('code', 'post', 0);
    $data_code = $db->query('SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_code WHERE status=0 AND code = ' . $db->quote( $code ) )->fetch();
    if( !empty( $data_code )){
        $stmt = $db->prepare( 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_code SET status=1 WHERE id=' . $data_code['id'] );
        $exc = $stmt->execute();
        $nv_Request->set_Session($module_data . '_mobile', serialize( $data_code ) );
        nv_jsonOutput(array(
            'status' => 'ok',
            'mess' => 'Kích hoạt thành công! Hệ thống sẽ chuyển bạn qua trang khởi tạo website!'));
    }else{
        nv_jsonOutput(array(
            'status' => 'error',
            'mess' => 'Mã kích hoạt không chính xác. Hãy kiểm tra lại'));
    }
}
elseif( $nv_Request->isset_request( 'checkphone', 'post' ) )
{

    if (!nv_capcha_txt(($global_config['captcha_type'] == 2 ? $nv_Request->get_title('g-recaptcha-response', 'post', '') : $nv_Request->get_title('fcode', 'post', '')))) {

        nv_jsonOutput(array(
            'status' => 'error',
            'mess' => ($global_config['captcha_type'] == 2 ? $lang_global['securitycodeincorrect1'] : $lang_global['securitycodeincorrect'])));

    }else{

        $phone = $nv_Request->get_title('phone', 'post', '');
        $preg = '/^(01[2689]|03|05|07|08|09)[0-9]{8}$/';
        if(preg_match($preg, $phone)) {
            //sdt chinh xac
            $check_mobile = $db->query("SELECT COUNT(*) FROM " . $db_config['prefix'] . '_affiliate_users WHERE status=1 AND mobile=' . $db->quote( $phone ))->fetchColumn();

            if( $check_mobile == 0 ){
                nv_jsonOutput(array(
                    'status' => 'error',
                    'mess' => 'Bạn chưa phải là thành viên của hệ thống CASH 13. Hãy liên hệ người đã giới thiệu bạn để được hỗ trợ!'));
            }
            $random_number = mt_rand(100000, 999999);
            $domain = $db->query("SELECT domain FROM " . $db_config['prefix'] . "_" . $module_data . ' WHERE mobile=' . $db->quote( $phone ))->fetch();
            //gui tin nhan
            if( empty( $domain ) ){
                //tao ma code sms
                $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_code WHERE code = ' . $random_number )->fetchColumn();
                while ( $count != 0 ){
                    $random_number = mt_rand(100000, 999999);
                    $count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_code WHERE code = ' . $random_number )->fetchColumn();
                }
                $stmt = $db->prepare( 'INSERT INTO ' . $db_config['prefix']  . '_' . $module_data . '_code ( mobile, addtime, status, code) 
                VALUES ( :mobile, ' . NV_CURRENTTIME . ', 0, :code)' );

                $stmt->bindParam( ':mobile', $phone, PDO::PARAM_STR );
                $stmt->bindParam( ':code', $random_number, PDO::PARAM_STR );
                $exc = $stmt->execute();
                if( $exc )
                {
                    if( $module_config[$module_name]['sms_on'] == 1){
                        $content = $random_number . ' la ma kich hoat cua ban tai ' . NV_MAIN_DOMAIN;
                        $apikey = $module_config[$module_name]['apikey'];
                        $secretkey = $module_config[$module_name]['secretkey'];
                        $sms_type = $module_config[$module_name]['sms_type'];
                        $content = urlencode($content);

                        $url = '';
                        if( $sms_type == 2 ){
                            $url = '&Brandname=' . $module_config[$module_name]['brandname'];
                        }

                        $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $phone . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;
                        //die($data);
                        $curl = curl_init($data);
                        curl_setopt($curl, CURLOPT_FAILONERROR, true);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $result = curl_exec($curl);

                        $obj = json_decode($result, true);
                        $totalsend = $exit = 0;
                        $status = 'success';
                        $messenger = '';

                        $obj['CodeResult'] = 100;
                        if ($obj['CodeResult'] == 100) {
                            //gui thanh cong
                            nv_jsonOutput(array(
                                'status' => 'ok',
                                'mess' => 'Vui lòng kiểm tra tin nhắn số ' . $phone . ' để lấy mã kích hoạt'));
                        } else {
                            //gui loi
                            nv_jsonOutput(array(
                                'status' => 'error',
                                'mess' => 'Hệ thống không thể gửi tin nhắn đến số ' . $phone . '. Vui lòng gọi số hotline: 0243.915.6666 để được trợ giúp'));
                        }
                    }
                }

            }else{
                nv_jsonOutput(array(
                    'status' => 'error',
                    'mess' => 'Số điện thoại này đã được dùng để đăng ký website: ' . $domain['domain'] ));
            }
        }else{
            nv_jsonOutput(array(
                'status' => 'error',
                'mess' => 'Số điện thoại bạn đăng ký không đúng chuẩn!'));
        }


    }
}
elseif( $nv_Request->isset_request('submit', 'post')){

    $siterefer = $array_domain[NV_SERVER_NAME];
    $array_data['mobile_refer'] = '';
    if( $siterefer > 0 ){
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . "_regsite WHERE id=" . $siterefer;
        $data_content = $db->query($sql)->fetch();
        $array_data['mobile_refer'] = $data_content['mobile'];
    }

    $array_data['domain'] = $nv_Request->get_title('domain_name', 'post');
    $array_data['domain'] = strtolower( change_alias( $array_data['domain'] ) );
    $array_data['site_title'] = $nv_Request->get_title('site_title', 'post');
    $array_data['site_email'] = $nv_Request->get_title('site_email', 'post');
    $array_data['facebook_link'] = $nv_Request->get_title('facebook_link', 'post', '');
    $array_data['banner_site'] = '';
    $array_data['facebook_link'] = empty( $array_data['facebook_link'] )? 'https://www.facebook.com/cash13group/' : $array_data['facebook_link'];
    if( empty($array_data['domain'] )){
        $error[] = 'Bạn chưa chọn tên miền cần chạy';
    }elseif(!empty($array_data['domain'] )){
        $array_data['domain_name'] = $array_data['domain'] . '.cash13.vn';
        $numdomain = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . ' WHERE domain = ' . $db->quote( $array_data['domain_name'] ) )->fetchColumn();
        if( $numdomain > 0 ){
            $error[] = 'Tên miền này đã được sử dụng! Hãy chọn tên miền khác';
        }
    }
    if( empty($array_data['site_title'] )){
        $error[] = 'Bạn cần nhập tiêu đề website.';
    }
    $array_data['image_site'] = $nv_Request->get_title($module_data . '_image_site', 'session');

    if( empty( $error )){
        if (empty( $error )){

            $data_code = $nv_Request->get_string($module_data . '_mobile', 'session');
            $data_code = unserialize( $data_code );
            $array_data['mobile'] = $data_code['mobile'];
            /*
            list( $array_data['userid'] ) = $db->query("SELECT userid FROM " . $db_config['prefix'] . '_affiliate_users WHERE mobile=' . $db->quote( $array_data['mobile'] ))->fetch( 3 );
            $array_data['userid'] = intval( $array_data['userid'] );
            */
            $sql = "INSERT INTO " . $db_config['prefix'] . "_" . $module_data . " (id, userid, domain, title, mobile, email, image_site, banner_site, mobile_refer, facebook, zalo, youtube, instagram, addtime, status) VALUES (NULL,:userid, :domain, :title, :mobile, :email, :image_site, :banner_site, :mobile_refer, :facebook, '', '', '', " . NV_CURRENTTIME . ", 1)";

            $data_insert = array();
            $data_insert['domain'] = $array_data['domain_name'];
            $data_insert['userid'] = $user_info['userid'];
            $data_insert['title'] = $array_data['site_title'];
            $data_insert['mobile'] = $array_data['mobile'];
            $data_insert['email'] = $array_data['site_email'];
            $data_insert['image_site'] = $array_data['image_site'];
            $data_insert['banner_site'] = $array_data['banner_site'];
            $data_insert['mobile_refer'] = $array_data['mobile_refer'];
            $data_insert['facebook'] = $array_data['facebook_link'];
            if ($idsite = $db->insert_id($sql, 'id', $data_insert)) {
                addasite( $idsite, $array_data );//khoi tao website
                $contents = nv_theme_reg_ok( $array_data );
                $nv_Request->unset_request($module_data . '_mobile', 'session');

                // gui tin thong bao
                if( $module_config[$module_name]['sms_on'] == 1){

                    $apikey = $module_config[$module_name]['apikey'];
                    $secretkey = $module_config[$module_name]['secretkey'];
                    $sms_type = $module_config[$module_name]['sms_type'];

                    $url = '';
                    if( $sms_type == 2 ){
                        $url = '&Brandname=' . $module_config[$module_name]['brandname'];
                    }

                    //gui thong bao cho ng gioi thieu
                    if( !empty( $array_data['mobile_refer'] ) )
                    {
                        $content = 'Chuc mung ban co dai ly dang ky voi thong tin sau: ' . $array_data['site_title'] . ' - ' . $array_data['mobile'] . ' - ' . $array_data['domain_name'];
                        $content = urlencode($content);
                        $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $array_data['mobile_refer'] . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;
                        //die($data);
                        $curl = curl_init($data);
                        curl_setopt($curl, CURLOPT_FAILONERROR, true);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        $result = curl_exec($curl);
                    }
                    //gui tin nhan cho tong cty
                    $mobile_congty = '0868236236';
                    $content = '[' . $array_data['mobile_refer'] . ']He thong nhan duoc don dang ky DL: ' . $array_data['site_title'] . ' - ' . $array_data['mobile'] . ' - ' . $array_data['domain_name'];
                    $content = urlencode($content);
                    $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $mobile_congty . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;
                    //die($data);
                    $curl = curl_init($data);
                    curl_setopt($curl, CURLOPT_FAILONERROR, true);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($curl);

                    $obj = json_decode($result, true);

                    $status = 'success';
                    $messenger = '';

                    $obj['CodeResult'] = 100;
                    if ($obj['CodeResult'] == 100) {

                    } else {
                        //gui loi
                    }
                }


                include NV_ROOTDIR . '/includes/header.php';
                echo nv_site_theme( $contents );
                include NV_ROOTDIR . '/includes/footer.php';
                exit();

            }else{
                //die('dfgdfg');
            }
        }
    }
}

$data_code = $nv_Request->get_string($module_data . '_mobile', 'session');
if( !empty( $data_code )){
    $data_code = unserialize( $data_code );
    $step = 2;
}else{
    $step = 1;
}

if (empty($user_info['photo'])) {
    $array_data['photo'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/users/no_avatar.png';
    $array_data['photoWidth'] = 80;
    $array_data['photoHeight'] = 80;
    $array_data['imgDisabled'] = " disabled=\"disabled\"";
} else {
    $size = @getimagesize(NV_ROOTDIR . '/' . $user_info['photo']);
    $array_data['photoWidth'] = $size[0];
    $array_data['photoHeight'] = $size[1];
    $array_data['imgDisabled'] = '';
    $array_data['photo'] = NV_BASE_SITEURL . $user_info['photo'];
}

if (empty($array_data['image_site'])) {
    $array_data['image_site'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/users/no_avatar.png';
    $array_data['image_siteDisabled'] = " disabled=\"disabled\"";
} else {
    $size = @getimagesize(NV_ROOTDIR . '/' . $array_data['image_site']);
    $array_data['image_siteDisabled'] = '';
    $array_data['image_site'] = NV_BASE_SITEURL . $array_data['image_site'];
}


$contents = nv_theme_reg_main( $array_data, $data_code, $error, $step );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
