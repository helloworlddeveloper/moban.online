<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_producer ORDER BY id ASC';
$array_producer = $nv_Cache->db($_sql, 'id', $module_name);

$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_temcar ORDER BY id ASC';
$array_temcar = $nv_Cache->db($_sql, 'id', $module_name);

$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_typecar ORDER BY id ASC';
$array_typecar = $nv_Cache->db($_sql, 'id', $module_name);
