<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2018 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sun, 07 Jan 2018 13:39:51 GMT
 */
if (!defined('NV_IS_MOD_TASK')) die('Stop!!!');

$row = array();
$error = array();
$row['id'] = $nv_Request->get_int('id', 'post,get', 0);

if ($row['id'] > 0) {
    $lang_module['task_add'] = $lang_module['task_edit'];
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id=' . $row['id'])->fetch();
    if (empty($row) || !nv_check_task_admin($row['useradd'])) {
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
        die();
    }
    $row['performer'] = $row['performer_old'] = !empty($row['performer']) ? array_map('intval', explode(',', $row['performer'])) : array();
} else {
    $row['id'] = 0;
    $row['title'] = '';
    $row['performer'] = $row['performer_old'] = array();
    $row['begintime'] = 0;
    $row['exptime'] = 0;
    $row['description'] = '';
    $row['status'] = 0;
    $row['useradd'] = $user_info['userid'];
    $row['priority'] = 2;
}

if ($nv_Request->isset_request('submit', 'post')) {
    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['performer'] = $nv_Request->get_typed_array('performer', 'post', 'int');
    $row['status'] = $nv_Request->get_int('status', 'post', 0);
    if (!empty($array_config['allow_useradd'])) {
        $row['useradd'] = $nv_Request->get_int('useradd', 'post', 0);
    } else {
        $row['useradd'] = $user_info['userid'];
    }
    $row['priority'] = $nv_Request->get_int('priority', 'post', 0);

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('begindate', 'post'), $m)) {
        $begintime = $nv_Request->get_string('begintime', 'post');
        $begintime = !empty($begintime) ? explode(':', $begintime) : array(
            0,
            0
        );
        $row['begintime'] = mktime($begintime[0], $begintime[1], 0, $m[2], $m[1], $m[3]);
    } else {
        $row['begintime'] = 0;
    }

    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('expdate', 'post'), $m)) {
        $exptime = $nv_Request->get_string('exptime', 'post');
        $exptime = !empty($exptime) ? explode(':', $exptime) : array(
            23,
            59
        );
        $row['exptime'] = mktime($exptime[0], $exptime[1], 59, $m[2], $m[1], $m[3]);
    } else {
        $row['exptime'] = 0;
    }

    $row['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);
    $performer = !empty($row['performer']) ? implode(',', $row['performer']) : '';

    if (empty($row['title'])) {
        $error[] = $lang_module['error_required_title'];
    } elseif (empty($row['performer'])) {
        $error[] = $lang_module['error_required_userid'];
    }

    if (empty($error)) {
        try {
            $new_id = 0;
            if (empty($row['id'])) {
                $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title, performer, begintime, exptime, description, useradd, addtime, edittime, status, priority) VALUES (:title, :performer, :begintime, :exptime, :description, :useradd, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', :status, :priority)';
                $data_insert = array();
                $data_insert['title'] = $row['title'];
                $data_insert['performer'] = $performer;
                $data_insert['begintime'] = $row['begintime'];
                $data_insert['exptime'] = $row['exptime'];
                $data_insert['description'] = $row['description'];
                $data_insert['useradd'] = $row['useradd'];
                $data_insert['status'] = $row['status'];
                $data_insert['priority'] = $row['priority'];
                $new_id = $db->insert_id($_sql, 'id', $data_insert);
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['task_add'], sprintf($lang_module['logs_create_task'], $row['title']), $user_info['userid']);
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . ' SET title = :title, performer = :performer, begintime = :begintime, exptime = :exptime, description = :description, edittime = ' . NV_CURRENTTIME . ', status = :status, priority = :priority, useradd = :useradd WHERE id=' . $row['id']);
                $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
                $stmt->bindParam(':performer', $performer, PDO::PARAM_STR);
                $stmt->bindParam(':begintime', $row['begintime'], PDO::PARAM_INT);
                $stmt->bindParam(':exptime', $row['exptime'], PDO::PARAM_INT);
                $stmt->bindParam(':description', $row['description'], PDO::PARAM_STR, strlen($row['description']));
                $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);
                $stmt->bindParam(':useradd', $row['useradd'], PDO::PARAM_INT);
                $stmt->bindParam(':priority', $row['priority'], PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $new_id = $row['id'];
                }
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['task_add'], sprintf($lang_module['logs_edit_task'], $row['title']), $user_info['userid']);
            }

            if ($new_id > 0) {

                if ($row['performer'] != $row['performer_old']) {
                    $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_performer (taskid, userid) VALUES( :taskid, :userid)');
                    foreach ($row['performer'] as $userid) {
                        if (!in_array($userid, $row['performer_old'])) {
                            $sth->bindParam(':taskid', $new_id, PDO::PARAM_INT);
                            $sth->bindParam(':userid', $userid, PDO::PARAM_INT);
                            $sth->execute();
                        }
                    }

                    foreach ($row['performer_old'] as $userid) {
                        if (!in_array($userid, $row['performer'])) {
                            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_performer WHERE userid = ' . $userid . ' AND taskid=' . $new_id);
                        }
                    }
                }

                if (empty($row['id'])) {
                    // notification
                    if (isset($site_mods['notification']) && file_exists(NV_ROOTDIR . '/modules/notification/site.functions.php')) {
                        require_once NV_ROOTDIR . '/modules/notification/site.functions.php';
                        $content = sprintf($lang_module['new_task'], $row['title']);
                        $url = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=detail&id=' . $new_id;
                        nv_send_notification($row['performer'], $content, 'new_task', $module_name, $url);

                        if ($row['useradd'] != $user_info['userid']) {
                            $array_userid = array(
                                $row['useradd']
                            );
                            $content = sprintf($lang_module['new_task_add'], $workforce_assign[$user_info['userid']]['fullname'], $row['title']);
                            nv_send_notification($array_userid, $content, 'new_task_add', $module_name, $url);
                        }
                    }

                    // gui mail thong bao
                    $message = $db->query('SELECT econtent FROM ' . NV_PREFIXLANG . '_' . $module_data . '_econtent WHERE action="newtask"')->fetchColumn();

                    $user_working = array();
                    foreach ($row['performer'] as $userid) {
                        $user_working[] = $workforce_assign[$userid]['fullname'];
                    }
                    $user_working = !empty($user_working) ? implode(', ', $user_working) : '';

                    $array_replace = array(
                        'SITE_NAME' => $global_config['site_name'],
                        'TITLE' => $row['title'],
                        'USER_ADD' => $workforce_assign[$user_info['userid']]['fullname'],
                        'USER_WORKING' => $user_working,
                        'TIME_START' => !empty($row['begintime']) ? nv_date('H:I d/m/Y', $row['begintime']) : '',
                        'TIME_END' => !empty($row['exptime']) ? nv_date('H:I d/m/Y', $row['exptime']) : '',
                        'CONTENT' => $row['description'],
                        'STATUS' => $lang_module['task_status_' . $row['status']],
                        'TASK_URL' => NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=detail&amp;id=' . $new_id
                    );
                    $message = nv_unhtmlspecialchars($message);
                    foreach ($array_replace as $index => $value) {
                        $message = str_replace('[' . $index . ']', $value, $message);
                    }

                    $mail = new NukeViet\Core\Sendmail($global_config, NV_LANG_INTERFACE);
                    $mail->addReplyTo($workforce_assign[$user_info['userid']]['email'], $workforce_assign[$user_info['userid']]['fullname']);
                    $mail->Content($message);
                    $mail->Subject($row['title']);

                    foreach ($row['performer'] as $userid) {
                        $mail->To($workforce_assign[$userid]['email'], $workforce_assign[$userid]['fullname']);
                    }

                    if (!$mail->Send()) {
                        trigger_error($lang_module['error_send_mail']);
                    }
                }

                $nv_Cache->delMod($module_name);

                Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=task');
                die();
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
        }
    }
}

$row['begindate'] = !empty($row['begintime']) ? date('d/m/Y', $row['begintime']) : '';
$row['begintime'] = !empty($row['begintime']) ? date('H:i', $row['begintime']) : '';
$row['expdate'] = !empty($row['exptime']) ? date('d/m/Y', $row['exptime']) : '';
$row['exptime'] = !empty($row['exptime']) ? date('H:i', $row['exptime']) : '';

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
} elseif (!nv_function_exists('nv_aleditor') and file_exists(NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js')) {
    define('NV_EDITOR', true);
    define('NV_IS_CKEDITOR', true);
    $my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

    function nv_aleditor($textareaname, $width = '100%', $height = '450px', $val = '', $customtoolbar = '')
    {
        global $module_data;
        $return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
        $return .= "<script type=\"text/javascript\">
		CKEDITOR.replace( '" . $module_data . "_" . $textareaname . "', {" . (!empty($customtoolbar) ? 'toolbar : "' . $customtoolbar . '",' : '') . " width: '" . $width . "',height: '" . $height . "',});
		</script>";
        return $return;
    }
}

$row['description'] = htmlspecialchars(nv_editor_br2nl($row['description']));
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['description'] = nv_aleditor('description', '100%', '300px', $row['description']);
} else {
    $row['description'] = '<textarea style="width:100%;height:300px" name="description">' . $row['description'] . '</textarea>';
}

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);

if (!empty($workforce_leader)) {
    foreach ($workforce_leader as $user) {
        $user['selected'] = in_array($user['userid'], $row['performer']) ? 'selected="selected"' : '';
        $xtpl->assign('USER', $user);
        $xtpl->parse('main.leader.loop');
    }
    $xtpl->parse('main.leader');
}

if (!empty($workforce_member)) {
    foreach ($workforce_member as $user) {
        $user['selected'] = in_array($user['userid'], $row['performer']) ? 'selected="selected"' : '';
        $xtpl->assign('USER', $user);
        $xtpl->parse('main.member.loop');
    }
    $xtpl->parse('main.member');
}

foreach ($array_priority as $index => $value) {
    $sl = $index == $row['priority'] ? 'selected="selected"' : '';
    $xtpl->assign('VALUE', array(
        'index' => $index,
        'value' => $value,
        'selected' => $sl
    ));
    $xtpl->parse('main.looppriority');
}

if (!empty($workforce_assign)) {
    foreach ($workforce_assign as $user) {
        $user['selected'] = in_array($user['userid'], $row['performer']) ? 'selected="selected"' : '';
        $xtpl->assign('USER', $user);
        $xtpl->parse('main.user');
    }
}

if (!empty($array_config['allow_useradd'])) {
    if (!empty($workforce_assign)) {
        foreach ($workforce_assign as $user) {
            $user['selected'] = $user['userid'] == $row['useradd'] ? 'selected="selected"' : '';
            $xtpl->assign('USERADD', $user);
            $xtpl->parse('main.allow_useradd.useradd');
        }
    }
    $xtpl->parse('main.allow_useradd');
}

foreach ($array_task_status as $index => $value) {
    $sl = $index == $row['status'] ? 'selected="selected"' : '';
    $xtpl->assign('STATUS', array(
        'index' => $index,
        'value' => $value,
        'selected' => $sl
    ));
    $xtpl->parse('main.status');
}

if (!empty($error)) {
    $xtpl->assign('ERROR', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['task_add'];
$array_mod_title[] = array(
    'title' => $page_title,
    'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op
);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';