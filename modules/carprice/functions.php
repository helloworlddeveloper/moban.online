<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/03/2010 10:51
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

define('NV_IS_MOD_SM', true);

$_sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_location_fee';
$array_location_fee = $nv_Cache->db($_sql, '', $module_name);

if( !isset($site_mods['location'])){
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
}
$_sql = 'SELECT * FROM ' . $db_config['prefix'] . '_location_province';
$array_location = $nv_Cache->db($_sql, '', 'location');