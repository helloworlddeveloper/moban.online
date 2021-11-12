<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 03-05-2010
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}
    $array_content_keyword = array();
    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from($db_config['prefix'] . '_' . $mod_data . '_rows')
        ->where('status= 1');

    $num_items = $db_slave->query($db_slave->sql())
        ->fetchColumn();
    
    if ($num_items) {

        $db_slave->select(NV_LANG_DATA . '_title')
            ->order(NV_LANG_DATA . '_title DESC');
        $result = $db_slave->query($db_slave->sql());
        while (list ($title) = $result->fetch(3)) {
            $array_content_keyword[] = $title;
        }
    }
