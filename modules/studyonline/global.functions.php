<?php

/**
 * @Project NUKEVIET 4.x
 * @Author EDUSGROUP.JSC (thangbv@edus.vn)
 * @Copyright (C) 2014 EDUSGROUP.JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Tue, 14 Mar 2017 10:56:01 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$array_subject = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_subject', 'id', $module_name);
$array_teacher = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_teacher', 'id', $module_name);
$array_class = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_class', 'id', $module_name);
$array_tag = $nv_Cache->db('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tag', 'tag_id', $module_name);

define('NV_TABLE_AFFILIATE', $db_config['prefix'] . '_affiliate');