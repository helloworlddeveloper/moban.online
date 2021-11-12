<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Tue, 08 Nov 2016 01:39:51 GMT
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['config'];

if ($nv_Request->isset_request('savesetting', 'post')) {

    $array_config['sms_active'] = $nv_Request->get_int('sms_active', 'post', 0);
    $array_config['sms_type'] = $nv_Request->get_int('sms_type', 'post', 0);
    $array_config['apikey'] = $nv_Request->get_title('apikey', 'post', '');
    $array_config['secretkey'] = $nv_Request->get_title('secretkey', 'post', '');
    $array_config['email_notify'] = $nv_Request->get_title('email_notify', 'post', '');
    $array_config['brandname'] = $nv_Request->get_title('brandname', 'post', '');
    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = :config_name");
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delMod('settings');
    $nv_Cache->delMod($module_name);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $module_config[$module_name]);
$xtpl->assign('SMS_ON', $module_config[$module_name]['sms_on'] ? ' checked="checked"' : '');
// Cau hinh hien thi nguon tin
$array_config_sms_type = array(
    2 => $lang_module['config_sms_type_2'],
    4 => $lang_module['config_sms_type_4'],
    6 => $lang_module['config_sms_type_6'],
    8 => $lang_module['config_sms_type_8']
);
foreach ($array_config_sms_type as $key => $val) {
    $xtpl->assign('SMS_TYPE', array(
        'key' => $key,
        'title' => $val,
        'selected' => $key == $module_config[$module_name]['sms_type'] ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.sms_type');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';