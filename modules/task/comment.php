<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 21 Jan 2014 01:32:02 GMT
 */
if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!isset($is_delete)) {
    require_once NV_ROOTDIR . '/modules/' . $site_mods[$module]['module_file'] . '/language/' . NV_LANG_DATA . '.php';
    if (isset($site_mods['notification']) && file_exists(NV_ROOTDIR . '/modules/notification/site.functions.php')) {
        require_once NV_ROOTDIR . '/modules/notification/site.functions.php';
        list ($title, $performer, $useradd) = $db->query('SELECT title, performer, useradd FROM ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' WHERE id=' . $id)->fetch(3);
        $array_userid = array();
        $array_userid[] = $useradd;
        $performer = explode(',', $performer);
        foreach ($performer as $_userid) {
            $array_userid[] = intval($_userid);
        }
        $array_userid = array_unique($array_userid);
        $array_userid = array_diff($array_userid, array(
            $userid
        ));
        $content = sprintf($lang_module['comment_task'], $name, $title);
        $url = NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module . '&' . NV_OP_VARIABLE . '=detail&id=' . $id . '#cid_' . $new_id;
        nv_send_notification($array_userid, $content, 'comment_task', $module, $url);
    }
}

$db->query('UPDATE ' . NV_PREFIXLANG . '_' . $site_mods[$module]['module_data'] . ' SET edittime=' . NV_CURRENTTIME . ' WHERE id=' . $id);