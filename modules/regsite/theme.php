<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Thu, 19 Jun 2014 09:53:21 GMT
 */

if ( ! defined( 'NV_IS_MOD_REGSITE' ) ) die( 'Stop!!!' );


/**
 * nv_theme_reg_main()
 *
 * @param mixed $data_content
 * @param string $html_pages
 * @return
 */
function nv_theme_reg_main($data_content, $data_code, $error, $step )
{
    global $module_info, $op, $lang_module, $module_name, $module_file, $global_config, $lang_global;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('DATA_CODE', $data_code);
    $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=avatar/src', true));
    $xtpl->assign('DATA', $data_content);

    $xtpl->assign('AVATAR_DEFAULT', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/users/no_avatar.png');
    $xtpl->assign('URL_BANNER', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar/src', true));


    if( !empty( $error )){
        $xtpl->assign('ERROR', implode('<br />', $error ));
        $xtpl->parse('main.error');
    }

    if( $step == 1 ){
        if ($global_config['captcha_type'] == 2) {
            $xtpl->assign('RECAPTCHA_ELEMENT', 'recaptcha' . nv_genpass(8));
            $xtpl->assign('N_CAPTCHA', $lang_global['securitycode1']);
            $xtpl->parse('main.step1.recaptcha');
        } else {
            $xtpl->assign('GFX_WIDTH', NV_GFX_WIDTH);
            $xtpl->assign('GFX_HEIGHT', NV_GFX_HEIGHT);
            $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
            $xtpl->assign('CAPTCHA_REFRESH', $lang_global['captcharefresh']);
            $xtpl->assign('NV_GFX_NUM', NV_GFX_NUM);
            $xtpl->parse('main.step1.captcha');
        }
        $xtpl->parse('main.step1');
    }else{
        $xtpl->parse('main.step2');
    }


    $xtpl->parse('main');
    return $xtpl->text('main');
}


/**
 * nv_theme_reg_main()
 *
 * @param mixed $data_content
 * @param string $html_pages
 * @return
 */
function nv_theme_reg_update($data_content, $error )
{
    global $module_info, $op, $lang_module, $module_name, $module_file, $global_config, $lang_global;

    $xtpl = new XTemplate($op . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('AVATAR_DEFAULT', NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/users/no_avatar.png');
    $xtpl->assign('URL_AVATAR', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=avatar/src', true));
    $xtpl->assign('URL_BANNER', nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar/src', true));
    $xtpl->assign('DATA', $data_content);

    if( !empty( $error )){
        $xtpl->assign('ERROR', implode('<br />', $error ));
        $xtpl->parse('main.error');
    }


    $xtpl->parse('main');
    return $xtpl->text('main');
}

function nv_theme_reg_ok( $data_content )
{
    global $module_info, $op, $lang_module, $module_name, $module_file;

    $xtpl = new XTemplate('ok.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('DATA', $data_content);

    $xtpl->parse('main');
    return $xtpl->text('main');
}


function nv_theme_update_ok( $data_content )
{
    global $module_info, $op, $lang_module, $module_name, $module_file;

    $xtpl = new XTemplate('updateok.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('DATA', $data_content);

    $xtpl->parse('main');
    return $xtpl->text('main');
}


/**
 * nv_avatar()
 *
 * @param mixed $array
 * @return void
 */
function nv_avatar($array)
{
    global $module_info, $module_name, $lang_module, $lang_global, $global_config;

    $xtpl = new XTemplate('avatar.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('TEMPLATE', $global_config['module_theme']);
    $xtpl->assign('MODULE_FILE', $module_info['module_file']);

    $xtpl->assign('NV_AVATAR_WIDTH', 350);
    $xtpl->assign('NV_AVATAR_HEIGHT', 350);
    $xtpl->assign('NV_MAX_WIDTH', NV_MAX_WIDTH);
    $xtpl->assign('NV_MAX_HEIGHT', NV_MAX_HEIGHT);
    $xtpl->assign('NV_UPLOAD_MAX_FILESIZE', NV_UPLOAD_MAX_FILESIZE);

    $form_action = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=avatar';
    if (!empty($array['u'])) {
        $form_action .= '/' . $array['u'];
    }
    $xtpl->assign('NV_AVATAR_UPLOAD', $form_action);

    $lang_module['avatar_bigfile'] = sprintf($lang_module['avatar_bigfile'], nv_convertfromBytes(NV_UPLOAD_MAX_FILESIZE));
    $lang_module['avatar_bigsize'] = sprintf($lang_module['avatar_bigsize'], NV_MAX_WIDTH, NV_MAX_HEIGHT);
    $lang_module['avatar_smallsize'] = sprintf($lang_module['avatar_smallsize'], $global_config['avatar_width'], $global_config['avatar_height']);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);

    if ($array['error']) {
        $xtpl->assign('ERROR', $array['error']);
        $xtpl->parse('main.error');
    }

    if ($array['success'] == 1) {
        $xtpl->assign('FILENAME', $array['filename']);
        $xtpl->parse('main.complete');
    } elseif ($array['success'] == 2) {
        $xtpl->parse('main.complete2');
    } elseif ($array['success'] == 3) {
        $xtpl->assign('FILENAME', $array['filename']);
        $xtpl->parse('main.complete3');
    } else {
        $xtpl->parse('main.init');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}