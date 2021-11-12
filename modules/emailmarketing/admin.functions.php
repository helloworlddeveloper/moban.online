<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Sat, 15 Oct 2016 03:30:10 GMT
 */
if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) die('Stop!!!');

define('NV_IS_FILE_ADMIN', true);
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

$array_allow_sms_module = array();
//tich hop cho module nao thi them 1 mang nay
//contact = $module_file
$array_allow_sms_module['contact'] = array(
    'newcontact' => array(
        'keytitlecolumn' => $lang_module['config_sms_contact_newcontact_reply'],
        'keytitleactive' => $lang_module['config_sms_contact_newcontact_active']
    )
);

$array_allow_sms_module['shops'] = array(
    'neworder' => array(
        'keytitlecolumn' => $lang_module['config_sms_shops_neworder_reply'],
        'keytitleactive' => $lang_module['config_sms_shops_neworder_active']
    )
);

$array_allow_sms_module['site'] = array(
    'newsite' => array(
        'keytitlecolumn' => $lang_module['config_sms_site_newsite_reply'],
        'keytitleactive' => $lang_module['config_sms_site_newsite_active']
    )
);

$allow_func = array(
    'main',
    'listsms',
    'config',
    'customer',
    'customer-groups',
    'customer-content',
    'content',
    'contentsms',
    'dielist',
    'sender',
    'declined',
    'template',
    'template-content',
    'send',
    'sendsms',
    'mailserver'
);

$array_gender = array(
    1 => $lang_module['gender_1'],
    0 => $lang_module['gender_0'],
    2 => $lang_module['gender_2']
);

$array_customer_groups[0] = array(
    'id' => 0,
    'title' => $lang_module['undefine'],
    'weight' => 0,
    'status' => 1
);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_groups WHERE status=1 ORDER BY weight';
$array_customer_groups += $nv_Cache->db($sql, 'id', $module_name);

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_template WHERE status=1 ORDER BY weight';
$array_template = $nv_Cache->db($sql, 'id', $module_name);

function nv_customer_delete($id)
{
    global $db, $module_data;

    $count = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer WHERE id =' . $id);
    if ($count) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_customer_groups WHERE customerid =' . $id);
    }
}

function nv_row_delete($id)
{
    global $db, $module_data;

    $count = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id =' . $id);
    if ($count) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows_link WHERE rowsid =' . $id);
    }
}

function nv_emailmarketing_check_mobile($phone)
{
    global $lang_module;
    if (!preg_match("/^[0-9]{10,11}$/", $phone)) {
        return $lang_module['mobile_is_error'];
    }
}

function nv_get_balance()
{
    global $module_config, $module_name, $lang_module;

    $return = '';
    $apikey = $module_config[$module_name]['apikey'];
    $secretkey = $module_config[$module_name]['secretkey'];
    $xmlcontent = simplexml_load_file('http://rest.esms.vn/MainService.svc/xml/GetBalance/' . $apikey . '/' . $secretkey);
    switch ($xmlcontent->CodeResponse) {
        case '99':
            $return = $lang_module['error_sms_99'];
            break;
        case '101':
            $return = $lang_module['error_sms_101'];
            break;
        case '102':
            $return = $lang_module['error_sms_102'];
            break;
        case '103':
            $return = $lang_module['error_sms_103'];
            break;
        case '104':
            $return = $lang_module['error_sms_104'];
            break;
        default:
            $balance = number_format(doubleval($xmlcontent->Balance));
            $return = sprintf($lang_module['balance'], $balance);
            break;
    }
    return $return;
}