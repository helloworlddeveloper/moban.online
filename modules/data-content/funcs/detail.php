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

$array_page = explode('-', $array_op[1]);
$id = intval(end($array_page));
$number = strlen($id) + 1;
$alias_url = substr($array_op[1], 0, -$number);
if ($id > 0 and $alias_url != '') {
    $cache_file = '';
    $contents = '';

    if (!defined('NV_IS_MODADMIN') and $page < 5) {
        $cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '_' . $op . '_detail_' . $id . '_' . NV_CACHE_PREFIX . '.cache';
        if (($cache = $nv_Cache->getItem($module_name, $cache_file, 3600)) != false) {
            $contents = $cache;
        }
    }
   
    if (empty($contents)) {
    

    
        $db_slave->sqlreset()
            ->select('*')
            ->from($db_config['dbsystem'] . '.' .  NV_PREFIXLANG . '_' . $module_data )
            ->where('status=1 AND id=' . $id );
        $result = $db_slave->query($db_slave->sql());
        $array_catpage  = array();
        $data_content = $result->fetch();

        $time_set_rating = $nv_Request->get_int($module_name . '_' . $op . '_' . $data_content['id'], 'cookie', 0);
        if ($time_set_rating > 0) {
            $data_content['disablerating'] = 1;
        } else {
            $data_content['disablerating'] = 0;
        }
        $data_content['newscheckss'] = md5($data_content['id'] . NV_CHECK_SESSION);
        $data_content['stringrating'] = sprintf($lang_module['stringrating'], $data_content['total_rating'], $data_content['click_rating']);
        $data_content['numberrating'] = ($data_content['click_rating'] > 0) ? round($data_content['total_rating'] / $data_content['click_rating'], 1) : 0;
        $data_content['langstar'] = array(
            'note' => $lang_module['star_note'],
            'verypoor' => $lang_module['star_verypoor'],
            'poor' => $lang_module['star_poor'],
            'ok' => $lang_module['star_ok'],
            'good' => $lang_module['star_good}'],
            'verygood' => $lang_module['star_verygood']
        );


        $contents = detail_page($data_content);

    
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
}
