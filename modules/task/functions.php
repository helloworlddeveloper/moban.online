<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (contact@tdfoss.vn)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Sat, 13 Jan 2018 13:35:09 GMT
 */
if (!defined('NV_SYSTEM')) die('Stop!!!');

define('NV_IS_MOD_TASK', true);

if (!defined('NV_IS_USER')) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
    die();
}

$array_config = $module_config[$module_name];
require_once NV_ROOTDIR . '/modules/task/site.functions.php';

$array_task_status = array(
    0 => $lang_module['task_status_0'],
    1 => $lang_module['task_status_1'],
    2 => $lang_module['task_status_2']
);

$array_priority = array(
    1 => $lang_module['priority1'],
    2 => $lang_module['priority2'],
    3 => $lang_module['priority3']
);

if (!isset($site_mods['workforce'])) {
    $workforce_list = array();
    $where = '';

    $where .= !empty($array_config['groups_use']) ? ' AND userid IN (SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE group_id IN (' . $array_config['groups_use'] . '))' : '';
    $result = $db->query('SELECT userid, first_name, last_name, username, email FROM ' . NV_USERS_GLOBALTABLE . ' WHERE active=1' . $where);
    while ($row = $result->fetch()) {
        $row['fullname'] = nv_show_name_user($row['first_name'], $row['last_name'], $row['username']);
        $workforce_list[$row['userid']] = $row;
    }
}

if (empty($workforce_list)) {
    $contents = nv_theme_alert($lang_module['workforce_empty_title'], $lang_module['workforce_empty_content'], 'danger');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
}

$array_leader = $workforce_assign = $workforce_leader = $workforce_member = array();
$array_userid = $array_userid_allow = nv_task_premission($module_name, 'array_userid');

$count = $db->query('SELECT COUNT(*) FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE is_leader=1 AND approved=1 AND userid=' . $user_info['userid'])->fetchColumn();
if ($count > 0) {
    // nếu là trưởng nhóm bất kỳ thì có thể giao việc cho các trưởng nhóm khác
    $_query = $db->query('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users where is_leader=1');
    while (list ($userid) = $_query->fetch(3)) {
        $array_leader[] = $userid;
    }
    $array_userid_allow = $array_userid_allow + $array_leader;
} else {
    // lấy danh sách userid thuộc các nhóm người này tham gia
    $result = $db->query('SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE approved=1 AND group_id IN (SELECT group_id FROM ' . NV_USERS_GLOBALTABLE . '_groups_users WHERE approved=1 AND userid=' . $user_info['userid'] . ')');
    while (list ($userid) = $result->fetch(3)) {
        $array_userid[] = $userid;
    }
    $array_userid_allow = $array_userid_allow + $array_userid;
}
$array_userid_allow = !empty($array_userid_allow) ? array_unique($array_userid_allow) : array();

foreach (array_keys($workforce_list) as $userid) {
    if (in_array($userid, $array_userid_allow)) {
        $workforce_assign[$userid] = $workforce_list[$userid];
    }
}

foreach (array_keys($workforce_list) as $userid) {
    if (in_array($userid, $array_leader)) {
        $workforce_leader[$userid] = $workforce_list[$userid];
    }
}

foreach (array_keys($workforce_list) as $userid) {
    if (in_array($userid, $array_userid) && !in_array($userid, $array_leader)) {
        $workforce_member[$userid] = $workforce_list[$userid];
    }
}

function nv_task_delete($id)
{
    global $db, $module_data;

    $count = $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . ' WHERE id = ' . $id);
    if ($count) {
        $db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_performer WHERE taskid = ' . $id);
    }
}

/**
 * nv_task_download()
 *
 * @param mixed $array_data
 * @param mixed $type
 * @return
 *
 */
function nv_task_download($array_data, $type = 'xlsx')
{
    global $module_name, $admin_info, $lang_module;

    $array = array(
        'objType' => '',
        'objExt' => ''
    );
    switch ($type) {
        case 'xlsx':
            $array['objType'] = 'Excel2007';
            $array['objExt'] = 'xlsx';
            break;
        case 'ods':
            $array['objType'] = 'OpenDocument';
            $array['objExt'] = 'ods';
            break;
        default:
            $array['objType'] = 'CSV';
            $array['objExt'] = 'csv';
    }

    $objPHPExcel = PHPExcel_IOFactory::load(NV_ROOTDIR . '/modules/task/template.xls');
    $objPHPExcel->setActiveSheetIndex(0);

    // Set properties
    $objPHPExcel->getProperties()
        ->setCreator($admin_info['username'])
        ->setLastModifiedBy($admin_info['username'])
        ->setTitle($lang_module['task_list'])
        ->setSubject($lang_module['task_list'])
        ->setDescription($lang_module['task_list'])
        ->setCategory($module_name);

    $columnIndex = 0;
    $rowIndex = 4;

    // Hien thi du lieu
    $i = $rowIndex + 1;
    $z = $number = 1;
    foreach ($array_data as $data) {
        $j = $columnIndex;
        $data['id'] = $z++;
        foreach ($data as $field => $value) {
            $col = PHPExcel_Cell::stringFromColumnIndex($j);
            $CellValue = $value;
            $objPHPExcel->getActiveSheet()->setCellValue($col . $i, $CellValue);
            $j++;
        }
        $i++;
    }

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $array['objType']);
    $file_src = NV_ROOTDIR . NV_BASE_SITEURL . NV_TEMP_DIR . '/' . change_alias($lang_module['post_list'] . '-' . nv_date('d/m/Y', NV_CURRENTTIME)) . '.' . $array['objExt'];
    $objWriter->save($file_src);

    $download = new NukeViet\Files\Download($file_src, NV_ROOTDIR . NV_BASE_SITEURL . NV_TEMP_DIR);
    $download->download_file();
    die();
}

function nv_check_task_admin($useradd)
{
    global $user_info, $array_config;

    $group_manage = !empty($array_config['groups_manage']) ? explode(',', $array_config['groups_manage']) : array();
    $group_manage = array_map('intval', $group_manage);

    if (!empty(array_intersect($group_manage, $user_info['in_groups'])) || $user_info['userid'] == $useradd) {
        return true;
    }
    return false;
}