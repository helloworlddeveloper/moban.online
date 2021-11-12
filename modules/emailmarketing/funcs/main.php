<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Sat, 15 Oct 2016 03:30:10 GMT
 */
if (!defined('NV_IS_MOD_EMAILMARKETING')) die('Stop!!!');

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];


if( $nv_Request->isset_request('registermail', 'post')){
    
    $register_email = $nv_Request->get_title('register_email', 'post');
    $register_email = strtolower( $register_email );
    $email = nv_check_valid_email($register_email);
    $array_customer = array();
    $array_customer['phone'] = '';
    if (empty($register_email)) {
        exit('ERROR_' . $lang_module['error_required_email'] );
    } elseif ( $email != '') {
        if(!preg_match("/^[0-9]{10,11}$/", $register_email)) {
          exit('ERROR_' . $lang_module['error_email_or_phone'] );
        }else{
            $array_customer['phone'] = $register_email;
        }
    }
    $count = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE email=' . $db->quote($register_email))->fetchColumn();
    if (empty($count)) {
        
        $array_customer['email'] = $register_email;
        $array_customer['fullname'] = $register_email;
        $array_customer['gender'] = 1;
        $array_customer['birthday'] = 0;
        
        nv_add_customer($array_customer, array(0));
        exit('OK');
    }else{
        exit('ERROR_' . $lang_module['error_email_exits']);
    }
}

$array_data = array();

$contents = nv_theme_emailmarketing_main($array_data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
