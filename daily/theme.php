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
 * nv_theme_reg_main()
 *
 * @param mixed $data_content
 * @param string $html_pages
 * @return
 */
function nv_theme_daily_main( $data_content )
{
    global $module_info, $op, $lang_module, $module_name, $module_file;


    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('DATA', $data_content);
    $xtpl->assign( 'URL_REGISTER',  'http://daily.cash13.vn' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=regsite&mobile=' . $data_content['mobile']  );

    if( !empty( $data_content['banner_site'] )){
        $xtpl->parse('main.banner_site');
    }else{
        $img_default = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/banner-default.png';
        $xtpl->assign('banner_site_default', $img_default );
        $xtpl->parse('main.banner_site_default');
    }
    if( !empty( $data_content['image_site'] )){
        $xtpl->assign('image_site', $data_content['image_site'] );
        $xtpl->parse('main.image_site');
    }else{
        $img_default = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/image-default.jpg';
        $xtpl->assign('image_site', $img_default );
        $xtpl->parse('main.image_site');
    }
    if( !empty( $data_content['facebook'] )){
        $xtpl->parse('main.facebook');
    }
    if( !defined('NV_IS_USER')){
        $xtpl->parse('main.nousers');
        $xtpl->parse('main.nousers1');
        $xtpl->parse('main.nousers2');
        $xtpl->parse('main.nousers3');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}
