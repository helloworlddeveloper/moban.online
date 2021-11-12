<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Mon, 27 Jan 2014 00:08:04 GMT
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}


$groups_list = nv_groups_list();

if ($nv_Request->isset_request('submit', 'post')) {
    $array_config = array();

    $_groups_com = $nv_Request->get_array('group_view_workforce', 'post', array());
    if (in_array(-1, $_groups_com)) {
        $array_config['group_view_workforce'] = '-1';
    } else {
        $array_config['group_view_workforce'] = ! empty($_groups_com) ? implode(',', nv_groups_post(array_intersect($_groups_com, array_keys($groups_list)))) : '';
    }

    $_groups_com = $nv_Request->get_array('group_add_workforce', 'post', array());
    $array_config['group_add_workforce'] = ! empty($_groups_com) ? implode(',', nv_groups_post(array_intersect($_groups_com, array_keys($groups_list)))) : '';
    $array_config['precode'] = $nv_Request->get_title('precode', 'post', strtoupper(substr($module_name, 0, 1)) . '%04s');

    $sth = $db->prepare("UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' and module = :module_name and config_name = :config_name");
    $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
    foreach ($array_config as $config_name => $config_value) {
        $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $sth->execute();
    }
    $nv_Cache->delMod('settings');
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&rand=' . nv_genpass());
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA', $module_config[$module_name]);

$group_view_workforce =explode(',', $module_config[$module_name]['group_view_workforce']);
$group_add_workforce =explode(',', $module_config[$module_name]['group_add_workforce']);

foreach ($groups_list as $_group_id => $_title) {
    $xtpl->assign('OPTION', array(
        'value' => $_group_id,
        'checked' => in_array($_group_id, $group_view_workforce) ? ' checked="checked"' : '',
        'title' => $_title
    ));
    $xtpl->parse('main.group_view_workforce');

    $xtpl->assign('OPTION', array(
        'value' => $_group_id,
        'checked' => in_array($_group_id, $group_add_workforce) ? ' checked="checked"' : '',
        'title' => $_title
    ));
    $xtpl->parse('main.group_add_workforce');

}


$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';