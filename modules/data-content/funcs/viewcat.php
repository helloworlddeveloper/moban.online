<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3-6-2010 0:14
 */

if (!defined('NV_IS_MOD_SLIDE')) {
    die('Stop!!!');
}

$cache_file = '';
$contents = '';

$base_url_rewrite = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $global_array_cat[$catid]['alias'];
if ($page > 1) {
    $base_url_rewrite .= '/page-' . $page;
}
$base_url_rewrite = nv_url_rewrite($base_url_rewrite, true);
if ($_SERVER['REQUEST_URI'] != $base_url_rewrite and NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite) {
    nv_redirect_location($base_url_rewrite);
}

if (!defined('NV_IS_MODADMIN') and $page < 5) {
    $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_' . $catid . '_page_' . $page . '_' . NV_CACHE_PREFIX . '.cache';
    if (($cache = $nv_Cache->getItem($module_name, $cache_file, 3600)) != false) {
        $contents = $cache;
    }
}
$per_page = 20;

$page_title = $global_array_cat[$catid]['title'];
$stt = $per_page * ($page -1) + 1;
if (empty($contents)) {

    $base_url = $global_array_cat[$catid]['link'];

    $order_by ='addtime ASC';

    $db_slave->sqlreset()
        ->select('COUNT(*)')
        ->from($db_config['dbsystem'] . '.' .  NV_PREFIXLANG . '_' . $module_data )
        ->where('status=1 AND catid=' . $catid );

    $num_items = $db_slave->query($db_slave->sql())
        ->fetchColumn();

    $db_slave->select('*')
        ->order($order_by)
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);
    $result = $db_slave->query($db_slave->sql());
    $array_catpage  = array();
    while ($item = $result->fetch()) {
        $array_catpage[] = $item;
    }
    $generate_page = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
    $cattype = $global_array_cat[$catid]['cattype'];
    $contents = viewcat_page($array_catpage, $generate_page, $cattype, $stt);


    if (!defined('NV_IS_MODADMIN') and $contents != '' and $cache_file != '') {
        $nv_Cache->setItem($module_name, $cache_file, $contents);
    }
}

if ($page > 1) {
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
    $description .= ' ' . $page;
}


include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';