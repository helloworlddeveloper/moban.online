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
$draft = $nv_Request->isset_request('draft', 'post');

if ($row['id'] > 0) {
    $lang_module['campaign_add'] = $lang_module['campaign_edit'];
    $row = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows WHERE id=' . $row['id'] . ' AND sendstatus != 1')->fetch();
    if (empty($row) or $row['sendstatus'] == 1) {
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
        die();
    }
    $row['linkmd5'] = $row['linkmd5_old'] = array();
    $result = $db->query('SELECT linkmd5 FROM ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows_link WHERE rowsid=' . $row['id']);
    while (list ($linkmd5) = $result->fetch(3)) {
        $row['linkmd5'][] = $linkmd5;
    }
    $row['linkmd5_old'] = $row['linkmd5'];
} else {
    $row['id'] = 0;
    $row['content'] = '';
    $row['usergroup'] = array();
    $row['customergroup'] = array();
    $row['phonelist'] = '';
    $row['typetime'] = 0;
    $row['begintime'] = 0;
    $row['endtime'] = 0;
    $row['linkstatics'] = 1;
    $row['openstatics'] = 1;
    $row['linkmd5'] = $row['linkmd5_old'] = array();
}

$row['redirect'] = $nv_Request->get_title('redirect', 'get', '');

if ($nv_Request->isset_request('submit', 'post') or $draft) {
    $row['content'] = $nv_Request->get_textarea('content', '', 'br', 1);
    $row['usergroup'] = $nv_Request->get_typed_array('usergroup', 'post', 'int');
    $row['customergroup'] = $nv_Request->get_typed_array('customergroup', 'post', 'int');
    $row['phonelist'] = $nv_Request->get_textarea('phonelist', '', 0, 1);
    $row['totalsmssend'] = $nv_Request->get_int('totalsmssend', 'post', 0);
    $row['totalsmssuccess'] = $nv_Request->get_int('totalsmssuccess', 'post', 0);
    $row['typetime'] = $nv_Request->get_int('typetime', 'post', 0);
    if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $nv_Request->get_string('begintime', 'post'), $m)) {
        $_hour = $nv_Request->get_int('begintime_hour', 'post');
        $_min = $nv_Request->get_int('begintime_min', 'post');
        $row['begintime'] = mktime($_hour, $_min, 59, $m[2], $m[1], $m[3]);
    } else {
        $row['begintime'] = 0;
    }
    $row['endtime'] = $nv_Request->get_int('endtime', 'post', 0);
    $row['linkstatics'] = $nv_Request->get_int('linkstatics', 'post', 0);
    $row['openstatics'] = $nv_Request->get_int('openstatics', 'post', 0);
    if (!$draft) {
        if (empty($row['content'])) {
            $error[] = $lang_module['error_required_contentsms'];
        }
        $row['sendstatus'] = 0;
    } else {
        $row['sendstatus'] = 2;
    }

    $is_vaild = 0;
    if (!empty($row['usergroup'])) {
        $is_vaild = 1;
        $row['usergroup'] = serialize($row['usergroup']);
    } else {
        $row['usergroup'] = '';
    }

    if (!empty($row['customergroup'])) {
        $is_vaild = 1;
        $row['customergroup'] = serialize($row['customergroup']);
    } else {
        $row['customergroup'] = '';
    }
    if (!empty($row['phonelist'])) {
        $is_vaild = 1;
        $row['phonelist'] = explode('<br />', $row['phonelist']);
        foreach ($row['phonelist'] as $index => $phone) {
            if (!empty($phone)) {
                if ($check = nv_emailmarketing_check_mobile($phone)) {
                    if (!empty($check)) {
                        $error[] = '<strong>[' . $phone . ']</strong> ' . $check;
                    }
                }
            } else {
                unset($row['phonelist'][$index]);
            }
        }

        if (!empty($row['phonelist'])) {
            $row['phonelist'] = implode('<br />', $row['phonelist']);
        }
    }

    if (!$is_vaild) {
        $error[] = $lang_module['error_required_phone'];
    }

    if (empty($error)) {
        try {
            $new_id = 0;

            // Kiem tra link trong noi dung
            if ($row['linkstatics']) {
                $array_link = array();
                $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
                if (preg_match_all("/$regexp/siU", $row['content'], $matches)) {
                    foreach ($matches[2] as $index => $link) {
                        $linkmd5 = md5($link);
                        $array_link[$index] = array(
                            'text' => $matches[3][$index],
                            'link' => $link,
                            'linkmd5' => $linkmd5
                        );
                        $row['linkmd5'][$index] = $linkmd5;
                    }
                }
            }

            if (empty($row['id'])) {
                $_sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows (content, customergroup, phonelist, addtime, typetime, begintime, endtime, linkstatics, openstatics, sendstatus) VALUES (:content, :customergroup, :phonelist, ' . NV_CURRENTTIME . ', :typetime, :begintime, :endtime, :linkstatics, :openstatics, :sendstatus)';
                $data_insert = array();
                $data_insert['content'] = $row['content'];
                $data_insert['customergroup'] = $row['customergroup'];
                $data_insert['phonelist'] = $row['phonelist'];
                $data_insert['typetime'] = $row['typetime'];
                $data_insert['begintime'] = $row['begintime'];
                $data_insert['endtime'] = $row['endtime'];
                $data_insert['linkstatics'] = $row['linkstatics'];
                $data_insert['openstatics'] = $row['openstatics'];
                $data_insert['sendstatus'] = $row['sendstatus'];
                $new_id = $db->insert_id($_sql, 'id', $data_insert);
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows SET content = :content, customergroup = :customergroup, phonelist = :phonelist, begintime = :begintime, typetime = :typetime, endtime = :endtime, linkstatics = :linkstatics, openstatics = :openstatics, sendstatus = :sendstatus WHERE id=' . $row['id']);
                $stmt->bindParam(':content', $row['content'], PDO::PARAM_STR, strlen($row['content']));
                $stmt->bindParam(':customergroup', $row['customergroup'], PDO::PARAM_STR, strlen($row['customergroup']));
                $stmt->bindParam(':phonelist', $row['phonelist'], PDO::PARAM_STR, strlen($row['phonelist']));
                $stmt->bindParam(':typetime', $row['typetime'], PDO::PARAM_INT);
                $stmt->bindParam(':begintime', $row['begintime'], PDO::PARAM_INT);
                $stmt->bindParam(':endtime', $row['endtime'], PDO::PARAM_INT);
                $stmt->bindParam(':linkstatics', $row['linkstatics'], PDO::PARAM_INT);
                $stmt->bindParam(':openstatics', $row['openstatics'], PDO::PARAM_INT);
                $stmt->bindParam(':sendstatus', $row['sendstatus'], PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $new_id = $row['id'];
                }
            }

            if ($new_id > 0) {

                if ($row['linkstatics']) {
                    if ($row['linkmd5'] != $row['linkmd5_old']) {
                        $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows_link (rowsid, linkmd5, link) VALUES(:rowsid, :linkmd5, :link)');
                        foreach ($row['linkmd5'] as $index => $linkmd5) {
                            if (!in_array($linkmd5, $row['linkmd5_old'])) {
                                $sth->bindParam(':rowsid', $new_id, PDO::PARAM_INT);
                                $sth->bindParam(':linkmd5', $linkmd5, PDO::PARAM_STR);
                                $sth->bindParam(':link', $array_link[$index]['link'], PDO::PARAM_STR);
                                $sth->execute();
                            }
                        }

                        foreach ($row['linkmd5_old'] as $linkmd5_old) {
                            if (!in_array($linkmd5_old, $row['linkmd5'])) {
                                $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_smsrows_link WHERE linkmd5 = ' . $db->quote($linkmd5_old) . ' AND rowsid=' . $new_id);
                            }
                        }
                    }
                }

                $nv_Cache->delMod($module_name);

                if (!empty($row['redirect'])) {
                    $url = nv_redirect_decrypt($row['redirect']);
                } elseif ($row['typetime'] == 0) {
                    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=sendsms&id=' . $new_id;
                } else {
                    $url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
                }

                Header('Location: ' . $url);
                die();
            }
        } catch (PDOException $e) {
            trigger_error($e->getMessage());
            die($e->getMessage()); // Remove this line after checks finished
        }
    }
}

if (empty($row['begintime'])) {
    $row['begintimef'] = '';
} else {
    $row['begintimef'] = date('d/m/Y', $row['begintime']);
}

if (!empty($row['phonelist'])) {
    $row['phonelist'] = nv_br2nl($row['phonelist']);
}

$row['style_begintime'] = $row['typetime'] == 0 ? 'style="display: none"' : '';
$row['ck_linkstatics'] = $row['linkstatics'] ? 'checked="checked"' : '';
$row['ck_openstatics'] = $row['openstatics'] ? 'checked="checked"' : '';

$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('ROW', $row);
$xtpl->assign('NOTE', nv_get_balance());

$row['customergroup'] = !empty($row['customergroup']) ? unserialize($row['customergroup']) : array();
if (!empty($array_customer_groups)) {
    foreach ($array_customer_groups as $customergroups) {
        $customergroups['checked'] = in_array($customergroups['id'], $row['customergroup']) ? 'checked="checked"' : '';
        $xtpl->assign('CUSGROUP', $customergroups);
        $xtpl->parse('main.customergroup');
    }
}

$array_usergroup = nv_groups_list();
$row['usergroup'] = !empty($row['usergroup']) ? unserialize($row['usergroup']) : array();
if (!empty($array_usergroup)) {
    foreach ($array_usergroup as $groupid => $title) {
        $ck = in_array($groupid, $row['usergroup']) ? 'checked="checked"' : '';
        $xtpl->assign('USERGROUP', array(
            'id' => $groupid,
            'title' => $title,
            'checked' => $ck
        ));
        $xtpl->parse('main.usergroup');
    }
}

$array_typetime = array(
    0 => $lang_module['typetime_0']
);

if ($array_config['allow_cronjobs']) {
    $array_typetime += array(
        1 => $lang_module['typetime_1']
    );
}

foreach ($array_typetime as $index => $value) {
    $ck = $index == $row['typetime'] ? 'checked="checked"' : '';
    $xtpl->assign('TYPETIME', array(
        'index' => $index,
        'value' => $value,
        'checked' => $ck
    ));
    $xtpl->parse('main.typetime');
}

$hour = !empty($row['begintime']) ? date('H', $row['begintime']) : 0;
for ($i = 0; $i <= 23; $i++) {
    $sl = $i == $hour ? 'selected="selected"' : '';
    $xtpl->assign('HOUR', array(
        'index' => $i,
        'selected' => $sl
    ));
    $xtpl->parse('main.hour');
}

$min = !empty($row['begintime']) ? date('i', $row['begintime']) : 0;
for ($i = 0; $i <= 59; $i++) {
    $sl = $i == $min ? 'selected="selected"' : '';
    $xtpl->assign('MIN', array(
        'index' => $i,
        'selected' => $sl
    ));
    $xtpl->parse('main.min');
}

array_unshift($array_template, array(
    'id' => 0,
    'title' => $lang_module['nonuse'],
    'image' => ''
));

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

$page_title = $lang_module['campaign_add'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';