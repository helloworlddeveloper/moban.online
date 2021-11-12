<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */
if (!defined('NV_IS_MOD_WORKFORCE')) die('Stop!!!');

if (!nv_user_in_groups($array_config['group_add_workforce'])) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    die();
}

$table_name = NV_PREFIXLANG . "_" . $module_data . "_producttype";
if ($nv_Request->isset_request('delete', 'get', 0)) {
    
    $id = $nv_Request->get_int('id', 'post,get', 0);
    $contents = "NO_" . $id;
    
    if ($id > 0) {
        $sql = "DELETE FROM " . $table_name . " WHERE id=" . $id;
        if ($db->exec($sql)) {
            $contents = "OK_" . $id;
        }
    } else {
        $listall = $nv_Request->get_string('listall', 'post,get');
        $array_id = explode(',', $listall);
        $array_id = array_map("intval", $array_id);
        
        foreach ($array_id as $id) {
            if ($id > 0) {
                $sql = "DELETE FROM " . $table_name . " WHERE id=" . $id;
                $db->query($sql);
            }
        }
        $contents = "OK_0";
    }
    die($contents);
}

$page_title = $lang_module['producttype'];
$error = "";

$data = array(
    "title" => "",
    'note' => ""
);
$data['id'] = $nv_Request->get_int('id', 'post,get', 0);
$savecat = $nv_Request->get_int('savecat', 'post', 0);
$add = $nv_Request->get_int('add', 'get', 0);
if (!empty($savecat)) {
    $data['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 255);
    $data['note'] = $nv_Request->get_title('note', 'post', '', 1);
    if (empty($data['title'])) {
        $error = $lang_module['producttype_name_error'];
    } else {
        if ($data['id'] == 0) {
            
            $sql = "INSERT INTO " . $table_name . " (id, title, note) VALUES (NULL, " . $db->quote($data['title']) . ", " . $db->quote($data['note']) . ")";
            
            if ($db->insert_id($sql)) {
                $nv_Cache->delMod($module_name);
                
                Header("Location: " . NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
                die();
            } else {
                $error = $lang_module['errorsave'];
            }
        } else {
            $stmt = $db->prepare("UPDATE " . $table_name . " SET title= :title, note = :note WHERE id =" . $data['id']);
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':note', $data['note'], PDO::PARAM_STR);
            if ($stmt->execute()) {
                $error = $lang_module['saveok'];
                
                $nv_Cache->delMod($module_name);
                Header("Location: " . NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
                die();
            } else {
                $error = $lang_module['errorsave'];
            }
        }
    }
} else {
    if ($data['id'] > 0) {
        $data_old = $db->query("SELECT * FROM " . $table_name . " WHERE id=" . $data['id'])->fetch();
        $data = array(
            "id" => $data_old['id'],
            "title" => $data_old['title'],
            "note" => $data_old['note']
        );
    }
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $data);
$xtpl->assign('add_more', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . '&add=1');

if ($add == 0 && $data['id'] == 0) {
    $result = $db->query("SELECT id, title, note FROM " . $table_name . " ORDER BY id DESC");
    while (list ($id, $title, $note) = $result->fetch(3)) {
        $xtpl->assign('title', $title);
        $xtpl->assign('note', $note);
        $xtpl->assign('id', $id);
        $xtpl->assign('link_edit', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&id=" . $id);
        $xtpl->assign('link_del', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&delete=1&id=" . $id);
        $xtpl->parse('main.data.row');
    }
    $xtpl->parse('main.data');
    $xtpl->assign('URL_DEL', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . '&delete=1');
    $xtpl->assign('URL_DEL_BACK', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
} else {
    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.add.error');
    }
    $xtpl->parse('main.add');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

// $xtpl->parse('main');
// $contents = nv_theme_workforce_control( $array_control );
// $contents .= $xtpl->text( 'main' );

$array_mod_title[] = array(
    'catid' => 0,
    'title' => $page_title,
    'link' => NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
