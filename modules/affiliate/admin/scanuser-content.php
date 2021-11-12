<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.net)
 * @Copyright (C) 2016 mynukeviet. All rights reserved
 * @Createdate Tue, 08 Nov 2016 01:39:51 GMT
 */
if (!defined('NV_IS_FILE_ADMIN')) die('Stop!!!');

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);

if ($row['id'] > 0) {
    $lang_module['scenario_add'] = $lang_module['scenario_edit'];
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_scanuser_config WHERE id=' . $row['id'] )->fetch();
    if (empty($row)) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        die();
    }
} else {
    $row['id'] = 0;
    $row['content'] = '';
    $row['hourscan'] = 10;
    $row['action'] = 0;
}

$row['redirect'] = $nv_Request->get_title('redirect', 'get', '');

if ($nv_Request->isset_request('submit', 'post') or $nv_Request->isset_request('draft', 'post') ) {
    $row['sid'] = $nv_Request->get_int('sid', 'post', 0);
    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['content'] = $nv_Request->get_textarea('content', '', 'br', 1);
    $row['hourscan'] = $nv_Request->get_int('hourscan', 'post', 0);
    $row['action'] = $nv_Request->get_int('action', 'post', 0);
    if ($nv_Request->isset_request('draft', 'post')) {
        $row['status'] = 0;
    } else {
        if (empty($row['content'])) {
            $error[] = $lang_module['error_required_content_sms'];
        }if ($row['hourscan'] == 0) {
            $error[] = $lang_module['error_required_action_scan'];
        }
        $row['status'] = 1;
    }

    if (empty($error)) {
        try {
            $new_id = $insert= 0;

            if (empty($row['id'])) {
                $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_scanuser_config ( content, hourscan, action, status) VALUES (:content, :hourscan, :action, :status)';
                $data_insert = array();
                $data_insert['content'] = $row['content'];
                $data_insert['hourscan'] = $row['hourscan'];
                $data_insert['action'] = $row['action'];
                $data_insert['status'] = $row['status'];
                $new_id = $db->insert_id($_sql, 'id', $data_insert);
                $insert = 1;
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_scanuser_config SET content = :content, hourscan = :hourscan, action = :action, status = :status WHERE id=' . $row['id']);
                $stmt->bindParam(':content', $row['content'], PDO::PARAM_STR, strlen($row['content']));
                $stmt->bindParam(':hourscan', $row['hourscan'], PDO::PARAM_INT);
                $stmt->bindParam(':action', $row['action'], PDO::PARAM_INT);
                $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $new_id = $row['id'];
                }
            }

            if ($new_id > 0) {
                $nv_Cache->delMod($module_name);
                Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=scanuser' );
                die();
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            $error[] = $e->getMessage();

        }
    }
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

$array_action = array(
    0 => $lang_module['action_0'],
    1 => $lang_module['action_1']
);

foreach ($array_action as $index => $value) {
    $sl = $index == $row['action'] ? 'selected="selected"' : '';
    $xtpl->assign('ACTION', array(
        'key' => $index,
        'val' => $value,
        'selected' => $sl
    ));
    $xtpl->parse('main.action');
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

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['scanuser_content'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';