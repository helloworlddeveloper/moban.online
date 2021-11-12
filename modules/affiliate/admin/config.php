<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2017 mynukeviet. All rights reserved
 * @Createdate Tue, 07 Nov 2017 07:57:51 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$groups_list = nv_groups_list();

if ($nv_Request->isset_request('submit', 'post')) {
    $array_config = array();
    $name_postion = $nv_Request->get_array('name_postion', 'post', array());
    $percent_postion = $nv_Request->get_array('percent_postion', 'post', array());
    $array_config['config_fercent_return'] = $nv_Request->get_int('config_fercent_return', 'post', 0);
    if( $array_config['config_fercent_return'] > 100)  {
        $array_config['config_fercent_return'] = 100;
    }
    $array_config['config_fercent_return_agency'] = $nv_Request->get_int('config_fercent_return_agency', 'post', 0);
    if( $array_config['config_fercent_return_agency'] > 100)  {
        $array_config['config_fercent_return_agency'] = 100;
    }
    $array_config['min_payment'] = $nv_Request->get_int('min_payment', 'post', 0);
    $array_config['per_page'] = $nv_Request->get_int('per_page', 'post', 0);
    $array_config['mail_notification'] = $nv_Request->get_int('mail_notification', 'post', 0);
    $array_config['verify_user'] = $nv_Request->get_int('verify_user', 'post', 0);

    $array_config['sms_register'] = $nv_Request->get_int('sms_register', 'post', 0);
    $array_config['scan_user'] = $nv_Request->get_int('scan_user', 'post', 0);
    $array_config['inactive_or_delete'] = $nv_Request->get_int('inactive_or_delete', 'post', 0);

    $array_config['register_product_type'] = $nv_Request->get_int('register_product_type', 'post', 0);
    $array_config['precode'] = $nv_Request->get_title('precode', 'post', 'GM%01s');

    $_groups_post = $nv_Request->get_array('nhansu_group_edit_ngaycong', 'post', array());
    $array_config['nhansu_group_edit_ngaycong'] = !empty($_groups_post) ? implode(',', nv_groups_post(array_intersect($_groups_post, array_keys($groups_list)))) : '';

    $array_config['dimuon'] = htmlspecialchars_decode($nv_Request->get_title('dimuon', 'post', ''));
    $array_config['vesom'] = htmlspecialchars_decode($nv_Request->get_title('vesom', 'post', ''));
    $array_config['nghigiuaca'] = $nv_Request->get_float('nghigiuaca', 'post', 0);
    $array_config['nuaca'] = htmlspecialchars_decode($nv_Request->get_title('nuaca', 'post', ''));
    $array_config['motca'] = htmlspecialchars_decode($nv_Request->get_string('motca', 'post', ''));

    $array_config['max_infringe'] = $nv_Request->get_int('max_infringe', 'post', 0);
    $array_config['config_postion'] = array();
    foreach( $name_postion as $key => $val ){
        $array_config['config_postion'][$key] = array('name_postion' => $val, 'percent_postion' => floatval( $percent_postion[$key] ) );
    }
    $array_config['config_postion'] = serialize($array_config['config_postion']);

    $array_config['companyname'] = $nv_Request->get_title('companyname', 'post', '');
    $array_config['headerfile'] = $nv_Request->get_title('headerfile', 'post', '');
    $array_config['address'] = $nv_Request->get_title('address', 'post', '');

    if (!nv_is_url($array_config['headerfile']) and is_file(NV_DOCUMENT_ROOT . $array_config['headerfile'])) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $array_config['headerfile'] = substr($array_config['headerfile'], $lu);
    }


    if (empty($error)) {
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

}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('module_upload', $module_upload);
$xtpl->assign('OP', $op);

$array_config = $module_config[$module_name];

if (!empty($array_config['headerfile']) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $array_config['headerfile'])) {
    $array_config['headerfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $array_config['headerfile'];
}
$array_config['verify_user'] = ( $array_config['verify_user'] == 1)? 'checked=checked'  : '';
$array_config['scan_user'] = ( $array_config['scan_user'] == 1)? 'checked=checked'  : '';
$array_config['sms_register'] = ( $array_config['sms_register'] == 1)? 'checked=checked'  : '';
$array_config['mail_notification'] = ( $array_config['mail_notification'] == 1)? 'checked=checked'  : '';
$xtpl->assign('DATA_CONFIG', $array_config);


/*
$sql = 'SELECT MAX(lev) AS maxlev FROM ' . $db_config['prefix'] . '_' . $module_data . '_users';
$max_lev = $db->query( $sql )->fetchColumn();
$lev = 0;
while( $lev <= $max_lev)
{
    $lev++;
    $xtpl->assign('DATA', isset($config_data[$lev])? $config_data[$lev] : '' );
    $xtpl->assign('LEV', $lev);
    $xtpl->parse('main.lev');
}
*/

$array_inactive_or_delete = array('0' => $lang_module['inactive_or_delete_0'], '1' => $lang_module['inactive_or_delete_1']);
foreach ( $array_inactive_or_delete as $key => $val ){
    $sl = ( $key == $module_config[$module_name]['inactive_or_delete'] )? ' selected=selected' : '';
    $xtpl->assign('inactive_or_delete', array('key' =>$key, 'value' => $val, 'sl' => $sl ));
    $xtpl->parse('main.inactive_or_delete');
}

$nhansu_group_edit_ngaycong = explode(',', $array_config['nhansu_group_edit_ngaycong']);
foreach ($groups_list as $_group_id => $_title) {
    $xtpl->assign('NHANSU_EDIT', array(
        'value' => $_group_id,
        'checked' => in_array($_group_id, $nhansu_group_edit_ngaycong) ? ' checked="checked"' : '',
        'title' => $_title
    ));
    $xtpl->parse('main.daytot_group_access_ngaycong');
}


if (!empty($array_personal_sms)) {
    foreach ($array_personal_sms as $index => $value) {
        $xtpl->assign('PERSONAL', array(
            'index' => $index,
            'value' => $value
        ));
        $xtpl->parse('main.personal');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['config'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';