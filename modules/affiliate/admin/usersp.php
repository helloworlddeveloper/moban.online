<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['usersp'];

$userid = $nv_Request->get_int('userid', 'post,get', 0);
//Xoa thanh vien
if ($nv_Request->isset_request('del', 'post')) {

    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data  . '_users WHERE userid=' . $userid;
    if ($db->exec($sql)) {
        nv_fix_users_order();
        die('OK');
    }
    die('NO');
}


if ( $nv_Request->isset_request('act', 'get') )
{
    $num_count = $db->query('SELECT COUNT(*) FROM ' . $db_config['prefix'] . '_' . $module_data . '_users WHERE userid=' . $userid)->fetchColumn();
    if (  $num_count  == 1 ){
        $result = $db->query('UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_users SET status=1, edit_time=' . NV_CURRENTTIME . ' WHERE userid =' . $userid );

        if ($result) {
            $nv_Cache->delMod($module_name);
        }
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op );
    }
}

$global_array_users = array();
$sql = 'SELECT t1.lev, t1.datatext, t2.userid, t2.username, t2.first_name, t2.last_name, t2.birthday, t2.email FROM ' . $db_config['prefix'] . '_' . $module_data . '_users AS t1 INNER JOIN ' . NV_USERS_GLOBALTABLE . ' AS t2 ON t1.userid=t2.userid WHERE t1.status != 1 ORDER BY t1.sort ASC';
$result = $db_slave->query($sql);
while ($row = $result->fetch()) {
    $row['fullname'] = nv_show_name_user( $row['first_name'], $row['last_name'], $row['username'] );
    $global_array_users[$row['userid']] = $row;
}
$array_users_list = array();
foreach ($global_array_users as $userid_i => $array_value) {
    $lev_i = $array_value['lev'];
    $xtitle_i = '';
    if ($lev_i > 0) {
        $xtitle_i .= '&nbsp;&nbsp;&nbsp;|';
        for ($i = 1; $i <= $lev_i; ++$i) {
            $xtitle_i .= '---';
        }
        $xtitle_i .= '>&nbsp;';
    }
    $array_value['datatext'] = unserialize( $array_value['datatext'] );
    $array_value['fullname'] = $xtitle_i . $array_value['fullname'];
    $array_users_list[$userid_i] = $array_value;

}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
if (!empty($array_users_list)) {
    foreach ($array_users_list as  $users_list ) {
        $users_list['birthday'] = ( $users_list['birthday'] > 0 )? date('d/m/Y', $array_value['birthday'] ): '';
        $users_list['active_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;act=1&amp;userid=' . $users_list['userid'];
        $xtpl->assign('ROW', $users_list );
        $xtpl->parse('main.data.loop');
    }
    $xtpl->parse('main.data');
}else{
    $xtpl->parse('main.nodata');
}


$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
