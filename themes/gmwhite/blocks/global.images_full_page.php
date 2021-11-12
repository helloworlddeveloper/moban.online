<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES ., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jan 17, 2011 11:34:27 AM
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

if (! nv_function_exists('nv_image_full_page')) {
    function nv_image_full_page_config( $module, $data_block, $lang_block)
    {
        global $lang_global, $selectthemes;

        // Find language file
        if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/language/' . NV_LANG_INTERFACE . '.php')) {
            include NV_ROOTDIR . '/themes/' . $selectthemes . '/language/' . NV_LANG_INTERFACE . '.php';
        }

        $html = '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['browse_image'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_image" value="' . $data_block['image'] . '"><span></span></div>';
        $html .= '</div>';
        $html .= '<div class="form-group">';
        $html .= '<label class="control-label col-sm-6">' . $lang_global['company_website'] . ':</label>';
        $html .= '<div class="col-sm-18"><input type="text" class="form-control" name="config_website" value="' . $data_block['website'] . '"><span></span></div>';
        $html .= '</div>';

        return $html;
    }

    function nv_image_full_page_config_submit()
    {
        global $nv_Request;

        $return = array();
        $return['error'] = array();
        $return['config']['image'] = $nv_Request->get_title('config_image', 'post');
        $return['config']['website'] = $nv_Request->get_title('config_website', 'post');

        return $return;
    }

    /**
     * nv_menu_theme_default_footer()
     *
     * @param mixed $block_config
     * @return
     */
    function nv_image_full_page($block_config)
    {
        global $global_config, $lang_global, $blockID;

        if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/blocks/global.images_full_page.tpl')) {
            $block_theme = $global_config['module_theme'];
        } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.images_full_page.tpl')) {
            $block_theme = $global_config['site_theme'];
        } else {
            $block_theme = 'default';
        }


        $xtpl = new XTemplate('global.images_full_page.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');
        $xtpl->assign('LANG', $lang_global);
        $xtpl->assign('BLOCKID', $blockID);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        if (! empty($block_config['website'])) {
            if (! preg_match("/^https?\:\/\//", $block_config['website'])) {
                $block_config['website'] = 'http://' . $block_config['website'];
            }
        }
        $xtpl->assign('DATA', $block_config);


        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    $content = nv_image_full_page($block_config);
}
