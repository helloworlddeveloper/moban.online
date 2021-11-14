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

    $array_config['percent_book_one'] = $nv_Request->get_int('percent_book_one', 'post', 0);
    $array_config['percent_allow_ok'] = $nv_Request->get_int('percent_allow_ok', 'post', 0);
    foreach( $array_permissions_action as $permissions_action )
    {
        $array_config[$permissions_action['key']] = $nv_Request->get_array( $permissions_action['key'], 'post', array() );
        $array_config[$permissions_action['key']] = implode( ',', $array_config[$permissions_action['key']] );
    }

    $array_config['sms_on'] = $nv_Request->get_int('sms_on', 'post', 0);
    $array_config['sms_type'] = $nv_Request->get_int('sms_type', 'post', 0);
    $array_config['apikey'] = $nv_Request->get_title('apikey', 'post', '');
    $array_config['secretkey'] = $nv_Request->get_title('secretkey', 'post', '');
    $array_config['email_notify'] = $nv_Request->get_title('email_notify', 'post', '');
    $array_config['brandname'] = $nv_Request->get_title('brandname', 'post', '');

    $array_config['percent_discount_1'] = $nv_Request->get_int('percent_discount_1', 'post', 0);
    $array_config['percent_discount_2'] = $nv_Request->get_int('percent_discount_2', 'post', 0);
    $array_config['percent_discount_3'] = $nv_Request->get_int('percent_discount_3', 'post', 0);
    $array_config['deposits'] = $nv_Request->get_int('deposits', 'post', 0);

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

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$sql = "SELECT t1.userid, t1.username, t1.email, t1.first_name, t1.last_name FROM " . NV_USERS_GLOBALTABLE . " AS t1 INNER JOIN " . NV_AUTHORS_GLOBALTABLE . " AS t2 ON t1.userid=t2.admin_id WHERE t1.active=1 AND t2.lev=3";
$result = $db->query( $sql );
$array_mods = $result->fetchAll();

$array_config = $module_config[$module_name];
foreach( $array_permissions_action as $permissions_action )
{
    $xtpl->assign( 'PERMISSIONS_ACTION', $permissions_action );
    foreach( $permissions_action['list_op'] as $list_op )
    {
        if( isset( $lang_module[$list_op] ) )
        {
            $xtpl->assign( 'LIST_OP', $lang_module[$list_op] );
            $xtpl->parse( 'main.permissions_action.list_op' );
        }
    }
    $array_config[$permissions_action['key']] = explode( ',', $array_config[$permissions_action['key']] );
    foreach( $array_mods as $user_data )
    {
        $user_data['checked'] = in_array( $user_data['userid'], $array_config[$permissions_action['key']] ) ? ' checked=checked' : '';
        $user_data['full_name'] = ( empty( $user_data['full_name'] ) ) ? $user_data['username'] : $user_data['full_name'];
        $xtpl->assign( 'USER_DATA', $user_data );
        $xtpl->parse( 'main.permissions_action.userid' );
    }
    $xtpl->parse( 'main.permissions_action' );
}

$xtpl->assign('SMS_ON', $array_config['sms_on'] ? ' checked="checked"' : '');
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
        'selected' => $key == $array_config['sms_type'] ? ' selected="selected"' : ''
    ));
    $xtpl->parse('main.sms_type');
}


$xtpl->assign('DATA_CONFIG', $array_config);
$xtpl->parse('main');
$contents = $xtpl->text('main');
$page_title = $lang_module['config'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';