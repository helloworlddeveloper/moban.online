<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Apr 20, 2010 10:47:41 AM
 */

if(! defined('NV_IS_MOD_PAGE'))
{
    die('Stop!!!');
}

$mydomain = $nv_Request->get_title('mydomain', 'get', '');
$domain_refer = nv_get_base_url($mydomain);

$array_domain_allow = explode(',', $module_config[$module_name]['domain_accept']);
if(! in_array($domain_refer, $array_domain_allow))
{
    die($domain_refer);
}
else
{
    $myFunction = $nv_Request->get_title('function', 'get', '');
    if($myFunction == 'nvGetProductInfo')
    {
        $arr_param = array(
            'mydomain' => $nv_Request->get_title('mydomain', 'get', ''),
            'function' => $myFunction,
            'productid' => $nv_Request->get_int('productid', 'get', 0)
        );
        $secure_code = $nv_Request->get_title('secure_code', 'get', '');
        $secure_code_check = md5(implode(' ', $arr_param) . ' ' . $module_config[$module_name]['apikey']);
        
        if($secure_code_check == $secure_code)
        {
            $data = call_user_func($myFunction, $arr_param['productid']);
            print_r(json_encode($data));
        }else{
            die('ERROR');
        }
    }
    elseif($myFunction == 'nvGetRoomPhonghoc' || $myFunction == 'nvGetTagPhonghoc' || $myFunction == 'nvGetChiNhanhPhonghoc' || $myFunction == 'nvGetCustomerPhonghoc')
    {
        
        $arr_param = array(
            'mydomain' => $nv_Request->get_title('mydomain', 'get', ''),
            'function' => $myFunction);

        $secure_code = $nv_Request->get_title('secure_code', 'get', '');
        $secure_code_check = md5(implode(' ', $arr_param) . ' ' . $module_config[$module_name]['apikey']);
        if($secure_code_check == $secure_code)
        {
            $data = call_user_func($myFunction, $arr_param);
            print_r(json_encode($data));
        }
    }

}
