<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if (! defined('NV_IS_MOD_SEARCH_SUGGUEST')) {
    die('Stop!!!');
}

function nv_theme_main()
{
    global $lang_module, $module_info, $lang_global;
    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_FILE', $module_info['module_theme']);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->parse('main');
    return $xtpl->text('main');
}
