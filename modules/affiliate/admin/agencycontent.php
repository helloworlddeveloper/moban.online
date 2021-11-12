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
    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_agency WHERE id=' . $id;
    $row = $db->query($sql)->fetch();

    if (empty($row)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $page_title = $lang_module['edit_agencycontent'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $id;
} else {
    $page_title = $lang_module['add_agencycontent'];
    $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op;
}

$error = '';

if ($nv_Request->get_int('save', 'post') == '1') {
    $row['title'] = $nv_Request->get_title('title', 'post', '', 1);
    $row['alias'] = $nv_Request->get_title('alias', 'post', '', 1);
    $row['price_require'] = $nv_Request->get_title('price_require', 'post',0);
    $row['number_sale'] = $nv_Request->get_int('number_sale', 'post',0);
    $row['number_gift'] = $nv_Request->get_int('number_gift', 'post',0);
    $row['price_discount'] = $nv_Request->get_title('price_discount', 'post',0);
    $row['price_for_discount'] = $nv_Request->get_title('price_for_discount', 'post',0);
    $row['percent_discount'] = $nv_Request->get_float('percent_discount', 'post',0);
    $row['price_require'] = floatval(preg_replace('/[^0-9\.]/', '', $row['price_require']));
    $row['price_discount'] = floatval(preg_replace('/[^0-9\.]/', '', $row['price_discount']));
    $row['price_for_discount'] = floatval(preg_replace('/[^0-9\.]/', '', $row['price_for_discount']));

    $image = $nv_Request->get_string('image', 'post', '');
    if (nv_is_file($image, NV_UPLOADS_DIR . '/' . $module_upload)) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/');
        $row['image'] = substr($image, $lu);
    } else {
        $row['image'] = '';
    }
    $row['description'] = $nv_Request->get_textarea('description', '', 'br', 1);
    $row['bodytext'] = $nv_Request->get_editor('bodytext', '', NV_ALLOWED_HTML_TAGS);
    $row['keywords'] = nv_strtolower($nv_Request->get_title('keywords', 'post', '', 0));

    if (empty($row['title'])) {
        $error = $lang_module['empty_title'];
    } elseif (strip_tags($row['bodytext']) == '') {
        $error = $lang_module['empty_bodytext'];
    } elseif ($row['price_require'] == 0) {
        $error = $lang_module['empty_price_require'];
    //}elseif ($row['percent_sale'] == 0) {
    //    $error = $lang_module['empty_percent_sale'];
    } else {
        $row['alias'] = empty($row['alias']) ? change_alias($row['title']) : change_alias($row['alias']);

        if (empty($row['keywords'])) {
            $row['keywords'] = nv_get_keywords($row['title']);
            if (empty($row['keywords'])) {
                $row['keywords'] = nv_unhtmlspecialchars($row['keywords']);
                $row['keywords'] = strip_punctuation($row['keywords']);
                $row['keywords'] = trim($row['keywords']);
                $row['keywords'] = nv_strtolower($row['keywords']);
                $row['keywords'] = preg_replace('/[ ]+/', ',', $row['keywords']);
            }
        }

        if ($id and !$copy) {
            $_sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_agency SET title = :title, alias = :alias, image = :image, price_require = :price_require, number_sale=:number_sale, number_gift=:number_gift, price_for_discount=:price_for_discount, price_discount=:price_discount, percent_discount=:percent_discount, description = :description, bodytext = :bodytext, keywords = :keywords, edit_time = ' . NV_CURRENTTIME . ' WHERE id =' . $id;
            $publtime = $row['add_time'];
        } else {

            $weight = $db->query("SELECT MAX(weight) FROM " . $db_config['prefix'] . "_" . $module_data . '_agency')->fetchColumn();
            $weight = intval($weight) + 1;

            $_sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_agency
				(title, alias, image, price_require, number_sale, number_gift, price_for_discount, price_discount, percent_discount, description, bodytext, keywords, weight, add_time, edit_time, status) VALUES
				(:title, :alias, :image, :price_require, :number_sale, :number_gift, :price_for_discount, :price_discount, :percent_discount, :description, :bodytext, :keywords, ' . $weight . ', ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 1)';

            $publtime = NV_CURRENTTIME;
        }

        try {

            $sth = $db->prepare($_sql);
            $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
            $sth->bindParam(':alias', $row['alias'], PDO::PARAM_STR);
            $sth->bindParam(':image', $row['image'], PDO::PARAM_STR);
            $sth->bindParam(':price_require', $row['price_require'], PDO::PARAM_INT);
            $sth->bindParam(':number_sale', $row['number_sale'], PDO::PARAM_INT);
            $sth->bindParam(':number_gift', $row['number_gift'], PDO::PARAM_INT);
            $sth->bindParam(':price_for_discount', $row['price_for_discount'], PDO::PARAM_INT);
            $sth->bindParam(':price_discount', $row['price_discount'], PDO::PARAM_INT);
            $sth->bindParam(':percent_discount', $row['percent_discount'], PDO::PARAM_INT);
            $sth->bindParam(':description', $row['description'], PDO::PARAM_STR);
            $sth->bindParam(':bodytext', $row['bodytext'], PDO::PARAM_STR, strlen($row['bodytext']));
            $sth->bindParam(':keywords', $row['keywords'], PDO::PARAM_STR);
            $sth->execute();

            if ($sth->rowCount()) {
                if ($id) {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Edit', 'ID: ' . $id, $admin_info['userid']);
                } else {
                    nv_insert_logs(NV_LANG_DATA, $module_name, 'Add', ' ', $admin_info['userid']);
                }

                $nv_Cache->delMod($module_name);
                nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=agency');
            } else {
                $error = $lang_module['errorsave'];
            }
        } catch (PDOException $e) {
            $error = $lang_module['errorsave'];
        }
    }
} elseif (empty($id)) {
    $row['image'] = '';
    $row['description'] = '';
    $row['bodytext'] = '';
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$row['description'] = nv_htmlspecialchars(nv_br2nl($row['description']));
$row['bodytext'] = htmlspecialchars(nv_editor_br2nl($row['bodytext']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['bodytext'] = nv_aleditor('bodytext', '100%', '300px', $row['bodytext']);
} else {
    $row['bodytext'] = '<textarea style="width:100%;height:300px" name="bodytext">' . $row['bodytext'] . '</textarea>';
}

if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}

$row['price_require'] = number_format($row['price_require'], 0, '.', ',');
$row['price_discount'] = number_format($row['price_discount'], 0, '.', ',');
$row['price_for_discount'] = number_format($row['price_for_discount'], 0, '.', ',');

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', $action);
$xtpl->assign('UPLOADS_DIR_USER', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('DATA', $row);
$xtpl->assign('BODYTEXT', $row['bodytext']);

if (empty($row['alias'])) {
    $xtpl->parse('main.get_alias');
}

if ($error) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
