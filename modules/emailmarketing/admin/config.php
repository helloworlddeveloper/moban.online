<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Tue, 08 Nov 2016 01:39:51 GMT
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$page_title = $lang_module['config'];

$array_module = array();
if (isset($array_allow_sms_module) && !empty($array_allow_sms_module)) {
    foreach ($array_allow_sms_module as $mod_file => $info) {
        $mquery = $db->query("SELECT title FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_modules WHERE module_file='" . $mod_file . "' AND act=1");
        while (list ($module) = $mquery->fetch(3)) {
            $array_module[] = $module;
        }
    }
}

if ($nv_Request->isset_request('savesetting', 'post')) {
    $data['requiredfullname'] = $nv_Request->get_int('requiredfullname', 'post', 0);
    $data['allow_declined'] = $nv_Request->get_int('allow_declined', 'post', 0);
    $data['allow_cronjobs'] = $nv_Request->get_int('allow_cronjobs', 'post', 0);
    $data['show_undefine'] = $nv_Request->get_int('show_undefine', 'post', 0);
    $data['stoperror'] = $nv_Request->get_int('stoperror', 'post', 0);
    $data['numsend'] = $nv_Request->get_int('numsend', 'post', 30);

    $data['sms_active'] = $nv_Request->get_int('sms_active', 'post', 0);
    $data['sms_type'] = $nv_Request->get_int('sms_type', 'post', 0);
    $data['apikey'] = $nv_Request->get_title('apikey', 'post', '');
    $data['secretkey'] = $nv_Request->get_title('secretkey', 'post', '');

    $data['new_customer_group'] = $nv_Request->get_typed_array('new_customer_group', 'post', 'int');
    if (!empty($data['new_customer_group'])) {
        $data['new_customer_group'] = implode(',', $data['new_customer_group']);
    } else {
        $data['new_customer_group'] = 0;
    }

    $data['config'] = $nv_Request->get_array('config', 'post');
    foreach ($data['config'] as $index => $value) {
        foreach ($value as $_index => $_value) {
            $_value['active'] = isset($_value['active']) ? $_value['active'] : 0;

            $data_new = array();
            $data_new[$index . '_' . $_index . '_reply'] = $_value['reply'];
            $data_new[$index . '_' . $_index . '_active'] = $_value['active'];

            if (isset($module_config[$module_name][$index . '_' . $_index . '_active'])) {
                $sql = "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = :lang AND module = :module AND config_name = :config_name";
            } else {
                $sql = 'INSERT INTO ' . NV_CONFIG_GLOBALTABLE . '(lang, module, config_name, config_value ) VALUES(:lang, :module, :config_name, :config_value)';
            }

            $sth = $db->prepare($sql);
            $sth->bindValue(':lang', NV_LANG_DATA, PDO::PARAM_STR);
            $sth->bindParam(':module', $module_name, PDO::PARAM_STR);
            foreach ($data_new as $key => $value) {
                $sth->bindParam(':config_name', $key, PDO::PARAM_STR);
                $sth->bindParam(':config_value', $value, PDO::PARAM_STR);
                $sth->execute();
            }
        }
    }

    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['config'], "Config", $admin_info['userid']);
    $nv_Cache->delMod('settings');

    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . '=' . $op);
    die();
}

$array_config['ck_requiredfullname'] = $array_config['requiredfullname'] ? 'checked="checked"' : '';
$array_config['ck_allow_declined'] = $array_config['allow_declined'] ? 'checked="checked"' : '';
$array_config['ck_allow_cronjobs'] = $array_config['allow_cronjobs'] ? 'checked="checked"' : '';
$array_config['ck_show_undefine'] = $array_config['show_undefine'] ? 'checked="checked"' : '';
$array_config['ck_stoperror'] = $array_config['stoperror'] ? 'checked="checked"' : '';

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $array_config);

$xtpl->assign('SMS_active', $module_config[$module_name]['sms_active'] ? ' checked="checked"' : '');
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
if (!empty($array_customer_groups)) {
    $array_config['new_customer_group'] = explode(',', $array_config['new_customer_group']);
    foreach ($array_customer_groups as $group) {
        $group['checked'] = in_array($group['id'], $array_config['new_customer_group']) ? 'checked="checked"' : '';
        $xtpl->assign('GROUP', $group);
        $xtpl->parse('main.group');
    }
}

if (!empty($array_module)) {
    foreach ($array_module as $module) {
        if (isset($site_mods[$module])) {
            $mod_file = $site_mods[$module]['module_file'];
            $mod_data = $site_mods[$module]['module_data'];
            foreach ($array_allow_sms_module[$mod_file] as $keymodule => $info_allow) {
                $info_allow['data_value'] = isset($module_config[$module_name][$mod_data . '_' . $keymodule . '_reply']) ? $module_config[$module_name][$mod_data . '_' . $keymodule . '_reply'] : '';
                $info_allow['ck'] = (isset($module_config[$module_name][$mod_data . '_' . $keymodule . '_active']) and $module_config[$module_name][$mod_data . '_' . $keymodule . '_active']) ? ' checked="checked"' : '';
                $xtpl->assign('INFO_ALLOW', $info_allow);
                $xtpl->assign('module', $mod_data);
                $xtpl->assign('keymodule', $keymodule);
                $xtpl->parse('main.info_allow.loop');
            }
            $xtpl->assign('MODULE_TITLE', $site_mods[$module]['custom_title']);
            $xtpl->parse('main.info_allow');
        }
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';