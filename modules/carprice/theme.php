<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 11, 2010 8:43:46 PM
 */

if (!defined('NV_IS_MOD_SM')) {
    die('Stop!!!');
}

/**
 * nv_page_main()
 *
 * @param mixed $row
 * @param mixed $ab_links
 * @return
 */
function nv_carprice_main( $array_content_show, $array_location_fee_show, $config_module )
{
    global $module_name, $lang_module, $lang_global, $module_info, $array_producer, $array_location;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('CONFIG', $config_module);
    $xtpl->assign('LIST_BRAND', json_encode( $array_content_show ));
    $xtpl->assign('LIST_LOCATION', json_encode( $array_location_fee_show ));
    foreach ( $array_producer as $procuder ){
        $xtpl->assign('PROCUDER', $procuder);
        $xtpl->parse('main.procuder');
    }
    foreach ( $array_location as $location ){
        $xtpl->assign('LOCATION', $location);
        $xtpl->parse('main.location');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}
