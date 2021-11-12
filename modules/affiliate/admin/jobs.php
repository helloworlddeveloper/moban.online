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


if( $nv_Request->isset_request('change_weight', 'post', 0) ){

    $id = $nv_Request->get_int('id', 'post', 0);

    $sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_jobs WHERE id=' . $id;
    $id = $db->query($sql)->fetchColumn();
    if (empty($id)) {
        die('NO_' . $id);
    }

    $new_weight = $nv_Request->get_int('new_weight', 'post', 0);
    if (empty($new_weight)) {
        die('NO_' . $mod);
    }

    $sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_jobs WHERE id!=' . $id . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        if ($weight == $new_weight) {
            ++$weight;
        }

        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_jobs SET weight=' . $weight . ' WHERE id=' . $row['id'];
        $db->query($sql);
    }

    $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_jobs SET weight=' . $new_weight . ' WHERE id=' . $id;
    $db->query($sql);

    $nv_Cache->delMod($module_name);

    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $id;
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}else if( $nv_Request->isset_request('change_status', 'post', 0) ){

    $id = $nv_Request->get_int('id', 'post', 0);

    $sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_jobs WHERE id=' . $id;
    $id = $db->query($sql)->fetchColumn();
    if (empty($id)) {
        die('NO_' . $id);
    }

    $new_status = $nv_Request->get_bool('new_status', 'post');
    $new_status = ( int )$new_status;

    $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_jobs SET status=' . $new_status . ' WHERE id=' . $id;
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

        $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $module_data . '_jobs WHERE id = ' . $id;
        if ($db->exec($sql)) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'Delete possiton', 'ID: ' . $id, $admin_info['userid']);

            $sql = 'SELECT id FROM ' . $db_config['prefix'] . '_' . $module_data . '_jobs ORDER BY weight ASC';
            $result = $db->query($sql);
            $weight = 0;
            while ($row = $result->fetch()) {
                ++$weight;
                $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_possiton SET weight=' . $weight . ' WHERE id=' . $row['id'];
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
$action = '';
$id = $nv_Request->get_int('id', 'post,get', 0);
if( $nv_Request->isset_request('add', 'get' ) || $id > 0 ){

    if ($id) {
        $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_jobs WHERE id=' . $id;
        $row = $db->query($sql)->fetch();

        if (empty($row)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);
        }

        $page_title = $lang_module['edit_jobs'];
        $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&add=1&amp;id=' . $id;
    } else {
        $page_title = $lang_module['add_jobs'];
        $action = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&add=1';
    }

    $error = '';

    if ($nv_Request->isset_request('submit', 'post')) {
        $row['title'] = $nv_Request->get_title('title', 'post', '', 1);
        $row['description'] = $nv_Request->get_textarea('description', 'post',0);

        if (empty($row['title'])) {
            $error = $lang_module['empty_title'];
        } else {
            if ($id) {
                $_sql = 'UPDATE ' . $db_config['prefix'] . '_' . $module_data . '_jobs SET title = :title, desctiption = :desctiption WHERE id =' . $id;

            } else {

                $weight = $db->query("SELECT MAX(weight) FROM " . $db_config['prefix'] . "_" . $module_data . '_jobs')->fetchColumn();
                $weight = intval($weight) + 1;

                $_sql = 'INSERT INTO ' . $db_config['prefix'] . '_' . $module_data . '_jobs
				(title, desctiption, weight, status) VALUES
				(:title, :desctiption, ' . $weight . ', 1)';
            }

            try {
                $sth = $db->prepare($_sql);
                $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
                $sth->bindParam(':desctiption', $row['desctiption'], PDO::PARAM_STR);

                $sth->execute();

                if ($sth->rowCount()) {
                    $nv_Cache->delMod($module_name);
                    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
                } else {
                    $error = $lang_module['errorsave'];
                }
            } catch (PDOException $e) {
                $error = $e->getMessage();
            }
        }
    }

}else{

    $sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $module_data . '_jobs ORDER BY weight ASC';
    $_rows = $db->query($sql)->fetchAll();
    $num = sizeof($_rows);
    if ($num < 1) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add=1');
    }

}
$xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('FORM_ACTION', $action);
$xtpl->assign('DATA', $row);
$xtpl->assign('op', $op);
$xtpl->assign('add_jobs', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&add=1');
if( !empty( $_rows )){
    $i = 0;
    foreach ($_rows as $row) {
        $row['url_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;id=' . $row['id'];
        $row['checkss'] = md5($row['id'] . NV_CHECK_SESSION);
        for ($i = 1; $i <= $num; ++$i) {
            $xtpl->assign('WEIGHT', array(
                'w' => $i,
                'selected' => ($i == $row['weight']) ? ' selected="selected"' : ''
            ));
            $xtpl->parse('main.data.row.weight');
        }
        $array_status = array(
            $lang_module['inactive'],
            $lang_module['active']
        );
        foreach ($array_status as $key => $val) {
            $xtpl->assign('STATUS', array(
                'key' => $key,
                'val' => $val,
                'selected' => ($key == $row['status']) ? ' selected="selected"' : ''
            ));

            $xtpl->parse('main.data.row.status');
        }
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.data.row');
    }
    $xtpl->parse('main.data');
}else{
    if ($error) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.add.error');
    }
    $xtpl->parse('main.add');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
