<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 3/9/2010 23:25
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (!nv_function_exists('nv_block_search_suggest')) {
   
    function nv_block_search_suggest()
    {
        global $global_config;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/search-suggest/block_search.tpl')) {
            $block_theme = $global_config['module_theme'];
        } else {
            $block_theme = 'default';
        }

        $xtpl = new XTemplate('block_search.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/search-suggest');
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('MODULE_FILE', 'search-suggest');
        $xtpl->parse('main');
        return $xtpl->text('main');
        
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_block_search_suggest();
}
