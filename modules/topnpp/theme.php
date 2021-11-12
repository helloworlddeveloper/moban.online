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
function nv_theme_topnpp_main( $array_statistic, $array_agency, $array_province, $page_title )
{
    global $module_info, $op, $lang_module, $module_name, $module_file;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('module_file', $module_file);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('DATE', date('m/Y', NV_CURRENTTIME ));
    $xtpl->assign('TITLE_PAGE', $page_title);
    $i = 1;
    foreach ($array_statistic as $data ) {

        if ( !empty( $data['image_site'] ) ) {
            $data['photo'] = NV_BASE_SITEURL . $data['image_site'];
        } else {
            $data['photo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_file . '/top' . $i . '.png';
        }
        $data['mobile'] = substr_replace($data['mobile'],'xxx',strlen( $data['mobile'] ) - 3 );
        //$data['photo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $site_mods[$module]['module_upload'] . '/top' . $i . '.png';
        $data['total_price'] = number_format($data['total_price'], 0, '.', ',' );
        //$data['fullname'] = nv_show_name_user(  $data['first_name'], $data['last_name'] );

        $data['agency'] = $array_agency[$data['agencyid']]['title'];
        $data['province'] = $array_province[$data['provinceid']]['title'];
        $xtpl->assign('ROW', $data);

        if( $i ==1 ){
            $xtpl->parse('main.showmain');
        }else{
            $xtpl->parse('main.showsub.loop');
        }
        $i++;
    }
    if( $i > 1 ){
        $xtpl->parse('main.showsub');
    }
    $xtpl->parse('main');
    return $xtpl->text('main');
}
