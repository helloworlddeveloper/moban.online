<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2017 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10-01-2017 20:08
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );
 
define( 'NV_IS_MOD_MESSAGE', true );
define( 'API_URL_FIREBASE', $module_config[$module_name]['firebase_url'] );
define( 'API_ACCESS_KEY', $module_config[$module_name]['firebase_api_access_key'] );

function nv_call_curl($data_string)
{
    $ch = curl_init();

    $headers = array
    (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );

    curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string);
    curl_setopt( $ch,CURLOPT_URL, API_URL_FIREBASE );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );

    $result_return = curl_exec ($ch);
    curl_close($ch);
    return $result_return;
}