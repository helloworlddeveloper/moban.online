<?php

function sendsms($content, $mobile ){
    global $module_config;
    $apikey = $module_config['sm']['apikey'];
    $secretkey = $module_config['sm']['secretkey'];
    $sms_type = $module_config['sm']['sms_type'];
    $url = '';
    if( $sms_type == 2 ){
        $url = '&Brandname=' . $module_config['sm']['brandname'];
    }

    $content = urlencode($content);

    $data = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?Phone=' . $mobile . '&ApiKey=' . $apikey . '&SecretKey=' . $secretkey . '&Content=' . $content . '&SmsType=' . $sms_type . $url;

    $curl = curl_init($data);
    curl_setopt($curl, CURLOPT_FAILONERROR, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);
    $obj = json_decode($result, true);

    if ($obj['CodeResult'] == '100') {
        //gui thanh cong
    }else{
        //gui loi
    }

}