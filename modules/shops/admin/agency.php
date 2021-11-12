<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 24-06-2011 10:35
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if( $nv_Request->isset_request('change_weight', 'post', 0) ){

    $id = $nv_Request->get_int('id', 'post', 0);

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_agency WHERE id=' . $id;
    $id = $db->query($sql)->fetchColumn();
    if (empty($id)) {
        die('NO_' . $id);
    }

    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);
    if (empty($new_weight)) {
        die('NO_' . $mod);
    }

    $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_agency WHERE id!=' . $id . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }

        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_agency SET weight=' . $weight . ' WHERE id=' . $row['id'];
        $db->query($sql);
    }

    $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_agency SET weight=' . $new_weight . ' WHERE id=' . $id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $id;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}else if( $nv_Request->isset_request('change_status', 'post', 0) ){

        $id = $nv_Request->get_int('id', 'post', 0);

        $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_agency WHERE id=' . $id;
        $id = $db->query($sql)->fetchColumn();
        if (empty($id)) {
            die('NO_' . $id);
        }

        $new_status = $nv_Request->get_bool('new_status', 'post');
        $new_status = ( int )$new_status;

        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_agency SET status=' . $new_status . ' WHERE id=' . $id;
        $db->query($sql);
        $nv_Cache->delMod($module_name);

        include NV_ROOTDIR . '/includes/header.php';
        echo 'OK_' . $id;
        include NV_ROOTDIR . '/includes/footer.php';
        exit();
}else if( $nv_Request->isset_request('del', 'post', 0) ){
    $id = $nv_Request->get_int('id', 'post', 0);
    $checkss = $nv_Request->get_string('checkss', 'post', '');

    if (md5($id . NV_CHECK_SESSION) == $checkss) {
        $content = 'NO_' . $id;

        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_agency WHERE id = ' . $id;
        if ($db->exec($sql)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete', 'ID: ' . $id, $admin_info['userid']);

            $sql = 'SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_agency ORDER BY weight ASC';
            $result = $db->query($sql);
            $weight = 0;
            while ($row = $result->fetch()) {
                ++$weight;
                $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_agency SET weight=' . $weight . ' WHERE id=' . $row['id'];
                $db->query($sql);
            }
            $nv_Cache->delMod($module_name);

            $content = 'OK_' . $id;
        }
    } else {
        $content = 'ERR_' . $id;
    }
    die($content);
}


$page_title = $lang_module['agency_title'];
$array = array();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_agency ORDER BY weight ASC';
$_rows = $db->query($sql)->fetchAll();
$num = sizeof($_rows);

if ($num < 1) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=agencycontent');
}

$array_status = array(
    $lang_module['inactive'],
    $lang_module['active']
);

$xtpl = new XTemplate('agency.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('agencycontent', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=agencycontent');
$i = 0;
foreach ($_rows as $row) {
    $row['url_view'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=agencyprice&amp;id=' . $row['id'];
    $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=agencycontent&amp;id=' . $row['id'];
    $row['checkss'] = md5($row['id'] . NV_CHECK_SESSION);
    $row['price_require'] = number_format($row['price_require'], 0, '.', ',');
    $row['sale_product_show'] = sprintf( $lang_module['sale_product_show'], $row['number_sale'], $row['number_gift']);
    for ($i = 1; $i <= $num; ++$i) {
        $xtpl->assign('WEIGHT', array(
            'w' => $i,
            'selected' => ($i == $row['weight']) ? ' selected="selected"' : ''
        ));

        $xtpl->parse('main.row.weight');
    }

    foreach ($array_status as $key => $val) {
        $xtpl->assign('STATUS', array(
            'key' => $key,
            'val' => $val,
            'selected' => ($key == $row['status']) ? ' selected="selected"' : ''
        ));

        $xtpl->parse('main.row.status');
    }
    $row['edit_time'] = nv_date('H:i d/m/y', $row['edit_time']);
    $row['add_time'] = nv_date('H:i d/m/y', $row['add_time']);

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
