<?php

/**
 * @Project NUKEVIET 4.x
 * @Author TDFOSS.,LTD (quanglh268@gmail.com)
 * @Copyright (C) 2018 TDFOSS.,LTD. All rights reserved
 * @Createdate Fri, 12 Jan 2018 02:38:03 GMT
 */

if ( ! defined( 'NV_IS_MOD_WORKFORCE' ) ) die( 'Stop!!!' );


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

    $xtpl->assign('NV_AVATAR_WIDTH', 200);
    $xtpl->assign('NV_AVATAR_HEIGHT', 200);
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
    }  else {
        $xtpl->parse('main.init');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_theme_doctor_control()
 *
 * @param mixed $array_data
 * @return
 */
function nv_theme_workforce_control( $array_control )
{
    global $global_config, $module_name, $module_file, $lang_module, $module_info, $op;

    $link = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=';
    $xtpl = new XTemplate( 'menu_control.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
    $xtpl->assign( 'LANG', $lang_module );

    foreach( $array_control as $key => $control )
    {
        $control['active'] = ( $op == $key ) ? ' class=active' : '';
        $xtpl->assign( 'CONTROL', $control );
        if( isset( $control['number'] ) )
        {
            $xtpl->parse( 'main.control.number' );
        }
        $xtpl->parse( 'main.control' );
    }

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}