<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post,get', 0);

if ($id) {
    $sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $id;
    $row = $db->query($sql)->fetch();

    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $page_title = $lang_module['edit'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
} else {
    $page_title = $lang_module['add'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

$selectthemes = (!empty($site_mods[$module_name]['theme'])) ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);
$error = '';
$groups_list = nv_groups_list();

if ($nv_Request->get_int('save', 'post') == '1') {
    $row['title'] = $nv_Request->get_title('title', 'post', '', 1);
    $row['title_english'] = $nv_Request->get_title('title_english', 'post', '', 1);
    $row['link'] = $nv_Request->get_title('link', 'post', '', 1);
    $row['price'] = $nv_Request->get_int('price', 'post', 0);
    $image = $nv_Request->get_string('image', 'post', '');
    if (nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $row['image'] = substr($image, $lu);
    } else {
        $row['image'] = '';
    }
    if (empty($row['title'])) {
        $error = $lang_module['empty_title'];
    } elseif (empty($row['title_english'])) {
        $error = $lang_module['empty_title_english'];
    } else {

        if (!$id) {
            $weight = $db->query("SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data)->fetchColumn();
            $weight = intval($weight) + 1;

            $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '
				(title, title_english, image, link, price, weight, add_time, edit_time, status) VALUES
				(:title, :title_english, :image, :link, :price, ' . $weight . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 1)';
            $publtime = NV_CURRENTTIME;
        }else{
            $_sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title = :title, title_english = :title_english, image=:image, link = :link, price = :price, edit_time = ' . NV_CURRENTTIME . ' WHERE id =' . $id;
        }

        try {

            $sth = $db->prepare($_sql);
            $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
            $sth->bindParam(':title_english', $row['title_english'], PDO::PARAM_STR);
            $sth->bindParam(':image', $row['image'], PDO::PARAM_STR);
            $sth->bindParam(':link', $row['link'], PDO::PARAM_STR);
            $sth->bindParam(':price', $row['price'], PDO::PARAM_INT);

            $sth->execute();

            if ($sth->rowCount()) {
                if ($id) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit', 'ID: ' . $id, $admin_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add', ' ', $admin_info['userid']);
                }

                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
            } else {
                $error = $lang_module['errorsave'];
            }
        } catch (PDOException $e) {
            $error = $lang_module['errorsave'];
        }
    }
}
elseif (empty($id)) {
    $row['image'] = '';
}
if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', $action);
$xtpl->assign('DATA', $row);
$xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
if ($error) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
