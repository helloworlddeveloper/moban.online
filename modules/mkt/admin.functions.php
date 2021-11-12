<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 18 Nov 2014 01:50:26 GMT
 */

if(! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN'))
    die('Stop!!!');


$group_allow_eventcontent = 10;

$array_permissions_action = array('list_op' => array(
        'jobs',
        'relationship',
        'from',
        'eventtype',
        'eventcontent',
        'viewschool',
        'viewstudent',
        'student',
        'measure',
        ), 'submenu' => array(
        'student',
        'eventcontent',
        'eventtype',
        'from',
        'measure',
        'config'));
/*
//neu la admin lev 2 hoac 1
if(defined('NV_IS_SPADMIN'))
{
*/
    $submenu['main'] = $lang_module['main'];
    $submenu['student'] = $lang_module['student'];
    $submenu['event'] = $lang_module['event'];
    $submenu['eventtype'] = $lang_module['eventtype'];
    $submenu['eventcontent'] = $lang_module['event_content'];
    $submenu['from'] = $lang_module['from'];
    $submenu['measure'] = $lang_module['measure'];
    $submenu['copy-data'] = $lang_module['copy_data'];
    $submenu['import-data'] = $lang_module['import_data'];
    $submenu['config'] = $lang_module['config'];

    $allow_func = array(
        'main',
        'from',
        'measure',
        'event',
        'list-sms',
        'smscontent',
        'eventtype',
        'eventcontent',
        'config',
        'viewschool',
        'viewstudent',
        'student',
        'facebook',
        'province',
        'district',
        'copy-data',
        'import-data',
        'export-student',
        'updatemap');

    foreach($array_permissions_action['list_op'] as $list_op)
    {
        $permissions_users[$admin_info['admin_id']][$list_op]['add'] = 1;
        $permissions_users[$admin_info['admin_id']][$list_op]['edit'] = 1;
        $permissions_users[$admin_info['admin_id']][$list_op]['view'] = 1;
        $permissions_users[$admin_info['admin_id']][$list_op]['order'] = 1;
        $permissions_users[$admin_info['admin_id']][$list_op]['del'] = 1;
    }
    /*
}
else
{
    $allow_func[] = 'main';
    $permissions_users = unserialize($module_config[$module_name]['permissions_users']);
    foreach($array_permissions_action['list_op'] as $list_op)
    {
        if(isset($lang_module[$list_op]))
        {
            if((isset($permissions_users[$admin_info['admin_id']][$list_op]['view']) && $permissions_users[$admin_info['admin_id']][$list_op]['view'] == 1))
            {
                $allow_func[] = $list_op;
                foreach($array_permissions_action['submenu'] as $submenu_permission)
                {
                    if($submenu_permission == $list_op)
                    {
                        $submenu[$list_op] = $lang_module[$list_op];
                    }
                }
            }
        }
    }
}
*/
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

function fix_catWeight()
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_province ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while($row = $result->fetch())
    {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_province SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query($query);
    }
}

function fix_DisWeight($pro)
{
    global $db, $module_data;

    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . "_district WHERE idprovince=" . $pro . " ORDER BY weight ASC";
    $result = $db->query($sql);
    $weight = 0;
    while($row = $result->fetch())
    {
        $weight++;
        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_district SET weight=" . $weight . " WHERE id=" . $row['id'] . " AND idprovince=" . $pro;
        $db->query($query);
    }
}

define('NV_IS_FILE_ADMIN', true);
