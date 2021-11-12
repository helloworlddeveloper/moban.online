<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang <contact@vinades.vn>
 * @Copyright (C) 2010 - 2014 Mr.Thang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 08 Apr 2012 00:00:00 GMT
 */
if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

if (!nv_user_in_groups($array_config['group_add_workforce'])) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    die();
}

if ($nv_Request->isset_request('delete_id', 'get') and $nv_Request->isset_request('delete_checkss', 'get')) {
    $id = $nv_Request->get_int('delete_id', 'get');
    $delete_checkss = $nv_Request->get_string('delete_checkss', 'get');
    
    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory WHERE id=' . $id;
    list ($id_delete) = $db->query($sql)->fetch(3);
    
    if ($id > 0 and $id_delete > 0 and $delete_checkss == md5($id . NV_CACHE_PREFIX . $client_info['session_id'])) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory  WHERE id = ' . $db->quote($id));
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory_detail  WHERE iid = ' . $db->quote($id));
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_inventory_users  WHERE iid = ' . $db->quote($id));
        
        $nv_Cache->delMod($module_name);
        
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
}

$page_title = $lang_module['inventory_list'];
$departmentid = $nv_Request->get_int('departmentid', 'get', 0);
$per_page = 30;

$from = NV_PREFIXLANG . '_' . $module_data . '_inventory';

$where = array();
$page = $nv_Request->get_int('page', 'get', 1);
$checkss = $nv_Request->get_string('checkss', 'get', '');

if ($checkss == NV_CHECK_SESSION) {
    if ($departmentid > 0) {
        $where[] = " departmentid = " . $departmentid;
    }
}

$db_slave->sqlreset()
    ->select('COUNT(*)')
    ->from($from)
    ->where(implode(' AND ', $where));

$_sql = $db_slave->sql();

$num_items = $db_slave->query($_sql)->fetchColumn();
$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;per_page=' . $per_page;
if ($departmentid) {
    $base_url .= '&amp;departmentid=' . $departmentid;
}

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('addinventory', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=inventory');
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_SITEURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$db_slave->select('*')
    ->order('addtime DESC')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

$result = $db_slave->query($db_slave->sql());
$stt = ($page - 1) * $per_page + 1;
while ($row = $result->fetch()) {
    $row['stt'] = $stt++;
    $row['time_inventory'] = nv_date('H:i d/m/y', $row['time_inventory']);
    if ($row['departmentid'] == 0) {
        $row['department'] = $lang_module['all_company'];
    } else {
        $row['department'] = $array_department[$row['departmentid']]['title'];
    }
    
    $row['link_detail'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=inventory-detail&id=' . $row['id'];
    $row['link_edit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=addproduct&id=' . $row['id'];
    $row['link_delete'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;delete_id=' . $row['id'] . '&amp;delete_checkss=' . md5($row['id'] . NV_CACHE_PREFIX . $client_info['session_id']);
    $xtpl->assign('ROW', $row);
    
    $xtpl->parse('main.loop');
}

foreach ($array_department as $cat) {
    $cat['selected'] = ($cat['id'] == $departmentid) ? ' selected=selected' : '';
    $xtpl->assign('CAT_CONTENT', $cat);
    $xtpl->parse('main.cat_content');
}

if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}
$xtpl->parse('main');
$contents = $xtpl->text('main');

// $xtpl->parse( 'main' );
// $contents = nv_theme_workforce_control( $array_control );
// $contents .= $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
